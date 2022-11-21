<?php

$a = new A(); $b = new B();

$someVariable = 'test';
$a->create($someVariable);

$someObject = new SomeObject();
$b->create($someObject);

?>
-----
<?php

$a = new A(); $b = new B();

$someVariable = 'test';
$a->create();

$someObject = new SomeObject();
$b->create();

?>
