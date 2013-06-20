<?php
/**
 * string functions
 */
class StringHelper
{
    /**
     * @param $contents
     * @param $start
     * @param $end
     * @param bool $removeStart
     * @return string
     */
    public function getBetweenString($contents, $start, $end, $removeStart = false)
    {
        $startPos = strpos($contents, $start);
        $endPos = strpos($contents, $end, $startPos);
        if ($removeStart) {
            $startPos += strlen($start);
        }
        $len = $endPos - $startPos;
        $subString = substr($contents, $startPos, $len);
        return $subString;
    }

    /**
     * @static
     * @param $fileName
     * @return mixed
     */
    public static function getValidFileName($fileName)
    {
        $fileName = preg_replace('/[^0-9a-z ]+/i', '', $fileName);
        return $fileName;
    }

    /**
     * @static
     * @param $string
     * @return mixed|string
     */
    public static function slug($string)
    {
        $string = strtolower($string);
        $string = preg_replace('/[^0-9a-z ]/', '', $string);
        while (strpos($string, '  ') !== false) $string = str_replace('  ', ' ', $string);
        $string = str_replace(' ', '-', $string);
        return $string;
    }


}