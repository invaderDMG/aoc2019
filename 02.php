<?php 
include_once 'class/Intcode.php';
$file_lines = file('input/02.txt');

$input = explode(",", $file_lines[0]);
$intcode = new Intcode($input);
$intcode->setInput(12,2);
$intcode->operate();
echo "Solution for nº1: ".$intcode->getOutput()."\n";
for ($noun = 0; $noun <= 99; $noun++) {
    for ($verb = 0; $verb <= 99; $verb++) {
        $intcode = new Intcode($input);
        $intcode->setInput($noun,$verb);
        $intcode->operate();
        if ($intcode->getOutput() == 19690720) {
            echo "Solution for nº2: \n - Noun: ".$noun."\n - Verb: ".$verb."\n";
            echo "100 * noun + verb = ".(100*$noun+$verb)."\n";
        }
    }
}