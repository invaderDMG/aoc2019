<?php 
include_once 'class/Intcode.php';
$file_lines = file('input/02.txt');

$intcode = new Intcode(explode(",",$file_lines[0]));
$intcode->setInput(12,2);
$intcode->operate();
echo $intcode->getOutput()."\n";