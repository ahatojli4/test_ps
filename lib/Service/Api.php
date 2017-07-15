<?php

namespace Service;

use Model\Responses\Response;
use Model\Responses\SubscribeResponse;
use Model\Responses\TableResponse;
use Model\Responses\WrongParamsResponse;
use Model\Responses\WrongParamsValuesResponse;

class Api
{
    /**
     * @var PdoStorage
     */
    private $storage;

    const TABLE_TYPE = 'Table';
    const SESSION_SUBSCRIBE_TYPE = 'SessionSubscribe';

    /**
     * UserGetter constructor.
     * @param PdoStorage $storage
     */
    public function __construct(PdoStorage $storage)
    {
        $this->storage = $storage;
    }


    /**
     * @param array $params $_POST array
     * @return Response
     */
    public function Table($params)
    {
        if (!$this->checkParams($params, self::TABLE_TYPE)) {
            $result = new WrongParamsResponse();
        } else {
            try {
                $objects = $this->storage->getTableObjects($params);
                $result = new TableResponse($objects);
            } catch (\PDOException $exception) {
                $result = new WrongParamsValuesResponse();
            }
        }

        return $result;
    }

    /**
     * @param array $params $_POST array
     * @return Response
     */
    public function SessionSubscribe($params)
    {
        if (!$this->checkParams($params, self::SESSION_SUBSCRIBE_TYPE)) {
            $result = new WrongParamsResponse();
        } else {
            $subscribeResult = $this->storage->subscribeUser($params);
            $result = new SubscribeResponse($subscribeResult['message']);
        }

        return $result;
    }

    /**
     * @param array $params $_POST array
     * @param string $type
     * @return bool
     */
    private function checkParams($params, $type)
    {
        switch ($type) {
            case self::TABLE_TYPE:
                $result = !empty($params) && array_key_exists('table', $params);
                break;
            case self::SESSION_SUBSCRIBE_TYPE:
                $result = !empty($params) && array_key_exists('sessionId', $params) && array_key_exists('userId', $params);
                break;
            default:
                $result = false;
        }

        return $result;
    }

}