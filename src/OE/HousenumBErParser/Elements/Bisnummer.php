<?php
/**
 * @copyright 2007-2014 {@link https://www.onroerenderfgoed.be Onroerend Erfgoed}
 * @author Dieter Standaert <dieter.standaert@eds.com> 
 * @author Koen Van Daele <koen.vandaele@rwo.vlaanderen.be>
 * @license MIT
 */

namespace OE\HousenumBErParser\Elements;

/**
 * Een huisnummer met een bisnummer.
 *
 * Een enkel huisnummer met bisnummer, bijv "3/1" of "21/5"
 */
class Bisnummer extends Biselement{

	/**
	 * __construct
	 * @param integer huisnummer
	 * @param integer bisnummer
	 */
	public function __construct($huis, $bis){
		parent::__construct($huis,$bis);
		$this->bisIndex = 1;
	}
	/**
	 * __toString
	 * @return string representatie van het nummer
	 */
	public function __toString(){
		return $this->getHuisnummer()."/".$this->getBiselement();
	}
	/**
	 * getBisnummer
	 * @return integer
	 */
	public function getBisnummer(){
		return $this->getBiselement();
	}

}
?>
