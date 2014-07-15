<?php
/**
 * @copyright 2007-2014 {@link https://www.onroerenderfgoed.be Onroerend Erfgoed}
 * @author Dieter Standaert <dieter.standaert@eds.com> 
 * @author Koen Van Daele <koen.vandaele@rwo.vlaanderen.be>
 * @license MIT
 */

namespace OE\HousenumBErParser\Elements;

/**
 * Een huisnummer met een biselement.
 *
 * Eeen enkel huisnummer met biselement, bijv "3/1" of "21 bus C"
 */
abstract class Biselement extends EnkelElement{

	protected $bisIndex;
	
	public function getBiselement() {
		return $this->getData($this->bisIndex);
	}
}
?>
