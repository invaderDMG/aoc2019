<?php


class Node
{
    /** @var string */
    private $value;

    /** @var int */
    private $parent = null;

    private $numberOfOrbits = 0;

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param int|null $parent
     */
    public function setParent($parent): void
    {
        $this->parent = $parent;
    }

    public function setNumberOfOrbits($orbitsOfParent)
    {
        $this->numberOfOrbits = $orbitsOfParent;
    }

    public function getNumberOfOrbits()
    {
        return $this->numberOfOrbits;
    }
}