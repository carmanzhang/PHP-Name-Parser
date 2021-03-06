<?php
include("FullNameParser.php");
$parser = new FullNameParser();

// Assign JSON encoded string to a PHP variable
$json = '{"Peter":["1","2","3"]}';

// Decode JSON data to PHP object
$obj = json_decode($json);
$names = $obj->Peter;
$arrlength = count($names);

$arr = array();
for ($x = 0; $x < $arrlength; $x++) {
    echo $names[$x];
    $arr[] = $names[$x] . 'xx';
}
for ($x = 0; $x < $arrlength; $x++) {
    echo $arr[$x];
}
echo json_encode($arr);

// Loop through the object
//foreach ($obj as $key => $value) {
////    echo $key . " " . $value . "<br>";
//    echo gettype($key);
//    echo gettype($value);
//
//
//}


$parser = new FullNameParser();
$startTime = microtime(true);

for ($i = 0; $i < 99; $i++) {
    $ls = strtolower("Mr Anthony R Von Fange III");
//    print_r($ls);
    $name_parts = $parser->parse_name($ls);
//    echo gettype($arr);

    $name_parts['fname'];

//    [initials] => R
//    [lname] => Von Fange
//    print_r ($arr);
}

$endTime = microtime(true);
$runTime = ($endTime - $startTime) * 1000 . ' ms';
print_r($runTime);

$values = array( 'one', 'two', 'three' );
$valueList = implode( ' ', $values );
echo gettype($valueList);
echo $valueList;