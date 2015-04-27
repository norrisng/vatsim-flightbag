<style type="text/css">

    .smalltext {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    }

</style>

<p>
    <p class = "smalltext"><a href="fltsim_resource.html">&lt; Return to menu</a></p>
</p>

<p class = smalltext><b>ATC stations currently online</b></p>

<?php

/**
 * Created by PhpStorm.
 * User: Norris
 * Date: 26/04/2015
 * Time: 3:46 PM
 */

    // yes, this is stupid, but it works........blame the VATEUD API -.-"
    $online_atc_json = file_get_contents('http://api.vateud.net/online/atc/a,b,c,d,e,f,g,h,j,k,l,m,o,p,r,s,t,u,v,w,y,z.json');

    /* Local JSON file for testing purposes*/
//     $online_atc_json = file_get_contents('test.json');

    $online_atc = json_decode($online_atc_json, true);

    $output_table = '<table>';
    $sup_table = '<table>';

    foreach($online_atc as $station) {

            $callsign = $station['callsign'];
            $rating = $station['rating'];
            $freq = $station['frequency'];
            $name = $station['name'];
            $login_time = $station['online_since'];

        // don't add atc station if it's an ATIS, or an observer (who all have freq = 199.998)
        if (strpos($callsign, '_ATIS') === false and strpos($freq, '199.998') === false and strpos($rating, 'OBS') === false) {

            $output_table = $output_table . '<tr>';
                $output_table = $output_table . '<td>' . $callsign . '</td>';
                $output_table = $output_table . '<td>' . $freq . '</td>';
                $output_table = $output_table . '<td>' . $name . '</td>';
                $output_table = $output_table . '<td>' . $login_time . '</td>';
            $output_table = $output_table . '</tr>';

        }
        else if (strpos($freq, '199.998') === true and strcomp($rating, 'Supervisor') === 0) {

            $sup_table = $sup_table . '<tr>';
                $sup_table = $sup_table . '<td>' . $callsign . '</td>';
                $sup_table = $sup_table . '<td>' . $name . '</td>';
                $sup_table = $sup_table . '<td>' . $login_time . '</td>';
            $sup_table = $sup_table . '</tr>';

        }
    }


    $output_table = $output_table . '</table>';
    $sup_table = $sup_table . '</table>';

    echo $output_table;

    echo '

';

//    echo '<p><b>Supervisors currently online</b></p>';

    echo '

';

    echo $sup_table;

?>