<?php

include_once 'Node.php';

class OrbitTree
{
    /** @var Node[] */
    private $nodeList = [];

    private $youList = [];
    private $sanList = [];

    /**
     * OrbitTree constructor.
     */
    public function __construct()
    {
        $this->addNode("COM");
    }


    public function addOrbit($orbit)
    {
        list($parent, $child) = explode(")", str_replace("\n","",$orbit));
        $parentIndex = (int)$this->findByValue($parent);
        if ($parentIndex == -1) {
            $this->addNode($parent);
            $parentIndex = array_key_last($this->nodeList);
        }
        $childIndex = (int)$this->findByValue($child);
        if ($childIndex == -1) {
            $this->addNode($child);
            $childIndex = array_key_last($this->nodeList);
        }

        if ($childIndex != -1) {
            $this->connectNode($parentIndex, $childIndex);
        }
    }

    /**
     * @param $value
     * @return int
     */
    private function findByValue($value)
    {
        foreach($this->nodeList as $i => $node) {
            if ($node->getValue() == $value) {
                return $i;
            }
        }
        return -1;
    }

    public function getTotalOrbits($sum)
    {
        foreach($this->nodeList as $node) {
            if (is_null($node->getParent())) {
                return 0;
            } else {}
            $sum++;
        }
        return $sum;
    }

    /**
     * @param $parent
     * @param null $parentIndex
     */
    private function addNode($parent, $parentIndex = null)
    {
        $node = new Node();
        $node->setValue($parent);
        $node->setParent($parentIndex);
        $this->nodeList[] = $node;
    }

    public function getSumOfDepths()
    {
        $sum = 0;
        foreach($this->nodeList as $node) {
            $depth = $this->calculateDepth($node);
            $sum+=$depth;
        }
        return $sum;
    }

    /**
     * @param Node $node
     * @return int
     */
    private function calculateDepth(Node $node): int
    {
        $depth = 0;
        while (!is_null($node->getParent())) {
            $depth++;
            $node = $this->nodeList[$node->getParent()];
        }
        return $depth;
    }

    private function connectNode(int $parentIndex, int $childIndex)
    {
        $this->nodeList[$childIndex]->setParent($parentIndex);
    }

    public function getDistance($base, $target)
    {
        $this->youList = $this->getPathToCom($this->findByValue($base));
        $this->sanList = $this->getPathToCom($this->findByValue($target));
        return $this->removeRepeatedElementsOfTwoPaths(array_reverse($this->youList), array_reverse($this->sanList));
    }

    private function getPathToCom(int $index)
    {
        $array = [];
        $parentIndex = $this->nodeList[$index]->getParent();
        while(!is_null($parentIndex)) {
            if ($this->nodeList[$parentIndex]->getValue() != "COM") {
                $array[] = $this->nodeList[$parentIndex]->getValue();
            }
            $parentIndex = $this->nodeList[$parentIndex]->getParent();
        }
        return $array;
    }

    private function removeRepeatedElementsOfTwoPaths($firstList, $secondList)
    {
        for($i = 0; $i < min(sizeof($firstList), sizeof($secondList)); $i++) {
            if ($firstList[$i] == $secondList[$i]) {
                unset($firstList[$i]);
                unset($secondList[$i]);
            }
        }
        return sizeof($firstList)+sizeof($secondList);
    }
}