<?php 
include_once 'class/Intcode.php';
$file_lines = file('input/05.txt');

$input = explode(",", $file_lines[0]);
$intcode = new Intcode($input);
$intcode->printSource();
$intcode->setInput(5);
$intcode->operate();
echo "Solution for nº1: ".$intcode->getOutput()."\n";
