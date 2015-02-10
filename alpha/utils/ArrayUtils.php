<?php
/**
 * ArrayUtils
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Utils;

/**
 * Helper class for operations related to Arrays.
 */
class ArrayUtils
{
    /**
     * Recursively encodes an array to utf8.
     * 
     * @param array $array The source array.
     * 
     * @return array
     */
    public static function encodeToUtf8(array &$array)
    {
        array_walk_recursive($array, function(&$item) {
            if (is_array($item)) {
                static::encodeToUtf8($item);
            } else if ($item instanceof \Iterator) {
                $array = iterator_to_array($item);
                $item = static::encodeToUtf8($array);
            } else if (!mb_detect_encoding($item, 'utf-8', true)) {
                $item = utf8_encode($item);
            }
        }
        );
        return $array;
    }
}

