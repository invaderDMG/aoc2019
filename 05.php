<?php 
include_once 'class/Intcode.php';
$file_lines = file('input/05.txt');

$input = explode(",", $file_lines[0]);
$intcode = new Intcode($input);
$intcode->setInput(1);
$intcode->operate();
echo "Solution for nº1: ".$intcode->getOutput()."\n";
