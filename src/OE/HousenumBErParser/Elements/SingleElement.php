<?php
/**
 * @copyright 2007-2014 {@link https://www.onroerenderfgoed.be Onroerend Erfgoed}
 * @author Dieter Standaert <dieter.standaert@eds.com> 
 * @author Koen Van Daele <koen.vandaele@rwo.vlaanderen.be>
 * @license MIT
 */

namespace OE\HousenumBErParser\Elements;

/**
 * A single element (ie. not a sequence).
 *
 * An abstract class for any type of single element. This can be a simple
 * housenumber, or a housenumber with a bis number, ...
 *
 */
abstract class SingleElement extends Element{

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
	public function getHousenumber(){
		return $this->getData(Element::HNR);
	}
	/**
	 * setHuisnummer
	 * @param integer het nieuwe huisnummer van dit element
	 */
	public function setHousenumber($nummer){
		$this->setData(Element::HNR , $nummer);
	}
}
?>
