<?php
/**
 * @copyright 2007-2014 {@link https://www.onroerenderfgoed.be Onroerend Erfgoed}
 * @author Dieter Standaert <dieter.standaert@eds.com> 
 * @author Koen Van Daele <koen.vandaele@rwo.vlaanderen.be>
 * @license MIT
 */

namespace OE\HousenumBErParser\Elements;

/**
 * Een huisnummer met bisletter.
 *
 * Een enkel huisnummer met bisletter, bijv "3A" of "53D"
 */
class Bisletter extends Biselement{

	/**
	 * __construct
	 * @param integer huisnummer
	 * @param string bisletter
	 */
	public function __construct($huis, $bis){
		parent::__construct($huis,-1,$bis);
		$this->bisIndex = 2;
	}
	/**
	 * __toString
	 * @return string representatie van het nummer
	 */
	public function __toString(){
		return $this->getHuisnummer().$this->getBiselement();
	}
	/**
	 * getBisletter
	 * @return string
	 */
	public function getBisletter(){
		return $this->getBiselement();
	}

}
?>
