<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="icon" href="https://dl.dropboxusercontent.com/u/18938199/elato.duckdns.org/icons/appicon.png" />
    <title>Online ATC stations - Flight simulation resources</title>
</head>

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

//    set_error_handler(
//        create_function(
//            '$severity, $message, $file, $line',
//            'throw new ErrorException($message, $severity, $severity, $file, $line);'
//        )
//    );

    include('atc_backend.php');

        // yes, this is stupid, but it works........blame the VATEUD API -.-"
        $online_atc_json = file_get_contents('http://api.vateud.net/online/atc/a,b,c,d,e,f,g,h,j,k,l,m,o,p,r,s,t,u,v,w,y,z.json');

    /* Local JSON file for testing purposes*/
//     $online_atc_json = file_get_contents('test.json');

    $online_atc = json_decode($online_atc_json, true);

    $num_online = 0;

    $output_table = '<table border="1" cellpadding="4" class="smalltext">';
    $sup_table = '<table>';

    // column titles
    $output_table = $output_table . '<tr>
                                        <td width = 130><b>Station</b></td>
                                        <td width = 110><b>Frequency&nbsp;&nbsp;</b></td>
                                        <td width = 250><b>Callsign</b></td>
                                        <td width = 250><b>Controller</b></td>
                                        <td width = 200><b>Online since</b></td>
                                        <td><b>Time online</b>
                                    </tr>';

    // future feature: have different tables for each position type

    $position_types = array('control','center', 'centre', 'radar', 'approach', 'departure', 'director', 'arrival', 'tower', 'ground', 'delivery', 'clearance', 'terminal');


    foreach($online_atc as $station) {

            $callsign = $station['callsign'];
            $rating = $station['rating'];
            $freq = $station['frequency'];
            $name = $station['name'];
            $login_time = $station['online_since'];

            // format the login time a little better
            $login_time_unix = strtotime(preg_replace("/\d\d\d\d(-)\d\d(-)\d\d(T)/", "", $login_time));
            $login_time = str_replace('T',' at ',$login_time);

        // don't add atc station if it's an ATIS, or an observer (who all have freq = 199.998)
        if (strpos($callsign, '_ATIS') === false and strpos($freq, '199.998') === false and strpos($rating, 'OBS') === false) {

            // exception handling in case 'atis_message' doesn't exist for some odd reason
            if (array_key_exists('atis_message', $station) === true) {

                $position_name = str_replace('charts', '', $station['atis_message']);   // strip the word "charts"; it seems to be a common word that escapes subsequent filters
                $position_name = str_replace('Charts', '', $station['atis_message']);
                $position_name = str_replace("\\s", '', $position_name);
                $position_name = str_replace("providing", '', $position_name);      // Seattle likes to start their msg's with "Providing xxx services at...."
                $position_name = str_replace("Providing", '', $position_name);      // TODO: "Tower" still appears in the final result

                // strip all punctuation and <br/> tags, as well as escaped quotation marks
                if (strpos($position_name, "<br />")) {
                    $position_name = remove_after_word($position_name, "<br />");
                    $position_name = str_replace("<br /", '', $position_name);
                }

                // Please excuse the ridiculously long regex and blame places such as Munich, Sondrestrom etc.
                $position_name = preg_replace("/[^ \wÀÁÅÃÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿ]+/", "", $position_name);

                // get rid of everything except for the first three words, which will likely contain the station callsign
                $position_name = implode(' ', array_slice(explode(' ', $position_name), 0, 3));

                // exception for some two-worded city names, as well as controllers who prefix the callsign with "Callsign"
                // TODO: implement a more general regex-based solution
                $two_word_cities = array('los', 'las', 'hong', 'de', 'callsign', 'saint', 'santa');
                $pos_lowercase = strtolower($position_name);
                if (contains_word($pos_lowercase, $two_word_cities) === false)
                    $position_name = implode(' ', array_slice(explode(' ', $position_name), 0, 2));

            }
            else $position_name = '';

            $output_table = $output_table . '<tr>';
                $output_table = add_cell($output_table, $callsign);
                $output_table = add_cell($output_table, $freq);

            /**** Determine the station name ****/

            $pos_name_lowercase = strtolower($position_name);

            // remove the word "callsign" if it exists
            if (contains_word($pos_name_lowercase, array('callsign')))
                $position_name = strstr($position_name, ' ');

            if (contains_word($pos_name_lowercase, $position_types))
                $output_table = add_cell($output_table, $position_name);
            else
                $output_table = add_cell($output_table, '<font color = gray> &nbsp;- </font>');

            /**** Fin ****/


                $output_table = add_cell($output_table, $name);
                $output_table = add_cell($output_table, $login_time);


            /** Determine time online***/

            $seconds_online = time() - $login_time_unix;

            // not dealing with days because, seriously, who the hell controls airspace for more than a day?!
            $hours_online = floor($seconds_online / 3600);     //60^2 seconds in an hour
            $minutes_online = floor($seconds_online / 60) - 60 * $hours_online;

            // padding zeroes
            if ($minutes_online < 10)
                $minutes_online = '0' . $minutes_online;

            // hours sometimes ends up being negative......shitty handling for this case
            if ($hours_online < 0)
                $hours_online = $hours_online + 24;

            // not using helper method due to needing the <td align = center>
            $output_table = $output_table . '<td align = center>' . $hours_online . ':' . $minutes_online . '</td>';
//            $output_table = add_cell($output_table, $hours_online . ':' . $minutes_online);

            /** Fin **/


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

<p class = smalltext-medium><b>ATC stations currently online:</b> <?php echo $num_online ?> &nbsp;<b>[<a href = "atc.php">refresh</a>]</b></p>

<?php

    $output_table = $output_table . '</table>';
    $sup_table = $sup_table . '</table>';

    echo $output_table;

    echo '

    ';

    //    echo '<p><b>Supervisors currently online</b></p>';

    //    echo '<b class = smalltext>ATC positions online:</b> ' . $num_online;

//    echo $sup_table;

?>

<p>&nbsp;</p>

<hr>

<p class = smalltext-medium><b>FAQ</b></p>

<p class = smalltext><b>Why isn't the callsign showing?</b></p>

<p class = smalltext>
    The callsign is pulled from the controller's information (also known as the ATIS message).
    If the controller does not provide any, then there is nothing to pull from.
    As well, given the large number of ways that controllers specify their callsign, it's difficult to detect every single possibility out there.
    Therefore, even if it's provided by the controller, it might not be displayed above.
</p>

<p class = smalltext><b>Why am I seeing warnings at the top of the page?</b></p>

<p class="smalltext">
    Data for this table is pulled from the <a href = "http://api.vateud.net/">VATEUD API</a>.
    It occasionally craps out and doesn't serve any data at all, which confuses <i>my</i> application.
    If you're looking for somebody to blame, blame them for not being able to handle a large number of parameters.
    That, or blame them for not providing an API call that returns all online ATC stations, which forces me to literally poll for online stations within every single ICAO region (hence the large number of parameters).
</p>

<hr>

<p class="smalltext"><a href="#top">Return to top</a></p>