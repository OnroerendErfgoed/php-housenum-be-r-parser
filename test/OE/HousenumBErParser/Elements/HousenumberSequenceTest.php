<?php

namespace OE\HousenumBErParser\Elements;

class HousenumberSequenceTest extends \PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $hnr = new HousenumberSequence(68, 78);
        $this->assertFalse($hnr->isException());
        $this->assertEquals(68, $hnr->getStart());
        $this->assertEquals(78, $hnr->getEnd());
    }

    public function testSetAndGet()
    {
        $hnr = new HousenumberSequence(28, 38);
        $hnr->setStart(68);
        $hnr->setEnd(78);
        $this->assertEquals(
            68,
            $hnr->getStart()
        );
        $this->assertEquals(
            78,
            $hnr->getEnd()
        );
    }

    public function testToString()
    {
        $hnr = new HousenumberSequence(68, 78);
        $this->assertEquals(
            '68-78',
            (string) $hnr
        );
    }


}
?>
