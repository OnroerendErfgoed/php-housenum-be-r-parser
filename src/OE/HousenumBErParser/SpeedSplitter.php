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
	 * bisnummer syntax:
	 * " <nummer>  <'/' of '_'>  <nummer> "
	 * @var string  
	 */	
	const BISNR = "#,[ ]*(\d+)[ ]*[/|_][ ]*(\d+)#";

	/**
	 * huisnummerreeks syntax:
	 * "<nummer> '-' <nummer>"
	 * @var string 
	 */	
    const HNR_REEKS = "#,[ ]*([\d]+)[ ]*-[ ]*([\d]+)#";
	
	/**
	 * bisnummerreeks syntax:
	 * " <nummer>  <'/' of '_'>  <nummer>  '-' <nummer> "
	 * @var string  
	 */	
	const BISNR_REEKS = "#,[ ]*(\d+)[ ]*[/|_][ ]*(\d+)[ ]*-[ ]*(\d+)#";
	 
	/**
	 * bisletterreeks syntax:
	 * " <nummer>  <letters>  '-' <letters> "
	 * @var string  
	 */	
	const BISLR_REEKS = "#,[ ]*(\d+)[ ]*([a-zA-Z]+)[ ]*-[ ]*(\w+)#";

	/**
	 * busreeks syntax:
	 * " <nummer>  'bus'  <nummer of letter>  '-' <nummer of letter> "
	 * @var string  
	 */
	const BUSNR_REEKS = "#,[ ]*(\d+)[ ]*bus[ ]*(\w+)[ ]*-[ ]*(\w+)#";

	/**
	 * splitHuisnummers
	 * @param array met begin huisnummer en einde huisnummer
	 * @return string met de gesplitste huisnummerreeks
	 */
	private static function splitHuisnummers(array $matches)
    {
		$res = "";
		$jump = (((int)$matches[1] - (int)$matches[2])%2 ==0)? 2 : 1;
		for($i = (int)$matches[1]; $i<= (int)$matches[2]; $i+=$jump) {
			$res .= ", ".$i;
		}
		return $res;
    }

	/**
	 * splitBisnummer
	 * @param array met huisnummer, begin bisnummer en einde bisnummer
	 * @return string met de gesplitste reeks
	 */
	private static function splitBisnummer(array $matches)
    {
		$res = "";
		for($i = $matches[2]; $i<= $matches[3]; $i++) {
			$res .= ", ".$matches[1]."/".$i;
		}
		return $res;

    }

	/**
	 * splitBisnummer
	 * @param array met huisnummer, begin busnummer en einde busnummer
	 * @return string met de gesplitste reeks
	 */
	private static function splitBusnummer(array $matches)
	{
		$res = "";
		for($i = $matches[2]; $i<= $matches[3]; $i++) {
			$res .= ", ".$matches[1]." bus ".$i;
		}
		return $res;

    }

	/**
	 * splitBisletter
	 * @param array met huisnummer, begin bisletter en einde bisletter
	 * @return string met de gesplitste reeks
	 */
	private static function splitBisLetter(array $matches)
	{
		$res = "";
		for($i = $matches[2]; $i<= $matches[3]; $i++) {
			$res .= ", ".$matches[1].$i;
		}
		return $res;

    }

	/**
     * split
     *
	 * @param string inputstring met huisnummer(s) en reeksen
	 * @return array Array met gesplitste huisnummers
	 */
	public static function split($input) 
    {
        if ($input{0} != ',' ) {
            $input = ',' . $input;
        }
		$input = preg_replace_callback(self::BUSNR_REEKS, 'self::splitBusnummer', $input);
		$input = preg_replace_callback(self::BISNR_REEKS, 'self::splitBisnummer', $input);
        $input = preg_replace_callback(self::BISLR_REEKS, 'self::splitBisLetter', $input);
        $input = preg_replace_callback(self::HNR_REEKS, 'self::splitHuisnummers', $input);
        $input = preg_replace(self::BISNR, ', $1/$2', $input);
		return array_map('trim', explode(",", trim($input,',')));
	}
}
?>
