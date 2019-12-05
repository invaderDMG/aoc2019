<?php


class Opcode
{
    private $operation;
    private $modes = [];

    const MODE_POSITION = 0;
    const MODE_IMMEDIATE = 1;
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

    public function getMode(int $int)
    {
        $mode = self::MODE_POSITION;
        if (isset($this->modes[$int])) {
            $mode = $this->modes[$int];
        }
        return $mode;
    }
}