<?php


class Intcode
{
    const OFFSET_INPUT1 = 1;
    const OFFSET_INPUT2 = 2;
    const OFFSET_OUTPUT = 3;
    const STEP_FORWARD_ADD = 4;
    const STEP_FORWARD_MULTIPLY = 4;
    const STEP_FORWARD_HALT = 1;
    private $source;
    private $instructionPointer = 0;

    public function __construct($source)
    {
        $this->source = $source;
    }

    public function getOpcode()
    {
        return $this->source[$this->instructionPointer];
    }

    public function getInputs()
    {
        return [$this->source[$this->instructionPointer+ self::OFFSET_INPUT1], $this->source[$this->instructionPointer + self::OFFSET_INPUT2]];
    }


    public function setOutput($output)
    {
        $address = $this->getValue($this->instructionPointer + self::OFFSET_OUTPUT);
        $this->source[$address] = $output;
    }

    public function stepForward($numberOfValues)
    {
        $this->instructionPointer += $numberOfValues;
    }

    public function operate()
    {
        switch($this->getOpcode()) {
            case 1:
                $this->setOutput($this->sumInputs());
                $this->stepForward(self::STEP_FORWARD_ADD);
                break;
            case 2:
                $this->setOutput($this->multiplyInputs());
                $this->stepForward(self::STEP_FORWARD_MULTIPLY);
                break;
            case 99:
                $this->stepForward(self::STEP_FORWARD_HALT);
                return 1;
        }

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

    public function setInput($noun, $verb)
    {
        $this->setValue(1, $noun);
        $this->setValue(2, $verb);
    }

    public function getOutput()
    {
        return $this->getValue(0);
    }
}