<?php

namespace Bindeveloperz\Core\Helper;


class NumHelper
{


    public static function formatNumber($number)
    {

        // make sure the number is actually a number
        if (is_numeric($number)) {

            // if number starts with a 0 replace with 9
            if ($number[0] == 0) {
                $number[0] = str_replace("0", "2", $number[0]);
                $number = "9" . $number;
            }
            $number = preg_replace("/([0-9]{2})([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3-$4", $number);
            // return the number
            return $number;
            // number is not a number
        } else {

            // return nothing
            return false;
        }
    }


    public static function convertNumToRoman($number)
    {

        $table = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $return = '';
        while ($number > 0) {
            foreach ($table as $rom => $arb) {
                if ($number >= $arb) {
                    $number -= $arb;
                    $return .= strtolower($rom);
                    break;
                }
            }
        }
        return $return;
    }

    /**
     * Limits the number between two bounds.
     * @param int $number
     * @param int $min
     * @param int $max
     * @return int
     */
    public static function limitTwoNumbers($number, $min, $max)
    {
        return self::maxNumberDecrease(self::minNumberInc($number, $min), $max);
    }

    /**
     * Decrease the number to the maximum if above threshold.
     * @param int $number
     * @param int $max
     * @return int
     */
    public static function maxNumberDecrease($number, $max)
    {
        if ($number > $max) {
            $number = $max;
        }
        return $number;
    }

    /**
     * Increase the number to the minimum if below threshold.
     * @param int $number
     * @param int $min
     * @return int
     */
    public static function minNumberInc($number, $min)
    {
        if ($number < $min) {
            $number = $min;
        }
        return $number;
    }

    /**
     * Return true if the number is within the min and max.
     * @param int|float $number
     * @param int|float $min
     * @param int|float $max
     * @return bool
     */
    public static function isIn($number, $min, $max)
    {
        return ($number >= $min && $number <= $max);
    }

    /**
     * Is the current value negative; less than zero.
     * @param int $number
     * @return bool
     */
    public static function isNegative($number)
    {
        return ($number < 0);
    }

    /**
     * Is the current value odd?
     * @param int $number
     * @return bool
     */
    public static function isOdd($number)
    {
        return !self::isEven($number);
    }

    /**
     * Is the current value even?
     * @param int $number
     * @return bool
     */
    public static function isEven($number)
    {
        return ($number % 2 === 0);
    }

    /**
     * Is the current value positive; greater than or equal to zero.
     * @param int $number
     * @param bool $zero
     * @return bool
     */
    public static function isPositive($number, $zero = true)
    {
        return ($zero ? ($number >= 0) : ($number > 0));
    }

    /**
     * Return true if the number is outside the min and max.
     * @param int $number
     * @param int $min
     * @param int $max
     * @return bool
     */
    public static function outMinMax($number, $min, $max)
    {
        return ($number < $min || $number > $max);
    }


    public static function numberFormat($num, $space = 5, $fill = ' ', $deci = 2)
    {
        if (!empty($num) and is_numeric($num)) {
            return str_pad(number_format($num, $deci, '.', ','), $space, $fill, STR_PAD_LEFT);
        } else {
            return null;
        }
    }




    // $curency = $params['currency'];

    public static function currency($num, $sym = '')
    {
        $params = '';
        if ($sym == null or $sym == '') {
            if (isset($params['currency'])) {
                $sym = $params['currency'];
            } else {
                $sym = '';
            }
        }
        if ($num == 0) {
            return $sym . ' 0.0';
        } else {
            $amnt = self::numberFormat($num, 5, ' ', 0);
            if ($amnt != null) {
                return $sym . ' ' . $amnt;
            } else {
                return null;
            }
        }
    }


    /**
     * @param string $value
     * @param int $round
     * @return float
     */
    public static function floatInt($value, $round = 10)
    {
        $cleaned = preg_replace('#[^0-9eE\-\.\,]#ius', '', $value);
        $cleaned = str_replace(',', '.', $cleaned);
        preg_match('#[-+]?[0-9]+(\.[0-9]+)?([eE][-+]?[0-9]+)?#', $cleaned, $matches);
        $result = isset($matches[0]) ? $matches[0] : 0.0;
        $result = round($result, $round);
        return (float)$result;
    }




    /**
     * Return only digits chars
     * @param $value
     * @return mixed
     */
    public static function onlyDigits($value)
    {

        // we need to remove - and + because they're allowed in the filter
        $cleaned = str_replace(array(
            '-',
            '+',
        ), '', $value);
        $cleaned = filter_var($cleaned, FILTER_SANITIZE_NUMBER_INT);
        return $cleaned;
    }




}