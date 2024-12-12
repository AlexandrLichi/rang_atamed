<?php
namespace Model\ReviewsModel;

use lib\ConvertDate\ConvertDate;
use Model\FilialModel\FilialModel;
use Pet\Model\Model;
use Pet\Request\Request;


class ReviewsModel extends Model{

    public $table = '';
    public $insert = false;
    public $join = ['filial_id'];

    public function __construct($api) {
        $this->table = $api;
    }


   public function set(array $Rewiews, $filial, $platform){


            $this->insert = true;
            $report = ["UPDATE"=> 0, "INSERT"=>0 , "ERROR"=>[]];

            foreach($Rewiews as $row){

                $row['filial'] = $filial;
                $row['platform'] = $platform;

                $row['date'] = (new ConvertDate)->convert($row['date']);

                if($row['dateanswer']) $row['dateanswer'] = (new ConvertDate)->convert($row['dateanswer']);
                $count = $this->findUpOrIn(['contrib'=>$row['contrib'], 'name'=>$row['name']], fn($finds)=>count($finds) != 0, $row);

                if($count == 'ERROR') $report[$count][] = $this->errorSQL;
                $report[$count]++;

            }
            return $report;
        
    }


    public function getReviews(array|null $searh = null){
        $this->connect();
        $column = ['text', 'contrib', 'name', 'text', 'filial', 'date', 'answer', 'star', 'dateanswer', 'platform', 'img'];

        if($searh){
        $where = '';
        $where .= $this->OR('platform', $searh);

        if (array_key_exists('star', $searh)){
            $where .= $this->OR('star', $searh);
        } 

        if(array_key_exists('filial_id', $searh) && $searh['filial_id'] != "all")
        $where .= "`filial`= '".(new FilialModel())->name($searh['filial_id'])."' AND";

        if (array_key_exists('filial', $searh)){
            $where .= "`filial`= '" .$searh['filial'] . "' AND";
        }

        if(array_key_exists('st', $searh) && array_key_exists('en', $searh)){
            $where .= " `date` BETWEEN '".$searh['st']."' AND '".$searh['en']."' AND";
        }

        $where = substr($where, 0, -intval(strlen("AND")));
        
        if($where == '') $where = 1;
        $where .= ' ORDER BY date DESC';


        if(array_key_exists('text', $searh)){
            $searchText = [];
            foreach($this->select($column, $where) as $row){
                if(str_contains(strtolower($row['text']), strtolower($searh['text'])) || 
                str_contains(strtolower($row['name']), strtolower($searh['text']))){
                    $searchText[] = $row;
                }
            }
            return $searchText;
        }
           
        

       
            return $this->select($column, $where);

        }
    
       return  $this->find([], $column);
    }

    private function OR($name, $searh){
         $where = '';
        if (count($searh[$name]) != 0) {
            $where .= '(';
            foreach ($searh[$name] as $value) $where .= "`$name`='$value' OR";
            $where = substr($where, 0, -intval(strlen("OR")));
            $where .= ") AND ";
            if(count($searh[$name]) == 1) $where = str_replace(['(', ')'], '',$where);
        }

        return $where;
    }
}
?>