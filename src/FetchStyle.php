<?php

namespace gr\maax\betterpdo;

use PDO;

class FetchStyle {
    const ASSOC = PDO::FETCH_ASSOC;
    const COLUMN = PDO::FETCH_COLUMN;
    const KEY_PAIR = PDO::FETCH_KEY_PAIR;
    const OBJECT = 50;
    const JSON = 51;
}