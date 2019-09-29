<?php

namespace gr\maax\betterpdo;

use RuntimeException;

class BetterPdoException extends RuntimeException {


    /**
     * BetterPdoException constructor.
     */
    public function __construct(string $message, ...$args) {
        parent::__construct(sprintf($message, $args));
    }

}