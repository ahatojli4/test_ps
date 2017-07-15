<?php

namespace Service;


class Container
{
    /**
     * @var null|\PDO $pdo
     */
    private $pdo = null;
    /**
     * @var array $configuration
     */
    private $configuration;

    /**
     * @var null|Api
     */
    private $api = null;

    /**
     * @var PdoStorage $pdoStorage
     */
    private $pdoStorage = null;


    /**
     * Container constructor.
     * @param string $dsn
     * @param string $username
     * @param string $password
     */
    public function __construct($dsn, $username, $password)
    {
        $this->configuration = [
            'dsn' => $dsn,
            'username' => $username,
            'password' => $password,
        ];
    }

    /**
     * @return \PDO
     */
    public function getPdo()
    {
        if (is_null($this->pdo)) {
            $this->pdo = new \PDO(
                $this->configuration['dsn'],
                $this->configuration['username'],
                $this->configuration['password']
            );
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        }

        return $this->pdo;
    }

    /**
     * @return Api
     */
    public function getApi()
    {
        if (is_null($this->api)) {
            $this->api = new Api($this->getPdoStorage());
        }

        return $this->api;
    }

    /**
     * @return PdoStorage
     */
    public function getPdoStorage()
    {
        if (is_null($this->pdoStorage)) {
            $this->pdoStorage = new PdoStorage($this->getPdo());
        }

        return $this->pdoStorage;
    }


}