<?php
/**
 * @copyright 2007-2014 {@link https://www.onroerenderfgoed.be Onroerend Erfgoed}
 * @author Dieter Standaert <dieter.standaert@eds.com> 
 * @author Koen Van Daele <koen.vandaele@rwo.vlaanderen.be>
 * @license MIT
 */

namespace OE\HousenumBErParser\Elements;

/**
 * Een reeks van huisnummers.
 *
 * Een reeks van huisnummers.
 *  bijv "33, 35, 37" -> "33-37"
 *  bijv "33, 34, 35, 36" -> "33-36"
 *  bijv "32, 33, 34, 35, 36"-> "32, 33-36"
 */
class HousenumberSequence extends SequenceElement{
	/**
	 * @var boolean geeft weer of de huisnummers in deze reeks elke opvolgen
	 *  bijvoorbeeld:  35-38
	 * 	of telkens 1 nummer overslaan, zoals bij 35-37.
	 */
	private $spring;

	/**
	 * __construct
	 * @param int begin eerste huisnummer van de reeks
	 * @param int einde laatste huisnummer van de reeks
	 * @param boolean spring geeft weer of de reeks telkens een huisnummer overslaat.
	 */
	public function __construct($begin, $einde, $spring = true){
		parent::__construct($begin,-1, -1,-1,-1,$einde);
		$this->startIndex = 0;
		$this->endIndex = 5;
		$this->spring = $spring;
    }

	/**
	 * __toString
	 * Geeft een string representatie van de reeks weer.
	 * bijv "33, 35, 37" -> "33-37"
	 * bijv "33, 34, 35, 36" -> "33-36"
	 * bijv "32, 33, 34, 35, 36"-> "32, 33-36"
	 * @return string representatie van deze reeks
	 */
	public function __toString(){
		$diff = ($this->getEnd() - $this->getStart());
		if (($diff%2 == 0) && (!$this->spring))
			return $this->getStart().", ".($this->getStart()+1)."-".$this->getEnd();
		return $this->getStart().'-'.$this->getEnd();
	}
	
	/**
     * Is this a consecutive sequence or not?
     *
	 * Geeft weer of de nummers in deze reeks elkaar opvolgend of telkens een
	 *  nummer overslaan
	 *  bijv "33, 35, 37" -> false
	 *  bijv "33, 34, 35, 36" -> true
     *  bijv "32, 33, 34, 35, 36"-> true
     *
	 * @return boolean of de nummers elkaar opvolgen
	 */
	public function isConsecutive(){
		return !($this->spring);
    }

	/**
	 * setSprong
	 * @param boolean of de rij telkens een nummer overslaat (true) of niet (false).
	 */
	public function setSprong($val){
		$this->spring = $val;
	}

	/**
	 * @return array een array met de individuele huisnummers van deze reeks
	 */
	public function split(){
		$r = array();
		$jump = (!$this->isConsecutive()) ? 2 : 1;
		for($i = $this->getStart(); $i<= $this->getEnd(); $i += $jump){
			$r[] = new Housenumber($i);
		}
		return $r;
	}
}
?>
