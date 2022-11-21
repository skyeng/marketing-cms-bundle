<?php

$someVariable = 'test';
A::create($someVariable);

$someObject = new SomeObject();
B::create($someObject);

?>
-----
<?php

$someVariable = 'test';
A::create();

$someObject = new SomeObject();
B::create();

?>
