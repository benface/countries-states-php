<?php

$states_contents = file_get_contents('http://download.geonames.org/export/dump/admin1CodesASCII.txt');
$states_lines = explode("\n", $states_contents);
$states = [];
foreach ($states_lines as $line) {
    $parts = explode("\t", $line);
    if (count($parts) <= 1) {
        continue;
    }
    $country_code = substr($parts[0], 0, 2);
    $state = $parts[2];
    if (!isset($states[$country_code])) {
        $states[$country_code] = [];
    }
    $states[$country_code][] = $state;
}
foreach ($states as $country_code => $country_states) {
    sort($states[$country_code]);
}

$countries_contents = file_get_contents('http://download.geonames.org/export/dump/countryInfo.txt');
$countries_lines = explode("\n", $countries_contents);
$countries = [];
foreach ($countries_lines as $line) {
    if (substr($line, 0, 1) === "#") {
        continue;
    }
    $parts = explode("\t", $line);
    if (count($parts) <= 1) {
        continue;
    }
    $country_code = $parts[0];
    $country_name = $parts[4];
    $country_states = [];
    if (isset($states[$country_code])) {
        $country_states = $states[$country_code];
    }
    $countries[$country_name] = [
        'name' => $country_name,
        'code' => $country_code,
        'states' => $country_states
    ];
}
ksort($countries);

echo json_encode($countries);

/*
foreach ($countries as $country) {
    echo "'".str_replace("'", "\'", $country['name'])."' => [<br>";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;'name' => '".$country['name']."',<br>";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;'code' => '".$country['code']."',<br>";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;'states' => [<br>";
    foreach ($country['states'] as $n => $state) {
        if ($n > 0) {
            echo ',<br>';
        }
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'".str_replace("'", "\'", $state)."'";
    }
    echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;]<br>";
    echo "],<br>";
}
*/