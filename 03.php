<?php
include_once 'class/WirePanel.php';
include_once 'class/Wire.php';
$wirePanel = new WirePanel();
$wirePanel->drawStatus();

$file_lines = file('input/demo.txt');
foreach($file_lines as $wireInput) {
    $wire = new Wire(explode(",", $wireInput));
    var_dump($wire);
    //$wirePanel->addWire($wire);
    //$wirePanel->drawStatus();
}
//$input = explode(",", $file_lines[0]);
//$intcode = new Intcode($input);
