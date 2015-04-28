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

    function remove_after_word($str, $remove_this) {

        $pos = strpos($str, $remove_this);
        $endpoint = $pos + strlen($remove_this);
        $newStr = substr($str,0,$endpoint );

        return $newStr;
    }

?>