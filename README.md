Foomanchu
=========

An Absurdly simple templating engine 

Usage
-----

```php
<?php
include "foomanchu.php";

$foomanchu = new FooManChu;

$template = file_get_contents("templates/index.fmc");

$template_data = array("username" => "micah blu", "tired" => true);

$foomanchu->render($template, $template_data);
```
