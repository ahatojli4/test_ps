<?php

namespace Model\Responses;


class SubscribeResponse extends Response
{
    /**
     * SubscribeResponse constructor.
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct(parent::SUCCESS_STATUS,$message, []);
    }

    /**
     * Created only for satisfying task condition
     * @return string
     */
    public function getJson()
    {
        $responseData['status'] = $this->status;
        $responseData['message'] = $this->message;
        return json_encode($responseData);

    }
}