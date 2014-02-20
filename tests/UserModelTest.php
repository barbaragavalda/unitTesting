<?php

namespace Development;

class UserModelTest extends \PHPUnit_Framework_TestCase
{
    private $userTest = array('user_name' => 'testUserModel', 'email' => 'testUserModel@test.com', 'password' => 'testUserModel1234', 'activation_key' => '1234');

    public static function setUpBeforeClass()
    {
        $db = new \PDO( 'mysql:host=127.0.0.1; dbname=mpwar', 'root', '' );
        $sql = '
            CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) COLLATE utf8_danish_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_danish_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_danish_ci DEFAULT NULL,
  `activation_key` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_user`)
)';
        $stmt = $db->prepare($sql);
        $stmt->execute();
    }

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