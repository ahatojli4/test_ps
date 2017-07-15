<?php

namespace Model\Responses;


use Model\Element;

class TableResponse extends Response
{
    /**
     * TableResponse constructor.
     * @param Element[] $objects
     */
    public function __construct($objects)
    {
        parent::__construct(parent::SUCCESS_STATUS, '', $objects);
    }
}