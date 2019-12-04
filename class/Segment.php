<?php
include_once 'Coordinate.php';

class Segment
{
    /** @var Coordinate */
    private $first;

    /** @var Coordinate */
    private $second;

    /**
     * Segment constructor.
     * @param Coordinate $a
     * @param Coordinate $b
     */
    public function __construct(Coordinate $a, Coordinate $b)
    {
        if ($a->getX() == $b->getX()) {
            if ($a->getY() < $b->getY()) {
                $this->setCoordinates($a, $b);
            } else {
                $this->setCoordinates($b, $a);
            }
        } else {
            if ($a->getX() < $b->getX()) {
                $this->setCoordinates($a, $b);
            } else {
                $this->setCoordinates($b, $a);
            }
        }
    }

    /**
     * @return Coordinate
     */
    public function getFirst(): Coordinate
    {
        return $this->first;
    }

    /**
     * @param Coordinate $first
     */
    public function setFirst(Coordinate $first): void
    {
        $this->first = $first;
    }

    /**
     * @return Coordinate
     */
    public function getSecond(): Coordinate
    {
        return $this->second;
    }

    /**
     * @param Coordinate $second
     */
    public function setSecond(Coordinate $second): void
    {
        $this->second = $second;
    }

    public function contains(Coordinate $target)
    {
        if ($target->getX() == $this->getFirst()->getX() && $target->getX() == $this->getSecond()->getX()) {
            return $target->getY() >= $this->getFirst()->getY() && $target->getY() <= $this->getSecond()->getY();
        }
        if ($target->getY() == $this->getFirst()->getY() && $target->getY() == $this->getSecond()->getY()) {
            return $target->getX() >= $this->getFirst()->getX() && $target->getX() <= $this->getSecond()->getX();
        }
    }

    /**
     * @param Coordinate $a
     * @param Coordinate $b
     */
    public function setCoordinates(Coordinate $a, Coordinate $b)
    {
        $this->first = $a;
        $this->second = $b;
    }


}