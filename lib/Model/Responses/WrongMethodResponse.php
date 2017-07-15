<?php

namespace Model\Responses;


class WrongMethodResponse extends Response
{
    public function __construct()
    {
        parent::__construct(parent::FAULT_STATUS, 'Method doesn\'t exists', []);
    }
}