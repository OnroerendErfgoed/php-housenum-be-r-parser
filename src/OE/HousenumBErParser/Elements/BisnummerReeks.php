<?php
/**
 * @copyright 2007-2014 {@link https://www.onroerenderfgoed.be Onroerend Erfgoed}
 * @author Dieter Standaert <dieter.standaert@eds.com> 
 * @author Koen Van Daele <koen.vandaele@rwo.vlaanderen.be>
 * @license MIT
 */

namespace OE\HousenumBErParser\Elements;

/**
 * Een reeks van bisnummers.
 *
 * Een reeks van bisnummers.
 *  bijv "33/1, 32/2, 33/3" -> "33/1-3"
 */
class BisnummerReeks extends ReeksElement{

	/**
	 * @param integer huis het huisnummer
	 * @param integer begin het eerste bisnummer van de reeks
	 * @param integer einde het laatste bisnummer van de reeks
	 */
	public function __construct($huis, $begin, $einde){
		parent::__construct($huis, $begin,-1, -1,-1, $huis, $einde);
		$this->beginIndex = 1;
		$this->eindeIndex = 6;
	}
	/**
	 * __toString
	 * @return string de string representatie van de reeks
	 */ 
	public function __toString(){
		return $this->getHuisnummer()."/".$this->getBegin()."-".$this->getEinde();
	}

	/**
	 * split
	 * @return array een array met de individuele bisnummers van deze reeks
	 */
	public function split(){
		$r = array();
		for($i = $this->getBegin(); $i<= $this->getEinde(); $i++){
			$r[] = new Bisnummer($this->getHuisnummer(), $i);
		}
		return $r;
	}
}
?>
