<?php

namespace Lib\ImapGet;

use Pet\Email\Imap;

    class ImapGet{

        function getReviews(array $iParametr):array{

                $imap = Imap::start($iParametr['imap'], $iParametr['port'], $iParametr['user_imap'], $iParametr['password_imap']);
                
                $reviews = [];
                if ($imap->error){
                    
                    logs()->set($imap->error);
                    return [];
                }
                
                foreach($iParametr['prefix'] as $prefix){

                    set_time_limit(120);

                    $imap->limite = 100;

                    $imap->prefixBody = $prefix;

                    $reviews[$prefix] = [];
                    $imap->search = 'ON "'.date('d-M-Y', mktime(0,0,0, date('m'), date('d')-1, date('Y'))).'" ';
            
                foreach($imap->messages() as $mail ){

                            $client = [];
                            $client['text'] = $mail['body'];
                            $client['date'] = explode(' ', $mail['date'])[0];
                            $client['platform'] = $prefix;
                            $client['img'] = $client['answer'] = $client['dateanswer'] = null;
                            $client['contrib'] = str_replace(['<','>'],'', $mail['message_id']);
                            $client['star'] = '0';
                            $client['name'] = 'Отзыв из почты';
                            $client['filial'] = $iParametr['filial'];
                            $client['hash'] = hash('sha256', $client['date']. $client['contrib']);
                            $reviews[$prefix][] = $client; 

                    }
                }
                // dm($reviews);
                return $reviews;
        }
    }
?>