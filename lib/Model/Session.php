<?php

namespace Model;

class Session extends Element
{


    /**
     * Session constructor.
     * @param array $sessionData
     */
    public function __construct($sessionData)
    {
        parent::__construct($sessionData);
        $this->dataArray['Speakers'] = [];
    }

    /**
     * @param array $speaker
     */
    public function addSpeaker($speaker)
    {
        $this->dataArray['Speakers'][] = $speaker;
    }


}