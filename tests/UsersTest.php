<?php

include_once __DIR__ . '/../src/Users.php';

class UsersTest extends \PHPUnit_Framework_TestCase
{

    const USERNAME = 'test';
    const PASSWORD = 'php-unit-8';

    public function setUp()
    {

    }

    public static function tearDownAfterClass(){
        $dsn = 'mysql:dbname=user;hostlocalhost';
        $db_user = 'root';
        $db_password = '';

        try {
            $db = new PDO($dsn, $db_user, $db_password);
        } catch (PDOException $e) {
            echo 'BD connection failed: ' . $e->getMessage();
        }

        $sql = 'DELETE FROM user WHERE username = "'.self::USERNAME.'" AND password = "'.self::PASSWORD.'"';
        $stmt = $db->prepare($sql);
        $stmt->execute();
    }

    public function testInsertUser()
    {
        $user = new Users();
        $user->insertUser(self::USERNAME, self::PASSWORD);

        $data = $user->getUserData();
        $this->assertEquals($data, array('username'=>self::USERNAME, 'password'=>self::PASSWORD, 'num_actions'=>'') );

        return $user->getID();
    }

    /**
     * @expectedException PDOException
     */
    public function testWrongInsertUser()
    {
        $user = new Users();
        $user->insertUser("NULL", self::PASSWORD);
    }

    /**
     * @depends testInsertUser
     */
    public function testGetUserData( $id )
    {
        $user = new Users( $id );
        $data = $user->getUserData();

        $this->assertEquals($data, array('username'=>self::USERNAME, 'password'=>self::PASSWORD, 'num_actions'=>'') );

        return $data;
    }

    /**
     * @depends testInsertUser
     * @expectedException InvalidArgumentException
     */
    public function testWrongGetUserData( $id ){
        $user = new Users( $id-1 );
        $data = $user->getUserData();

        return $data;
    }

    /**
     * @depends testInsertUser
     */
    public function testGetActions( $id )
    {
        $user = new Users( $id );
        $actions = $user->getActions();

        $this->assertEquals(0, $actions);
    }

    /**
     * @depends testInsertUser
     * @expectedException PDOException
     */
    public function testWrongGetActions( $id )
    {
        $user = new Users( $id-1 );
        $actions = $user->getActions();
    }

    /**
     * @depends testInsertUser
     * @depends testGetActions
     */
    public function testInsertUserAction( $id )
    {
        $user = new Users( $id );
        $user->insertUserAction();
        $user->insertUserAction();

        $actions = $user->getActions();
        $this->assertEquals(2, $actions);
    }
    /**
     * @depends testInsertUser
     * @expectedException PDOException
     */
    public function testWrongInsertUserAction( $id )
    {
        $user = new Users( $id-1 );
        $user->insertUserAction();
    }

    /**
     * @depends testInsertUser
     * @depends testGetActions
     * @dataProvider karma
     */
    public function testGetUserKarma( $actions, $expected_karma, $id )
    {
        $user = new Users( $id );
        $karma = $user->getUserKarma($actions);

        $this->assertEquals($expected_karma, $karma);
    }

    public function karma()
    {
        return array(
            array(0,1),
            array(10,1),
            array(11,2),
            array(20,2),
            array(99,2),
            array(100,2),
            array(101,3),
            array(150,3),
            array(499,3),
            array(500,3),
            array(501,5.01),
            array(600,6),
            array(768,7.68),
        );
    }
}