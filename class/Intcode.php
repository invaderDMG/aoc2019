<?php


class Intcode
{
    const OFFSET_INPUT1 = 1;
    const OFFSET_INPUT2 = 2;
    const OFFSET_OUTPUT = 3;
    const STEP_FORWARD = 4;
    private $source;
    private $cursor = 0;

    public function __construct($source)
    {
        $this->source = $source;
    }

    public function getOpcode()
    {
        return $this->source[$this->cursor];
    }

    public function getInputs()
    {
        return [$this->source[$this->cursor+ self::OFFSET_INPUT1], $this->source[$this->cursor + self::OFFSET_INPUT2]];
    }


    public function setOutput($output)
    {
        $address = $this->getValue($this->cursor + self::OFFSET_OUTPUT);
        $this->source[$address] = $output;
    }

    public function stepForward()
    {
        $this->cursor = $this->cursor+self::STEP_FORWARD;
    }

    public function operate()
    {
        switch($this->getOpcode()) {
            case 1:
                $this->setOutput($this->sumInputs());
                break;
            case 2:
                $this->setOutput($this->multiplyInputs());
                break;
            case 99:
                return 1;
        }
        $this->stepForward();
        $this->operate();
    }

    public function printSource()
    {
        echo implode(",", $this->source)."\n";
    }

    private function sumInputs()
    {
        return $this->getValue($this->getInputs()[0]) + $this->getValue($this->getInputs()[1]);
    }

    private function multiplyInputs()
    {
        return $this->getValue($this->getInputs()[0]) * $this->getValue($this->getInputs()[1]);
    }

    public function getValue($address)
    {
        return $this->source[$address];
    }

    public function setValue($address, $value)
    {
        $this->source[$address] = $value;
    }
}