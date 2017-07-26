<?php

class NumReader
{
    // === usable library
    private $wordID = [
        '1' => 'satu',
        '2' => 'dua',
        '3' => 'tiga',
        '4' => 'empat',
        '5' => 'lima',
        '6' => 'enam',
        '7' => 'tujuh',
        '8' => 'delapan',
        '9' => 'sembilan',
        'e1' => 'puluh',
        'e2' => 'ratus',
        'e3' => 'ribu',
        'e6' => 'juta',
        'e9' => 'milyar',
        'e12' => 'triliun',
        'o1' => 'se',
        'o2' => 'belas',
    ];

    // === Read 3 digit numbers
    private function readTriad($hundredths, $tenths, $ones, $powerOf3 = false)
    {
        $words = [];
        $num = $hundredths;
        if ($num == 1) {
            array_push($words, 'o1', 'e2');
        } else if ($num >= 2 && $num <= 9) {
            array_push($words, $num, 'e2');
        }

        if ($tenths != 1) {
            if ($tenths != 0) {
                array_push($words, $tenths, 'e1');
            }
            if (($ones != 0 && $powerOf3 == false) || ($ones >= 2 && $ones <= 9 && $powerOf3 == true)) {
                array_push($words, $ones);
            } else if ($ones == 1 && $powerOf3 == true)
                array_push($words, 'o1');
        } else {
            if ($ones == 0) {
                array_push($words, 'o1', 'e1');
            } elseif ($ones == 1) {
                array_push($words, 'o1', 'o2');
            } else {
                array_push($words, $ones, 'o2');
            }
        }
        return $words;
    }

    // === Translate string into word ID's
    public function translateIt($inputNumber)
    {
        $string = "$inputNumber";
        $digit = strlen($string);
        $numOfOp = floor($digit / 3);
        $lastOp = $digit % 3;
        $words = [];

        if ($lastOp !== 0) {                            // === Evaluating the front non 'multiple of 3' digits
            if ($lastOp == 2) {
                $tenths = substr($string, 0, 1);
                $ones = substr($string, 1, 1);
            } else if ($lastOp == 1) {
                $tenths = 0;
                $ones = substr($string, 0, 1);
            }
            $hundredths = 0;
            $power = "e".(3 * $numOfOp);
            if ($power == 'e3') {
                $powerOf3 = 1;
            } else {
                $powerOf3 = 0;
            }
            $temp = $this->readTriad($hundredths, $tenths, $ones, $powerOf3);
            if ($numOfOp > 0) {
                array_push($temp, $power);
            }
            $words = array_merge($words, $temp);
        }
        $string = substr($string, $lastOp);                #removes an amont of digits so that the string have 'multiple of 3' digits
        for ($i = $numOfOp; $i >= 1; $i--) {            // === Evaluating per 3 digit
            echo ".$string<br/>";
            $ones = substr($string, (2), 1);
            $tenths = substr($string, (1), 1);
            $hundredths = substr($string, (0), 1);
            $power = "e".(3 * ($i - 1));
            $temp = $this->readTriad($hundredths, $tenths, $ones);
            if ($power !== "e0") {
                $temp[] = $power;
            }    #avoid printing "e0" into the array
            $words = array_merge($words, $temp);
            $string = substr($string, 3);            #removes the first 3 digit number to be used for next sequence

        }
        return $words;
    }

    // === Turning code into sentence
    public function readIt($inputCode)
    {
        $sentence = "";
        foreach ($inputCode as $key => $code) {
            $sentence .= $this->wordID[$code];
            if ($code != 'o1') {
                $sentence .= " ";
            }
        }
        return $sentence;
    }
}