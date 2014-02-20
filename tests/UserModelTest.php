<?php

namespace Development;

class UserModelTest extends \PHPUnit_Framework_TestCase
{
    private $userTest = array('user_name' => 'testUserModel', 'email' => 'testUserModel@test.com', 'password' => 'testUserModel1234', 'activation_key' => '1234');

    public function tearDown(){
        $db = new \PDO( 'mysql:host=127.0.0.1; dbname=mpwar', 'root', '' );
        $sql = '
            DELETE FROM users
            WHERE user_name = "'.$this->userTest['user_name'].'"
                AND email = "'.$this->userTest['email'].'"
                AND password = "'.$this->userTest['password'].'"
                AND activation_key = "'.$this->userTest['activation_key'].'"';

        $stmt = $db->prepare($sql);
        $stmt->execute();
    }

    public function testAddNewUser()
    {
        $user_model = new UserModel();
        $res = $user_model->addNewUser( $this->userTest );
        $this->assertInstanceOf('\PDOStatement', $res);
    }

    public function testExistsUserName()
    {
        $user_model = new UserModel();
        $user_model->addNewUser( $this->userTest );
        $exists = $user_model->existsUserName( 'testUserModel' );
        $this->assertTrue($exists);

        $exists = $user_model->existsUserName( 'admin' );
        $this->assertFalse($exists);
    }

}