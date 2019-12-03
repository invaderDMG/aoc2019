<?php


class WirePanel
{
    private $width;
    private $height;
    private $panel;

    /**
     * WirePanel constructor.
     * @param $width
     * @param $height
     */
    public function __construct()
    {
        $this->width = 11;
        $this->height = 10;
        $this->panel = [];
        $this->reset();
    }

    private function reset()
    {
        for($row = 1; $row <= $this->height; $row++) {
            for($column = 1; $column <= $this->width; $column++) {
                $this->panel[$row][$column] = ".";
            }
        }
    }

    public function drawStatus()
    {
        for($row = 1; $row <= $this->height; $row++) {
            for($column = 1; $column <= $this->width; $column++) {
                echo $this->panel[$row][$column];
            }
            echo "\n";
        }
    }


}