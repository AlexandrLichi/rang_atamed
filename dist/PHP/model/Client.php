<?php
namespace Model\ClientModel;

use Model\CompanyModel\CompanyModel;
use Model\FilialModel\FilialModel;
use Model\HistoryClientModel\HistoryClientModel;
use Pet\Model\Model;
use Pet\Request\Request;
use Pet\Validator\Validator;

class ClientModel extends Model{

    public $table = 'client';
    public $insert = false;
    public $join = [];

    function index(Request $request){

    }

    function setStackTest($telefon, $api, $filialId){
        $client = new ClientModel();
        
        if(!Validator::telefon($telefon)) return "Не валидный номер телефона";
        // dm($telefon);

        $filial = (new FilialModel())->find(['id'=>$filialId] , ['filial', 'company','sample_bonus', 'platform_marketing'], ['company'=>'company_id'])[0];
        $row = $client->find(['telefon'=>$telefon], []);
        $data = ['name'=> "Тестов Тест Тестович",
                 'date-time'=> date('Y-m-d H:i:s'),
                 "telefon"=>$telefon,
                 "filial" => $filial['filial'],
                 "company" => $filial['company'],
                 'api'=>$api,
                 'canal'=>'NEW',
                 'url'=>uniqid(),
                 'status_canal'=>null,
                 'path'=>null,
                 'date_path'=>null,
                 'platform_marketing'=>$filial['platform_marketing'],
                 'platform_path'=>null,
                 'like_star'=>0,
                 'text_negative'=>null,
                 'watch'=>null,
                 'bonus'=>$filial['sample_bonus']
                ];
        if(count($row) == 0){

            $client->insert = true;
            $client->insert($data);

         }else{
            $client->insert = true;
            $client->findUpdate(['id'=>$row[0]['id']], $data);
        }

        return [];

    }

    public function setStack(Request $request):bool|array|string{

        $this->insert = true;
        $data = array_merge(supple(), attr());
        if (!Validator::telefon($data['telefon'])) return "Не валидный номер телефона";
        $data['canal'] = "NEW";
        $client = new ClientModel();
        $filial = (new FilialModel())->find(['id' => $data['id']], ['filial', 'company', 'sample_bonus', 'platform_marketing'], ['company' => 'company_id'])[0];
        $client->BETWEEN = ['date-time' => [date('Y-m-d') . " 00:00:00", date('Y-m-d') . " 23:59:59"]];
        $check = $client->find(['api' => $data['api'], 'telefon' => $data['telefon'], 'filial' => $filial['filial']]);
        if (count($check) != 0) {
            return "Данному номеру отправлялось сообщение в этом филиале";
        }

        if (!$filial['platform_marketing'] || $filial['platform_marketing'] == '') return "Нет в настройках ни одной платформы для рекламации";
        $data['bonus'] = $filial['sample_bonus'];
        unset($filial['sample_bonus']);
        unset($data['id']);
        $data = array_merge($data, $filial);
        $data['url'] = uniqid();

        return $this->insert($data) ? $data : false;

    }

    function getClientUrl($url){
       return $this->find(['url'=>$url],['bonus','filial', 'path', 'platform_marketing', 'platform_path','like_star', 'text_negative', 'name','url', 'api', 'watch']);
    }



    public function status_path($url, $status, $star = null, $platform_path = null, $text_negative = null, $watch = null) {

        $this->insert = true;

        $data = ['path' => $status];
        // $w = date('Y-m-d H:i:s') . "--$status:";
        $text = '';
        if ($star) {
            $data['like_star'] = $star;
            // $w .= strval($star);
            $text = strval($star);
        }
        if ($text_negative) {
            $data['text_negative'] = $text_negative;
            // $w .= $text_negative ? true : false;
            $text  = $text_negative;
        }

        if ($platform_path) {
            $data['platform_path'] = $platform_path;
            // $w .= $platform_path;
            $text  = $platform_path;
        }

        // $data['watch'] = $watch ? $watch . "//" . $w : $w;

        if ($status == 'CONNECT') $data['date_path'] = date('Y-m-d H:i:s');

        $this->findUpdate(['url' => $url], $data);
        $id = $this->find(['url' => $url], ['id'])[0]['id'];
        (new HistoryClientModel())->setHistory($id, $status, $text);
        if ($status == 'DESTROYED') {
            $this->findUpdate(['id' => $id], ['url' => ""]);
        }
    }



    function setStatusCanal($url,  $canal, $status){
        $this->insert = true;
        $this->findUpdate(['url'=>$url], ['canal'=>$canal,'status_canal'=>$status ]);
    }


    public function getReport($api = null, $filial_id = null) {
        $data = [];
        if ($api) $data['api'] = $api;
        if (is_numeric($filial_id)) $data['filial'] = (new FilialModel())->name($filial_id);

        return $this->find(
            $data,
            ['bonus_check', 'bonus', 'id', 'date-time', 'name', 'telefon', 'filial', 'canal', 'path', 'like_star', 'text_negative', 'platform_path', 'watch'],
            [],
            ' ORDER BY `date-time` DESC'
        );
    }


    public function getsearch($api, $filial_id, $text) {
        $filial = '';
        // $text = $this->escapeStr($text);

        if (is_numeric($filial_id)) $filial = "AND `filial`='" . (new FilialModel())->name($filial_id) . "'";

        return $this->select(
            ['bonus_check', 'bonus', 'id', 'date-time', 'name', 'telefon', 'filial', 'canal', 'path', 'like_star', 'text_negative', 'platform_path', 'watch'],
            "`api`='$api' $filial AND (`telefon`LIKE '%$text%' OR `name` LIKE '%$text%' OR `text_negative` LIKE '%$text%')"
        );
    }

    function clientAnalitis($searh, $neg = false , $pos = false){
        $this->BETWEEN[ 'date-time'] = [ $searh['st'], $searh['en'] ] ;
     
        
        unset($searh['st']);
        unset($searh['en']);
        
        if(array_key_exists('platform', $searh))
        {
            $searh['platform_path'] = $searh['platform'][0];
            unset($searh['platform']);
        }
        

        if($neg)$this->BETWEEN['like_star'] = [ 1, 3 ];
        

        if ($pos) $this->BETWEEN['like_star'] = [4, 5];
        

        return $this->find($searh, [],[],' ORDER BY `date-time` DESC');
    }
}
?>