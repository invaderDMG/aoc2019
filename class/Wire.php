<?php


class Wire
{
    private $path;
    private $value;
    /**
     * Wire constructor.
     */
    public function __construct($path, $value)
    {
        $this->path = $path;
        $this->value = $value;
    }

    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }


}