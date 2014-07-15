<?php
/**
 * @copyright 2014 {@link https://www.onroerenderfgoed.be Onroerend Erfgoed}
 * @author Dieter Standaert <dieter.standaert@eds.com> 
 * @author Koen Van Daele <koen.vandaele@rwo.vlaanderen.be>
 * @license MIT
 */

namespace OE\HousenumBErParser;

/**
 * Fast splitter.
 *
 * Klasse die in een string met huisnummers, de reeksen opsplitst.
 */
class SpeedSplitter{

	/**
	 * [ ]* staat voor een willekeurig aantal spaties. Dit wordt tussen elk element gezet om overtollige of afwezige spaties op te vangen.
	 * (\d+)  staat voor een nummer.
	 * [/|_] staat voor één '/' of '_'
	 * (\w+) staat voor een woord: aantal letters of cijfers.
	 */


	/**
	 * huisnummerreeks syntax:
	 * "<nummer> '-' <nummer>"
	 * @var string 
	 */	
	const reeks = "#,[ ]*([\d]+)[ ]*-[ ]*([\d]+)#";
	
	/**
	 * bisnummerreeks syntax:
	 * " <nummer>  <'/' of '_'>  <nummer>  '-' <nummer> "
	 * @var string  
	 */	
	const bisnr = "#,[ ]*(\d+)[ ]*[/|_][ ]*(\d+)[ ]*-[ ]*(\d+)#";
	 
	/**
	 * bisletterreeks syntax:
	 * " <nummer>  <'/' of '_'>  <letters>  '-' <letters> "
	 * @var string  
	 */	
	const bislr = "#,[ ]*(\d+)[ ]*([a-zA-Z]+)[ ]*-[ ]*(\w+)#";

	/**
	 * busreeks syntax:
	 * " <nummer>  'bus'  <nummer>  '-' <nummer of letter> "
	 * @var string  
	 */
	const busnr = "#,[ ]*(\d+)[ ]*[bus][ ]*(\w+)[ ]*-[ ]*(\w+)#";


	/**
	 * splitHuisnummers
	 * @param array met begin huisnummer en einde huisnummer
	 * @return string met de gesplitste huisnummerreeks
	 */
	static function splitHuisnummers($match)
	{
		$res = "";
		$jump = (((int)$match[1] - (int)$match[2])%2 ==0)? 2 : 1;
		for($i = (int)$match[1]; $i<= (int)$match[2]; $i+=$jump) {
			$res .= ", ".$i;
		}
		return $res;
	}
	/**
	 * splitBisnummer
	 * @param array met huisnummer, begin bisnummer en einde bisnummer
	 * @return string met de gesplitste reeks
	 */
	static function splitBisnummer($match)
	{
		$res = "";
		for($i = $match[2]; $i<= $match[3]; $i++) {
			$res .= ", ".$match[1]."/".$i;
		}
		return $res;

	}
	/**
	 * splitBisnummer
	 * @param array met huisnummer, begin busnummer en einde busnummer
	 * @return string met de gesplitste reeks
	 */
	static function splitBusnummer($match)
	{
		$res = "";
		for($i = $match[2]; $i<= $match[3]; $i++) {
			$res .= ", ".$match[1]." bus ".$i;
		}
		return $res;

	}
	/**
	 * splitBisletter
	 * @param array met huisnummer, begin bisletter en einde bisletter
	 * @return string met de gesplitste reeks
	 */
	static function splitBisLetter($match)
	{
		$res = "";
		for($i = $match[2]; $i<= $match[3]; $i++) {
			$res .= ", ".$match[1].$i;
		}
		return $res;

	}
	/**
	 * split
	 * @param string inputstring met huisnummer(s)
	 * @return string met uitgewerkte huisnummerreeksen
	 */
	static function split($input) 
	{
		$pos = 0;
		$input = preg_replace_callback(self::busnr, array("SpeedSplitter", "splitBusnummer"), $input);
		$input = preg_replace_callback(self::bisnr, array("SpeedSplitter", "splitBisnummer"), $input);
		$input = preg_replace_callback(self::bislr, array("SpeedSplitter", "splitBisLetter"), $input);
		$input = preg_replace_callback(self::reeks, array("SpeedSplitter", "splitHuisnummers"), $input);
		return explode(", ", $input);
	}
}
?>
