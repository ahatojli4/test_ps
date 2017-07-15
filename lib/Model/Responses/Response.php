<?php

namespace Model\Responses;


use Model\Element;

abstract class Response
{
    const SUCCESS_STATUS = 'ok';
    const FAULT_STATUS = 'error';

    /**
     * @var string
     */
    protected $status;
    /**
     * @var array
     */
    protected $data;
    /**
     * @var string
     */
    protected $message;

    /**
     * Response constructor.
     * @param string $status
     * @param string $message
     * @param Element[] $dataObjects
     */
    public function __construct($status, $message, $dataObjects)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $this->setResponseData($dataObjects);
    }

    /**
     * @param Element[] $data
     * @return array
     */
    private function setResponseData($data)
    {
        $result = [];
        foreach ($data as $element) {
            $result[] = $element->getArray();
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getJson()
    {
        $responseData['status'] = $this->status;
        $responseData['data'] = $this->data;
        $responseData['message'] = $this->message;
        return json_encode($responseData);

    }


}