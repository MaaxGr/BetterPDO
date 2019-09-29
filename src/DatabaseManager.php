<?php

namespace gr\maax\betterpdo;

class DatabaseManager {

    private static $databases = array();

    public static function registerConnection(string $identifier, BetterPDO $betterPDO) {
        if (self::isRegistered($identifier)) {
            throw new BetterPdoException('There is already a database registered with the name %s!', $identifier);
        }

        if (!$betterPDO->isConnected()) {
            $betterPDO->connect();
        }

        self::$databases[$identifier] = $betterPDO;
    }


    public static function getConnection(string $identifier): BetterPDO {
        if (!self::isRegistered($identifier)) {
            throw new BetterPdoException('No database registered with the name %s!', $identifier);
        }

        return self::$databases[$identifier];
    }

    private static function isRegistered(string $identifier): bool {
        return isset(self::$databases[$identifier]);
    }

}