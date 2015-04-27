<style type="text/css">

    .rawmetar {
        font-family: "Consolas", monospace;
        font-size: 36px;
    }

    .content {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        font-size: 36px;
    }

    .smalltext {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    }

</style

<p>
<p class = "content"><a href="fltsim_resource.html">&lt; Return to menu</a></p>
</p>

<p>
<p class = "content"><b>Config</b></p>
</p>

<p class = "content">Download OFP</p>

<form method="post" action="">
    <input type="text" name="link" style="text-transform: lowercase; font-size: 24">
    <input type="submit" value="Go" style="font-size: 24">
</form>

<p>
    <?php
    $last_saved = file_get_contents('last_saved.txt');
    echo '<p class = "smalltext"><b>Last download:</b> ' . $last_saved . '</p>';
    ?>
</p>

<?php



// suppress errors on inital load
if (!isset($_POST['link']))
    exit;

$url = $_POST['link'];

// alternate data source, with no restrictions (NOAA): http://weather.noaa.gov/pub/data/observations/metar/stations/[ICAO].TXT

$downloaded_ofp = file_get_contents($url);

$success = file_put_contents("ofp.pdf", $downloaded_ofp);

if ($success) {
    echo '<p>Downloaded ' . $url . '</p>';
    $savestring = $url . ' at ' . gmdate("M d Y H:i:s", time());
    file_put_contents('last_saved.txt', $savestring);
}
else echo '<p>Failed!</p>';


//    $press = preg_grep('~[AQ]\d{4}~', str_split($metar_string));
//    var_dump($press);
?>

<p>&nbsp;</p>

<table style="width:100%" class = content>
    <tr>
        <td><b><?php echo ''?></b></td>
    </tr>
</table>