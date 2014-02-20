<?php

namespace Development;

class ServiceProviderTest extends \PHPUnit_Framework_TestCase
{

    public function testGetInstance()
    {
        $service_provider = ServiceProvider::getInstance();
        $this->assertInstanceOf('\Development\ServiceProvider', $service_provider);
    }

    public function testSetService()
    {
        $service_provider = ServiceProvider::getInstance();
        $service_provider->setService( '\Development\Mail' );

        $mail = $service_provider->getService( '\Development\Mail' );
        $this->assertInstanceOf('\Development\Mail', $mail);

    }

    public function testGetService()
    {
        $service_provider = ServiceProvider::getInstance();
        $service_provider->setService( '\Development\FacebookAdapter' );

        $fb = $service_provider->getService( '\Development\FacebookAdapter' );
        $this->assertInstanceOf('\Development\FacebookAdapter', $fb);
    }
}