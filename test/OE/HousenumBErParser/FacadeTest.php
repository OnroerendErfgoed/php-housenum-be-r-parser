<?php

namespace OE\HousenumBErParser;

class FacadeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OE\HousenumBErParser\Facade
     */
    private $facade;
    
    public function setUp( )
    {
     $this->facade = new Facade( );
    }

    public function tearDown( )
    {
        $this->facade = null;
    }

    public function testSplitEenNummer( )
    {
        $label = '25';
        $huisnummers = $this->facade->split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals ( 1, count( $huisnummers ) );
        $hnr = $huisnummers[0];
        $this->assertEquals ( '25', (string) $hnr );
    }

    public function testSplitNummerMetLetterBisnummer( )
    {
        $label = '25A';
        $huisnummers = $this->facade->split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals ( 1, count( $huisnummers ) );
        $hnr = $huisnummers[0];
        $this->assertEquals ( '25A', (string) $hnr );
    }

    public function testSplitNummerMetCijferBisnummer( )
    {
        $label = '25/1';
        $huisnummers = $this->facade->split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals ( 1, count( $huisnummers ));
        $hnr = $huisnummers[0];
        $this->assertEquals ( '25/1', (string) $hnr );
    }

    public function testSplitHuisnummerMetCijferBisnummerGescheidenDoorUnderscore( )
    {
        $label = '111_1';
        $huisnummers = $this->facade->split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals ( 1, count( $huisnummers ) );
        $hnr = $huisnummers[0];
        $this->assertEquals ( '111/1', (string) $hnr );
    }

    public function testSplitNummerMetBusnummer( )
    {
        $label = '25 bus 3';
        $huisnummers = $this->facade->split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals ( 1, count( $huisnummers ) , '25 bus 3 wordt gesplitst in een verkeerd aantal elementen: '.count( $huisnummers ) );
        $hnr = $huisnummers[0];
        $this->assertEquals ( '25 bus 3', (string) $hnr );
    }

    public function testHuisnummerReeks( )
    {
        $label = '25,27,29,31';
        $huisnummers = $this->facade->split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals ( 4, count( $huisnummers ) );
        $this->assertEquals ( '25', ( string ) $huisnummers[0] );
        $this->assertEquals ( '27', ( string ) $huisnummers[1] );
        $this->assertEquals ( '29', ( string ) $huisnummers[2] );
        $this->assertEquals ( '31', ( string ) $huisnummers[3] );
    }

    public function testHuisnummerBereikEvenVerschil( )
    {
        $label = '25-31';
        $huisnummers = $this->facade->split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals ( 4, count( $huisnummers ) );
        $this->assertEquals ( '25', (string) $huisnummers[0] );
        $this->assertEquals ( '27', (string) $huisnummers[1] );
        $this->assertEquals ( '29', (string) $huisnummers[2] );
        $this->assertEquals ( '31', (string) $huisnummers[3] );
    }

    public function testHuisnummerBereikOnevenVerschil( )
    {
        $label = '25-32';
        $huisnummers = $this->facade->split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals ( 8, count( $huisnummers ));
        $this->assertEquals ((string) $huisnummers[0], '25' );
        $this->assertEquals ((string) $huisnummers[1], '26' );
        $this->assertEquals ((string) $huisnummers[2], '27' );
        $this->assertEquals ((string) $huisnummers[3], '28' );
        $this->assertEquals ((string) $huisnummers[4], '29' );
        $this->assertEquals ((string) $huisnummers[5], '30' );
        $this->assertEquals ((string) $huisnummers[6], '31' );
        $this->assertEquals ((string) $huisnummers[7], '32' );
    }

    public function testHuisnummerBereikSpeciaal( )
    {
        $label = '25,26-31';
        $huisnummers = $this->facade->split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals ( 7, count( $huisnummers ) );
        $this->assertEquals ((string) $huisnummers[0], '25' );
        $this->assertEquals ((string) $huisnummers[1], '26' );
        $this->assertEquals ((string) $huisnummers[2], '27' );
        $this->assertEquals ((string) $huisnummers[3], '28' );
        $this->assertEquals ((string) $huisnummers[4], '29' );
        $this->assertEquals ((string) $huisnummers[5], '30' );
        $this->assertEquals ((string) $huisnummers[6], '31' );
    }

    public function testCombinatieHuisnummerBereiken( )
    {
        $label = '25-31,18-26';
        $huisnummers = $this->facade->split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals( 9, count( $huisnummers ) );
        $this->assertEquals ((string) $huisnummers[0], '25' );
        $this->assertEquals ((string) $huisnummers[1], '27' );
        $this->assertEquals ((string) $huisnummers[2], '29' );
        $this->assertEquals ((string) $huisnummers[3], '31' );
		$this->assertEquals ((string) $huisnummers[4], '18' );
        $this->assertEquals ((string) $huisnummers[5], '20' );
        $this->assertEquals ((string) $huisnummers[6], '22' );
        $this->assertEquals ((string) $huisnummers[7], '24' );
        $this->assertEquals ((string) $huisnummers[8], '26' );
    }

    public function testBusnummerBereik( )
    {
        $label = '25 bus 3-7';
        $huisnummers = $this->facade->split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals( 5, count( $huisnummers ) );
        $this->assertEquals ((string)  $huisnummers[0], '25 bus 3' );
        $this->assertEquals ((string) $huisnummers[1], '25 bus 4' );
        $this->assertEquals ((string) $huisnummers[2], '25 bus 5' );
        $this->assertEquals ((string) $huisnummers[3], '25 bus 6' );
        $this->assertEquals ((string) $huisnummers[4], '25 bus 7' );
    }

    public function testAlfaBusnummerBereik( )
    {
        $label = '25 bus C-F';
        $huisnummers = $this->facade->split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals( 4, count( $huisnummers ) );
        $this->assertEquals ( (string) $huisnummers[0], '25 bus C' );
        $this->assertEquals ( (string) $huisnummers[1], '25 bus D' );
        $this->assertEquals ( (string) $huisnummers[2], '25 bus E' );
        $this->assertEquals ( (string) $huisnummers[3], '25 bus F' );
    }

    public function testHuisnummerBereikMetLetterBisnummer( )
    {
        $label = '25C-F';
        $huisnummers = $this->facade->split( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals( 4, count( $huisnummers ) );
        $this->assertEquals ( (string)  $huisnummers[0], '25C' );
        $this->assertEquals ( (string) $huisnummers[1], '25D' );
        $this->assertEquals ( (string) $huisnummers[2], '25E' );
        $this->assertEquals ( (string) $huisnummers[3], '25F' );
    }

    public function testHuisnummerBereikMetCijferBisnummer( )
    {
        $label = '25/3-7';
        $huisnummers = $this->facade->split( $label );
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
        $huisnummers = $this->facade->split( $label );
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
        $huisnummers = $this->facade->split( $label );
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
        $huisnummers = $this->facade->split( $label );
        $this->assertInternalType('array', $huisnummers);
        $this->assertEquals(count($huisnummers), 3);
        $this->assertEquals($huisnummers[0], 'A');
        $this->assertEquals($huisnummers[1], '1/3');
        $this->assertEquals($huisnummers[2], '?');
    }

    public function testInputWithSpaces( )
    {
        $label = ' A , 1/3 , 5 - 7 ';
        $huisnummers = $this->facade->split( $label );
        $this->assertInternalType('array', $huisnummers);
        $this->assertEquals(count($huisnummers), 4);
        $this->assertEquals($huisnummers[0], 'A');
        $this->assertEquals($huisnummers[1], '1/3');
        $this->assertEquals($huisnummers[2], '5');
        $this->assertEquals($huisnummers[3], '7');
    }

    public function testMergeUnits()
    {
        $label = '32-36, 25-31, 1A-F, 2/1-10, 4 bus 1-30 , 43, 44 bus 1, 45/1, 46A';
        $huisnummers = $this->facade->merge( $label );
        $this->assertInternalType('array', $huisnummers);
        $this->assertEquals(count($huisnummers), 9);
        $this->assertEquals($huisnummers[0], '1A-F');
        $this->assertEquals($huisnummers[1], '2/1-10');
        $this->assertEquals($huisnummers[2], '4 bus 1-30');
        $this->assertEquals($huisnummers[3], '25-31');
        $this->assertEquals($huisnummers[4], '32-36');
        $this->assertEquals($huisnummers[5], '43');
        $this->assertEquals($huisnummers[6], '44 bus 1');
        $this->assertEquals($huisnummers[7], '45/1');
        $this->assertEquals($huisnummers[8], '46A');
    }

    public function testMergeHuisnummerReeksen( )
    {
        $label = '32, 34, 36, 38, 25,27,29,31, 39, 40, 41, 42, 43, 44, 46, 47, 48, 49, 50';
        $huisnummers = $this->facade->merge( $label );
        $this->assertInternalType('array', $huisnummers);
        $this->assertEquals(count($huisnummers), 4);
        $this->assertEquals($huisnummers[0], '25-31');
        $this->assertEquals($huisnummers[1], '32-50');
        $this->assertEquals($huisnummers[2], '39-43');
        $this->assertEquals($huisnummers[3], '47-49');
    }

    public function testMergeBusdingesReeksen()
    {
        $label = '25, 27, 38 bus A, 38 bus B, 17/1, 17/3';
        $huisnummers = $this->facade->merge( $label );
        $this->assertEquals(
            array( '17/1', '17/3', '25-27', '38 bus A-B' ),
            $huisnummers
        );
    }

    public function testMergeCombinatieHuisnummerBereiken( )
    {
        $label = '25-31,18-26';
        $huisnummers = $this->facade->merge( $label );
        $this->assertInternalType( 'array', $huisnummers );
        $this->assertEquals ( 2, count( $huisnummers ) );
        $this->assertEquals ((string)  $huisnummers[0], '18-26' );
        $this->assertEquals ((string)  $huisnummers[1], '25-31' );
    }


    /*
    public function testPerformantie()
    {
			$start = microtime(true);
			$input = "25C-F,28-32,29 bus 2-5, 35C-F,38-42,39 bus 2-5, 45C-F,48-52,59 bus 2-5";
			for($i = 0; $i < 1000 ; $i++) {
				$this->facade->split($input);
			}
			$stop = microtime(true);
			echo "** NummerFacade\n";
			echo "Time: ".($stop-$start)." ($start - $stop);\n";
    }
    
    public function testSpeedyPerformantie()
    {
			$input = "25C-F,28-32,29 bus 2-5, 35C-F,38-42,39 bus 2-5, 45C-F,48-52,59 bus 2-5";
			$start = microtime(true);
			for($i = 0; $i < 1000 ; $i++) {
				$this->facade->speedySplit($input);
			}
			$stop = microtime(true);
			echo "** NummerFacade fast\n";
			echo "Time: ".($stop-$start)." ($start - $stop);\n";
    }

     */

    public function testToArrayOneElement( )
    {
        $string = '25';
        $array = $this->facade->stringToNumbers( $string );
        $this->assertInternalType( 'array', $array );
        $this->assertEquals ( 1, count( $array ) );
        $this->assertEquals ( '25', (string) $array[0] );

    }

    public function testToArraySeveralElements( )
    {
        $string = '25,35,45';
        $array = $this->facade->stringToNumbers( $string );
        $this->assertInternalType( 'array', $array );
        $this->assertEquals ( 3, count( $array ) );
        $this->assertEquals ( (string) $array[0], '25' );
        $this->assertEquals ( (string) $array[1], '35' );
        $this->assertEquals ( (string) $array[2], '45' );
    }

    public function testToArrayUglyInput( )
    {
        $string = ' 25 , 35,45 ';
        $array = $this->facade->stringToNumbers( $string );
        $this->assertInternalType( 'array', $array );
        $this->assertEquals ( 3, count( $array ) );
        $this->assertEquals ( (string) $array[0], '25' );
        $this->assertEquals ( (string) $array[1], '35' );
        $this->assertEquals ( (string) $array[2], '45' );
    }

    public function testToStringOneElement( )
    {
        $array = array( '25' );
        $string = $this->facade->numbersToString( $array );
        $this->assertInternalType( 'string', $string );
        $this->assertEquals ( '25', $string );
    }

    public function testToStringSeveralElements( )
    {
        $array = array( '25', '35', '45' );
        $string = $this->facade->numbersToString( $array );
        $this->assertInternalType( 'string', $string );
        $this->assertEquals ('25, 35, 45', $string );
    }

    public function testToStringUglyInput( )
    {
        $array = array( ' 25 ', '   35', '45   ' );
        $string = $this->facade->numbersToString( $array );
        $this->assertInternalType( 'string', $string );
        $this->assertEquals( '25, 35, 45', $string );
    }

    public function testMergeEenHuisnummer( )
    {
        $label = $this->facade->merge( '25' );
        $this->assertEquals($this->facade->numbersToString($label), '25');
    }

    public function testMergeHuisnummerMetBisnummerLetter( )
    {
        $label = $this->facade->merge( '25A' );
        $this->assertEquals($this->facade->numbersToString($label), '25A' );
    }

    public function testMergeNummerMetCijferBisnummer( )
    {
        $labels = $this->facade->merge('25/1');
        $this->assertEquals($this->facade->numbersToString($labels), '25/1' );
    }

    public function testMergeHuisnummerMetCijferBisnummerGescheidenDoorUnderscore( )
    {
        $labels = $this->facade->merge('111_1');
        $this->assertEquals($this->facade->numbersToString($labels), '111/1' );
    }

    public function testMergeNummerMetBusnummer( )
    {
        $labels = $this->facade->merge( '25 bus 3' );
        $this->assertEquals($this->facade->numbersToString($labels), '25 bus 3' );
    }

    public function testMergeNummerMetBusletter( )
    {
        $labels = $this->facade->merge( '25 bus C' );
        $this->assertEquals($this->facade->numbersToString($labels), '25 bus C' );
    }

    public function testMergeHuisnummerReeks( )
    {
        $labels = $this->facade->merge('25-31');
        $this->assertEquals($this->facade->numbersToString($labels), '25-31');
    }

    public function testMergeHuisnummerBereikEvenVerschil( )
    {
        $labels = $this->facade->merge('25, 27, 29, 31');
        $this->assertEquals($this->facade->numbersToString($labels), '25-31');
    }

    public function testMergeHuisnummerBereikOnevenVerschil()
    {
        $labels = $this->facade->merge('24, 25, 26, 27, 28');
        $this->assertEquals('24-28, 25-27', $this->facade->numbersToString($labels));
    }

    public function testMergeHuisnummerBereikEvenVerschilStraight( )
    {
        $labels = $this->facade->merge('25, 27, 29, 31', false);
        $this->assertEquals($this->facade->numbersToString($labels), '25-31');
    }

    public function testMergeHuisnummerBereikOnevenVerschilStraight()
    {
        $labels = $this->facade->merge('24, 25, 26, 27', false);
        $this->assertEquals('24-27', $this->facade->numbersToString($labels));
    }

    public function testMergeHuisnummerBereikLongJump( )
    {
        $labels = $this->facade->merge('25, 47, 49, 51');
        $this->assertEquals($this->facade->numbersToString($labels), '25, 47-51');
    }

    public function testMergeSeparate()
    {
        $this->assertEquals(
            $this->facade->merge('25, 27, 29, 31'),
            $this->facade->separateMerge('25, 27, 29, 31')
        );
    }

    public function testStraightMerge()
    {
        $this->assertEquals(
            $this->facade->merge('25, 27, 29, 31', false),
            $this->facade->straightMerge('25, 27, 29, 31')
        );
    }

    public function testSplitEqualsSpeedySplit()
    {
        $this->assertEquals(
            $this->facade->split('25-31'),
            $this->facade->speedySplit('25-31')
        );
    }

    public function testMergeCrap()
    {
        $this->assertEquals(
            'A, , 25, 31',
            $this->facade->numbersToString($this->facade->merge( 'A, 25, , 31' ))
        );
    }
}
?>
