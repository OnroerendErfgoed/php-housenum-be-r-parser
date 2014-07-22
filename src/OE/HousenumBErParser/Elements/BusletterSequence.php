<?php
/**
 * @copyright 2007-2014 {@link https://www.onroerenderfgoed.be Onroerend Erfgoed}
 * @author Dieter Standaert <dieter.standaert@eds.com> 
 * @author Koen Van Daele <koen.vandaele@rwo.vlaanderen.be>
 * @license MIT
 */

namespace OE\HousenumBErParser\Elements;

/**
 * Een reeks van busletters.
 *
 * Een reeks van busletters.
 *  bijv "33 bus A, 32 bus B, 33 bus C" -> "33 bus A-C"
 */
class BusletterSequence extends SequenceElement{
	/**
	 * __construct
	 * @param integer huisnummer
	 * @param integer het eerste nummer van de reeks
	 * @param integer het laatste nummer van de reeks
	 */		
	public function __construct($huis, $begin, $einde){
		parent::__construct($huis,-1,-1,-1, $begin,$huis,-1,-1,-1,$einde);
		$this->startIndex = 4;
		$this->endIndex = 9;
	}
	/**
	 * __toString
	 * @return Een string representatie van de busnummer reeks, bijv: 13 bus 1-3.
	 */
	public function __toString(){
		return $this->getHousenumber()." bus ".$this->getStart()."-".$this->getEnd();
	}	
	/**
	 * split
	 * @return array een array met de individuele bisnummers van deze reeks
	 */	
	public function split(){
		$r = array();
		for($i = $this->getStart(); $i<= $this->getEnd(); $i++){
			$r[] = new Busletter($this->getHousenumber(), $i);
		}
		return $r;
	}
}
?>
