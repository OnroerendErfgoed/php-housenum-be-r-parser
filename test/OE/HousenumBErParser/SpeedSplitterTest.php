<?php

namespace OE\HousenumBErParser;

class SpeedSplitterTest extends \PHPUnit_Framework_TestCase
{

    public function testSplitEenNummer( )
    {
        $label = '25';
        $huisnummers = SpeedSplitter::split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals( 1, count( $huisnummers ) );
        $hnr = $huisnummers[0];
        $this->assertEquals( '25', $hnr);
    }

    public function testSplitNummerMetLetterBisnummer( )
    {
        $label = '25A';
        $huisnummers = SpeedSplitter::split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals( 1, count( $huisnummers ) );
        $hnr = $huisnummers[0];
        $this->assertEquals( '25A', $hnr );
    }

    public function testSplitNummerMetCijferBisnummer( )
    {
        $label = '25/1';
        $huisnummers = SpeedSplitter::split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals( 1, count( $huisnummers ));
        $hnr = $huisnummers[0];
        $this->assertEquals( '25/1', $hnr );
    }

    public function testSplitHuisnummerMetCijferBisnummerGescheidenDoorUnderscore( )
    {
        $label = '111_1';
        $huisnummers = SpeedSplitter::split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals( 1, count( $huisnummers ) );
        $hnr = $huisnummers[0];
        $this->assertEquals( '111/1', $hnr );
    }

    public function testSplitNummerMetBusnummer( )
    {
        $label = '25 bus 3';
        $huisnummers = SpeedSplitter::split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals( 1, count( $huisnummers ) , '25 bus 3 wordt gesplitst in een verkeerd aantal elementen: '.count( $huisnummers ) );
        $hnr = $huisnummers[0];
        $this->assertEquals( '25 bus 3', $hnr );
    }

    public function testHuisnummerReeks( )
    {
        $label = '25,27,29,31';
        $huisnummers = SpeedSplitter::split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals( 4, count( $huisnummers ) );
        $this->assertEquals( '25', $huisnummers[0] );
        $this->assertEquals( '27', $huisnummers[1] );
        $this->assertEquals( '29', $huisnummers[2] );
        $this->assertEquals( '31', $huisnummers[3] );
    }

    public function testHuisnummerBereikEvenVerschil( )
    {
        $label = '25-31';
        $huisnummers = SpeedSplitter::split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals( 4, count( $huisnummers ) );
        $this->assertEquals( '25', $huisnummers[0] );
        $this->assertEquals( '27', $huisnummers[1] );
        $this->assertEquals( '29', $huisnummers[2] );
        $this->assertEquals( '31', $huisnummers[3] );
    }

    public function testHuisnummerBereikOnevenVerschil( )
    {
        $label = '25-32';
        $huisnummers = SpeedSplitter::split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals( 8, count( $huisnummers ));
        $this->assertEquals($huisnummers[0], '25' );
        $this->assertEquals($huisnummers[1], '26' );
        $this->assertEquals($huisnummers[2], '27' );
        $this->assertEquals($huisnummers[3], '28' );
        $this->assertEquals($huisnummers[4], '29' );
        $this->assertEquals($huisnummers[5], '30' );
        $this->assertEquals($huisnummers[6], '31' );
        $this->assertEquals($huisnummers[7], '32' );
    }

    public function testHuisnummerBereikSpeciaal( )
    {
        $label = '25,26-31';
        $huisnummers = SpeedSplitter::split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals( 7, count( $huisnummers ) );
        $this->assertEquals($huisnummers[0], '25' );
        $this->assertEquals($huisnummers[1], '26' );
        $this->assertEquals($huisnummers[2], '27' );
        $this->assertEquals($huisnummers[3], '28' );
        $this->assertEquals($huisnummers[4], '29' );
        $this->assertEquals($huisnummers[5], '30' );
        $this->assertEquals($huisnummers[6], '31' );
    }

    public function testCombinatieHuisnummerBereiken( )
    {
        $label = '25-31,18-26';
        $huisnummers = SpeedSplitter::split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals( 9, count( $huisnummers ) );
        $this->assertEquals($huisnummers[0], '25' );
        $this->assertEquals($huisnummers[1], '27' );
        $this->assertEquals($huisnummers[2], '29' );
        $this->assertEquals($huisnummers[3], '31' );
		$this->assertEquals($huisnummers[4], '18' );
        $this->assertEquals($huisnummers[5], '20' );
        $this->assertEquals($huisnummers[6], '22' );
        $this->assertEquals($huisnummers[7], '24' );
        $this->assertEquals($huisnummers[8], '26' );
    }

    public function testBusnummerBereik( )
    {
        $label = '25 bus 3-7';
        $huisnummers = SpeedSplitter::split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals(
            array('25 bus 3', '25 bus 4', '25 bus 5', '25 bus 6', '25 bus 7'),
            $huisnummers
        );
    }

    public function testAlfaBusnummerBereik( )
    {
        $label = '25 bus C-F';
        $huisnummers = SpeedSplitter::split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals(
            array('25 bus C', '25 bus D', '25 bus E', '25 bus F'),
            $huisnummers
        );
    }

    public function testHuisnummerBereikMetLetterBisnummer( )
    {
        $label = '25C-F';
        $huisnummers = SpeedSplitter::split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals(4, count( $huisnummers ) );
        $this->assertEquals($huisnummers[0], '25C' );
        $this->assertEquals($huisnummers[1], '25D' );
        $this->assertEquals($huisnummers[2], '25E' );
        $this->assertEquals($huisnummers[3], '25F' );
    }

    public function testHuisnummerBereikMetCijferBisnummer( )
    {
        $label = '25/3-7';
        $huisnummers = SpeedSplitter::split( $label );
        $this->assertInternalType('array', $huisnummers);
        $this->assertEquals( count( $huisnummers ) , 5);
        $this->assertEquals($huisnummers[0], '25/3');
        $this->assertEquals($huisnummers[1], '25/4');
        $this->assertEquals($huisnummers[2], '25/5');
        $this->assertEquals($huisnummers[3], '25/6');
        $this->assertEquals($huisnummers[4], '25/7');
    }

    public function testCombinatieBereiken( )
    {
        $label = '25C-F,28-32,29 bus 2-5';
        $huisnummers = SpeedSplitter::split( $label );
        $this->assertInternalType('array', $huisnummers);
        $this->assertEquals(count($huisnummers) , 11);
        $this->assertEquals($huisnummers[0], '25C' );
        $this->assertEquals($huisnummers[1], '25D' );
        $this->assertEquals($huisnummers[2], '25E' );
        $this->assertEquals($huisnummers[3], '25F' );
        $this->assertEquals($huisnummers[4], '28' );
        $this->assertEquals($huisnummers[5], '30' );
        $this->assertEquals($huisnummers[6], '32' );
        $this->assertEquals($huisnummers[7], '29 bus 2' );
        $this->assertEquals($huisnummers[8], '29 bus 3' );
        $this->assertEquals($huisnummers[9], '29 bus 4' );
        $this->assertEquals($huisnummers[10], '29 bus 5' );
    }

    public function testBisnummerEnHuisnummerBereik( )
    {
        $label = '2A,7-11';
        $huisnummers = SpeedSplitter::split( $label );
        $this->assertInternalType('array', $huisnummers);
        $this->assertEquals(count($huisnummers) , 4);
        $this->assertEquals($huisnummers[0], '2A');
        $this->assertEquals($huisnummers[1], '7');
        $this->assertEquals($huisnummers[2], '9');
        $this->assertEquals($huisnummers[3], '11');
    }

    public function testBogusInput( )
    {
        $label = 'A,1/3,?';
        $huisnummers = SpeedSplitter::split( $label );
        $this->assertInternalType('array', $huisnummers);
        $this->assertEquals(count($huisnummers), 3);
        $this->assertEquals($huisnummers[0], 'A');
        $this->assertEquals($huisnummers[1], '1/3');
        $this->assertEquals($huisnummers[2], '?');
    }

    public function testInputWithSpaces( )
    {
        $label = ' A , 1/3 , 5 - 7 ';
        $huisnummers = SpeedSplitter::split( $label );
        $this->assertInternalType('array', $huisnummers);
        $this->assertEquals(count($huisnummers), 4);
        $this->assertEquals($huisnummers[0], 'A');
        $this->assertEquals($huisnummers[1], '1/3');
        $this->assertEquals($huisnummers[2], '5');
        $this->assertEquals($huisnummers[3], '7');
    }

}
?>
