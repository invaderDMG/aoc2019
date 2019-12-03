<?php
include_once 'class/WirePanel.php';
$file_lines = file('input/demo.txt');
//$input = explode(",", $file_lines[0]);
//$intcode = new Intcode($input);
$wirePanel = new WirePanel();
$wirePanel->drawStatus();