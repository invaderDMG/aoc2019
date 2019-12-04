<?php
include_once 'class/WirePanel.php';
include_once 'class/Wire.php';
$wirePanel = new WirePanel();
$file_lines = file('input/demo.txt');
$wireValue = 1;
foreach($file_lines as $wireInput) {
    $wire = new Wire(explode(",", $wireInput), $wireValue);
    $wirePanel->addWire($wire);
    //$wirePanel->drawStatus();
    $wireValue++;
}
echo "Nearest intersection is ".$wirePanel->getNearestIntersection()." units away\n";