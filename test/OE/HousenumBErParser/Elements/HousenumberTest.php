<?php

namespace OE\HousenumBErParser\Elements;

class HousenumberTest extends \PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $hnr = new Housenumber(68);
        $this->assertFalse($hnr->isException());
        $this->assertEquals(
            array($hnr),
            $hnr->split()
        );
        $this->assertEquals(
            68,
            $hnr->getHousenumber()
        );
    }

    public function testSetAndGet()
    {
        $hnr = new Housenumber(28);
        $hnr->setHousenumber(68);
        $this->assertEquals(
            68,
            $hnr->getHousenumber()
        );
    }

    public function testToString()
    {
        $hnr = new Housenumber(68);
        $this->assertEquals(
            68,
            (string) $hnr
        );
    }


}
?>
