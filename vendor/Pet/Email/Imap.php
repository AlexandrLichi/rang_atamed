<?php

namespace Pet\Email;

class Imap
{
    public $host;
    public $port;
    public $user;
    public $password;
    public $error;
    public $noHTML = true;
    public $imap;
    public $folder = "INBOX";
    private $server;
    public $limite = 100;
    public $search = 'ALL';
    public $prefixBody = false;
    public $prefixHeader = false;

    public function __construct()
    {
    }

    static function start($host, $port,  $user, $password): Imap|false
    {
        $imap =  new Imap();
        $imap->host = $host;
        $imap->port = $port;
        $imap->user = $user;
        $imap->password = $password;
        $imap->server = "{" . $imap->host . ":" . $imap->port . "/imap/ssl}";
        return $imap->connect() ? $imap : false;
    }

    public function connect()
    {

        if ($this->imap = imap_open($this->server . $this->folder, $this->user, $this->password)) {
            return  true;
        } else {
            $this->error = imap_last_error();
            return false;
        }
    }

    public function folder($str = null): array
    {
        $list = imap_list($this->imap, $this->server, '*');
        $folder = [];

        if (is_array($list)) {
            foreach ($list as $val) {
                $name = str_replace($this->server, "", $val);
                $folder[$name] = $val;
            }
        }

        if ($str) {
            if (array_key_exists($str, $folder)) {
                return  $folder[$str];
            } else {
                $this->error = 'Not folder email';
            }
        }

        return $folder;
    }

    function messages(): array
    {

        $mails_id = imap_search($this->imap, $this->search, SE_FREE, 'UTF-8');
      
        if (!$mails_id) return [];
        $content = [];
     
        $limite = 0;
        foreach(array_reverse($mails_id) as $num){

            if ($limite >= $this->limite) return $content;
            $limite++;

            $header = $this->header($num);
            
            if (array_key_exists('subject', $header) && count($header['subject']) != 0 && $this->prefixHeader) {
                if (!str_contains(strtolower($header['subject'][0]->text), strtolower($this->prefixHeader))) continue;
            }
        
            $header['body'] = $this->noHTML ? $this->delete_simbol($this->body($num)) : $this->body($num);

            if ($this->prefixBody) {
                if (!str_contains(strtolower($header['body']),  strtolower($this->prefixBody))) continue;
            }

            $content[] = $header;
        }

        

        return $content;
    }


    // function

    function header(int $num): array
    {

        $header = imap_headerinfo($this->imap, $num);
    
        $header = json_decode(json_encode($header), true);
        // print_r($header);
        if (array_key_exists('subject', $header)) $header['subject'] = imap_mime_header_decode($header['subject']);
        if (array_key_exists('Subject', $header)) $header['Subject'] = imap_mime_header_decode($header['Subject']);
        if (array_key_exists('fromaddress', $header)) $header['fromaddress'] = imap_mime_header_decode($header['fromaddress']);

        foreach ($header as $key => $value) {
            if ($key == 'Date' || $key ==  'date'){
            //    print_r($header['Date']);
             $header[$key] = date("Y-m-d H:i:s", strtotime($header[$key]));
            }
            // if ($header[$key] == '') unset($header[$key]);
        }

        return $header;
    }

    private function delete_simbol($str)
    {
        $str = html_entity_decode($str);
        $str = strip_tags($str);
        $str = str_replace(["\r", "\n", "\t"], "", $str);
        $str = preg_replace('/(\{.*?\})/i', '', $str);

        $str = str_replace(['html', 'body', 'a:hover', '.ExternalClass p', '.ExternalClass span', '.ExternalClass font', '.ExternalClass td', '.ExternalClass', '}', '{'], '', $str);

        return trim($str);
    }

    function body(int $num): string
    {

        $body = imap_fetchbody($this->imap, $num, '1.1');

        $base64 = false;
        if (str_contains($body, "Content-Transfer-Encoding: base64")) {

            $body = substr($body,  - (strlen($body) - (strrpos($body, 'base64') + strlen('base64'))));
            $body = trim(substr($body, 0,  - (strlen($body) - strpos($body, '='))));
            $body = base64_decode($body);
            $base64 = true;
            // $body = html_entity_decode($body);
        } else {
            // print_r($body);
            if (!$body  || $body == '') {
                $body = imap_fetchbody($this->imap, $num, '1');
                if (base64_decode($body, true)) {
                    $base64 = true;
                    $body = base64_decode($body);
                }
            }
        }

        if (!$base64) $body = mb_convert_encoding(quoted_printable_decode($body), 'UTF-8', 'auto');
        // print_r($body);
        return $body;
    }

    public function __destruct()
    {
        imap_close($this->imap);
    }
}

?>