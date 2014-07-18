<?php
/**
 * @copyright 2007-2014 {@link https://www.onroerenderfgoed.be Onroerend Erfgoed}
 * @author Dieter Standaert <dieter.standaert@eds.com> 
 * @author Koen Van Daele <koen.vandaele@rwo.vlaanderen.be>
 * @license MIT
 */

namespace OE\HousenumBErParser\Elements;

/**
 * Een huisnummer met een busnummer.
 *
 * Een enkel huisnummer met busnummer, bijv "3 bus 1" of "53 bus 5"
 */
class Busnummer extends Biselement{
	/**
	 * __construct
	 * @param integer huisnummer
	 * @param integer busnummer
	 */
	public function __construct($huis, $bus){
		parent::__construct($huis,-1, -1, $bus);
		$this->bisIndex = 3;
	}
	/**
	 * @return string representatie van het nummer
	 */
	public function __toString(){
		return $this->getHousenumber()." bus ".$this->getBiselement();
	}
	/**
	 * getBusnummer
	 * @return integer
	 */
	public function getBusnummer(){
		return $this->getBiselement();
	}

}
?>
