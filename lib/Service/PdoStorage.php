<?php

namespace Service;


use Model\Element;
use Model\Session;
use Model\User;

class PdoStorage
{
    /**
     * @var \PDO
     */
    private $pdo;

    const DUPLICATE_CODE = 23000;


    private $accessTables = [
        'Session' => 'Session',
        'Users' => 'Users',
    ];

    /**
     * UserGetter constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param array $params $_POST array
     * @return Element[]
     */
    public function getTableData($params)
    {
        $sqlString = 'SELECT * FROM ' . $this->accessTables[$params['table']];
        $inputParameters = null;
        if (array_key_exists('id', $params)) {
            $sqlString .= ' WHERE ID = ?';
            $inputParameters = [(int)$params['id']];
        }
        $statement = $this->pdo->prepare($sqlString);
        $statement->execute($inputParameters);

        $rawData = $statement->fetchAll();

        if ($params['table'] == 'Users') {
            $returnData = $this->getUserObjects($rawData);
        } else {
            $sqlSpeakersString = 'SELECT * FROM Users INNER JOIN SessionSpeakers ON ID = SessionSpeakers.UserId AND SessionId IN (' . implode(',', array_fill(0, count($rawData), '?')) . ')';

            $statement = $this->pdo->prepare($sqlSpeakersString);
            $statement->execute(array_column($rawData, 'ID'));

            $rawSpeakersData = $statement->fetchAll();

            $returnData = $this->getSessionObjects($rawData, $rawSpeakersData);
        }

        return $returnData;
    }

    /**
     * @param array $rawSessionsData
     * @param array $rawSpeakersData
     * @return Session[]
     */
    private function getSessionObjects($rawSessionsData, $rawSpeakersData)
    {
        $result = [];
        foreach ($rawSessionsData as $session) {
            $result[$session['ID']] = new Session($session);
        }

        foreach ($rawSpeakersData as $speaker) {
            $result[$speaker['SessionId']]->addSpeaker([
                'ID' => $speaker['ID'],
                'Email' => $speaker['Email'],
                'Name' => $speaker['Name'],
            ]);
        }

        return $result;
    }

    /**
     * @param array $data
     * @return User[]
     */
    private function getUserObjects($data)
    {
        $result = [];
        foreach ($data as $user) {
            $result[$user['ID']] = new User($user);
        }

        return $result;
    }


    /**
     * @param array $params $_POST array
     * @return array
     */
    public function subscribeUser($params)
    {
        $result = false;
        $message = '';
        try {
            $sqlStrSpeakersLimit = 'SELECT SpeakersLimit FROM Session WHERE ID = ?';
            $sqlStrSpeakersSubscribed = 'SELECT count(*) as cnt FROM SessionSpeakers WHERE SessionId = ?';
            $sqlStrSubscribe = 'INSERT INTO SessionSpeakers (UserId, SessionId) VALUES (?, ?)';
            $sqlStrUser = 'SELECT ID FROM Users WHERE id = ?';

            $statementLimit = $this->pdo->prepare($sqlStrSpeakersLimit);
            $statementSubscribed = $this->pdo->prepare($sqlStrSpeakersSubscribed);
            $statementSubscribe = $this->pdo->prepare($sqlStrSubscribe);
            $statementUser = $this->pdo->prepare($sqlStrUser);

            $this->pdo->beginTransaction();

            $statementLimit->execute([$params['sessionId']]);
            $statementSubscribed->execute([$params['sessionId']]);
            $limit = $statementLimit->fetch()['SpeakersLimit'];
            $userQuantity = $statementSubscribed->fetch()['cnt'];

            $insertResult = false;

            if (is_null($limit)) {
                $message = 'Wrong params value';
            } else {
                if ($userQuantity < $limit) {
                    $statementUser->execute([$params['userId']]);
                    if ($statementUser->fetch()) {
                        $insertResult = $statementSubscribe->execute([$params['userId'], $params['sessionId']]);
                        $message = 'You are subscribed';
                    } else {
                        $message = 'Wrong params value';
                    }
                } else {
                    $message = 'Too late. Out of place';
                }
            }

            $result = $this->pdo->commit() && $insertResult;
        } catch (\PDOException $exception) {
            $this->pdo->rollBack();
            $result = false;
            if ($exception->getCode() == PdoStorage::DUPLICATE_CODE) {
                $message = 'You are already subscribed';
            }
        }

        return [
            'result' => $result,
            'message' => $message,
        ];
    }
}