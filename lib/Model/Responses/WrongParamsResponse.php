<?php

namespace Model\Responses;


class WrongParamsResponse extends Response
{
    public function __construct()
    {
        parent::__construct(parent::FAULT_STATUS, 'Wrong params', []);
    }
}