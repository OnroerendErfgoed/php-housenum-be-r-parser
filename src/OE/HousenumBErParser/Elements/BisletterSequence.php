<?php
/**
 * @copyright 2007-2014 {@link https://www.onroerenderfgoed.be Onroerend Erfgoed}
 * @author Dieter Standaert <dieter.standaert@eds.com> 
 * @author Koen Van Daele <koen.vandaele@rwo.vlaanderen.be>
 * @license MIT
 */

namespace OE\HousenumBErParser\Elements;

/**
 * Een reeks van bisletters.
 *
 * Een reeks van bisletters.
 *  bijv "33A, 32B, 33C" -> "33A-C"
 */
class BisletterSequence extends SequenceElement{

	/**
	 * __construct
	 * @param integer huisnummer
	 * @param integer het eerste nummer van de reeks
	 * @param integer het laatste nummer van de reeks
	 */	
	public function __construct($huis, $begin, $einde){
		parent::__construct($huis,-1, $begin,-1,-1,$huis,-1, $einde);
		$this->startIndex = 2;
		$this->endIndex = 7;
	}
	/**
	 * __toString
	 * @return Een string representatie van de bisletter reeks, bijv: 13A-C.
	 */
	public function __toString(){
		return $this->getHousenumber().$this->getStart()."-".$this->getEnd();
	}	
	
	
	/**
	 * split
	 * @return array een array met de individuele bisnummers van deze reeks
	 */	
	public function split(){
		$r = array();
		for($i = $this->getStart(); $i<= $this->getEnd(); $i++){
			$r[] = new Bisletter($this->getHousenumber(), $i);
		}
		return $r;
	}
}
?>
