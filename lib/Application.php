<?php

class Application
{
    /**
     * @var array $serverRequestUri
     */
    private $serverRequestUri;
    /**
     * $_POST array
     * @var array $postData
     */
    private $postData;

    /**
     * @var \Service\Container
     */
    private $container;

    /**
     * @var string
     */
    private $methodName;

    /**
     * @var \Service\Api
     */
    private $api;

    /**
     * @var null|\Model\Responses\Response
     */
    private $response = null;

    /**
     * Application constructor.
     * @param array $server
     * @param array $postData
     */
    public function __construct($server, $postData)
    {
        $this->serverRequestUri = $server['REQUEST_URI'];
        $this->postData = $postData;
        $this->container = new Service\Container(\Config::DB_DSN, \Config::DB_USER, \Config::DB_PASS);
        $this->methodName = $this->getMethodName($this->serverRequestUri);
        $this->api = $this->container->getApi();
    }

    /**
     * Handle and exec API method
     */
    public function process()
    {
        if (!$this->checkMethodExisting()) {
            $this->response = new \Model\Responses\WrongMethodResponse();
        } else {
            $methodName = $this->methodName;
            $this->response = $this->api->$methodName($this->postData);
        }
    }

    /**
     * Return json string
     * @return string
     */
    public function getJsonResponse()
    {
        return $this->response->getJson();
    }

    /**
     * @return bool
     */
    private function checkMethodExisting()
    {
        return method_exists($this->api, $this->methodName);
    }

    /**
     * @param string $requestUri
     * @return string
     */
    private function getMethodName($requestUri)
    {
        $methodName = '';
        if (preg_match('#\/api\/([\w]+)$#', $_SERVER['REQUEST_URI'], $matches)) {
            $methodName = $matches[1];
        }

        return $methodName;
    }
}