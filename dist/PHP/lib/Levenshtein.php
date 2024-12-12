<?php

namespace PHP\lib;

class Levenshtien
{
    /**
     * compareWord
     *
     * @param  string $str
     * @param  array $words
     * @return string
     */
    public static function compareWord(string $str, array $words = []): string
    {

        $shortest = -1;
        $result = '';

        foreach ($words as $word) {
            $lev = levenshtein($str, $word);
            if ($lev == 0) {
                $result = $word;
                $shortest = 0;
                break;
            }
            if ($lev <= $shortest || $shortest < 0) {
                $result  = $word;
                $shortest = $lev;
            }
        }
        return $result; // вернет пустую строку если масив пустой
    }
}
