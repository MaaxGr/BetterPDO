<?php

namespace gr\maax\betterpdo;

use PDO;
use PDOStatement;

class BetterStatement
{

    /** @var PDO $server */
    private $pdo;

    /** @var PDOStatement $statement */
    private $statement;

    /** @var bool $executed */
    private $executed = false;

    private $success = null;

    //error code in ansi standard
    private $errorCodeAnsi = null;

    //driver specific error code
    private $errorCode = null;

    //driver specific error code
    private $errorMessage = null;

    function __construct($pdo, $sql)
    {
        $this->pdo = $pdo;
        $this->statement = $this->pdo->prepare($sql);
    }

    public function addParam($param, $value)
    {
        $stmt = $this->statement;

        if (is_bool($value)) {
            $stmt->bindValue($param, $value, PDO::PARAM_BOOL);
        } elseif (ctype_digit($value)) {
            $stmt->bindValue($param, intval($value), PDO::PARAM_INT);
        } else {
            $stmt->bindValue($param, $value, PDO::PARAM_STR);
        }
        return $this;
    }

    public function execute()
    {
        $this->executed = true;
        $this->success = $this->statement->execute();

        $errorInfo = $this->statement->errorInfo();

        $this->errorCodeAnsi = $errorInfo[0];
        $this->errorCode = $errorInfo[1];
        $this->errorMessage = $errorInfo[2];

        return $this;
    }

    public function isExecuted(): bool
    {
        return $this->executed;
    }

    public function getSuccess(): bool
    {
        if (!isset($this->success)) {
            throw new BetterPdoException('Statement not executed yet!');
        }
        return $this->success;
    }

    public function getErrorCodeAnsi()
    {
        if (!isset($this->success)) {
            throw new BetterPdoException('Statement not executed yet!');
        }
        return $this->errorCodeAnsi;
    }

    public function getErrorCode()
    {
        if (!isset($this->success)) {
            throw new BetterPdoException('Statement not executed yet!');
        }
        return $this->errorCode;
    }

    public function getErrorMessage()
    {
        if (!isset($this->success)) {
            throw new BetterPdoException('Statement not executed yet!');
        }
        return $this->errorMessage;
    }

    public function getInsertedId()
    {
        return $this->pdo->lastInsertId();
    }

    public function fetch($fetchStyle, $extraParam = null)
    {
        if (!$this->isExecuted()) {
            $this->execute();
        }

        if ($fetchStyle == FetchStyle::COLUMN) {
            return $this->statement->fetchColumn();
        }

        if ($fetchStyle == FetchStyle::OBJECT) {
            if (!isset($extraParam)) {
                exit('You have to define the classname as second parameter of fetchAll()');
            }

            return DatabaseWrapUtil::assocToObject($extraParam, $this->fetch(FetchStyle::ASSOC));
        }

        if ($fetchStyle == FetchStyle::JSON) {
            return json_encode($this->statement->fetch(PDO::FETCH_ASSOC));
        }

        return $this->statement->fetch($fetchStyle);
    }

    public function fetchAll($fetchStyle, $extraParam = null)
    {
        if (!$this->isExecuted()) {
            $this->execute();
        }

        if ($fetchStyle == FetchStyle::OBJECT) {
            if (!isset($extraParam)) {
                exit('You have to define the classname as second parameter of fetchAll()');
            }

            return DatabaseWrapUtil::assocToObjectArray($extraParam, $this->fetchAll(FetchStyle::ASSOC));
        }

        if ($fetchStyle == FetchStyle::JSON) {
            return json_encode($this->statement->fetchAll(PDO::FETCH_ASSOC));
        }

        return $this->statement->fetchAll($fetchStyle);
    }

}
