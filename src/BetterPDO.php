<?php

namespace gr\maax\betterpdo;

use PDO;

class BetterPDO {

    private static $DEFAULT_CHARSET = 'utf8';

    private $host;
    private $database;
    private $user;
    private $password;
    private $charset;
    private $port;

    /** @var $pdo PDO */
    private $pdo = null;

    public function __construct($data) {
        if ($data instanceof PDO) {
            $this->pdo = $data;
        } else {
            $this->host = $data['host'];
            $this->database = $data['database'];
            $this->user = $data['username'];
            $this->password = $data['password'];
            $this->charset = $data['charset'] ?? BetterPDO::$DEFAULT_CHARSET;
            $this->port = $data['port'] ?? 3306;
        }
    }

    /**
     * Change the charset, if it's not the default charset (on default utf8)
     *
     * @param string $charset
     */
    public function setCharset($charset) {
        $this->charset = $charset;
    }

    public function connect() {
        $this->pdo = new PDO(sprintf('mysql:host=%s;dbname=%s', $this->host . ':' . $this->port,
            $this->database), $this->user, $this->password,
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . $this->charset));
    }

    public function isConnected() {
        return isset($this->pdo);
    }

    public function createStatement($sql) {
        return new BetterStatement($this->pdo, $sql);
    }

    /**
     * Create a new instance of BetterPDO
     *
     * @param $data
     * @return BetterPDO
     */
    public static function create($data) {
        return new BetterPDO($data);
    }

    /**
     * Set the default charset for all new connections
     *
     * @param string $charset for all new connections
     */
    public static function setDefaultCharset($charset) {
        BetterPDO::$DEFAULT_CHARSET = $charset;
    }

}
