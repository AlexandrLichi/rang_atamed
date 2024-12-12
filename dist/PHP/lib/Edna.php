<?php
namespace Lib\Edna;
use stdClass;
class Edna{

    public $ApiKey = '2c12de9c-ec51-4929-ad16-96e7fb02e6ad';
    public $urlEdna = 'https://app.edna.ru/api/cascade/schedule';
    public $urlCheck = 'https://app.edna.ru/api/messages/history';
    public $cascadeId = '2007';

    // создает объект отравки
    public function createDataWhatsApp($tel, $text, $headerWA = null, $footerWA = null, $buttons = null){

        $data = new stdClass();
        $data->requestId = uniqid();
        $data->cascadeId = $this->cascadeId;

        $data->subscriberFilter = new stdClass();
        $data->subscriberFilter->address = $tel;
        $data->subscriberFilter->type = 'PHONE';

        $data->content = new stdClass();
        $data->content->whatsappContent = new stdClass();
        $data->content->whatsappContent->contentType = 'TEXT';

        // $data->content->footer = new stdClass();
        $data->content->whatsappContent->text = $text;

        if ($headerWA) {
            $data->content->whatsappContent->header = new stdClass();
            $data->content->whatsappContent->header->text = $headerWA;
        }

        if ($footerWA) {
            $data->content->whatsappContent->footer = new stdClass();
            $data->content->whatsappContent->footer->text = $footerWA;
        }

        if ($buttons) {
            $data->content->whatsappContent->keyboard = new stdClass();
            $data->content->whatsappContent->keyboard->rows = [];
            $rows = new stdClass();
            $rows->buttons = [];

            foreach ($buttons as $but) {
                $rows->buttons[] = $but;
            }

            $data->content->whatsappContent->keyboard->rows[] = $rows;
        }

        return [$data, $this->urlEdna];
    }

    //  создает объект проверки 
    public function createCheckWhatsApp(string $telefon, string $dateFrom, string $timeFrom, string $dateTo = null, string $timeTo = null)
    {
       
        $d = explode('-', $dateFrom); //**  раскладываем дату в массив [ 2000, 12 , 23 ]
        $t = explode(':', $timeFrom); //**раскладываем время в массив [ 23, 59 , 59 ]*/

        $ms = mktime(intval($t[0]), intval($t[1]), intval($t[2]), intval($d[1]), intval($d[2]), intval($d[0])) - 10800;

        $dateFrom = date('Y-m-d', $ms - 10);
        $timeFrom  = date('H:i:s', $ms - 10);
        if($dateTo && $timeTo){
            $d = explode('-', $dateTo); //**  раскладываем дату в массив [ 2000, 12 , 23 ]
            $t = explode(':', $timeTo); //**раскладываем время в массив [ 23, 59 , 59 ]*/
            $ms = mktime(intval($t[0]), intval($t[1]), intval($t[2]), intval($d[1]), intval($d[2]), intval($d[0])) - 10800;
            $dateTo = date('Y-m-d', $ms - 10);
            $timeTo  = date('H:i:s', $ms - 10); 
        }else{
            $dateTo = date('Y-m-d', $ms + 10);
            $timeTo  = date('H:i:s', $ms + 10); 
        }

        $data = new stdClass();
        $data->subscriberFilter = new stdClass();

        $data->subscriberFilter->address = $telefon;
        $data->subscriberFilter->type = 'PHONE';
        $data->channelTypes = ["WHATSAPP"];
        $data->subjectId = 1941;
        $data->trafficType = ["HSM"];
        $data->dateFrom = $dateFrom . 'T' . $timeFrom . 'Z';
        $data->dateTo = $dateTo . 'T' . $timeTo . 'Z';
        $data->limit = 1;
        $data->offset = 0;
        // $SORTOBJ = new stdClass();
        // $SORTOBJ->property = "messageId";
        // $SORTOBJ->direction = "DESC";
        // $data->sort = [$SORTOBJ];
        return [$data, $this->urlCheck];
    }


    public function sample( $tel, $name, $clinik, $mess, $url){
        $edna = new Edna();
        $buttons = new stdClass();
        $buttons->type = "URL";
        $buttons->url = "https://rang.altamedplus.ru/$url";
        $buttons->text = "Оценить нашу работу";
        $messange = "Здравствуйте, $name!\r\n\r\nМы надеемся, что ваше посещение клиники «{$clinik}» было приятным. \r\n\r\nПожалуйста, поделитесь своими впечатлениями, оставив отзыв о клинике. $mess. \r\n\r\nЭто не займет много времени.";
        $data = $edna->createDataWhatsApp($tel, $messange, null, "Спасибо за ваше доверие!", [$buttons]);
        return $edna->send($data);
    }
    

    public function statusMessange(object $resultObject){

            $st = null;

            if (gettype($resultObject) == 'object' && property_exists($resultObject, 'content') && count($resultObject->content) > 0) {

                if (gettype($resultObject->content[0]) == 'object' && property_exists($resultObject->content[0], 'deliveryStatus')) 
                    $st = $resultObject->content[0]->deliveryStatus;

            }

            return $st;
    }


    public function send($createData)
    {
        $headers = ['Content-Type: application/json', 'x-api-key:' . $this->ApiKey];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($createData[0]));
        curl_setopt($curl, CURLOPT_URL, $createData[1]);
        curl_setopt($curl, CURLOPT_POST, true);
        $result = curl_exec($curl); // результат POST запроса */
        return $result;
        // print_r($result);
        // return json_decode('{"requestId":"' . $createData[0]->requestId . '"}');
    }
}





class Sms{
    public $password = 'MxiMgxThyG7C';
    public $login = 'altamedplus';


 public function send($telefon, $sms){
        $get = array(
            'login'  => $this->login,
            'psw' => $this->password,
            'phones'=>$telefon,
            'mes' => $sms,
        );

        $ch = curl_init('https://smsc.ru/sys/send.php?' . http_build_query($get));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $html = curl_exec($ch);
        curl_close($ch);
        return $html;
 }
}

// date_default_timezone_set('Europe/Moscow');
// //  Пример

// $edna->ApiKey = '2c12de9c-ec51-4929-ad16-96e7fb02e6ad';
// $edna->cascadeId = '2007';


// // $time = date("H:i:s");


// print_r(json_encode($data[0], JSON_UNESCAPED_UNICODE));

// $result = $edna->send($data);
// print_r($result);
// $checkData = $edna->createCheckWhatsApp('79775956853','2024-06-06', '08:57:53');
//  $res = $edna->send($checkData);
// $res =json_decode($res);
// print_r($edna->statusMessange($res))
// $sms = new Sms;
// $sms->login = 'altamedplus';
// $sms->password = 'MxiMgxThyG7C';
// print_r($sms->send('79775956853', 'Тест привет 2'));
?>