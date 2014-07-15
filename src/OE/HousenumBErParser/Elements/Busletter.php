<?php
/**
 * @copyright 2007-2014 {@link https://www.onroerenderfgoed.be Onroerend Erfgoed}
 * @author Dieter Standaert <dieter.standaert@eds.com> 
 * @author Koen Van Daele <koen.vandaele@rwo.vlaanderen.be>
 * @license MIT
 */

namespace OE\HousenumBErParser\Elements;

/**
 * Een huisnummer met een busletter.
 *
 * Een enkel huisnummer met busletter, bijv "3 bus A" of "53 bus D"
 */
class Busletter extends Biselement{
	/**
	 * __construct
	 * @param integer huisnummer
	 * @param string busletter
	 */
	public function __construct($huis, $bus){
		parent::__construct($huis,-1,-1,-1,$bus);
		$this->bisIndex = 4;
	}
	/**
	 * __toString
	 * @return string representatie van het nummer
	 */
	public function __toString(){
		return $this->getHuisnummer()." bus ".$this->getBiselement();
	}
	/**
	 * getBusletter
	 * @return string
	 */
	public function getBusletter(){
		return $this->getBiselement();
	}

}
?>
