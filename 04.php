<?php
include_once 'class/PasswordChecker.php';
$input = "234208-765869";
$passwordChecker = new PasswordChecker();
echo "Number of possible passwords: ".$passwordChecker->getPossiblePasswords($input)."\n";
echo "Number of possible passwords: ".$passwordChecker->getPossiblePasswordsWithExtraRules($input)."\n";