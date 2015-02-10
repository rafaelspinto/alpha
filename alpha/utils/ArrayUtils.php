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
        array_walk_recursive($array, function(&$item, $key) {
            if (!mb_detect_encoding($item, 'utf-8', true)) {
                if (is_array($item)) {
                    static::encodeToUtf8($item);
                } else if ($item instanceof \Iterator) {
                    $item = static::encodeToUtf8(iterator_to_array($item));
                } else {
                    $item = utf8_encode($item);
                }
            }
        });
        return $array;
    }
}

