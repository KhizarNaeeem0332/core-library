<?php

namespace Bindeveloperz\Core\Helper;


use InvalidArgumentException;

class ArrHelper
{


    /*
    * Gets a GET request variable by key.
    *
    * @param mixed $key     $_GET variable key
    * @param mixed $default Default return value; defaults to null
    * @param bool  $found   Flag to indicate whether the variable exists
    *
    * @return mixed
    */
    public static function getVar($key, $default = null, &$found = null)
    {
        $var = self::arrayGet($_GET, $key, $default, $found);
        return strlen(trim($var)) ? $var : $default;
    }

    /*
    * Gets a POST request variable by key.
    *
    * @param mixed $key     $_POST variable key
    * @param mixed $default Default return value; defaults to null
    * @param bool  $found   Flag to indicate whether the variable exists
    *
    * @return mixed
    */
    public static function postVar($key, $default = null, &$found = null)
    {
        $var = self::arrayGet($_POST, $key, $default, $found);
        return strlen(trim($var)) ? $var : $default;
    }

    /*
    * Gets a REQUEST variable by key.
    *
    * @param mixed $key     $_REQUEST variable key
    * @param mixed $default Default return value; defaults to null
    * @param bool  $found   Flag to indicate whether the variable exists
    *
    * @return mixed
    */
    public static function requestVar($key, $default = null, &$found = null)
    {
        $var = self::arrayGet($_REQUEST, $key, $default, $found);
        return strlen(trim($var)) ? $var : $default;
    }


    /*
    * Gets a server variable by key.
    *
    * @param mixed $key     $_SERVER variable key
    * @param mixed $default Default return value; defaults to null
    * @param bool  $found   Flag to indicate whether the variable exists
    *
    * @return mixed
    */
    public static function serverVar($key, $default = null, &$found = null)
    {
        return self::arrayGet($_SERVER, $key, $default, $found);
    }

    /*
    * Gets a session variable by key.
    *
    * @param mixed $key     $_SESSION variable key
    * @param mixed $default Default return value; defaults to null
    * @param bool  $found   Flag to indicate whether the variable exists
    *
    * @return mixed
    */
    public static function sessionVar($key, $default = null, &$found = null)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        return self::arrayGet($_SESSION, $key, $default, $found);
    }

    /*
    * Gets a cookie variable by key.
    *
    * @param mixed $key     $_COOKIE variable key
    * @param mixed $default Default return value; defaults to null
    * @param bool  $found   Flag to indicate whether the variable exists
    *
    * @return mixed
    */
    public static function cookieVar($key, $default = null, &$found = null)
    {
        return self::arrayGet($_COOKIE, $key, $default, $found);
    }


    /**
     * Remove the duplicates from an array.
     * @param array $array
     * @param bool $keepKeys
     * @return array
     */
    public static
    function Unique($array, $keepKeys = false)
    {
        if ($keepKeys) {
            $array = array_unique($array);
        } else {

            // This is faster version than the builtin array_unique().
            // http://stackoverflow.com/questions/8321620/array-unique-vs-array-flip
            // http://php.net/manual/en/function.array-unique.php
            $array = array_keys(array_flip($array));
        }
        return $array;
    }

    /**
     * Check is key exists
     * @param string $key
     * @param mixed $array
     * @param bool $returnValue
     * @return mixed
     */
    public static
    function KeyExist($key, $array, $returnValue = false)
    {
        $isExists = array_key_exists((string)$key, (array)$array);
        if ($returnValue) {
            if ($isExists) {
                return $array[$key];
            }
            return null;
        }
        return $isExists;
    }

    /**
     * Check is value exists in the array
     * @param string $value
     * @param mixed $array
     * @param bool $returnKey
     * @return mixed
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public static
    function ValueExist($value, array $array, $returnKey = false)
    {
        $inArray = in_array($value, $array, true);
        if ($returnKey) {
            if ($inArray) {
                return array_search($value, $array, true);
            }
            return null;
        }
        return $inArray;
    }

    /**
     * Returns the first element in an array.
     * @param  array $array
     * @return mixed
     */
    public static
    function FirstElement(array $array)
    {
        return reset($array);
    }

    /**
     * Returns the last element in an array.
     * @param  array $array
     * @return mixed
     */
    public static
    function LastElement(array $array)
    {
        return end($array);
    }

    /**
     * Returns the first key in an array.
     * @param  array $array
     * @return int|string
     */
    public static
    function FirstKey(array $array)
    {
        reset($array);
        return key($array);
    }

    /**
     * Returns the last key in an array.
     * @param  array $array
     * @return int|string
     */
    public static
    function LastKey(array $array)
    {
        end($array);
        return key($array);
    }

    /**
     * Flatten a multi-dimensional array into a one dimensional array.
     * @param  array $array The array to flatten
     * @param  boolean $preserve_keys Whether or not to preserve array keys. Keys from deeply nested arrays will
     *                                overwrite keys from shallowy nested arrays
     * @return array
     */
    public static
    function Flat(array $array, $preserve_keys = true)
    {
        $flattened = array();
        array_walk_recursive($array,
            function ($value, $key) use (&$flattened, $preserve_keys) {
                if ($preserve_keys && !is_int($key)) {
                    $flattened[$key] = $value;
                } else {
                    $flattened[] = $value;
                }
            });
        return $flattened;
    }

    /**
     * Searches for a given value in an array of arrays, objects and scalar values. You can optionally specify
     * a field of the nested arrays and objects to search in.
     * @param  array $array The array to search
     * @param  mixed $search The value to search for
     * @param  bool $field The field to search in, if not specified all fields will be searched
     * @return boolean|mixed  False on failure or the array key on success
     */
    public static
    function Search(array $array, $search, $field = false)
    {

        // *grumbles* stupid PHP type system
        $search = (string)$search;
        foreach ($array as $key => $elem) {

            // *grumbles* stupid PHP type system
            $key = (string)$key;
            if ($field) {
                if (is_object($elem) && $elem->
                    {
                    $field} === $search) {
                    return $key;
                } elseif (is_array($elem) && $elem[$field] === $search) {
                    return $key;
                } elseif (is_scalar($elem) && $elem === $search) {
                    return $key;
                }
            } else {
                if (is_object($elem)) {
                    $elem = (array)$elem;
                    if (in_array($search, $elem)) {
                        return $key;
                    }
                } elseif (is_array($elem) && in_array($search, $elem)) {
                    return $key;
                } elseif (is_scalar($elem) && $elem === $search) {
                    return $key;
                }
            }
        }
        return false;
    }

    /**
     * Returns an array containing all the elements of arr1 after applying
     * the callback function to each one.
     * @param  string $callback Callback function to run for each element in each array
     * @param  array $array An array to run through the callback function
     * @param  boolean $onNoScalar Whether or not to call the callback function on nonscalar values
     *                             (Objects, resources, etc)
     * @return array
     */
    public static
    function MapDeep(array $array, $callback, $onNoScalar = false)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $args        = array(
                    $value,
                    $callback,
                    $onNoScalar,
                );
                $array[$key] = call_user_func_array(array(
                    __CLASS__,
                    __FUNCTION__,
                ), $args);
            } elseif (is_scalar($value) || $onNoScalar) {
                $array[$key] = call_user_func($callback, $value);
            }
        }
        return $array;
    }

    /**
     * Clean array by custom rule
     * @param array $haystack
     * @return array
     */
    public static
    function Clean($haystack)
    {
        return array_filter($haystack);
    }

    /**
     * Clean array before serialize to JSON
     * @param array $array
     * @return array
     */
    public static
    function cleanBeforeJson(array $array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = self::cleanBeforeJson($array[$key]);
            }
            if ($array[$key] === '' || is_null($array[$key])) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    /**
     * Check is array is type assoc
     * @param $array
     * @return bool
     */
    public static
    function IsAssoc($array)
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * Add cell to the start of assoc array
     * @param array $array
     * @param string $key
     * @param mixed $value
     * @return array
     */
    public static
    function UnshiftAssoc(array & $array, $key, $value)
    {
        $array       = array_reverse($array, true);
        $array[$key] = $value;
        $array       = array_reverse($array, true);
        return $array;
    }

    /**
     * Get one field from array of arrays (array of objects)
     * @param array $arrayList
     * @param string $fieldName
     * @return array
     */
    public static
    function arrGetField($arrayList, $fieldName = 'id')
    {
        $result = array();
        if (!empty($arrayList) && is_array($arrayList)) {
            foreach ($arrayList as $option) {
                if (is_array($option)) {
                    $result[] = $option[$fieldName];
                } elseif (is_object($option)) {
                    if (isset($option->
                        {
                        $fieldName})) {
                        $result[] = $option->
                        {
                        $fieldName};
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Group array by key
     * @param array $arrayList
     * @param string $key
     * @return array
     */
    public static
    function GroupByKey(array $arrayList, $key = 'id')
    {
        $result = array();
        foreach ($arrayList as $item) {
            if (is_object($item)) {
                if (isset($item->
                    {
                    $key})) {
                    $result[$item->
                    {
                    $key}][] = $item;
                }
            } elseif (is_array($item)) {
                if (self::KeyExist($key, $item)) {
                    $result[$item[$key]][] = $item;
                }
            }
        }
        return $result;
    }

    /**
     * Recursive array mapping
     * @param \Closure $function
     * @param array $array
     * @return array
     */
    public static
    function RecursiveMap($function, $array)
    {
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result[$key] = self::RecursiveMap($function, $value);
            } else {
                $result[$key] = call_user_func($function, $value);
            }
        }
        return $result;
    }

    /**
     * Sort an array by keys based on another array
     * @param array $array
     * @param array $orderArray
     * @return array
     */
    public static
    function SortByArray(array $array, array $orderArray)
    {
        return array_merge(array_flip($orderArray), $array);
    }

    /**
     * Add some prefix to each key
     * @param array $array
     * @param string $prefix
     * @return array
     */
    public static
    function AddEachKey(array $array, $prefix)
    {
        $result = array();
        foreach ($array as $key => $item) {
            $result[$prefix . $key] = $item;
        }
        return $result;
    }

    /**
     * Convert assoc array to comment style
     * @param array $data
     * @return string
     */
    public static
    function toComment(array $data)
    {
        $result = array();
        foreach ($data as $key => $value) {
            $result[] = $key . ': ' . $value . ';';
        }
        return implode(PHP_EOL, $result);
    }

    /**
     * Wraps its argument in an array unless it is already an array
     * @example
     *   Arr.wrap(null)      # => []
     *   Arr.wrap([1, 2, 3]) # => [1, 2, 3]
     *   Arr.wrap(0)         # => [0]
     * @param mixed $object
     * @return array
     */
    public static
    function Wrap($object)
    {
        if (is_null($object)) {
            return array();
        } elseif (is_array($object) && !self::IsAssoc($object)) {
            return $object;
        }
        return array(
            $object,
        );
    }

    /**
     * @param string $glue
     * @param array $array
     * @return string
     */
    public static
    function implodeByGlue($glue, array $array)
    {
        $result = '';
        foreach ($array as $item) {
            if (is_array($item)) {
                $result .= self::implodeByGlue($glue, $item) . $glue;
            } else {
                $result .= $item . $glue;
            }
        }
        if ($glue) {
            $result = Str::strPart($result, 0, 0 - Str::lenght($glue));
        }
        return $result;
    }

    /**
     * Cleanup array
     * @param mixed $value
     * @param string|\Closure $filter
     * @return string
     */
    public static
    function cleanUp($value, $filter = null)
    {
        $intoarray = (array)$value;
        if ($filter === 'noempty') {
            $intoarray = StrFun::clean($intoarray);
        } elseif ($filter instanceof Closure) {
            $intoarray = array_filter($intoarray, $filter);
        }
        return $intoarray;
    }

    /**
     * Searches for an element on a **sorted** array.
     * @param array $array Where to search.
     * @param string $what What to search for.
     * @param int $probe The position where the element was found, or where it would be if it existed.
     * @param callable $comparator A function that returns zero for equality or a positive or negative number.
     * @return bool True a match was found.
     */
    public static
    function binarySearch(array $array, $what, &$probe, $comparator)
    {
        $count = count($array);
        $high  = $count - 1;
        $low   = 0;
        while ($high >= $low) {
            $probe      = (($high + $low) >> 1);
            $comparison = $comparator($array[$probe], $what);
            if ($comparison < 0) {
                $low = $probe + 1;
            } elseif ($comparison > 0) {
                if ($high == $low) {
                    break;
                }
                $high = $probe;
            } else {
                return true;
            }
        }
        $probe = $low;
        return false;
    }

    /**
     * Merges an array to the target array, modifying the original.
     * @param array $a Target being modified.
     * @param array $b Source data.
     */
    public static
    function mergeInto(array & $a, array $b)
    {
        $a = array_merge($a, $b);
    }

    /**
     * Merges an array, object or iterable to the target array, modifying the original, but only for keys already
     * existing on the target.
     * @param array $array Target being modified.
     * @param array|object|Traversable $data Source data.
     */
    public static
    function mergeExisting(array & $array, $data)
    {
        foreach ($data as $k => $v) {
            if (array_key_exists($k, $array)) {
                $array[$k] = $v;
            }
        }
    }

    /**
     * Merges an array or an object to the target array, modifying the original, recursively.
     * It supports nested object properties.
     * @param array $a Target being modified.
     * @param array|object|Traversable $b Source data.
     */
    function recursiveMergeInto(array & $a, $b)
    {
        foreach ($b as $k => $v) {
            if (!isset($a[$k])) {
                $a[$k] = $v;
            } else {
                $c = $a[$k];
                if (is_array($c)) {
                    $a[$k] = array_merge($c, $v);
                } elseif (is_object($c)) {
                    ObjectFun::extend($c, $v);
                } else {
                    $a[$k] = $v;
                }
            }
        }
    }

    /**
     * Merges an array, object or iterable to the target array, modifying the original, but only for keys already
     * existing on the target.
     * @param array $array Target being modified.
     * @param array|object|Traversable $data Source data.
     * @param array $only A List of key names.
     */
    public static
    function mergeOnly(array & $array, $data, array $only)
    {
        $keys = array_flip($only);
        foreach ($data as $k => $v) {
            if (isset($keys[$k]) && array_key_exists($k, $array)) {
                $array[$k] = $v;
            }
        }
    }

    /**
     * Generates a new array where each element is a list of values extracted from the corresponding element on the
     * input array.
     * @param array $a The source data.
     * @param array $keys The keys of the values to be extracted from each $array element.
     * @param mixed $def An optional default value to be returned for non-existing keys.
     * @return array      An array with the same cardinality as the input array.
     */
    public static
    function extract(array $a, array $keys, $def = null)
    {
        return self::map($a,
            function ($e) use ($keys, $def) {
                return self::fields($e, $keys, $def);
            });
    }

    /**
     * Extracts from an array a map of values having specific keys, in the same order as the given key list.
     * ><p>**Note:** keys that have no value on the target array will generate values of value $def (defaults to null),
     * so the cardinality of the resulting array matches that of the keys array, unlike the result from {@see
     * array_only}.
     * ><p>**Tip:**<p>
     * ><p>- You may call `array_values()` on the result if you need a linear list of values; ex. for use with
     * `list()`.
     * @param array $a The array.
     * @param array $keys A list of keys to be extracted.
     * @param mixed $def An optional default value to be returned for non-existing keys.
     * @return array A subset of the original array, having the extracted values or defaults.
     */
    public static
    function fields(array $a, array $keys, $def = null)
    {
        $o = [];
        foreach ($keys as $k) {
            $o[$k] = array_key_exists($k, $a) ? $a[$k] : $def;
        }
        return $o;
    }

    /**
     * Returns a copy of the given array having only the specified keys.
     * <p>Properties not present on the target array will not be present on the output array.
     * @param array $a The original array.
     * @param array $keys A list of keys to be copied.
     * @return array A subset of the original array.
     */
    function specifiedKey(array $a, array $keys)
    {
        return array_intersect_key($a, array_flip($keys));
    }

    /**
     * Searches an array for the first element where the specified field matches the given value.
     * Supports arrays of objects or arrays of arrays.
     * @param array $arr
     * @param string $fld
     * @param mixed $val
     * @param int|string $key Outputs the key of the matched element or NULL if it was not found.
     * @param bool $strict TRUE to perform strict equality testing.
     * @return array The value of the first matching element or NULL if none found.
     */
    public static
    function find(array $arr, $fld, $val, &$key = null, $strict = false)
    {
        if (isset($arr[0])) {
            if (is_object($arr[0])) {
                if ($strict) {
                    foreach ($arr as $key => $v) {
                        if ($v->$fld === $val) {
                            return $v;
                        }
                    }
                } else {
                    foreach ($arr as $key => $v) {
                        if ($v->$fld == $val) {
                            return $v;
                        }
                    }
                }
            }
            if (is_array($arr[0])) {
                if ($strict) {
                    foreach ($arr as $key => $v) {
                        if ($v[$fld] === $val) {
                            return $v;
                        }
                    }
                } else {
                    foreach ($arr as $key => $v) {
                        if ($v[$fld] == $val) {
                            return $v;
                        }
                    }
                }
            }
        }
        $key = null;
        return null;
    }

    /**
     * Estracts from an array all elements where the specified field matches the given value.
     * Supports arrays of objects or arrays of arrays.
     * @param array $arr
     * @param string $fld
     * @param mixed $val
     * @param bool $strict TRUE to perform strict equality testing.
     * @return array A list of matching elements.
     */
    public static
    function findAll(array $arr, $fld, $val, $strict = false)
    {
        $out = [];
        if (count($arr)) {
            reset($arr);
            $v = current($arr);
            if (is_object($v)) {
                if ($strict) {
                    foreach ($arr as $v) {
                        if ($v->$fld === $val) {
                            $out[] = $v;
                        }
                    }
                } else {
                    foreach ($arr as $v) {
                        if ($v->$fld == $val) {
                            $out[] = $v;
                        }
                    }
                }
            } elseif (is_array($v)) {
                if ($strict) {
                    foreach ($arr as $v) {
                        if ($v[$fld] === $val) {
                            $out[] = $v;
                        }
                    }
                } else {
                    foreach ($arr as $v) {
                        if ($v[$fld] == $val) {
                            $out[] = $v;
                        }
                    }
                }
            }
        }
        return $out;
    }

    /**
     * Returns the values from a single column of the array, identified by the column key.
     * This is a simplified implementation of the native array_column function for PHP < 5.5 but it
     * additionally allows fetching properties from an array of objects.
     * Array elements can be objects or arrays.
     * The first element in the array is used to determine the element type for the whole array.
     * @param array $array
     * @param int|string $key Null value is not supported.
     * @return array
     */
    public static
    function getColumn(array $array, $key)
    {
        return empty($array) ? [] : (is_array($array[0]) ? array_map(
            function ($e) use ($key) {
                return $e[$key];
            }
            , $array) : array_map(
            function ($e) use ($key) {
                return $e->$key;
            }
            , $array));
    }

    /**
     * Inserts a value with an optional key after an existing array element with a specific key, shifting other values
     * to make room.
     * <p>This function preserves the current order and keys of the array elements.
     * <p>String keys are supported.
     * <p>If no array element with the specified key is found, the new value is appended (with the corresponding key)
     * at
     * the end of the array.
     * @param array $array
     * @param int|string|null $afterKey When null, the value is appended to the array.
     * @param array $values The array to be inserted.
     * @return array
     */
    public static
    function insertAfterKey(array $array, $afterKey, $values)
    {
        $pos = self::keyIndex($array, $afterKey);
        if (!isset($pos)) {
            $pos = count($array);
        } else {
            ++$pos;
        }
        return self::insert($array, $pos, $values);
    }

    /**
     * Inserts a value with an optional key before an existing array element with a specific key, shifting other values
     * to make room.
     * <p>This function preserves the current order and keys of the array elements.
     * <p>String keys are supported.
     * <p>If no array element with the specified key is found, the new value is prepended (with the corresponding key)
     * to the beginning of the array.
     * @param array $array
     * @param int|string|null $beforeKey When null, the value is prepended to the array.
     * @param array $values The array to be inserted.
     * @return array
     */
    public static
    function insertBeforeKey(array $array, $beforeKey, $values)
    {
        return self::insert($array, self::keyIndex($array, $beforeKey), $values);
    }

    /**
     * Inserts an array into another at the specified position, irrespective of the target's keys.
     * @param array $target The array where data will be inserted into.
     * @param int $pos If offset is non-negative, the sequence will start at that offset in the array. If offset is
     *                      negative, the sequence will start that far from the end of the array.
     * @param array $source The data to be merged,
     * @return array        The resulting array.
     */
    public static
    function insert(array $target, $pos, array $source)
    {
        return array_merge(array_slice($target, 0, $pos, true), $source, array_slice($target, $pos, null, true));
    }

    /**
     * Gets the position of a key on an array.
     * @param array $array
     * @param string|int $key
     * @param int $notFoundIndex Value to be returned if the key was not found.
     * @return int|null
     */
    public static
    function keyIndex(array $array, $key, $notFoundIndex = null)
    {
        $keys = array_flip(array_keys($array));
        return isset($keys[$key]) ? $keys[$key] : $notFoundIndex;
    }

    /**
     * Returns the values from multiple columns of the array, identified by the given column keys.
     * Array elements can be objects or arrays.
     * The first element in the array is used to determine the element type for the whole array.
     * @param array $array
     * @param array $keys A list of integer or string keys.
     * @return array A list of objects or arrays, each one having the specified keys.
     * If some keys are absent from the input data, they will also be absent from the output.
     */
    public static
    function getColumns(array $array, array $keys)
    {
        $o = [];
        if (!empty($array)) {
            $mask = array_flip((array)$keys);
            if (is_array($array[0])) {
                foreach ($array as $k => $v) {
                    $o[$k] = array_intersect_key($v, $mask);
                }
            } elseif (is_object($array[0])) {
                foreach ($array as $k => $v) {
                    $o[$k] = (object)array_intersect_key((array)$v, $mask);
                }
            } else {
                throw new RuntimeException('Cannot invoke array_getColumns on an array of primitives.');
            }
        }
        return $o;
    }

    /**
     * Splits an array by one or more field values, generating a tree-like structure.
     * <p>The first argument is the input array.
     * <p>Each subsequent argument can be a field name or a function that returns the value to split on.
     * <p>Array elements can be arrays or objects.
     * Ex:
     * ```
     * array_group ($data, 'type', 'date', function (v) { return datePart(v['date']); });
     * ```
     * Ex:
     * ```
     * $a = [
     *   [
     *     "type" => "animal",
     *     "color" => "red",
     *   ],
     *   [
     *     "type" => "animal",
     *     "color" => "green",
     *   ],
     *   [
     *     "type" => "robot",
     *     "color" => "red",
     *   ],
     *   [
     *     "type" => "robot",
     *     "color" => "green",
     *   ],
     *   [
     *     "type" => "robot",
     *     "color" => "blue",
     *   ],
     *   [
     *     "type" => "robot",
     *     "color" => "blue",
     *     "name" => "bee",
     *   ],
     * ];
     * array_group ($data, 'type', 'color');
     * ```
     * Generates:
     * ```
     * [
     *   "animal" => [
     *     "red" => [
     *       [
     *         "type" => "animal",
     *         "color" => "red",
     *       ],
     *     ],
     *     "green" => [
     *       [
     *         "type" => "animal",
     *         "color" => "green",
     *       ],
     *     ],
     *   ],
     *   "robot" => [
     *     "red" => [
     *       [
     *         "type" => "robot",
     *         "color" => "red",
     *       ],
     *     ],
     *     "green" => [
     *       [
     *         "type" => "robot",
     *         "color" => "green",
     *       ],
     *     ],
     *     "blue" => [
     *       [
     *         "type" => "robot",
     *         "color" => "blue",
     *       ],
     *       [
     *         "type" => "robot",
     *         "color" => "blue",
     *         "name" => "bee",
     *       ],
     *     ],
     *   ],
     * ]
     * ```
     * @param array $a The source data.
     * @return array
     */
    public static
    function group(array $a)
    {
        $args = func_get_args();
        array_shift($args);
        $c = count($args) - 1;
        $o = [];
        foreach ($a as $v) {
            $ptr = &$o;
            foreach ($args as $n => $field) {

                // Must be string, otherwise decimal places will be truncated.
                $idx = is_callable($field) ? $field($v) : (string)self::field($v, $field);
                if (!isset($ptr[$idx])) {
                    $ptr[$idx] = [];
                }
                if ($n < $c) {
                    $ptr = &$ptr[$idx];
                } else {
                    $ptr[$idx][] = $v;
                }
            }
        }
        return $o;
    }

    /**
     * Converts a PHP array of maps to an array if instances of the specified class.
     * @param array $array
     * @param string $className
     * @return array
     */
    public static
    function hidrate(array $array, $className)
    {
        $o = [];
        foreach ($array as $k => $v) {
            $o[$k] = self::toClass($v, $className);
        }
        return $o;
    }

    /**
     * Reindexes the array using the specified key field.
     * Array items should be arrays or objects.
     * @param array $a The source data.
     * @param string $field The field name.
     * @return array Self, for chaining.
     */
    public static
    function indexBy(array $a)
    {
        return self::map($a,
            function ($v, &$k) {
                $k = self::field($v, $k);
                return $v;
            });
    }

    /**
     * Calls a function for each element of an array.
     * The function will receive one argument for each specified column.
     * @param array $data
     * @param array $cols
     * @param callable $fn
     */
    public static
    function iterateColumns(array $data, array $cols, callable $fn)
    {
        foreach ($data as $r) {
            call_user_func_array($fn, self::fields($r, $cols));
        }
    }

    /**
     * Calls a transformation function for each element of an array.
     * The function will receive one argument for each specified column.
     * It should return an array/object that will replace the original array element.
     * Unlike array_map, the original keys will be preserved.
     * @param array $data
     * @param array $cols
     * @param callable $fn
     * @return array A transformed copy of the input array.
     */
    public static
    function arrMapColumns(array $data, array $cols, callable $fn)
    {
        $o = [];
        foreach ($data as $k => $r) {
            $o[$k] = call_user_func_array($fn, self::fields($r, $cols));
        }
        return $o;
    }

    /**
     * Sorts an array by one or more field values.
     * Ex: array_orderBy ($data, 'volume', SORT_DESC, 'edition', SORT_ASC);
     * @return array
     */
    public static
    function orderBy()
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = [];
                foreach ($data as $key => $row) {
                    $tmp[$key] = $row[$field];
                }
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }

    /**
     * Returns the input array stripped of null elements (with strict comparison).
     * ><p>**Note:** the resulting array may have sequence gaps on its keys. Use {@see array_values} on it if you want
     * ordinal sequential keys.
     * @param array $data
     * @return array
     */
    public static
    function prune(array $data)
    {
        return array_diff_key($data, array_flip(array_keys($data, null, true)));
    }

    /**
     * Returns the input array stripped of empty elements (those that are either `null` or empty strings).
     * ><p>**Note:** `false`, `0` and`'0'` are considered NOT to be empty.
     * ><p>**Note:** the resulting array may have sequence gaps on its keys. Use {@see array_values} on it if you want
     * ordinal sequential keys.
     * @param array $data
     * @return array
     */
    public static
    function pruneEmpty(array $data)
    {
        return array_diff_key($data, array_flip(array_merge(array_keys($data, null, true), array_keys($data, '', true))));
    }

    /**
     * Returns a copy of the input array with a set of **keys** excluded from it.
     * <p>Unlike {@see array_diff_key}, the keys are specified as a list of string values.
     * @param array $data
     * @param string[] $keys
     * @return array
     * @see array_diff if you want to exclude **values** instead of keys.
     */
    public static
    function exclude(array $data, array $keys)
    {
        return array_diff_key($data, array_flip($keys));
    }

    /**
     * Removes duplicates values from an array.
     * <p>This is similar to {@see array_unique} but its much faster when dealing with large amounts of string values.
     * @param array $data
     * @return array
     */
    public static
    function duplicate(array $data)
    {
        return array_flip(array_flip($data));
    }

    /**
     * Converts a PHP array map to an instance of the specified class.
     * @param array $array
     * @param string $className
     * @return mixed
     */
    public static
    function toClass(array $array, $className)
    {
        return unserialize(sprintf('O:%d:"%s"%s', strlen($className), $className, strstr(serialize($array), ':')));
    }

    /**
     * Reads a value from the given array (or object implementing ArrayAccess) at the specified index/key.
     * <br /><br />
     * Unlike the usual array access operator [], this function does not generate warnings when the key is not present
     * on the array; instead, it returns null or a default value.
     * @param array|ArrayAccess $array The target array.
     * @param number|string $key The list index or map key.
     * @param mixed $def An optional default value.
     * @return mixed
     */
    public static
    function get($array = null, $key, $def = null)
    {
        return isset($array[$key]) ? $array[$key] : $def;
    }

    /**
     * Calls a transformation function for each element of an array.
     * The function will receive a value and a key for each array element and it should return a value that will replace
     * the original array element.
     * Unlike array_map, the original keys will be preserved, unless the callback defines the
     * key parameter as a reference and modifies it.
     * @param array|Traversable $src Anything that can be iterated on a `foreach` loop.
     *                                   If `null`, `null` is returned.
     * @param callable $fn The callback.
     * @param bool $useKeys [optional] When true, the iteration keys are passed as a second argument to the
     *                                   callback. Set to false for compatibility with native PHP functions used as
     *                                   callbacks, as they will complain if an extra argument is provided.
     * @return array
     */
    public static
    function map($src, callable $fn, $useKeys = true)
    {
        if (isset($src)) {
            if (is_array($src) || $src instanceof Traversable) {
                $o = [];
                if ($useKeys) {
                    foreach ($src as $k => $v) {
                        $o[$k] = $fn($v, $k);
                    }
                } else {
                    foreach ($src as $k => $v) {
                        $o[$k] = $fn($v);
                    }
                }
                return $o;
            }
            throw new InvalidArgumentException;
        }
        return $src;
    }

    /**
     * Generates a new array where each item is an array consisting of the values from all specified arrays at the same
     * index.
     * <p>The arrays are assumed to be indexed, not associative. The resulting array is indexed.
     * #### Example
     * ```
     * $a = array(1, 2, 3, 4, 5);
     * $b = array("one", "two", "three");
     * $c = array("uno", "dos", "tres");
     * $d = array_combine_values ($a, $b, $c);
     * ```
     * ##### Yields
     * ```
     * [
     *   [1, "one",   "uno"],
     *   [2, "two",   "dos"],
     *   [3, "three", "tres"],
     * ]
     * ```
     * @return array
     */
    public static
    function combineValues()
    {
        return array_map(null, func_get_args());
    }

    /**
     * Filters an array or a {@see Traversable} sequence by calling a callback.
     * The function will receive a value and a key for each array element and it should return `true` if the element
     * will be kept on the resulting array, `false` to drop it. Unlike array_filter, the original keys will be
     * preserved, unless the callback defines the key parameter as a reference and modifies it.
     * @param array|Traversable $src Anything that can be iterated on a `foreach` loop.
     *                                     If `null`, `null` is returned.
     * @param callable $fn The callback.
     * @param bool $resetKeys When true, the resulting keys will be regenerated as a monotonic increasing
     *                                     sequence.
     * @return array
     */
    public static
    function filter($src, callable $fn, $resetKeys = false)
    {
        if (isset($src)) {
            if (is_array($src)) {
                $o = array_filter($src, $fn, ARRAY_FILTER_USE_BOTH);
            } elseif ($src instanceof Traversable) {
                $o = [];
                foreach ($src as $k => $v) {
                    if ($fn($v, $k)) {
                        $o[$k] = $v;
                    }
                }
            } else {
                throw new InvalidArgumentException;
            }
        } else {
            return $src;
        }
        return $resetKeys ? array_values($o) : $o;
    }

    /**
     * Calls a transformation function for each element of an array and allows that function to drop elements from the
     * resulting array,
     * The function will receive a value and a key for each array element and it should return a value that will replace
     * the original array element, or `null` to drop the element.
     * Unlike array_map, the original keys will be preserved, unless the callback defines the
     * key parameter as a reference and modifies it.
     * @param array|Traversable $src Anything that can be iterated on a `foreach` loop.
     *                               If `null`, `null` is returned.
     * @param callable $fn The callback.
     * @return array
     * @throws InvalidArgumentException If `$src` is not iterable.
     */
    public static
    function mapAndFilter($src, callable $fn)
    {
        if (isset($src)) {
            if (is_array($src) || $src instanceof Traversable) {
                $o = [];
                foreach ($src as $k => $v) {
                    if (!is_null($r = $fn($v, $k))) {
                        $o[$k] = $r;
                    }
                }
                return $o;
            }
            throw new InvalidArgumentException;
        }
        return $src;
    }

    /**
     * Checks if either the specified key is missing from the given array or it's corresponding value in the array is
     * empty.
     * @param array|null $array The target array.
     * @param string|int $key An array key / offset.
     * @return bool True if the key is missing or the corresponding value in the array is empty (null or empty string).
     * @see exists()
     */
    public static
    function missing(array $array = null, $key)
    {
        return !isset($array[$key]) || $array[$key] === '';
    }

    /**
     * Converts all values that are empty strings to `null`.
     * @param array $array The source array.
     * @param bool $recursive
     * @return array The modified array.
     */
    public static
    function normalizeEmptyValues(array $array, $recursive = false)
    {
        foreach ($array as $k => & $v) {
            if ($v === '') {
                $v = null;
            } elseif ($recursive && is_array($v)) {
                $v = self::normalizeEmptyValues($v, true);
            }
        }
        return $array;
    }

    /**
     * **ArrayOf** - Creates an array from the given arguments.
     * <p>This is quite useful when used with the splat/spread operator.<br />
     * >Ex: `aof($a, ...$b)`
     * @return array
     */
    public static
    function aof()
    {
        return func_get_args();
    }

    /**
     * Unified interface for checking if property exists an object or if a key exists on an array.
     * @param array|object $data
     * @param string $key
     * @return bool
     */
    public static
    function hasField($data, $key)
    {
        if (is_object($data)) {
            return property_exists($data, $key) || ($data instanceof ArrayAccess && isset($data[$key]));
        }
        if (is_array($data)) {
            return array_key_exists($key, $data);
        }
        throw new InvalidArgumentException;
    }

    /**
     * Unified interface for retrieving a value by property from an object or by key from an array.
     * @param array|object $data
     * @param string $key
     * @param mixed $default Value to return if the key doesn't exist.
     * @return mixed
     */
    public static
    function field($data, $key, $default = null)
    {
        if (is_object($data)) {
            if (property_exists($data, $key)) {
                return $data->$key;
            }
            if ($data instanceof ArrayAccess && isset($data[$key])) {
                return $data[$key];
            }
            return $default;
        }
        if (is_array($data)) {
            return array_key_exists($key, $data) ? $data[$key] : $default;
        }
        throw new InvalidArgumentException;
    }

    /**
     * Unified interface to set a value on an object's property or at an array's key.
     * @param array|object $data
     * @param string $key
     * @param mixed $value
     */
    public static
    function setField(&$data, $key, $value)
    {
        if (is_object($data)) {
            $data->$key = $value;
        } elseif (is_array($data)) {
            $data[$key] = $value;
        } else {
            throw new InvalidArgumentException;
        }
    }

    /**
     * Unified interface for retrieving a reference to an object's property or to an array's element.
     * If the key doesn't exist, it is initialized to a null value.
     * @param mixed $data
     * @param string $key
     * @param mixed $default Value to store at the specified key if that key doesn't exist.
     *                          Valid ony if `$createObj == false` (the default).
     * @param bool $createObj When true, the `$default` is ignored and a new instance of StdClass is used instead.<br />
     *                          This avoids unnecessary object instantiations.
     * @return mixed Reference to the value.
     * @throws InvalidArgumentException
     */
    public static
    function &getFieldRef(&$data, $key, $default = null, $createObj = false)
    {
        if (is_object($data)) {
            if (!property_exists($data, $key)) {
                $data->$key = $createObj ? new StdClass : $default;
            }
            return $data->$key;
        }
        if (is_array($data)) {
            if (!array_key_exists($key, $data)) {
                $data[$key] = $default;
            }
            return $data[$key];
        }
        throw new InvalidArgumentException("Not an object or array");
    }

    /**
     * Unified interface for retrieving a value by property name from an object or by key name from an array, using a
     * dot-delimited path to navigate a given data structure.
     * @param array|object $data The target data structure.
     * @param string $path A dot-delimited path.
     * @param mixed $def [optional] Default value if the key/property is missing or its value is null.
     * @return mixed|null
     */
    public static
    function getAt($data, $path, $def = null)
    {
        $segs = $path === '' ? [] : explode('.', $path);
        $cur  = $data;
        foreach ($segs as $seg) {
            if (is_null($cur = self::field($cur, $seg))) {
                break;
            };
        }
        return isset($cur) ? $cur : $def;
    }

    /**
     * Unified interface for retrieving a reference by property name from an object or by key name from an array, using
     * a dot-delimited path to navigate a given data structure.
     * @param array|object $data The target data structure.
     * @param string $path A dot-delimited path.
     * @return mixed|null
     */
    public static
    function &getRefAt(&$data, $path)
    {
        $segs = $path === '' ? [] : explode('.', $path);
        $cur  = $data;
        foreach ($segs as $seg) {
            if (is_null($cur = &self::getFieldRef($cur, $seg))) {
                break;
            };
        }
        return $cur;
    }

    /**
     * Unified interface for setting a value by property name on an object or by key name on an array, using a
     * dot-delimited path to navigate a given data structure.
     * @param array|object $data The target data structure.
     * @param string $path A dot-delimited path.
     * @param mixed $v The value.
     * @param bool $assoc true if arrays should be provided for missing path nodes, otherwise objects will be
     *                            created.
     */
    public static
    function setAt(&$data, $path, $v, $assoc = false)
    {
        $segs = $path === '' ? [] : explode('.', $path);
        $cur  = &$data;
        foreach ($segs as $seg) {
            $cur = &self::getFieldRef($cur, $seg, [], !$assoc);
        }
        $cur = $v;
    }

    /**
     * Unified interface for unsetting a value by property name on an object or by key name on an array, using a
     * dot-delimited path to navigate a given data structure.
     * @param array|object $data The target data structure.
     * @param string $path A dot-delimited path.
     */
    public static
    function unsetAt(&$data, $path)
    {
        if ($path === '') {
            $data = is_array($data) ? [] : (object)[];
        } else {
            $paths = explode('.', $path);
            $key   = array_pop($paths);
            $path  = implode('.', $paths);
            $v     = &self::getRefAt($data, $path);
            if (is_array($v)) {
                unset($v[$key]);
            } elseif (is_object($v)) {
                unset($v->$key);
            } else {
                throw new InvalidArgumentException("Not an object or array");
            }
        }
    }

    /*
 * Zips arrays and combine with keys each element of the zipped array.
 */
    public static function array_zip_keys($arrays, $keys)
    {
        $arrays = func_get_args();
        $keys   = array_pop($arrays);
        return array_map(function ($item) use ($keys) {
            return array_combine($keys, $item);
        }, call_user_func_array('array_zip', $arrays));
    }/*
 * Zips arrays. The reverse of `array_unzip()`.
 *
 * @param array $array     Array to unzip
 * @param array $array,... Unlimited optional arrays to unzip
 *
 * @return array
 */
    public static function array_zip($array)
    {
        $arrays = func_get_args();
        foreach ($arrays as $i => $array) {
            if (!is_array($array)) {
                throw new \InvalidArgumentException(sprintf(
                    'argument %d of %s is not an array',
                    $i + 1, __FUNCTION__
                ));
            }
        }
        $keys = array_values(array_reduce(func_get_args(), function ($keys, $arg) {
            if (empty($keys)) {
                return array_keys($arg);
            }
            return array_intersect(array_keys($arg), $keys);
        }, array()));
        $args = array_filter(func_get_args(), function ($arg) use ($keys) {
            return count(array_intersect(array_keys($arg), $keys)) >= count($keys);
        });
        $zip  = array();
        foreach ($keys as $key) {
            foreach ($args as $i => $arg) {
                $zip[$key][$i] = $arg[$key];
            }
        }
        return $zip;
    }

    /*
     * Returns a new array based on a whitelist of keys.
     *
     * @param array $array Original array
     * @param mixed $keys  Key whitelist
     *
     * @return array
     */
    public static function array_whitelist(array $array, $keys)
    {
        if (func_num_args() == 2 && is_array(func_get_arg(1))) {
            $keys  = func_get_arg(1);
            $array = func_get_arg(0);
        } else {
            $keys  = func_get_args();
            $array = array_shift($keys);
            foreach ($keys as $i => $key) {
                if (!is_string($key) && !is_int($key)) {
                    throw new \InvalidArgumentException(sprintf(
                        'key %d of %s is not a string or integer',
                        $i + 1, __FUNCTION__
                    ));
                }
            }
        }
        return array_intersect_key($array, array_flip($keys));
    }/*
 * Unzips an array. The reverse of `array_zip()`.
 *
 * @param array $array Array to unzip
 *
 * @return array
 */
    public static function array_unzip($array)
    {
        $unzip = array();
        foreach ($array as $element) {
            foreach ($element as $key => $value) {
                $unzip[$key][] = $value;
            }
        }
        return $unzip;
    }/*
 * Removes elements of an array based on given values.
 *
 * @param array $array     Array variable
 * @param mixed $value     Value to remove
 * @param mixed $value,... Unlimited optional values to remove
 *
 * @return int Number of elements removed
 */
    public static function array_remove(array &$array, $value = null)
    {
        $values = func_get_args();
        $array  = array_shift($values);
        $keys   = array();
        foreach ($values as $value) {
            $keys = array_merge($keys, array_keys($array, $value));
        }
        $removed = 0;
        $_array  = (array)$array;
        array_walk($_array, function ($item, $key) use (&$removed, &$array, $keys) {
            if (in_array($key, $keys)) {
                $removed += 1;
                unset($array[$key]);
            }
        });
        return $removed;
    }

    /*
     * Plucks an array of arrays based on a key.
     *
     * @param array $array         Array to pluck
     * @param mixed $key           Array key to pluck
     * @param bool  $preserve_keys Preserve keys; defaults to true
     *
     * @return array
     */
    public static function array_pluck($array, $key, $preserve_keys = true)
    {
        $pluck = array();
        foreach ($array as $k => $v) {
            if (self::array_key_valid($v, $key)) {
                if ($preserve_keys === true) {
                    $pluck[$k] = $v[$key];
                } else {
                    $pluck[] = $v[$key];
                }
            }
        }
        return $pluck;
    }/*
 * Returns the last element of an array.
 *
 * @param array $array   Source array
 * @param mixed $default Default return value; defaults to null
 * @param bool  $found   Flag to indicate whether the last element exists
 */
    public static function array_last($array, $default = null, &$found = null)
    {
        if ($array instanceof \Traversable) {
            $array = iterator_to_array($array);
        }
        if (!is_array($array)) {
            $found = false;
            return $default;
        }
        $keys = array_keys($array);
        if ($keys) {
            $found = true;
            return $array[$keys[count($keys) - 1]];
        }
        $found = false;
        return $default;
    }/*
 * Converts any type of value to an array.
 *
 * @param mixed $value   Any type of array-convertible value
 * @param mixed $default Default return value; defaults to array()
 *
 * @return array
 */
    public static function array_convert($value, $default = array())
    {
        if (is_array($value)) {
            return $value;
        }
        if ($value instanceof \Traversable) {
            return iterator_to_array($value);
        } elseif (is_object($value)) {
            return get_object_vars($value);
        } elseif (is_string($value)) {
            return str_split($value);
        } elseif (is_scalar($value) || is_null($value)) {
            return array($value);
        }
        return $default;
    }

    /*
 * Gets an element of an array. Can also be used to get an element deep inside
 * a multidimensional array by specifying an array of path.
 *
 * @param array $array   Original array
 * @param mixed $path    String or array of path
 * @param mixed $default Default return value; defaults to null
 * @param bool  $found   Flag to indicate if the value was found (optional)
 *
 * @return mixed
 */
    public static function arrayGet($array, $path, $default = null, &$found = null)
    {
        if ($array instanceof \Traversable) {
            $array = iterator_to_array($array);
        }
        if (!is_array($array)) {
            $found = false;
            return $default;
        }
        if (is_array($path)) {
            foreach ($path as $field) {
                $array = self::arrayGet($array, $field, $default, $found);
                if (!$found) {
                    break;
                }
            }
            return $array;
        } elseif (self::array_key_valid($array, $path)) {
            $found = true;
            return $array[$path];
        } else {
            $found = false;
            return $default;
        }
    }

    /*
 * Determines whether the provided key is a valid key within an array.
 *
 * @param mixed $key   Array key to test
 * @param array $array Array to test
 *
 * @return bool Boolean true if key is valid; false otherwise
 */
    public
    static function array_key_valid($array, $key)
    {
        return (is_scalar($key) || is_null($key)) && is_array($array) &&
            array_key_exists($key, $array);
    }


    public static function arrayColumn($array, $column_name)
    {
        return array_map(function ($element) use ($column_name) {
            return $element[$column_name];
        }, $array);
    }

    public static function arrayMergeRecursive2()
    {
        $args = func_get_args();
        $ret = array();
        foreach ($args as $arr) {
            if (is_array($arr)) {
                foreach ($arr as $key => $val) {
                    $ret[$key][] = $val;
                }
            }
        }
        return $ret;
    }


    /**
     * @param $data
     * @param $columns
     * @return array
     */
    public static function createCustomArr($data, $columns)
    {
        $newarr = [];
        $newdata = [];
        $serialno = 0;
        if (is_object($data)) {
            $newdata[] = self::objToArray($data);
        } elseif (is_array($data)) {
            $newdata = $data;
        }
        foreach ($newdata as $o => $row) {
            if (is_object($row)) {
                $row = self::objToArray($row);
            }
            $row = array_replace_recursive($columns, $row);
            // self::echoPre($row);
            foreach ($row as $k => $v) {
                if (isset($columns[$k]) and !is_array($v)) {
                    if ($k == 'serialno') {
                        $serialno++;
                        $newarr[$o][$columns[$k]] = $serialno;
                    } else {
                        $newarr[$o][$columns[$k]] = $v;
                    }
                }
            }
        }
        return $newarr;
    }


    /**
     * @param $obj
     * @return array
     */
    public static function objToArray($obj)
    {
        $newarr = array();
        $classlen = 0;
        if (is_object($obj)) {
            $classlen = strlen(get_class($obj));
            $obj = (array)$obj;
        }
        if (is_array($obj)) {
            foreach ($obj as $key => $val) {
                if (!is_array($val) and !is_object($val)) {
                    $newarr[StrHelper::lower(substr(trim($key), $classlen))] = $val;
                } else {
                    $newarr[] = self::objToArray($val);
                }
            }
        }
        return $newarr;
    }














}