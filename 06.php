<?php
include_once 'class/OrbitTree.php';
$orbits = file('input/06.txt');
$orbitTree = new OrbitTree();
foreach($orbits as $orbit) {
    $orbitTree->addOrbit($orbit);
}
//$orbitTree->printDebug();
echo "total number of direct and indirect orbits: ".$orbitTree->getSumOfDepths()."\n";
//1756 too low
//150150