<?php
/**
 * @copyright 2014 {@link https://www.onroerenderfgoed.be Onroerend Erfgoed}
 * @author Dieter Standaert <dieter.standaert@eds.com> 
 * @author Koen Van Daele <koen.vandaele@rwo.vlaanderen.be>
 * @license MIT
 */

namespace OE\HousenumBErParser;

use OE\HousenumBErParser\Elements\Housenumber;
use OE\HousenumBErParser\Elements\Bisletter;
use OE\HousenumBErParser\Elements\Busletter;
use OE\HousenumBErParser\Elements\Bisnumber;
use OE\HousenumBErParser\Elements\Busnumber;

use OE\HousenumBErParser\Elements\HuisnummerReeks;
use OE\HousenumBErParser\Elements\BisletterReeks;
use OE\HousenumBErParser\Elements\BusletterReeks;
use OE\HousenumBErParser\Elements\BisnummerReeks;
use OE\HousenumBErParser\Elements\BusnummerReeks;

use OE\HousenumBErParser\Elements\ReadException;

/**
 * Reader 
 * 
 * Klasse die een reeks huisnummers inleest. Bijvoorbeeld:
 *		"23 bus 5, 23 bus 6" -> array (Busnummer "23 bus 5", Busnummer "23 B-6")
 *		"23", "24 bus 2" -> array (Huisnummer "23", Busnummer "24 bus 2")
 *		"25-27" -> array(Huisnummerreeks "25, 26-27")
 */
class Reader{

	/**
	 * [ ]* staat voor een willekeurig aantal spaties. Dit wordt tussen elk element gezet om overtollige of afwezige spaties op te vangen.
	 * (\d+)  staat voor een nummer.
	 * [/|_] staat voor één '/' of '_'
	 * (\w+) staat voor een woord: aantal letters of cijfers.
	 */


	/**
	 * huisnummer syntax:
	 * "<nummer>"
	 * @var string 
	 */
	const huis = "#^[ ]*(\d+)[ ]*$#";
	/**
	 * bisnummer syntax:
	 * " <nummer>  <'/' of '_'>  <nummer> "
	 * @var string  
	 */
	const bisn = "#^[ ]*(\d+)[ ]*[/|_][ ]*(\d+)[ ]*$#";
	/**
	 * busletter syntax:
	 * " <nummer>  <woord> "
	 * @var string  
	 */
	const bisl = "#^[ ]*(\d+)[ ]*(\w+)[ ]*$#";
	/**
	 * busnummer syntax:
	 * " <nummer>  'bus' <nummer> " 
	 * @var string  
	 */
	const busn = "#^[ ]*(\d+)[ ]*bus[ ]*(\d+)[ ]*$#i";
	/**
	 * busletter syntax:
	 * " <nummer>  'bus' <woord> "
	 * @var string  
	 */
	const busl = "#^[ ]*(\d+)[ ]*bus[ ]*(\w+)[ ]*$#i";
	/**
	 * huisnummerreeks syntax:
	 * " <nummer>  '-'  <nummer> "
	 * @var string  
	 */	
	const huis_r = "#^[ ]*(\d+)[ ]*-[ ]*(\d+)[ ]*$#";
	/**
	 * bisnummerreeks syntax:
	 * " <nummer>  <'/' of '_'>  <nummer>  '-' <nummer> "
	 * @var string  
	 */	
	const bisn_r = "#^[ ]*(\d+)[ ]*[/|_][ ]*(\d+)[ ]*-[ ]*(\d+)[ ]*$#";
	/**
	 * bisletterreeks syntax:
	 * " <nummer>  <'/' of '_'>  <nummer>  '-' <nummer> "
	 * @var string  
	 */	
	const bisl_r = "#^[ ]*(\d+)[ ]*(\w+)[ ]*-[ ]*(\w+)[ ]*#i";
	/**
	 * busnummerreeks syntax:
	 * " <nummer>  'bus'  <nummer>  '-' <nummer> "
	 * @var string  
	 */
	const busn_r = "#^[ ]*(\d+)[ ]*bus[ ]*(\d+)[ ]*-[ ]*(\d+)[ ]*#";
	/**
	 * busletterreeks syntax:
	 * " <nummer>  'bus'  <woord>  '-' <woord> "
	 * @var string  
	 */
	const busl_r = "#^[ ]*(\d+)[ ]*bus[ ]*(\w+)[ ]*-[ ]*(\w+)[ ]*#i";

	/**
	 * @var integer
	 */
	const ERR_EXCEPTIONS = 0;
	/**
	 * @var integer
	 */
	const ERR_IGNORE_INVALID_INPUT = 1;
	/**
	 * @var integer
	 */
	const ERR_REMOVE_INVALID_INPUT = 2;
	
	

	/**
	 * readNummer
	 *  leest een huisnummer. Dit kan een eenvoudig huisnummer, bisnummer, reeks enz zijn.
	 * @param string input
	 * @return KVDUtil_HnrElement met het huisnummer of de fout indien de input incorrect is.
	 */	
	static function readNummer($input)
	{
        if(preg_match(self::huis, $input, $matches)) {
            return new Housenumber($matches[1]);
        } elseif(preg_match(self::busn, $input, $matches)) {
            return new Busnumber($matches[1], $matches[2]);
        } elseif(preg_match(self::busl, $input, $matches)) {
            return new Busletter($matches[1], $matches[2]);
        } elseif(preg_match(self::bisn, $input, $matches)) {
            return new Bisnumber($matches[1], $matches[2]);
        } elseif(preg_match(self::bisl, $input, $matches)) {
            return new Bisletter($matches[1], $matches[2]);
        } elseif(preg_match(self::huis_r, $input, $matches)){
            return new HuisnummerReeks($matches[1], $matches[2], (((int)$matches[1] - (int)$matches[2])%2 ==0));
        } elseif(preg_match(self::busn_r, $input, $matches)){
            return new BusnummerReeks($matches[1], $matches[2], $matches[3]);
        } elseif(preg_match(self::busl_r, $input, $matches)){
            return new BusletterReeks($matches[1], $matches[2], $matches[3]);
        } elseif(preg_match(self::bisn_r, $input, $matches)){
            return new BisnummerReeks($matches[1], $matches[2], $matches[3]);
        } elseif(preg_match(self::bisl_r, $input, $matches)){
            return new BisletterReeks($matches[1], $matches[2], $matches[3]);
        } else return new ReadException("Could not parse/understand", $input);
	}
		
	/**
	 * readString
	 *  leest een string met een lijst van huisnummers en geeft een array met huisnummerobjecten terug.
	 * @param string input
	 * @param integer flag voor error handling.
	 * @return array
	 */
	static function readString($input, $flag = 1)
	{
		return self::readArray(explode(",", $input), $flag);
	}
	
	
	static function handleException($exception, &$results = array(), $flag = 1)
	{
		switch($flag) {
			case (self::ERR_EXCEPTIONS): throw new Exception($exception->getMessage()); break;
			case (self::ERR_IGNORE_INVALID_INPUT): $results[] = $exception; return $results; break;
			case (self::ERR_REMOVE_INVALID_INPUT): return $results; break;
			default: throw new Exception("Invalid flag for KVDutil_HnrReader. Given ".$flag);
		}
	}
	/**
	 * readString
	 *  leest een array met huisnummerstring in en geeft een array met huisnummerobjecten terug.
	 * @param string input
	 * @param integer flag voor error handling.
	 * @return array
	 */	
	static function readArray($inputs, $flag = 1)
	{
		$result = array();
		foreach($inputs as $input) {
			$element = self::readNummer($input); 
			if($element->isException()) {self::handleException($element, $result, $flag); }
			else 
				$result[] = $element;	
		}
		return $result;
	}
}
?>
