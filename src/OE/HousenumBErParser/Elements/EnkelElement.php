<?php
/**
 * @copyright 2007-2014 {@link https://www.onroerenderfgoed.be Onroerend Erfgoed}
 * @author Dieter Standaert <dieter.standaert@eds.com> 
 * @author Koen Van Daele <koen.vandaele@rwo.vlaanderen.be>
 * @license MIT
 */

namespace OE\HousenumBErParser\Elements;

/**
 * Een enkel element.
 *
 * Een abstracte superklasse voor een huisnummer.
 */
abstract class EnkelElement extends Element{

	public function isException()
	{
		return false;
	}

	public function split()
	{
		return array($this);
	}
	/**
	 * getHuisnummer
	 * 
	 * @return integer het huisnummer van dit element
	 */
	public function getHuisnummer(){
		return $this->getData(Element::HUISNR);
	}
	/**
	 * setHuisnummer
	 * @param integer het nieuwe huisnummer van dit element
	 */
	public function setHuisnummer($nummer){
		$this->setData(Element::HUISNR , $nummer);
	}
}
?>
