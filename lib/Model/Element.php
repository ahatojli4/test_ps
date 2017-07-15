<?php

namespace Model;


abstract class Element
{
    /**
     * @var array
     */
    protected $dataArray;

    /**
     * Element constructor.
     * @param array $dataArray
     */
    public function __construct($dataArray)
    {
        $this->dataArray = $dataArray;
    }


    /**
     * @return array
     */
    public function getArray()
    {
        return $this->dataArray;
    }
}