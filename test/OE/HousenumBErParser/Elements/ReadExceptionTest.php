<?php

namespace OE\HousenumBErParser\Elements;

class ReadExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $re = new ReadException('This is not a valid housenumber', 'A');
        $this->assertTrue($re->isException());
        $this->assertEquals(
            array($re),
            $re->split()
        );
        $this->assertEquals(
            "This is not a valid housenumber: 'A'",
            $re->getMessage()
        );
    }

    public function testSetData()
    {
        $re = new ReadException('This is not a valid housenumber', 'A');
        $re->setData(Element::HNR, 5);
        $this->assertEquals(
            -1,
            $re->getData(Element::HNR)
        );
    }

    public function testToString()
    {
        $re = new ReadException('This is not a valid housenumber', 'A');
        $this->assertEquals(
            'A',
            (string) $re
        );
    }


}
?>
