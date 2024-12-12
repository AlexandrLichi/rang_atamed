<?php
namespace Model\AnaliticsModel;

use Model\ClientModel\ClientModel;
use Model\ParsersModel\ParsersModel;
use Model\ReviewsModel\ReviewsModel;
use Pet\Model\Model;
use Pet\Request\Request;


class AnaliticsModel extends Model{

    public $table = 'analytics';
    public $insert = false;
    public $join = [];
    public $actyal;

    function index(Request $request){

    }

    function set($data){
      $this->insert = true;

      $data['star'] = str_replace(',', '.', $data['star']);
      if($data['star'] == '') return logs()->set('Нет глобальной оценки от парсера'.$data['platform'].' filial '.$data['filial']);

      $this->findUpOrIn(['date'=>$data['date'], 
                         'api'=>$data['api'], 
                         'filial'=>$data['filial'],
                         'platform'=>$data['platform']],
                          fn(array $finds)=> count($finds) != 0, $data);
    }


    function get($search){
        $fil_id = null;
        // dm($search);
        if(array_key_exists('filial_id', $search)) {
          $fil_id = $search['filial_id'];
            unset($search['filial_id']);
        }
        
        $parsers =(new ParsersModel)->getFilialApi($search['api'], $fil_id);
        $result = [];
       
        foreach($parsers as $parser){

          $this->BETWEEN = ['date'=>[ $search['st'], $search['en'] ]];
          $search['platform'] = [$parser['name']];
          $search['filial'] = $parser['filial'];
          $negative = ['star'=>['1','2','3']];
          $positive = ['star' => ['4','5']];

          $result[] = ['platform' => $parser['name'],
                       'filial'=>$parser['filial'],
                       'analis'=> $this->analis($search['api'], $parser['filial'], $parser['name']),
                       'actyal'=>$this->actyal,
                       'countNegative' => count((new ReviewsModel($search['api']))->getReviews(array_merge($search, $negative))),
                       'countPositive'=>  count((new ReviewsModel($search['api']))->getReviews(array_merge($search, $positive))),
                       'count'=> count((new ReviewsModel($search['api']))->getReviews($search)),
                       'requesClientNegative'=>count((new ClientModel())->clientAnalitis($search, true)),
                       'requesClientPositive'=>count((new ClientModel())->clientAnalitis($search, false, true)),
                       'requesClient' => count((new ClientModel())->clientAnalitis($search))
                      ];
        }

        $intr = $this->increase($result);
        $All = [
          "increase"=>$intr[0],
          "requestAll"=>$intr[1],
          "requestAllNEGATIVE"=> $intr[2],
          "requestAllPOSITIVE"=> $intr[3],
        ];
        return [$result, $All];
    }



  private function analis($api, $filial, $platform ){
        $actual = ['date', 'star', 'count'];

        $res =[ 0=>[], 1=>[] ];
        $this->actyal = 0;

        foreach($this->find(['api' => $api, 'filial' => $filial, 'platform' => $platform], $actual) as $row){
          $res[1][] =[ $row['star'], $row['count']];
          $res[0][$row['date']] = [ $row['star'], $row['count']];
          $this->actyal = $row['star'];
        }
        return $res;
   }


  private function increase($result){
    $c = $rs= $n = $p = 0;
    foreach($result as $r){
      $c+= $r['count'];
      $rs += $r['requesClient'];
      $n += $r['requesClientNegative'];
      $p +=$r['requesClientPositive'];
    }
    return [$c, $rs, $n, $p];
  }



   function actual($api, $platform , $filial)
   {
   $stars = $this->find(['api'=>$api, 'filial'=>$filial, 'platform'=>$platform], ['star']);
 
    if(count($stars) != 0){
      return str_replace(',','.', $stars[count($stars) - 1]['star']);
    }else{
        return '0';
    }
   }
    
}

?>