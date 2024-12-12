<?php
namespace Lib\Sms;
class Sms
{
    public $password = 'MxiMgxThyG7C';
    public $login = 'altamedplus';


    public function send($telefon, $sms)
    {
        $get = array(
            'login'  => $this->login,
            'psw' => $this->password,
            'phones' => $telefon,
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

   public function sample($tel ,$name , $clinik , $mess){
    $sms = "Здравствуйте, $name! Пожалуйста, поделитесь своими впечатлениями, оставив отзыв о клинике «{$clinik}». $mess.";
    return  $this->send($tel, $sms);
   }
}
?>