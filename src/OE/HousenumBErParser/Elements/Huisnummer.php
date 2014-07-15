<?php
/**
 * @copyright 2007-2014 {@link https://www.onroerenderfgoed.be Onroerend Erfgoed}
 * @author Dieter Standaert <dieter.standaert@eds.com> 
 * @author Koen Van Daele <koen.vandaele@rwo.vlaanderen.be>
 * @license MIT
 */

namespace OE\HousenumBErParser\Elements;

/**
 * Een simpel huisnummer.
 *
 * Een eenvoudig huisnummer, bijv: 13 of 15.
 */
class Huisnummer extends EnkelElement {

	/**
	 * __construct
	 * @param integer het huisnummer
	 */
	public function __construct($nummer){
		parent::__construct($nummer);
    }

	/**
	 * __toString
	 * @return string representatie van het huisnummer, bijv "3" of "21"
	 */
	public function __toString(){
		return "".$this->getHuisnummer();
	}
}
?>
