<?php

namespace Development;

class UserTest extends \PHPUnit_Framework_TestCase
{

    public function testNeWebsiteUser()
    {
        $user_data = array(
            'user_name' => 'testUserModel',
            'email' => 'testUserModel@test.com',
            'password' => 'testUserModel1234',
            'activation_key' => '1234',
            'origin' => 'website'
        );

        $service_provider = ServiceProvider::getInstance();
        $user_model = $this->getMock('Development\UserModel');
        $user_model->expects( $this->once() )->method( 'existsUserName' )->will( $this->returnValue(false) );
        $service_provider->setService('UserModel', $user_model);

        $mail = $this->getMock('Development\Mail');
        $service_provider->setService('Mail', $mail);

        $user = new User( $service_provider );
        $user->newUser( $user_data );
        $error = $user->getErrors();
        $this->assertEmpty($error);

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

        $service_provider = ServiceProvider::getInstance();
        $user_model = $this->getMock('Development\UserModel');
        $service_provider->setService('UserModel', $user_model);
        $mail = $this->getMock('Development\Mail');
        $service_provider->setService('Mail', $mail);

        $user = new User( $service_provider );
        $user->newUser( $user_data );
    }

    public function testNewFacebookUser()
    {
        $user_data = array(
            'user_name' => 'testUserModel',
            'email' => 'testUserModel@test.com',
            'password' => 'testUserModel1234',
            'activation_key' => '1234',
            'origin' => 'facebook'
        );

        $service_provider = ServiceProvider::getInstance();
        $user_model = $this->getMock('Development\UserModel');
        $user_model->expects( $this->once() )->method( 'existsUserName' )->will( $this->returnValue(false) );
        $service_provider->setService('UserModel', $user_model);

        $mail = $this->getMock('Development\Mail');
        $service_provider->setService('Mail', $mail);

        $fb = $this->getMock('Development\FacebookAdapter');
        $service_provider->setService('FacebookAdapter', $fb);

        $user = new User( $service_provider );
        $user->newUser( $user_data );
        $error = $user->getErrors();
        $this->assertEmpty($error);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedMessage     User data is invalid
     */
    public function testWrongOrigin()
    {
        $user_data = array(
            'user_name' => 'testUserModel',
            'email' => 'testUserModel@test.com',
            'password' => 'testUserModel1234',
            'activation_key' => '1234',
            'origin' => 'sdsad'
        );

        $service_provider = ServiceProvider::getInstance();
        $user_model = $this->getMock('Development\UserModel');
        $user_model->expects( $this->once() )->method( 'existsUserName' )->will( $this->returnValue(true) );
        $service_provider->setService('UserModel', $user_model);

        $mail = $this->getMock('Development\Mail');
        $service_provider->setService('Mail', $mail);

        $user = new User( $service_provider );
        $user->newUser( $user_data );
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedMessage     User data is invalid
     */
    public function testDuplicateUser()
    {
        $user_data = array(
            'user_name' => 'testUserModel',
            'email' => 'testUserModel@test.com',
            'password' => 'testUserModel1234',
            'activation_key' => '1234',
            'origin' => 'website'
        );

        $service_provider = ServiceProvider::getInstance();
        $user_model = $this->getMock('Development\UserModel');
        $user_model->expects( $this->once() )->method( 'existsUserName' )->will( $this->returnValue(true) );
        $service_provider->setService('UserModel', $user_model);

        $mail = $this->getMock('Development\Mail');
        $service_provider->setService('Mail', $mail);

        $user = new User( $service_provider );
        try{
            $user->newUser( $user_data );
        }catch (InvalidArgumentException $e){
            $error = $user->getErrors();
            var_dump($error);
        }
    }
}