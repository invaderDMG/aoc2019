<?php 
include_once 'class/Intcode.php';
$file_lines = file('input/02.txt');

$intcode = new Intcode(explode(",",$file_lines[0]));
$intcode->setValue(1,12);
$intcode->setValue(2,2);

$intcode->printSource();
$intcode->operate();
$intcode->printSource();

//1258669 it's too low