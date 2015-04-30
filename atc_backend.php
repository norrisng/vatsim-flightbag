<?php
/**
 * Created by PhpStorm.
 * User: Norris
 * Date: 27/04/2015
 * Time: 9:20 PM
 */

    /**
     * Determines if one string contains one (or more) out of an array of strings
     *
     * @param $haystack          The string to search in
     * @param array $needles     The strings being searched for
     * @return bool              True if found, false otherwise
     */
    function contains_word($haystack, array $needles)
    {
        foreach($needles as $a) {
            if (stripos($haystack,$a) !== false) return true;
        }
        return false;
    }

    function remove_after_word($str, $remove_this) {

        $pos = strpos($str, $remove_this);
        $endpoint = $pos + strlen($remove_this);
        $newStr = substr($str,0,$endpoint );

        return $newStr;
    }

    function add_row($table, $strings) {

        $table = $table . '<tr>';
        foreach ($strings as $curr) {
            add_cell($table, $curr);
        }
        $table = $table . '</tr>';
    }

    function add_cell($table, $string) {
        $table = $table . '<td>' . $string . '</td>';
        return $table;
    }

?>