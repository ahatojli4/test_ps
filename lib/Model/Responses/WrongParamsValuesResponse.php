<?php

namespace Model\Responses;


class WrongParamsValuesResponse extends Response
{
    public function __construct()
    {
        parent::__construct(parent::FAULT_STATUS, 'Wrong param values!', []);
    }
}