<?php

$i = 0;
$_POST['random'] = rand($i, 100);
$_POST['date'] = date('m-d-y h:i:s A');


echo "<pre>";
    print_r($_POST);
echo "</pre>";

foreach ($_POST as $key => $value) {

	echo $key . ":" . $value . "<br>";
}
?>