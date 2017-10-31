<?php
    $csv  = array_map(function($v){return str_getcsv($v, "\t");}, file('Perú VIN Driver + Passenger (DLR)_amend3.xlsx'));
    print_r($csv);
?>