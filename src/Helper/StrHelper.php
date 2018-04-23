<?php

namespace Bindeveloperz\Core\Helper;




class StrHelper
{

    public static $encoding = 'UTF-8';

    public static function lower($str)
    {
        return strtolower(trim($str));
    }

    public static function upper($str)
    {
        return strtoupper(trim($str));
    }

    public static function initCap($str)
    {
        return ucwords(trim($str));
    }

    public static function uFirst($str)
    {
        return ucfirst(trim($str));
    }

    public static function nvl($value, $ifnull)
    {
        if ($value == null) {
            return $ifnull;
        } else {
            return $value;
        }
    }

    public static function toLower($string)
    {
        if (self::isMBString()) {
            return mb_strtolower($string, self::$encoding);
        } else {

            // @codeCoverageIgnoreStart
            return strtolower($string);
            // @codeCoverageIgnoreEnd
        }
    }



    public static function trim($value, $extendMode = false)
    {
        $result = (string)trim($value);
        if ($extendMode) {
            $result = trim($result, chr(0xE3) . chr(0x80) . chr(0x80));
            $result = trim($result, chr(0xC2) . chr(0xA0));
            $result = trim($result);
        }
        return $result;
    }


    public static function isEmpty($str)
    {
        $s = trim($str);
        if ((empty($s) and !is_numeric($s)) or (is_numeric($s) and $s == null)) {
            return true;
        } else {
            return false;
        }
    }

    public static function checkMin($num1, $num2, $eq = false)
    {
        if (($num1 < $num2 and !$eq) or ($num1 <= $num2 and $eq)) {
            return true;
        } else {
            return false;
        }
    }

    public static function getMin($num1, $num2, $eq = false)
    {
        if (($num1 < $num2 and !$eq) or ($num1 <= $num2 and $eq)) {
            return $num1;
        } else {
            return $num2;
        }
    }



    public static function removeBraces($str, $opbr = '(', $clbr = ')')
    {
        $rstr = trim($str);
        $leftchar = substr($rstr, 0, 1);
        $rightchar = substr($rstr, -1, 1);
        if (($opbr == '(' and ($leftchar == '(' or $leftchar == '{' or $leftchar == '[')) or $leftchar == $opbr) {
            $rstr = substr($rstr, 1);
        }
        if (($clbr == ')' and ($rightchar == ')' or $rightchar == '}' or $rightchar == ']')) or $rightchar == $clbr) {
            $rstr = substr($rstr, 0, -1);
        }
        return $rstr;
    }


    public static function randUniqID($in, $to_num = false, $pad_up = false, $passKey = null)
    {
        $index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        // $index = "789";
        if ($passKey !== null) {

            // Although this function's purpose is to just make the
            // ID short - and not so much secure,
            // you can optionally supply a password to make it harder
            // to calculate the corresponding numeric ID
            for ($n = 0; $n < strlen($index); $n++) {
                $i[] = substr($index, $n, 1);
            }
            $passhash = hash('sha256', $passKey);
            $passhash = (strlen($passhash) < strlen($index)) ? hash('sha512', $passKey) : $passhash;
            for ($n = 0; $n < strlen($index); $n++) {
                $p[] = substr($passhash, $n, 1);
            }
            array_multisort($p, SORT_DESC, $i);
            $index = implode($i);
        }
        $base = strlen($index);
        if ($to_num) {

            // Digital number  <<--  alphabet letter code
            $in = strrev($in);
            $out = 0;
            $len = strlen($in) - 1;
            for ($t = 0; $t <= $len; $t++) {
                $bcpow = bcpow($base, $len - $t);
                $out = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
            }
            if (is_numeric($pad_up)) {
                $pad_up--;
                if ($pad_up > 0) {
                    $out -= pow($base, $pad_up);
                }
            }
            $out = sprintf('%F', $out);
            $out = substr($out, 0, strpos($out, '.'));
        } else {

            // Digital number  -->>  alphabet letter code
            if (is_numeric($pad_up)) {
                $pad_up--;
                if ($pad_up > 0) {
                    $in += pow($base, $pad_up);
                }
            }
            $out = "";
            for ($t = floor(log($in, $base)); $t >= 0; $t--) {
                $bcp = bcpow($base, $t);
                $a = floor($in / $bcp) % $base;
                $out = $out . substr($index, $a, 1);
                $in = $in - ($a * $bcp);
            }
            $out = strrev($out); // reverse
        }
        return $out;
    }

    public static function randStr($length = 8, $seeds = 'alphanum')
    {

        // Possible seeds
        $seedings['alpha'] = 'abcdefghijklmnopqrstuvwqyz';
        $seedings['numeric'] = '0123456789';
        $seedings['alphanum'] = 'abcdefghijklmnopqrstuvwqyz0123456789';
        $seedings['hexidec'] = '0123456789abcdef';
        // Choose seed
        if (isset($seedings[self::lower($seeds)])) {
            $seeds = $seedings[self::lower($seeds)];
        }
        // Seed generator
        list($usec, $sec) = explode(' ', microtime());
        $seed = (float)$sec + ((float)$usec * 100000);
        mt_srand($seed);
        // Generate
        $str = '';
        $seeds_count = strlen($seeds);
        for ($i = 0; $length > $i; $i++) {
            $str .= $seeds{mt_rand(0, $seeds_count - 1)};
        }
        return $str;
    }



    /**
     * Make string safe
     * - Remove UTF-8 chars
     * - Remove all tags
     * - Trim
     * - Addslashes (opt)
     * - To lower (opt)
     * @param string $string
     * @param bool $toLower
     * @param bool $addslashes
     * @return string
     */
    public static function clean($string, $toLower = false, $addslashes = false)
    {
        $string = strip_tags($string);
        $string = trim($string);
        if ($addslashes) {
            $string = addslashes($string);
        }
        if ($toLower) {
            $string = self::toLower($string);
        }
        return $string;
    }


    /**
     * Check is mbstring loaded
     * @return bool
     */
    public static function isMBString()
    {
        static $isLoaded;
        if (null === $isLoaded) {
            $isLoaded = extension_loaded('mbstring');
            if ($isLoaded) {
                mb_internal_encoding(self::$encoding);
            }
        }
        return $isLoaded;
    }

    /**
     * Get unique string
     * @param  string $prefix
     * @return string
     */
    public static function unique($prefix = 'unique')
    {
        $prefix = rtrim(trim($prefix), '-');
        $random = mt_rand(10000000, 99999999);
        $result = $random;
        if ($prefix) {
            $result = $prefix . '-' . $random;
        }
        return $result;
    }

    /**
     * Pads a given string with zeroes on the left.
     * @param  int $number The number to pad
     * @param  int $length The total length of the desired string
     * @return string
     */
    public static function zeroPad($number, $length)
    {
        return str_pad($number, $length, '0', STR_PAD_LEFT);
    }

    /**
     * Truncate a string to a specified length without cutting a word off.
     * @param   string $string The string to truncate
     * @param   integer $length The length to truncate the string to
     * @param   string $append Text to append to the string IF it gets truncated, defaults to '...'
     * @return  string
     */
    public static function truncateSafe($string, $length, $append = '...')
    {
        $result = self::strPart($string, 0, $length);
        $lastSpace = self::rpos($result, ' ');
        if ($lastSpace !== false && $string != $result) {
            $result = self::strPart($result, 0, $lastSpace);
        }
        if ($result != $string) {
            $result .= $append;
        }
        return $result;
    }

    /**
     * Get part of string
     * @param string $string
     * @param int $start
     * @param int $length
     * @return string
     */
    public static function strPart($string, $start, $length = 0)
    {
        if (self::isMBString()) {
            if (0 == $length) {
                $length = self::length($string);
            }
            return mb_substr($string, $start, $length, self::$encoding);
        } else {

            // @codeCoverageIgnoreStart
            return substr($string, $start, $length);
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Get string length
     * @param $string
     * @return int
     */
    public static function length($string)
    {
        if (self::isMBString()) {
            return mb_strlen($string, self::$encoding);
        } else {

            // @codeCoverageIgnoreStart
            return strlen($string);
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Find position of last occurrence of a string in a string
     * @param string $haystack
     * @param string $needle
     * @param int $offset
     * @return int
     */
    public static function rpos($haystack, $needle, $offset = 0)
    {
        if (self::isMBString()) {
            return mb_strrpos($haystack, $needle, $offset, self::$encoding);
        } else {

            // @codeCoverageIgnoreStart
            return strrpos($haystack, $needle, $offset);
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Truncate the string to given length of characters.
     * @param string $string The variable to truncate
     * @param integer $limit The length to truncate the string to
     * @param string $append Text to append to the string IF it gets truncated, defaults to '...'
     * @return string
     */
    public static function limitChars($string, $limit = 100, $append = '...')
    {
        if (self::length($string) <= $limit) {
            return $string;
        }
        return rtrim(self::strPart($string, 0, $limit)) . $append;
    }

    /**
     * Truncate the string to given length of words.
     * @param string $string
     * @param int $limit
     * @param string $append
     * @return string
     */
    public static function limitWords($string, $limit = 100, $append = '...')
    {
        preg_match('/^\s*+(?:\S++\s*+){1,' . $limit . '}/u', $string, $matches);
        if (!Arr::keyExist(0, $matches) || self::length($string) === self::length($matches[0])) {
            return $string;
        }
        return rtrim($matches[0]) . $append;
    }


    /**
     * Check if a given string matches a given pattern.
     * @param  string $pattern Parttern of string exptected
     * @param  string $string String that need to be matched
     * @param  bool $caseSensitive
     * @return bool
     */
    public static function like($pattern, $string, $caseSensitive = true)
    {
        if ($pattern == $string) {
            return true;
        }
        // Preg flags
        $flags = $caseSensitive ? '' : 'i';
        // Escape any regex special characters
        $pattern = preg_quote($pattern, '#');
        // Unescape * which is our wildcard character and change it to .*
        $pattern = str_replace('\*', '.*', $pattern);
        return (bool)preg_match('#^' . $pattern . '$#' . $flags, $string);
    }

    /**
     * Finds first occurrence of a string within another
     * @param string $haystack
     * @param string $needle
     * @param bool $beforeNeedle
     * @return string
     */
    public static function strStr($haystack, $needle, $beforeNeedle = false)
    {
        if (self::isMBString()) {
            return mb_strstr($haystack, $needle, $beforeNeedle, self::$encoding);
        } else {
            // @codeCoverageIgnoreStart
            return strstr($haystack, $needle, $beforeNeedle);
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Finds first occurrence of a string within another, case insensitive
     * @param string $haystack
     * @param string $needle
     * @param bool $beforeNeedle
     * @return string
     */
    public static function istr($haystack, $needle, $beforeNeedle = false)
    {
        if (self::isMBString()) {
            return mb_stristr($haystack, $needle, $beforeNeedle, self::$encoding);
        } else {
            // @codeCoverageIgnoreStart
            return stristr($haystack, $needle, $beforeNeedle);
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Finds the last occurrence of a character in a string within another
     * @param string $haystack
     * @param string $needle
     * @param bool $part
     * @return string
     */
    public static function rchr($haystack, $needle, $part = null)
    {
        if (self::isMBString()) {
            return mb_strrchr($haystack, $needle, $part, self::$encoding);
        } else {

            // @codeCoverageIgnoreStart
            return strrchr($haystack, $needle);
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Make a string uppercase
     * @param string $string
     * @return string
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public static function toUp($string)
    {
        if (self::isMBString()) {
            return mb_strtoupper($string, self::$encoding);
        } else {

            // @codeCoverageIgnoreStart
            return strtoupper($string);
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Count the number of substring occurrences
     * @param string $haystack
     * @param string $needle
     * @return int
     */
    public static function subCount($haystack, $needle)
    {
        if (self::isMBString()) {
            return mb_substr_count($haystack, $needle, self::$encoding);
        } else {

            // @codeCoverageIgnoreStart
            return substr_count($haystack, $needle);
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Checks if the $haystack starts with the text in the $needle.
     * @param string $haystack
     * @param string $needle
     * @param bool $caseSensitive
     * @return bool
     */
    public static function isStart($haystack, $needle, $caseSensitive = false)
    {
        if ($caseSensitive) {
            return $needle === '' || self::pos($haystack, $needle) === 0;
        } else {
            return $needle === '' || self::ipos($haystack, $needle) === 0;
        }
    }

    /**
     * Find position of first occurrence of string in a string
     * @param string $haystack
     * @param string $needle
     * @param int $offset
     * @return int
     */
    public static function pos($haystack, $needle, $offset = 0)
    {
        if (self::isMBString()) {
            return mb_strpos($haystack, $needle, $offset, self::$encoding);
        } else {

            // @codeCoverageIgnoreStart
            return strpos($haystack, $needle, $offset);
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Finds position of first occurrence of a string within another, case insensitive
     * @param string $haystack
     * @param string $needle
     * @param int $offset
     * @return int
     */
    public static function ipos($haystack, $needle, $offset = 0)
    {
        if (self::isMBString()) {
            return mb_stripos($haystack, $needle, $offset, self::$encoding);
        } else {

            // @codeCoverageIgnoreStart
            return stripos($haystack, $needle, $offset);
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Checks if the $haystack ends with the text in the $needle. Case sensitive.
     * @param string $haystack
     * @param string $needle
     * @param bool $caseSensitive
     * @return bool
     */
    public static function isEnd($haystack, $needle, $caseSensitive = false)
    {
        if ($caseSensitive) {
            return $needle === '' || self::strPart($haystack, -self::length($needle)) === $needle;
        } else {
            $haystack = self::toLower($haystack);
            $needle = self::toLower($needle);
            return $needle === '' || self::strPart($haystack, -self::length($needle)) === $needle;
        }
    }


    /**
     * Escape UTF-8 strings
     * @param string $string
     * @return string
     */
    public static function esc($string)
    {
        return htmlspecialchars($string, ENT_NOQUOTES, self::$encoding);
    }

    /**
     * Convert camel case to human readable format
     * @param string $input
     * @param string $separator
     * @param bool $toLower *
     * @return string
     */
    public static function splitCamelCase($input, $separator = '_', $toLower = true)
    {
        $original = $input;
        $output = preg_replace(array(
            '/(?<=[^A-Z])([A-Z])/',
            '/(?<=[^0-9])([0-9])/',
        ), '_$0', $input);
        $output = preg_replace('#_{1,}#', $separator, $output);
        $output = trim($output);
        if ($toLower) {
            $output = strtolower($output);
        }
        if (strlen($output) == 0) {
            return $original;
        }
        return $output;
    }



    /**
     * Generates a universally unique identifier (UUID v4) according to RFC 4122
     * Version 4 UUIDs are pseudo-random!
     * Returns Version 4 UUID format: xxxxxxxx-xxxx-4xxx-Yxxx-xxxxxxxxxxxx where x is
     * any random hex digit and Y is a random choice from 8, 9, a, or b.
     * @see http://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid
     * @return string
     */
    public static function uuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), // 16 bits for "time_mid"
            mt_rand(0, 0xffff), // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000, // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000, // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
    }

    /**
     * Increments a trailing number in a string.
     * Used to easily create distinct labels when copying objects. The method has the following styles:
     *  - default: "Label" becomes "Label (2)"
     *  - dash:    "Label" becomes "Label-2"
     * @param   string $string The source string.
     * @param   string $style The the style (default|dash).
     * @param   integer $next If supplied, this number is used for the copy, otherwise it is the 'next' number.
     * @return  string
     */
    public static function inc($string, $style = 'default', $next = 0)
    {
        $styles = array(
            'dash' => array(
                '#-(\d+)$#',
                '-%d',
            ),
            'default' => array(
                array(
                    '#\((\d+)\)$#',
                    '#\(\d+\)$#',
                ),
                array(
                    ' (%d)',
                    '(%d)',
                ),
            ),
        );
        $styleSpec = isset($styles[$style]) ? $styles[$style] : $styles['default'];
        // Regular expression search and replace patterns.
        if (is_array($styleSpec[0])) {
            $rxSearch = $styleSpec[0][0];
            $rxReplace = $styleSpec[0][1];
        } else {
            $rxSearch = $rxReplace = $styleSpec[0];
        }
        // New and old (existing) sprintf formats.
        if (is_array($styleSpec[1])) {
            $newFormat = $styleSpec[1][0];
            $oldFormat = $styleSpec[1][1];
        } else {
            $newFormat = $oldFormat = $styleSpec[1];
        }
        // Check if we are incrementing an existing pattern, or appending a new one.
        if (preg_match($rxSearch, $string, $matches)) {
            $next = empty($next) ? ($matches[1] + 1) : $next;
            $string = preg_replace($rxReplace, sprintf($oldFormat, $next), $string);
        } else {
            $next = empty($next) ? 2 : $next;
            $string .= sprintf($newFormat, $next);
        }
        return $string;
    }

    /**
     * Strip quotes.
     * @param string $value
     * @return string
     */
    public static function stripQuotes($value)
    {
        if ($value[0] === '"' && substr($value, -1) === '"') {
            $value = trim($value, '"');
        }
        if ($value[0] === "'" && substr($value, -1) === "'") {
            $value = trim($value, "'");
        }
        return $value;
    }

    /**
     * Get safe string
     * @param $string
     * @return mixed
     */
    public static function strip($string)
    {
        $cleaned = strip_tags($string);
        $cleaned = self::trim($cleaned);
        return $cleaned;
    }



    /**
     * Rmove whitespaces
     * @param $value
     * @return string
     */
    public static function path($value)
    {
        $pattern = '#^[A-Za-z0-9_\/-]+[A-Za-z0-9_\.-]*([\\\\\/][A-Za-z0-9_-]+[A-Za-z0-9_\.-]*)*$#';
        preg_match($pattern, $value, $matches);
        $result = isset($matches[0]) ? (string)$matches[0] : '';
        return $result;
    }




    /**
     * Smart convert any string to int
     * @param string $value
     * @return int
     */
    public static function strToInt($value)
    {
        $cleaned = preg_replace('#[^0-9-+.,]#', '', $value);
        preg_match('#[-+]?[0-9]+#', $cleaned, $matches);
        $result = isset($matches[0]) ? $matches[0] : 0;
        return (int)$result;
    }

    /**
     * Return only alpha chars
     * @param $value
     * @return mixed
     */
    public static function onlyAlpha($value)
    {
        return preg_replace('#[^[:alpha:]]#', '', $value);
    }

    /**
     * Return only alpha and digits chars
     * @param $value
     * @return mixed
     */
    public static function onlyAlphaNum($value)
    {
        return preg_replace('#[^[:alnum:]]#', '', $value);
    }

    /**
     * Access an array index, retrieving the value stored there if it exists or a default if it does not.
     * This function allows you to concisely access an index which may or may not exist without raising a warning.
     * @param  array $var Array value to access
     * @param  mixed $default Default value to return if the key is not
     * @return mixed
     */
    public static function get(&$var, $default = null)
    {
        if (isset($var)) {
            return $var;
        }
        return $default;
    }


    /**
     * Get relative percent
     * @param float $normal
     * @param float $current
     * @return string
     */
    public static function relativePercent($normal, $current)
    {
        $normal = (float)$normal;
        $current = (float)$current;
        if (!$normal || $normal == $current) {
            return '100';
        } else {
            $normal = abs($normal);
            $percent = round($current / $normal * 100);
            return number_format($percent, 0, '.', ' ');
        }
    }

    /**
     * Checks if a string ends with a given substring.
     * @param string $str
     * @param string $substr
     * @return bool
     */
    public static function endsWith($str, $substr)
    {
        return substr($str, -strlen($substr)) == $substr;
    }

    /**
     * Limits a string to a certain length by imploding the middle part of it.
     * @param string $text
     * @param int $limit
     * @param string $more Symbol that represents the removed part of the original string.
     * @return string
     */
    public static function cut($text, $limit, $more = '...')
    {
        if (strlen($text) > $limit) {
            $chars = floor(($limit - strlen($more)) / 2);
            $p = strpos($text, ' ', $chars) + 1;
            $d = $p < 1 ? 0 : $p - $chars;
            return substr($text, 0, $chars + $d) . $more . substr($text, -$chars + $d);
        }
        return $text;
    }



    /**
     * Converts an hyphenated compound word into a camel-cased form.
     * Ex: `my-long-name => myLongName` or `my_long_name => myLongName`
     * @param string $name
     * @param bool $ucfirst When `true` the first letter is capitalized, otherwhise it is lower cased.
     * @param string $delimiter [optional] The character that is considered to be the 'hyphen'.
     * @return string
     */
    public static function dehyphenate($name, $ucfirst = false, $delimiter = '-')
    {
        $s = str_replace($delimiter, '', ucwords($name, $delimiter));
        return $ucfirst ? $s : lcfirst($s);
    }

    /**
     * Converts a string to camel cased form.
     * @param string $name
     * @param bool $ucfirst When `true` the first letter is capitalized, otherwhise it is lower cased.
     * @return string
     */
    public static function camelize($name, $ucfirst = false)
    {
        $s = str_replace(' ', '', ucwords($name));
        return $ucfirst ? $s : lcfirst($s);
    }

    /**
     * Converts a string from camel cased form to some symbol-delimited list of words.
     * @param string $name
     * @param bool $ucwords [optional] When true, each word is capitalized, otherwise it's "de-capitalized".
     * @param string $delimiter [optional] Joins words with this symbol. Defaults to space.
     * @return string
     */
    public static function decamelize($name, $ucwords = false, $delimiter = ' ')
    {
        $w = preg_split('/(?<!^)(?=[A-Z])|(?<!\d)(?=\d)/', $name);
        return implode($delimiter, $ucwords ? array_map('ucfirst', $w) : array_map('lcfirst', $w));
    }

    /**
     * Lowercase the first character of each word in a string.
     * @param string $str
     * @param string $delimiter The character used for delimiting words.
     * @return string
     */
    public static function lcwords($str, $delimiter = ' ')
    {
        return implode($delimiter, array_map('lcfirst', explode($delimiter, $str)));
    }

    public static function trimText($text, $maxSize, $marker = ' (...)')
    {
        if (strlen($text) <= $maxSize) {
            return $text;
        }
        $a = explode(' ', substr($text, 0, $maxSize));
        array_pop($a);
        return join(' ', $a) . $marker;
    }

    /**
     * Strip all witespaces from the given string.
     * @param  string $string The string to strip
     * @return string
     */
    public static function stripSpace($string)
    {
        return preg_replace('/\s+/', '', $string);
    }

    public static function yesNo($val, $label = array(
        "No",
        "Yes",
    ))
    {
        if ($val == 0) {
            return $label[0];
        } else {
            return $label[1];
        }
    }

    public static function br2nl($data)
    {
        return preg_replace('!<br.*>!iU', "\n", $data);
    }



    public static function nl2brLimit($string, $num)
    {
        $dirty = preg_replace('/\r/', '', $string);
        $clean = preg_replace('/\n{4,}/', str_repeat('<br/>', $num), preg_replace('/\r/', '', $dirty));
        return nl2br($clean);
    }




    public static function cleanUp($data)
    {
        $data = trim(strip_tags(htmlspecialchars($data)));
        return $data;
    }




    public static function join($s1, $s2, $delimiter)
    {
        return strlen($s1) && strlen($s2) ? $s1 . $delimiter . $s2 : (strlen($s1) ? $s1 : $s2);
    }

    /**
     * Performs cropping on strings having embedded tags.
     * This is specially useful when used with color-tagged strings meant for terminal output.
     * > Ex: `"
     * <color-name>text
     * </color-name>"`
     * @param string $str
     * @param int $width The desired minimum width, in characters.
     * @param string $marker The overflow marker.
     * @return string
     */
    public static function taggedStrCrop($str, $width, $marker = '')
    {
        $w = self::taggedStrLen($str);
        if ($w <= $width) {
            return $str;
        }
        $o = '';
        $tags = [];
        $curLen = 0;
        $markLen = mb_strlen($marker, 'UTF-8');
        while (strlen($str)) {
            if (!preg_match('/<(.*?)>/u', $str, $m, PREG_OFFSET_CAPTURE)) {
                return $o . mb_substr($str, 0, $width - $curLen - $markLen) . $marker;
            }
            list($tag, $ofs) = $m[0];
            $tagName = $m[1][0];
            $seg = mb_substr($str, 0, $ofs);
            $str = mb_substr($str, $ofs + mb_strlen($tag, 'UTF-8'));
            $segLen = mb_strlen($seg, 'UTF-8');
            $curLen += $segLen;
            if ($curLen >= $width) {
                $o .= mb_substr($seg, 0, $width - $curLen - $markLen) . $marker;
                break;
            } else {
                $o .= $seg;
            }
            if ($tag[1] == '/') {
                array_pop($tags);
            } else {
                $tags[] = $tagName;
            }
            $o .= "$tag";
        }
        while ($tags) {
            $o .= '
  </' . array_pop($tags) . '>';
        }
        return $o;
    }

    /**
     * Returns the true length of strings having embedded color tags.
     * This is specially useful when used with color-tagged strings meant for terminal output.
     * > Ex: `"
     * <color-name>text
     * </color-name>"`
     * @param string $str
     * @return int The string's length, in characters.
     */
    public static function taggedStrLen($str)
    {
        return mb_strlen(preg_replace('/
<[^>]*>/u', '', $str));
    }

    /**
     * Extracts a substring from a string using a search pattern, returning a fixed-length array with the match and
     * the capture groups.
     * @param string $source The string from where to match a pattern.
     * @param string $pattern A regular expression for selecting what text to match.
     * @param int $groups [optional] How many groups to return, even if not all optional groups are present.
     *                               The returned array will alwaus contain n+1 entries: the total match and the
     *     capture groups.
     * @param bool $falseOnNoMatch If true, when no match is found, false is returned instead of an array with
     *     empty
     *                               entries.
     * @param mixed $emptyValue The value with which to pad the returned array.
     * @return array|false The match, which consists of the total matched string and each of the captured groups.
     */
    public static function match($source, $pattern, $groups = 0, $falseOnNoMatch = false, $emptyValue = '')
    {
        if (!preg_match($pattern, $source, $m)) {
            return $falseOnNoMatch ? false : array_fill(0, $groups + 1, $emptyValue);
        }
        return array_pad($m, $groups + 1, $emptyValue);
    }

    /**
     * Extracts a substring from a string using a search pattern, removing the match from the original string and
     * returning it, or the first capture group, if one is defined.
     * @param string $source The string from where to extract a substring.
     * @param string $pattern A regular expression for selecting what text to extract.
     * @return string The extracted text, or '' if nothing matched.
     */
    public static function extract(&$source, $pattern)
    {
        $out = '';
        $source = preg_replace_callback($pattern, function ($m) use (&$out) {
            $out = count($m) > 1 ? $m[1] : $m[0];
            return '';
        }, $source);
        return $out;
    }

    /**
     * Extracts a substring from the beginning of string using a search pattern for matching a delimitir sequence,
     * returning both the extracted segment and the remaining string.
     * <p>Empty matches are skipped until a non-empty match is found.
     * @param string $source The string from where to extract a substring.
     * @param string $delimiterPattern A regular expression for selecting where to split. Note that everything that
     *     is matched by the pattern is stripped from both results.
     * @return string[] The extracted text and the remaining string. It always returns an array with 2 elements.
     */
    public static function extractSegment($source, $delimiterPattern)
    {
        return array_merge(preg_split($delimiterPattern, $source, 2, PREG_SPLIT_NO_EMPTY), [
            '',
            '',
        ]);
    }

    /**
     * Returns the first `$count` segments of a string segmented by a given delimiter.
     * >
     * <p>**Ex:** you can use this to extract file path segments (delimited by `'/'`).
     * @param string $str
     * @param string $delimiter The segment delimiter to search for (ex: '/').
     * @param int $count How many segments to retrieve.
     * @return string
     * @see splitGetFirst which is similar, but returns an array.
     */
    public static function segmentsFirst($str, $delimiter, $count = 1)
    {
        $p = -1;
        while ($count-- && $p !== false) {
            $p = strpos($str, $delimiter, $p + 1);
        }
        if ($p === false) {
            return $str;
        }
        return substr($str, 0, $p);
    }

    /**
     * Returns the last `$count` segments of a string segmented by a given delimiter.
     * >
     * <p>**Ex:** you can use this to extract file path segments (delimited by `'/'`) or to get a file extension.
     * @param string $str
     * @param string $delimiter The segment delimiter to search for (ex: '/').
     * @param int $count How many segments to retrieve.
     * @return string
     * @see splitGetLast which is similar, but returns an array.
     */
    public static function segmentsLast($str, $delimiter, $count = 1)
    {
        $p = 0;
        while ($count-- && $p !== false) {
            $p = strrpos($str, $delimiter, -$p - 1);
        }
        if ($p === false) {
            return $str;
        }
        return substr($str, $p + 1);
    }

    /**
     * Removes the first `$count` segments of a string segmented by a given delimiter.
     * >
     * <p>**Ex:** you can use this to remove segments of a file path.
     * @param string $str
     * @param string $delimiter The segment delimiter to search for (ex: '/').
     * @param int $count How many segments to remove.
     * @return string
     * @see splitStripFirst which is similar, but returns an array.
     */
    public static function segmentsStripFirst($str, $delimiter, $count = 1)
    {
        $p = -1;
        while ($count-- && $p !== false) {
            $p = strpos($str, $delimiter, $p + 1);
        }
        if ($p === false) {
            return $str;
        }
        return substr($str, $p + 1);
    }

    /**
     * Removes the last `$count` segments of a string segmented by a given delimiter.
     * >
     * <p>**Ex:** you can use this to remove an extension from a file path.
     * @param string $str
     * @param string $delimiter The segment delimiter to search for (ex: '/').
     * @param int $count How many segments to remove.
     * @return string
     * @see splitStripLast which is similar, but returns an array.
     */
    public static function segmentsStripLast($str, $delimiter, $count = 1)
    {
        $p = 0;
        while ($count-- && $p !== false) {
            $p = strrpos($str, $delimiter, -$p - 1);
        }
        if ($p === false) {
            return $str;
        }
        return substr($str, 0, $p);
    }

    /**
     * Returns the first `$count` segments of a string segmented by a given delimiter.
     * >
     * <p>**Ex:** you can use this to extract file path segments (delimited by `'/'`).
     * @param string $str
     * @param string $delimiter The segment delimiter to search for (ex: '/').
     * @param int $count How many segments to retrieve.
     * @return string[]
     * @see segmentsFirst which is similar, but returns a string.
     */
    public static function splitGetFirst($str, $delimiter, $count = 1)
    {
        return array_slice(explode($delimiter, $str, $count + 1), 0, $count);
    }

    /**
     * Returns the last `$count` segments of a string segmented by a given delimiter.
     * >
     * <p>**Ex:** you can use this to extract file path segments (delimited by `'/'`) or to get a file extension.
     * @param string $str
     * @param string $delimiter The segment delimiter to search for (ex: '/').
     * @param int $count How many segments to retrieve.
     * @return string[]
     * @see segmentsLast which is similar, but returns a string.
     */
    public static function splitGetLast($str, $delimiter, $count = 1)
    {
        return array_slice(explode($delimiter, $str), -$count);
    }

    /**
     * Removes the first `$count` segments of a string segmented by a given delimiter.
     * >
     * <p>**Ex:** you can use this to remove segments of a file path.
     * @param string $str
     * @param string $delimiter The segment delimiter to search for (ex: '/').
     * @param int $count How many segments to remove.
     * @return string[]
     * @see segmentsStripFirst which is similar, but returns a string.
     */
    public static function splitStripFirst($str, $delimiter, $count = 1)
    {
        return array_slice(explode($delimiter, $str), $count);
    }

    /**
     * Removes the last `$count` segments of a string segmented by a given delimiter.
     * >
     * <p>**Ex:** you can use this to remove an extension from a file path.
     * @param string $str
     * @param string $delimiter The segment delimiter to search for (ex: '/').
     * @param int $count How many segments to remove.
     * @return string[]
     * @see segmentsStripLast which is similar, but returns a string.
     */
    public static function splitStripLast($str, $delimiter, $count = 1)
    {
        return explode($delimiter, $str, -$count);
    }

    /**
     * Finds the position of the first occurrence of a pattern in a given string.
     * @param string $str The string where to search on.
     * @param string $pattern A regular expression.
     * @param string $match [optional] If a variable is specified, it will be set to the matched substring.
     * @return bool|int false if no match was found.
     * @internal param int $from The position where the search begins, counted from the beginning of the current string.
     */
    public static function search($str, $pattern, &$match = null)
    {
        if (preg_match($pattern, $str, $m, PREG_OFFSET_CAPTURE)) {
            list($match, $ofs) = $m[0];
            return $ofs;
        }
        return false;
    }

    /**
     * Performs a simple english pluralization of an "x thing(s)" phrase.
     * @param number $num
     * @param string $thing
     * @return string
     */
    public static function simplePluralize($num, $thing)
    {
        return sprintf('%s%s', $thing, $num == 1 ? '' : 's');
    }

    /**
     * Indents a (possibly multiline) string.
     * @param string $str
     * @param int $level The indentation level; it will be multiplied by 2.
     * @param string $indent A pattern to be output at the start of each line, repeated $level times.
     * @return string
     */
    public static function indent($str, $level = 1, $indent = '  ')
    {
        return preg_replace('/^/m', str_repeat($indent, $level), $str);
    }

    /**
     * Checks if the specified value is not empty.
     * <p>**Note:** an empty value is `null`, an empty string or an empty array.
     * <p>**Warning:** do not use this for checking the existence of array elements or object properties.<br />
     * `exists()` is not equivalent to `empty()` or `isset()`, as those are special language constructs.
     * <br />For instance, these expression will cause PHP warnings:
     * <code>
     *   if (empty($array[$key])
     *   if (empty($obj->$key)
     * </code>
     * @param mixed $exp
     * @return bool `true` if the value is not empty.
     */
    public static function exists($exp)
    {
        return isset($exp) && $exp !== '' && $exp !== [];
    }

    public static function humanise($string)
    {
        $spacedOut = str_replace([
            '-',
            '_',
        ], ' ', $string);
        $talkingInCaps = ucwords($spacedOut);
        return trim($talkingInCaps);
    }

    public static function tagStringToArray($tagstring)
    {
        $tags = explode(',', trim($tagstring));
        $tags = array_map(function ($string) {
            return htmlspecialchars(trim($string), ENT_QUOTES);
        }, $tags);
        return $tags;
    }

    /**
     * Generate a unique alpha numeric random string without uppercase letters.
     * @param int $length
     * @return string
     */
    public static function stringRandom($length = 32)
    {
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Given a string haystack, remove every occurrence of the string $needle in
     * $haystack and return the result string.
     * @param string $haystack
     * @param string $needle
     * @return string
     */
    public static function stringWithout($haystack, $needle)
    {
        if (!empty($haystack) && !empty($needle)) {
            if (strpos($haystack, $needle) !== false) {
                return str_replace($needle, '', $haystack);
            }
        }
        return $haystack;
    }

    /**
     * @param string $haystack
     * @param string $delimiter
     * @return bool|string
     */
    public static function stringAfter($haystack, $delimiter)
    {
        if (!empty($haystack) && !empty($delimiter)) {
            if (strpos($haystack, $delimiter) !== false) {

                // separate $haystack in two strings and put each string in an array
                $filter = explode($delimiter, $haystack);
                if (isset($filter[1])) {

                    // return the string after $delimiter
                    return $filter[1];
                }
                return false;
            }
            return false;
        }
        return false;
    }

    /**
     * @param string $haystack
     * @param string $delimiter1
     * @param string $delimiter2
     * @return bool|string
     */
    public static function stringBetween($haystack, $delimiter1, $delimiter2)
    {
        if (!empty($haystack) && !empty($delimiter1) && !empty($delimiter2)) {
            if (strpos($haystack, $delimiter1) !== false && strpos($haystack, $delimiter2) !== false) {

                // separate $haystack in two strings and put each string in an array
                $pre_filter = explode($delimiter1, $haystack);
                if (isset($pre_filter[1])) {

                    // remove everything after the $delimiter2 in the second line of the
                    // $pre_filter[] array
                    $post_filter = explode($delimiter2, $pre_filter[1]);
                    if (isset($post_filter[0])) {

                        // return the string between $delimiter1 and $delimiter2
                        return $post_filter[0];
                    }
                    return false;
                }
                return false;
            }
            return false;
        }
        return false;
    }

    /**
     * Determine if a given string starts with a given substring.
     * @param  string $haystack
     * @param  string|array $needles
     * @return bool
     */
    public static function stringStartsWith($haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if ($needle != '' && mb_strpos($haystack, $needle) === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if a given string ends with a given substring.
     * @param  string $haystack
     * @param  string|array $needles
     * @return bool
     */
    public static function stringEndsWith($haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if ((string)$needle === mb_substr($haystack, -self::stringLength($needle))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Return the length of the given string.
     * @param  string $value
     * @return int
     */
    public static function stringLength($value)
    {
        return mb_strlen($value);
    }

    /**
     * Determine if a given string matches a given pattern.
     * @param  string $pattern
     * @param  string $value
     * @return bool
     */
    public static function stringIs($pattern, $value)
    {
        if ($pattern == $value) {
            return true;
        }
        $pattern = preg_quote($pattern, '#');
        // Asterisks are translated into zero-or-more regular expression wildcards
        // to make it convenient to check if the strings starts with the given
        // pattern such as "library/*", making any string check convenient.
        $pattern = str_replace('\*', '.*', $pattern);
        return (bool)preg_match('#^' . $pattern . '\z#u', $value);
    }

    /**
     * Determine if a given string contains a given substring.
     * @param  string $haystack
     * @param  string|array $needles
     * @return bool
     */
    public static function stringContains($haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if ($needle != '' && mb_strpos($haystack, $needle) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Cap a string with a single instance of a given value.
     * @param  string $value
     * @param  string $cap
     * @return string
     */
    public static function stringFinish($value, $cap)
    {
        $quoted = preg_quote($cap, '/');
        return preg_replace('/(?:' . $quoted . ')+$/u', '', $value) . $cap;
    }

    /**
     * Determine if a string is a valid url.
     * @param string $string
     * @return bool
     */
    public static function isUrl($string)
    {
        return (bool)filter_var($string, FILTER_VALIDATE_URL);
    }


    /**
     * Determine whether the current environment is Windows based.
     * @return bool
     */
    public static function isWindowsOs()
    {
        return strtolower(substr(PHP_OS, 0, 3)) === 'win';
    }

    public static function randomEmail($length = 10)
    {
        return self::random($length) . '@' . self::random(5) . '.com';
    }

    /**
     * Generate ridable random string
     * @param int $length
     * @param bool $isReadable
     * @return string
     */
    public static function random($length = 10, $isReadable = true)
    {
        $result = '';
        if ($isReadable) {
            $vocal = array(
                'a',
                'e',
                'i',
                'o',
                'u',
                '0',
            );
            $conso = array(
                'b',
                'c',
                'd',
                'f',
                'g',
                'h',
                'j',
                'k',
                'l',
                'm',
                'n',
                'p',
                'r',
                's',
                't',
                'v',
                'w',
                'x',
                'y',
                'z',
                '1',
                '2',
                '3',
                '4',
                '5',
                '6',
                '7',
                '8',
                '9',
            );
            $max = $length / 2;
            for ($pos = 1; $pos <= $max; $pos++) {
                $result .= $conso[mt_rand(0, count($conso) - 1)];
                $result .= $vocal[mt_rand(0, count($vocal) - 1)];
            }
        } else {
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            for ($pos = 0; $pos < $length; $pos++) {
                $result .= $chars[mt_rand() % strlen($chars)];
            }
        }
        return $result;
    }



    public static function escStr($escape)
    {
        $escape = str_replace("'", "\\'", $escape);
        return $escape;
    }



}