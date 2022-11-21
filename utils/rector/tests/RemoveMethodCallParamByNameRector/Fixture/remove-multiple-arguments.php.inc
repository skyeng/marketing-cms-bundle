<?php

$someString = '';
$someInt = 0;
$someNull = null;
$someArray = [''];
$someObject = new SomeObject();

C::create($someString, $someObject, $someInt, $someNull, $someArray);

?>
-----
<?php

$someString = '';
$someInt = 0;
$someNull = null;
$someArray = [''];
$someObject = new SomeObject();

C::create($someString, $someInt);

?>
