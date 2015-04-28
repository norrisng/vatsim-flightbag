<style type="text/css">

    .smalltext {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    }

</style>

<p>
    <p class = "smalltext"><a href="fltsim_resource.html">&lt; Return to menu</a></p>
</p>

<?php

/**
 * Created by PhpStorm.
 * User: Norris
 * Date: 26/04/2015
 * Time: 3:46 PM
 */

    include('atc_backend.php');

    // yes, this is stupid, but it works........blame the VATEUD API -.-"
    $online_atc_json = file_get_contents('http://api.vateud.net/online/atc/a,b,c,d,e,f,g,h,j,k,l,m,o,p,r,s,t,u,v,w,y,z.json');

    /* Local JSON file for testing purposes*/
//     $online_atc_json = file_get_contents('test.json');

    $online_atc = json_decode($online_atc_json, true);

    $num_online = 0;

    $output_table = '<table border="1" cellpadding="2">';
    $sup_table = '<table>';

    $output_table = $output_table . '<tr> <td width = 130><b>Station</b></td> <td width = 110><b>Frequency&nbsp;&nbsp;</b></td> <td width = 200><b>Callsign</b></td> <td width = 200><b>Controller</b></td> <td><b>Online since</b></td> </tr>';

    // future feature: have different tables for each position type

    $position_types = array('control','center', 'centre', 'radar', 'approach', 'departure', 'director', 'arrival', 'tower', 'ground', 'delivery', 'clearance');

    foreach($online_atc as $station) {

            $callsign = $station['callsign'];
            $rating = $station['rating'];
            $freq = $station['frequency'];
            $name = $station['name'];
            $login_time = $station['online_since'];

        // don't add atc station if it's an ATIS, or an observer (who all have freq = 199.998)
        if (strpos($callsign, '_ATIS') === false and strpos($freq, '199.998') === false and strpos($rating, 'OBS') === false) {

            // exception handling in case 'atis_message' doesn't exist for some odd reason
            if (array_key_exists('atis_message', $station) === true) {

                // strip the word "charts"; it seems to be a common word that escapes subsequent filters
                $position_name = str_replace('charts', '', $station['atis_message']);
                $position_name = str_replace('Charts', '', $station['atis_message']);



                // strip all punctuation and <br/> tags
                if (strpos($position_name, "<br />")) {
                    $position_name = remove_after_word($position_name, "<br />");
                    $position_name = str_replace("<br /", '', $position_name);
                }

                // Please excuse the ridiculously long regex and blame places such as Munich, Sondrestrom etc.
                $position_name = preg_replace("/[^ \wÀÁÅÃÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿ]+/", "", $position_name);
//
//                // get the first 3 words of the atis_message, which will likely contain the station callsign
//                $position_name_pieces = array_slice(explode(' ', $position_name), 0, 3);
//
//                // figure out if the last word is one of the ones in position_type; if so, then
//                if (strcmp($position_name_pieces, $position_name_pieces[2]))
//                    ;
                $position_name = implode(' ', array_slice(explode(' ', $position_name), 0, 3));

                // exception for some two-worded city names, as well as controllers who prefix the callsign with "Callsign"
                $two_word_cities = array('los', 'las', 'hong', 'de', 'callsign');
                $pos_lowercase = strtolower($position_name);
                if (contains_word($pos_lowercase, $two_word_cities) === false)
                    $position_name = implode(' ', array_slice(explode(' ', $position_name), 0, 2));

            }
            else $position_name = '';

            $output_table = $output_table . '<tr>';
                $output_table = $output_table . '<td>' . $callsign . '</td>';
                $output_table = $output_table . '<td>' . $freq . '</td>';

            /**** Determine the station name ****/

            $pos_name_lowercase = strtolower($position_name);

            // remove the word "callsign" if it exists
            if (contains_word($pos_name_lowercase, array('callsign')))
                $position_name = strstr($position_name, ' ');

            if (contains_word($pos_name_lowercase, $position_types))
                $output_table = $output_table . '<td>' . $position_name . '</td>';
            else
                $output_table = $output_table . '<td>' . '<i><font color = gray>-</font></i>' . '</td>';

            /**** Fin ****/

                $output_table = $output_table . '<td>' . $name . '</td>';
                $output_table = $output_table . '<td>' . $login_time . '</td>';
            $output_table = $output_table . '</tr>';

            $num_online++;
        }
        else if (strpos($freq, '199.998') === true and strcomp($rating, 'Supervisor') === 0) {

            $sup_table = $sup_table . '<tr>';
                $sup_table = $sup_table . '<td>' . $callsign . '</td>';
                $sup_table = $sup_table . '<td>' . $name . '</td>';
                $sup_table = $sup_table . '<td>' . $login_time . '</td>';
            $sup_table = $sup_table . '</tr>';

        }
    }


?>

<p class = smalltext><b>ATC stations currently online:</b> <?php echo $num_online ?> &nbsp;<b>[<a href = "atc.php">refresh</a>]</b></p>

<?php

    $output_table = $output_table . '</table>';
    $sup_table = $sup_table . '</table>';

    echo $output_table;

    echo '

    ';

    //    echo '<p><b>Supervisors currently online</b></p>';

    //    echo '<b class = smalltext>ATC positions online:</b> ' . $num_online;

    echo $sup_table;

?>