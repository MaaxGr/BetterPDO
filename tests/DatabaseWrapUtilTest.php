<?php

namespace de\maaxgr\betterpdo;

use gr\maax\betterpdo\DatabaseWrapUtil;
use PHPUnit\Framework\TestCase;

class DatabaseWrapUtilTest extends TestCase
{

    public function test()
    {
        $input = array(
            'username' => 'maaxgr',
            'password' => '1234',
            'first_name' => 'Max',
            'last_name' => 'Gr'
        );

        /** @var TestUser $obj */
        $obj = DatabaseWrapUtil::assocToObject(TestUser::class, $input);

        $this->assertEquals('Max', $obj->firstName);
        $this->assertEquals('Gr', $obj->lastName);
        $this->assertEquals('1234', $obj->password);
        $this->assertEquals('maaxgr', $obj->user_name);
    }

}

class TestUser {
    public $user_name;
    public $password;
    public $firstName;
    public $lastName;
}
