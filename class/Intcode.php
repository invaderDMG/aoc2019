<?php
include_once 'Opcode.php';

class Intcode
{
    const OPCODE_ADD = 1;
    const OPCODE_ADD_PARAMETER_A = 1;
    const OPCODE_ADD_PARAMETER_B = 2;
    const OPCODE_ADD_OUTPUT = 3;
    const OPCODE_ADD_STEP = 4;

    const OPCODE_MULTIPLY = 2;
    const OPCODE_MULTIPLY_PARAMETER_A = 1;
    const OPCODE_MULTIPLY_PARAMETER_B = 2;
    const OPCODE_MULTIPLY_OUTPUT = 3;
    const OPCODE_MULTIPLY_STEP = 4;

    const OPCODE_INPUT = 3;
    const OPCODE_INPUT_PARAMETER_A = 1;
    const OPCODE_INPUT_STEP = 2;

    const OPCODE_OUTPUT = 4;
    const OPCODE_OUTPUT_PARAMETER_A = 1;
    const OPCODE_OUTPUT_STEP = 2;

    const OPCODE_HALT = 99;
    const OPCODE_HALT_STEP = 2;

    private $source;
    private $instructionPointer = 0;

    /** @var Opcode */
    private $currentOpcode;
    /** @var int */
    private $input;
    /** @var int */
    private $output;

    /**
     * @return int
     */
    public function getInput(): int
    {
        return $this->input;
    }

    /**
     * @param int $input
     */
    public function setInput(int $input): void
    {
        $this->input = $input;
    }



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
//                echo "debug: ";
//                echo $this->currentOpcode->getOperation().",";
//                echo $this->getAddress(self::OPCODE_ADD_PARAMETER_A).",";
//                echo $this->getAddress(self::OPCODE_ADD_PARAMETER_B).",";
//                echo $this->getAddress(self::OPCODE_ADD_OUTPUT)."\n";
                $this->sum();
                break;
            case self::OPCODE_MULTIPLY:
//                echo "debug: ";
//                echo $this->currentOpcode->getOperation().",";
//                echo $this->getAddress(self::OPCODE_MULTIPLY_PARAMETER_A).",";
//                echo $this->getAddress(self::OPCODE_MULTIPLY_PARAMETER_B).",";
//                echo $this->getAddress(self::OPCODE_MULTIPLY_OUTPUT)."\n";
                $this->multiply();
                break;

            case self::OPCODE_INPUT:
//                echo "debug: ";
//                echo $this->currentOpcode->getOperation().",";
//                echo $this->getAddress(self::OPCODE_INPUT_PARAMETER_A)."\n";
                $this->input();
                break;

            case self::OPCODE_OUTPUT:
//                echo "debug: ";
//                echo $this->currentOpcode->getOperation().",";
//                echo $this->getAddress(self::OPCODE_OUTPUT_PARAMETER_A)."\n";
                $this->output();
                break;

            case self::OPCODE_HALT:
                $this->stepForward(self::OPCODE_HALT_STEP);
                return 1;
            default:
                die("unsupported operation: ".$this->currentOpcode->getOperation()."\n");
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

    public function setNounAndVerb($noun, $verb)
    {
        $this->setValue(1, $noun);
        $this->setValue(2, $verb);
    }

    public function getOutput()
    {
        return $this->output;
    }

    private function offsetPointer(int $offset)
    {
        return $this->instructionPointer + $offset;
    }

    private function sum()
    {
        $inputA = $this->getParameter($this->currentOpcode->getMode(0), self::OPCODE_ADD_PARAMETER_A);
        $inputB = $this->getParameter($this->currentOpcode->getMode(1), self::OPCODE_ADD_PARAMETER_B);
        $outputAddress = $this->getAddress(self::OPCODE_ADD_OUTPUT);
//        echo $inputA." + ".$inputB ." in outputAddress = ".$outputAddress."\n";
        $this->setValue($outputAddress, $inputA + $inputB);
        $this->stepForward(self::OPCODE_ADD_STEP);
    }

    private function multiply()
    {
        $inputA = $this->getParameter($this->currentOpcode->getMode(0), self::OPCODE_MULTIPLY_PARAMETER_A);
        $inputB = $this->getParameter($this->currentOpcode->getMode(1), self::OPCODE_MULTIPLY_PARAMETER_B);
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
            case Opcode::MODE_IMMEDIATE:
                $parameter = $this->getImmediateValue($offset);
                break;
            default:
                $parameter = $this->getValueFromPosition($offset);
        }
        return $parameter;
    }

    private function input()
    {
        $address = $this->getParameter(Opcode::MODE_IMMEDIATE, self::OPCODE_INPUT_PARAMETER_A);
        $this->setValue($address, $this->input);
        $this->stepForward(self::OPCODE_INPUT_STEP);

    }

    private function output()
    {
        $address = $this->getParameter(Opcode::MODE_IMMEDIATE, self::OPCODE_OUTPUT_PARAMETER_A);
        $this->output = $this->getValue($address);
        $this->stepForward(self::OPCODE_OUTPUT_STEP);
    }
}