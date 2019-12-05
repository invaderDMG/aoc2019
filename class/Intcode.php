<?php


class Intcode
{
    const OPCODE_ADD = 1;
    const OPCODE_ADD_INPUT_A = 1;
    const OPCODE_ADD_INPUT_B = 2;
    const OPCODE_ADD_OUTPUT = 3;
    const OPCODE_ADD_STEP = 4;

    const OPCODE_MULTIPLY = 2;
    const OPCODE_MULTIPLY_INPUT_A = 1;
    const OPCODE_MULTIPLY_INPUT_B = 2;
    const OPCODE_MULTIPLY_OUTPUT = 3;
    const OPCODE_MULTIPLY_STEP = 4;

    const OPCODE_INPUT = 3;

    const OPCODE_OUTPUT = 4;

    const OPCODE_HALT = 99;
    const OPCODE_HALT_STEP = 1;

    const MODE_POSITION = 0;
    const MODE_IMMEDIATE = 1;

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

    public function stepForward($numberOfValues)
    {
        $this->instructionPointer += $numberOfValues;
    }

    public function operate()
    {
        switch($this->getOpcode()) {
            case self::OPCODE_ADD:
                $this->sum();
                break;
            case self::OPCODE_MULTIPLY:
                $this->multiply();
                break;
            case self::OPCODE_HALT:
                $this->stepForward(self::OPCODE_HALT_STEP);
                return 1;
        }

        $this->operate();
    }

    public function printSource()
    {
        echo implode(",", $this->source)."\n";
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

    private function offsetPointer(int $offset)
    {
        return $this->instructionPointer + $offset;
    }

    private function sum($mode = self::MODE_POSITION)
    {
        if ($mode == self::MODE_POSITION) {
            $inputA = $this->getValueFromAddress(self::OPCODE_ADD_INPUT_A);
            $inputB = $this->getValueFromAddress(self::OPCODE_ADD_INPUT_B);
        }
        $address = $this->getAddress(self::OPCODE_ADD_OUTPUT);
        $this->setValue($address, $inputA + $inputB);
        $this->stepForward(self::OPCODE_ADD_STEP);
    }

    private function multiply($mode = self::MODE_POSITION)
    {
        if ($mode == self::MODE_POSITION) {
            $inputA = $this->getValueFromAddress(self::OPCODE_MULTIPLY_INPUT_A);
            $inputB = $this->getValueFromAddress(self::OPCODE_MULTIPLY_INPUT_B);
        }
        $address = $this->getAddress(self::OPCODE_MULTIPLY_OUTPUT);
        $this->setValue($address, $inputA * $inputB);
        $this->stepForward(self::OPCODE_MULTIPLY_STEP);
    }

    /**
     * @param $offset
     * @return int
     */
    private function getValueFromAddress($offset)
    {
        $position = $this->getValue($this->offsetPointer($offset));
        return $this->getValue($position);
    }

    /**
     * @param $offset
     * @return int
     */
    private function getAddress($offset)
    {
        return $this->getValue($this->offsetPointer($offset));
    }
}