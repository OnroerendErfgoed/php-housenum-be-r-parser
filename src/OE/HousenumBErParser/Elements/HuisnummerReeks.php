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
class HuisnummerReeks extends ReeksElement{
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
		$this->beginIndex = 0;
		$this->eindeIndex = 5;
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
		$diff = ($this->getEinde() - $this->getBegin());
		if (($diff%2 == 0) && (!$this->spring))
			return $this->getBegin().", ".($this->getBegin()+1)."-".$this->getEinde();
		return $this->getBegin().'-'.$this->getEinde();
	}
	
	/**
	 * isVolgReeks
	 * Geeft weer of de nummers in deze reeks elkaar opvolgend of telkens een
	 *  nummer overslaan
	 *  bijv "33, 35, 37" -> false
	 *  bijv "33, 34, 35, 36" -> true
	 *  bijv "32, 33, 34, 35, 36"-> true
	 * @return boolean of de nummers elkaar opvolgen
	 */

	public function isVolgReeks(){
		return !($this->spring);
	}
	/**
	 * isSpringReeks
	 * Geeft weer of de nummers in deze reeks elkaar opvolgend of telkens een
	 *  nummer overslaan
	 *  bijv "33, 35, 37" -> true
	 *  bijv "33, 34, 35, 36" -> false
	 *  bijv "32, 33, 34, 35, 36"-> false
	 * @return boolean of de nummers in de rij telkens 1 nummer overslaan
	 */	
	public function isSpringReeks(){
		return ($this->spring);
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
		$jump = ($this->isSpringReeks()) ? 2 : 1;
		for($i = $this->getBegin(); $i<= $this->getEinde(); $i += $jump){
			$r[] = new Housenumber($i);
		}
		return $r;
	}
}
?>
