<?php
include_once 'Coordinate.php';
include_once 'Segment.php';

class WirePanel
{
    const MARKER_WIRE = "#";
    const MARKER_CROSS = "X";
    const MARKER_CENTER = "o";
    private $panel;
    /** @var Coordinate  */
    private $center;

    /** @var Coordinate[] */
    private $intersectionList = [];
    private $intersectionDistanceList = [];

    /** @var Wire[] */
    private $wireList;

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
        $img = imagecreatetruecolor(abs($max->getX())+abs($min->getX())+1,abs($max->getY())+abs($min->getY())+1);
        $black = imagecolorallocate($img,0,0,0);
        for($y = $min->getY(); $y <= $max->getY(); $y++) {
            for($x = $min->getX(); $x <= $max->getX(); $x++) {
                switch($this->getCoordinateValue(new Coordinate($x, $y))) {
                    case 1:
                        $color = imagecolorallocate($img,255,0,0);
                        break;
                    case 2:
                        $color = imagecolorallocate($img,0,0,255);
                        break;
                    case self::MARKER_CENTER:
                        $color = imagecolorallocate($img, 0,255,0);
                        break;
                    case self::MARKER_CROSS:
                        $color = imagecolorallocate($img, 255,0,255);
                        break;
                    default:
                        $color = $black;
                }
                imagesetpixel($img,$x,$max->getY()-$y,$color);
            }
        }
        header('Content-Type: image/png');
        imagepng($img, "wiremap.png");
        imagedestroy($img);
    }

    public function addWire(Wire $wire)
    {
        $this->wireList[] = $wire;
        $this->connectWire($wire);
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
                $this->intersectionList[] = $coordinate;
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
        foreach($this->intersectionList as $i => $coordinate) {
            $this->intersectionDistanceList[$i] = $this->manhattanDistance($this->center, $coordinate);
        }
        return min($this->intersectionDistanceList);
    }

    private function manhattanDistance(Coordinate $min, Coordinate $max)
    {
        return abs($min->getX() - $max->getX()) + abs($min->getY() - $max->getY());
    }

    private function connectWire($wire)
    {
        $startingPoint = $this->center;
        foreach ($wire->getPath() as $step) {
            $coordinate = $this->calculateNextStep($startingPoint, $step);
            $this->drawLine($startingPoint, $coordinate, $wire->getValue());
            $startingPoint = $coordinate;
        }
    }

    public function getShortestPathToIntersection()
    {
        $maxCoordinate = $this->getMaximumCoordinate();
        $minimumPath = $maxCoordinate->getX() * $maxCoordinate->getY();
        /** @var Coordinate $intersection */
        foreach($this->intersectionList as $intersection) {
            echo "getting path from ".$this->center->prettyPrint()." to ".$intersection->prettyPrint()."\n";
            $path0 = $this->followPath($this->wireList[0], $intersection);
            $path1 = $this->followPath($this->wireList[1], $intersection);
            $combinedPath = $path0+$path1;
            if ($combinedPath < $minimumPath) {
                $minimumPath = $combinedPath;
            }
            echo "Path 0 is ".$path0.", Path 1 is ".$path1.", combined is ".$combinedPath."\n";
        }
        return $minimumPath;
    }

    private function followPath(Wire $wire, Coordinate $target)
    {
        $startingPoint = $this->center;
        $stepCounter = 0;
        foreach ($wire->getPath() as $step) {
            $nextCoordinate = $this->calculateNextStep($startingPoint, $step);
            echo "- Going from ".$startingPoint->prettyPrint()." to ".$nextCoordinate->prettyPrint()." to reach ".$target->prettyPrint().". ";
            $segment = new Segment($startingPoint, $nextCoordinate);
            if (!$segment->contains($target)) {
                $manhattanDistance = $this->manhattanDistance($startingPoint, $nextCoordinate);
                $stepCounter+= $manhattanDistance;
                echo "Walked a distance of ".$manhattanDistance." units and a total of ".$stepCounter."\n";
            } else {
                $manhattanDistance = $this->manhattanDistance($startingPoint, $target);
                $stepCounter+= $manhattanDistance;
                echo "Walked a distance of ".$manhattanDistance." units and a total of ".$stepCounter." and reached it!\n";
                return $stepCounter;
            }
            $startingPoint = $nextCoordinate;
        }
    }


}