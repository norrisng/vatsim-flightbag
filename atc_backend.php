<?php
/**
 * Created by PhpStorm.
 * User: Norris
 * Date: 27/04/2015
 * Time: 9:20 PM
 */

    function contains_word($str, array $arr)
    {
        foreach($arr as $a) {
            if (stripos($str,$a) !== false) return true;
        }
        return false;
    }

?>