<?php

namespace Development;

class UserTest extends \PHPUnit_Framework_TestCase
{

    private $service_provider;
    static $user_data = array(
        'user_name' => 'testUserModel',
        'email' => 'testUserModel@test.com',
        'password' => 'testUserModel1234',
        'activation_key' => '1234',
        'origin' => 'website'
    );

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

    public function setUp()
    {
        $this->service_provider = ServiceProvider::getInstance();

        $user_model = $this->getMock('Development\UserModel');
        $user_model->expects( $this->any() )->method( 'existsUserName' )->will( $this->returnValue(false) );
        $this->service_provider->setService('UserModel', $user_model);

        $mail = $this->getMock('Development\Mail');
        $this->service_provider->setService('Mail', $mail);

        $fb = $this->getMock('Development\FacebookAdapter');
        $this->service_provider->setService('FacebookAdapter', $fb);
    }

    public function testNewWebsiteUser()
    {
        $user = new User( $this->service_provider );
        $user->newUser( self::$user_data );

        $this->assertEmpty( $user->getErrors() );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testsInvalidUser()
    {
        $user_data = array(
            'user_name' => '',
            'email' => '',
            'password' => '',
            'activation_key' => '',
            'origin' => ''
        );

        $user = new User( $this->service_provider );
        $user->newUser( $user_data );
    }

    public function testNewFacebookUser()
    {
        $user_data = self::$user_data;
        $user_data['origin'] = 'facebook';

        $user = new User( $this->service_provider );
        $user->newUser( $user_data );

        $this->assertEmpty( $user->getErrors() );
    }

    /**
     * @expectedException UnexpectedValueException
     * @expectedMessage     Invalid user origin
     */
    public function testWrongOrigin()
    {
        $user_data = self::$user_data;
        $user_data['origin'] = 'google+';

        $user = new User( $this->service_provider );
        $user->newUser( $user_data );
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedMessage     User data is invalid
     */
    public function testDuplicateUser()
    {
        $user_model = $this->getMock('Development\UserModel');
        $user_model->expects( $this->any() )->method( 'existsUserName' )->will( $this->returnValue(true) );
        $this->service_provider->setService('UserModel', $user_model);

        $user = new User( $this->service_provider );
        $user->newUser( self::$user_data );
    }
}