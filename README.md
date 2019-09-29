# BetterPDO (alpha)
Optimized sql libary for php! (currently only for MySQL)

With that libary, you can **save a lot of time, lines of code and spare nerves**!
It provides **easy and compact methods** for **faster working**.


## :heavy_plus_sign: Features

* Higher code clarity
* Easier to store connection data in an other file
* Use DatabaseManager::class to store database connections, instead of global variables or extra methods for accessing the database
* bindParam() automatically treats numeric values as integers (no intval() and PDO::PARAM_INT required) 

## :page_with_curl: Requirements

* PHP 7.0 or newer
* PHP extensions pdo and pdo-mysql

## :arrow_up_down: Vanilla-PDO vs Better-PDO

### Create a connection:
**Vanilla-PDO:**
```php
$mainDatabase = new PDO("mysql:host=localhost;dbname=foo;charset=utf8", 'root', 'bar'); 
```

**Better-PDO:**
```php
DatabaseManager::registerConnection('main', new BetterPDO(array(
    'host' => 'localhost',
    'database' => 'foo',
    'username' => 'root',
    'password' => 'bar',
    'charset' => 'utf8'
)));
```

### Fetch from a table:
**Vanilla-PDO:**
```php
function getUser($userId) {
    global $mainDatabase;
    $sql = 'SELECT * FROM `user` WHERE `user_id` < :userId';
    $stmt = $mainDatabase->prepare($sql);
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
```

**Better-PDO:**
```php
function getUser($userId) {
    $sql = 'SELECT * FROM `user` WHERE `user_id` < :userId';
    return DatabaseManager::getConnection('main')
        ->createStatement($sql)
        ->bindParam(':userId', $userId)
        ->fetch(FetchStyle::ASSOC);
}
```


## :floppy_disk: Install

### Use **composer** (recommend)

```
composer config maaxgr.betterpdo vcs https://github.com/MaaxGr/BetterPDO
composer require maaxgr.betterpdo:dev-master
composer update
```

### Use direct Download

1. Clone project or download all files of src/ directory
2. require_once('<PATH-TO-LIBARY>/autoload.php');
 