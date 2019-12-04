<?php
include_once 'Coordinate.php';

class WirePanel
{
    const MARKER_WIRE = "#";
    const MARKER_CROSS = "X";
    const MARKER_CENTER = "o";
    private $panel;
    private $center;
    private $intersections = [];

    public function __construct()
    {
        $this->panel = [];
        $this->center = new Coordinate(0,0);
        $this->setCoordinate($this->center, self::MARKER_CENTER);
    }

    /**
     * @return Coordinate
     */
    public function getMinimumCoordinate(): Coordinate
    {
        $minX = min(array_keys($this->panel));
        $minY = 0;
        foreach($this->panel as $column) {
            $minColumn = min(array_keys($column));
            $minY = ($minColumn <$minY)?$minColumn:$minY;
        }

        return new Coordinate($minX, $minY);
    }

    /**
     * @return Coordinate
     */
    public function getMaximumCoordinate(): Coordinate
    {
        $maxX = max(array_keys($this->panel));
        $maxY = 0;
        foreach($this->panel as $column) {
            $maxColumn = max(array_keys($column));
            $maxY = ($maxColumn > $maxY)?$maxColumn:$maxY;
        }
        return new Coordinate($maxX, $maxY);
    }

    /*
     * DEBUG PURPOSE ONLY, it delays the calculations in O(n^2)
     */
    public function drawStatus()
    {
        $min = $this->getMinimumCoordinate();
        $max = $this->getMaximumCoordinate();

        for($j = $max->getY(); $j >= $min->getY(); $j--) {
            for($i = $min->getX(); $i <= $max->getX(); $i++) {
                echo $this->getCoordinateValue(new Coordinate($i, $j));
            }
            echo "\n";
        }
        echo "\n";
    }

    public function addWire(Wire $wire)
    {
        $startingPoint = $this->center;
        foreach ($wire->getPath() as $step) {
            $coordinate = $this->calculateNextStep($startingPoint, $step);
            $this->drawLine($startingPoint, $coordinate, $wire->getValue());
            $startingPoint = $coordinate;
        }
    }

    private function calculateNextStep(Coordinate $coordinate, $step)
    {
        $step = str_replace("\n","",$step);
        $direction = substr($step, 0,1);
        $movement = substr($step, 1);
        $x = $coordinate->getX();
        $y = $coordinate->getY();
        switch ($direction) {
            case "U":
                $y += $movement;
                break;
            case "R":
                $x += $movement;
                break;
            case "D":
                $y = $y - $movement;
                break;
            case "L":
                $x -= $movement;
                break;
        }
        return new Coordinate($x, $y);
    }

    private function setCoordinate(Coordinate $coordinate, $value = self::MARKER_WIRE)
    {
        if (!isset($this->panel[$coordinate->getX()][$coordinate->getY()])) {
            $this->setCoordinateValue($coordinate, $value);
        } else {
            if ($this->getCoordinateValue($coordinate) == self::MARKER_CENTER) {
                $this->setCoordinateValue($coordinate, self::MARKER_CENTER);
            }
            if ($this->getCoordinateValue($coordinate) != 0 && $this->getCoordinateValue($coordinate) != $value && $this->getCoordinateValue($coordinate) != self::MARKER_CENTER) {
                $this->setCoordinateValue($coordinate, self::MARKER_CROSS);
                $this->intersections[] = $coordinate;
            }
        }
    }

    private function drawLine(Coordinate $startingPoint, Coordinate $endpoint, $value)
    {
        if ($startingPoint->getX() == $endpoint->getX()) {
            if ($startingPoint->getY() < $endpoint->getY()) {
                $this->drawHorizontalLine($startingPoint, $endpoint, $value);
            } else {
                $this->drawHorizontalLine($endpoint, $startingPoint, $value);
            }
        } else if ($startingPoint->getY() == $endpoint->getY()){
            if ($startingPoint->getX() < $endpoint->getX()) {
                $this->drawVerticalLine($startingPoint, $endpoint, $value);
            } else {
                $this->drawVerticalLine($endpoint, $startingPoint, $value);
            }

        }
    }

    private function drawHorizontalLine($leftest, $rightest, $value)
    {
        for($i = $leftest->getY(); $i<=$rightest->getY(); $i++){
            $this->setCoordinate(new Coordinate($leftest->getX(), $i), $value);
        }

    }

    private function drawVerticalLine($top, $bottom, $value)
    {
        for($i = $top->getX(); $i<=$bottom->getX(); $i++){
            $this->setCoordinate(new Coordinate($i, $top->getY()), $value);
        }
    }

    private function getCoordinateValue(Coordinate $coordinate)
    {
        if (!isset($this->panel[$coordinate->getX()][$coordinate->getY()])) {
            return ".";
        }
        return $this->panel[$coordinate->getX()][$coordinate->getY()];
    }

    private function setCoordinateValue(Coordinate $coordinate, string $value)
    {
        $this->panel[$coordinate->getX()][$coordinate->getY()] = $value;
    }

    public function getNearestIntersection()
    {
        foreach($this->intersections as $i => $coordinate) {
            $this->intersections[$i] = $this->manhattanDistance($this->center, $coordinate);
        }
        return min($this->intersections);
    }

    private function manhattanDistance(Coordinate $min, Coordinate $max)
    {
        return abs($min->getX() - $max->getX()) + abs($min->getY() - $max->getY());
    }


}