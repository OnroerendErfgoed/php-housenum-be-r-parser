<?php 

namespace OE\HousenumBErParser;

class ReaderTest extends \PHPunit_Framework_TestCase
{

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidErrorHandling()
    {
        Reader::readString('24, A, something', 458);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testThrowExceptions()
    {
        Reader::readString('24, A, something', Reader::ERR_EXCEPTIONS);
    }

    public function testIgnoreInvalid()
    {
        $this->assertEquals(
            array('24', 'A', 'something'),
            Reader::readString('24, A, something', Reader::ERR_IGNORE_INVALID_INPUT)
        );
    }

    public function testRemoveInvalid()
    {
        $this->assertEquals(
            array('24'),
            Reader::readString('24', Reader::ERR_REMOVE_INVALID_INPUT)
        );
    }
}
