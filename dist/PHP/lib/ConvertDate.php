<?php

namespace lib\ConvertDate;

use Exception;

class ConvertDate{

        public $next = ['час' => 'h', 'мес' => 'm', 'год' => 'Y', 'нед' => 'N', 'мин' => 'i', 'ден' => 'd', 'лет' => 'Y', 'дня' => 'd', 'дней' => 'd'];
        public $Moth = ['январ' => 1, 'янв' => 1, 'фев' => 2, 'март' => 3, 'апр' => 4, 'апрел' => 4,  'май' => 5, 'мая' => 5, 'июн' => 6, 'июл' => 7, 'авг' => 8, 'сент' => 9, 'сен' => 9, 'окт' => 10,  'октяб' => 10, 'ноя' => 11, 'ноябр' => 11, 'дек' => 12, 'декаб' => 12];
        public $dop = ['вчера', 'сегодня', 'позавчера', 'пол года'];

        
        public function convert($str)
        {

            $h = $i = $s = $m = $d = $Y = 0;

            foreach ($this->dop as $dop) {
                if (strpos($str, $dop) !== false) {
                    // print_r($dop);
                    switch ($dop) {
                        case 'вчера':
                            $d = 1;
                            break;
                        case 'позавчера':
                            $d = 2;
                            break;
                        case 'пол года':
                            $m = 6;
                            break;
                    }
                    return  date('Y-m-d', mktime(date('h') - $h, date('i') - $i, date('s') - $s, date('m') - $m, date('d') - $d, date('Y') - $Y));
                }
            }



            try {
                $search = strpos($str, 'наз');
                if ($search) {

                    $search = explode(' ', $str);
                    count($search) == 2 ? $count = 1 : $count = $search[0];

                    foreach ($search as $k => $text) {
                        foreach ($this->next as $key => $seartText) {

                            $res = strpos($text, $key);

                            if ($res !== false) {

                                switch ($seartText) {
                                    case 'h':
                                        $h = $count;
                                        break;
                                    case 'd':
                                        $d = $count;
                                        break;
                                    case 'Y':
                                        $Y = $count;
                                        break;
                                    case 'N':
                                        $d = $count * 7;
                                        break;
                                    case 'i':
                                        $i = $count;
                                        break;
                                    case 's':
                                        $s = $count;
                                        break;
                                    case 'm':
                                        $m = $count;
                                        break;
                                }

                                return  date('Y-m-d', mktime(date('h') - $h, date('i') - $i, date('s') - $s, date('m') - $m, date('d') - $d, date('Y') - $Y));
                            }
                        }
                    }
                }

                foreach ($this->Moth as $key => $m) {
                    $search = strpos($str, $key);
                    if ($search !== false) {
                        preg_match('/(*UTF8)([0-9]{1,2})(\s)([а-я]{1,15})(\s)([0-9]{1,4})/', $str, $matches);
                        if (count($matches) != 0) {
                            $r = explode(' ', trim($matches[0]));
                            strlen($r[2]) == 2 ? $Y = intval('20' . $r[2]) : $Y = intval($r[2]);
                            return  date('Y-m-d', mktime(0, 0, 0, date($m), intval($r[0]), $Y));
                        }
                    }
                }

                preg_match('/([0-9]{13,13})/', $str, $number);
                if (count($number) != 0) {
                    $str = substr($number[0], 0, 10);
                    return date('Y-m-d', intval($str));
                }

                preg_match('/([0-9]{10,10})/', $str, $numberS);
                if (count($numberS) != 0) {
                    return date('Y-m-d', intval($numberS[0]));
                }

                preg_match('/([0-9]{4,})-([0-9]{2,})-([0-9]{2,})/', $str, $matches);
                if (count($matches) != 0) {
                    return $matches[0];
                }
            } catch (Exception $e) {
                // print_r($e);
                // тут можно выбросить ошибку если кому надо 
                return $str;
            }
            return $str;
        }
    }
    //  print_r(gettype(1715848395633))
    // $date = new  ConvertDate();
    // echo $date->convert("1715848395633")

?>