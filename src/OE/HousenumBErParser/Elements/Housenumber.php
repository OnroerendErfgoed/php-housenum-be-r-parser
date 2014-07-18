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
class Housenumber extends SingleElement {

	/**
	 * __toString
	 * @return string representatie van het huisnummer, bijv "3" of "21"
	 */
	public function __toString(){
		return (string) $this->getHousenumber();
	}
}
?>
