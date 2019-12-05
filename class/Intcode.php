<?php
include_once 'Opcode.php';

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

    /** @var Opcode */
    private $currentOpcode;

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

        $this->parseOpcode();
        switch($this->currentOpcode->getOperation()) {
            case self::OPCODE_ADD:
                $this->sum();
                break;
            case self::OPCODE_MULTIPLY:
                $this->multiply();
                break;
            case self::OPCODE_HALT:
                $this->stepForward(self::OPCODE_HALT_STEP);
                return 1;
            default:
                die("unsupported operation\n");
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

    private function sum()
    {
        $mode = $this->currentOpcode->getModes();
        $inputA = $this->getParameter($mode[0], self::OPCODE_ADD_INPUT_A);
        $inputB = $this->getParameter($mode[1], self::OPCODE_ADD_INPUT_B);
        $outputAddress = $this->getAddress(self::OPCODE_ADD_OUTPUT);
        $this->setValue($outputAddress, $inputA + $inputB);
        $this->stepForward(self::OPCODE_ADD_STEP);
    }

    private function multiply()
    {
        $mode = $this->currentOpcode->getModes();
        $inputA = $this->getParameter($mode[0], self::OPCODE_MULTIPLY_INPUT_A);
        $inputB = $this->getParameter($mode[1], self::OPCODE_MULTIPLY_INPUT_B);
        $outputAddress = $this->getAddress(self::OPCODE_MULTIPLY_OUTPUT);
        $this->setValue($outputAddress, $inputA * $inputB);
        $this->stepForward(self::OPCODE_MULTIPLY_STEP);
    }

    /**
     * @param $offset
     * @return int
     */
    private function getValueFromPosition($offset)
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

    private function getImmediateValue(int $offset)
    {
        return $this->getValue($this->offsetPointer($offset));
    }

    private function parseOpcode()
    {
        $this->currentOpcode = new Opcode($this->getOpcode());
    }

    /**
     * @param int $mode
     * @param $offset
     * @return int
     */
    private function getParameter(int $mode, $offset)
    {
        switch ($mode) {
            case self::MODE_IMMEDIATE:
                $parameter = $this->getImmediateValue($offset);
                break;
            default:
                $parameter = $this->getValueFromPosition($offset);
        }
        return $parameter;
    }
}