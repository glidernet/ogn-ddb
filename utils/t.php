<?php
// Either download the JSON in your code like this,
// or just paste the contents into your code
$country_names = json_decode(
    file_get_contents("http://country.io/names.json")
, true);

function get_name($cc,$country_names) {
    return $country_names[$cc];
}

echo get_name("GB", $country_names); // => "United Kingdom"
?>
