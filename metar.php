<?php

//    class Metar
//    {

//        private $airport;
//        private $region;        // 'metric', 'north_america', or 'global'
//        private $time;
//        private $winds;
//        private $clouds;
//        private $temp;
//        private $dewpoint;
//        private $alt_pressure;

//        function __construct($apt)
//        {
//            $airport = $apt;
//            $metar_string =
//
//        }

//        /**
//         * Retrieves text between (inlcusive) the specified indicies
//         *
//         * @param $metar_string     the METAR to parse through
//         * @param $start            index to start at
//         * @param $end              index to end at
//         */
//        function parser($metar_string, $start, $end) {
//
//            $len = strlen($metar_string);
//            $ttl_chars = $end - $start;
//
//            $return_text = '';
//
//            for ($i = 0; $i < $ttl_chars; $i++) {
//                $return_text[i] = $metar_string[$start + $i];
//            }
//
//        }

        /**
         * Determines if a smaller string exists in a larger string.
         *
         * @param $term     the string to search for
         * @param $string   the string to search in
         * @return bool     true if $term exists in $string, false otherwise
         */
        function contains_string($term, $string) {

            if (strpos($string, $term) !== false)
                return true;
            else return false;

        }


        /**
         * Determines if an airport is in North America, and therefore uses inHg for pressure setting
         *
         * @param $airport      ICAO airport code
         * @return bool         true if North American, false otherwise
         */
        function is_north_american($airport) {
            $iterate_on = str_split($airport);
            if (strcmp($iterate_on[0], 'K') == 0 or strcmp($iterate_on[0], 'C') == 0
                    or strcmp($iterate_on[0], 'M') == 0)
                return true;
            else return false;
        }

        /**
         * Determines if an airport uses the metric system.
         *
         * @param $airport  ICAO airport code
         * @return bool     true if metric, false otherwise
         */
        function is_metric($airport) {
            $iterate_on = str_split($airport);
            if (strcmp($iterate_on[0], 'U') == 0 or strcmp($iterate_on[0], 'Z') == 0)
                return true;
            else return false;
        }

        /**
         * Retrieves the issue time of the METAR
         *
         * @param $metar_string     the entire METAR
         * @return string           METAR issue time, formatted as [DDHHMM]Z
         */
        function get_issue_time($metar_string) {
            $issued = '';
            $issue_time_start = 5;          // 5 == index at which time/date begins in a metar

            // split string for ease of operations
            $iterate_on = '';
            $iterate_on = str_split($metar_string);

            for ($i = 0; $i < 7; $i++) {
                $issued[$i] = $iterate_on[5 + $i];
            }

            $issue_time = implode($issued);

            return $issue_time;
        }



//    }
?>