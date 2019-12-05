<?php


class Opcode
{
    private $operation;
    private $modes;
    /**
     * Opcode constructor.
     */
    public function __construct($input)
    {
        $digits = str_split($input, 1);
        $this->operation = array_pop($digits)+10*array_pop($digits);
        while(sizeof($digits) > 0) {
            $this->modes[] = array_pop($digits);
        }
    }

    /**
     * @return int
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * @return array
     */
    public function getModes(): array
    {
        return $this->modes;
    }
}