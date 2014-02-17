<?php

include "../foomanchu.php";

$foomanchu = new FooManChu;

$template = file_get_contents("index.fmc");

$template_data = array("username" => "micah blu");

$foomanchu->render($template, $template_data);