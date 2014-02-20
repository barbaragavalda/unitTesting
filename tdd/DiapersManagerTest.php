<?php

namespace Development;

class DiapersManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider aviableDiapers
     */
    public function testSetAviableDiapers( $aviable_diapers )
    {
        $diapers_manager = new DiapersManager();
        $diapers_manager->setAviableDiapers( $aviable_diapers );

        $this->assertEquals( $aviable_diapers, $diapers_manager->getAviableDiapers() );
    }

    public function aviableDiapers()
    {
        return array(
            array(1),
            array(5),
            array(20),
            array(200),
            array(456),
        );
    }

    /**
     * @dataProvider babies
     */
    public function testSetBabys( $babies )
    {
        $diapers_manager = new DiapersManager();
        $this->assertEquals( 1, $diapers_manager->getBabies() );
        $diapers_manager->setBabies( $babies );

        $this->assertEquals( $babies, $diapers_manager->getBabies() );
    }

    public function babies()
    {
        return array(
            array(1),
            array(20),
            array(15),
            array(100),
            array(2),
        );
    }
}