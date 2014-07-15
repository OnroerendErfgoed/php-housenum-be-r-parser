<?php
/**
 * @copyright 2007-2014 {@link https://www.onroerenderfgoed.be Onroerend Erfgoed}
 * @author Dieter Standaert <dieter.standaert@eds.com> 
 * @author Koen Van Daele <koen.vandaele@rwo.vlaanderen.be>
 * @license MIT
 */

namespace OE\HousenumBErParser\Elements;

/**
 * Een reeks van elementen.
 *
 * Abstracte klasse voor alle reeksen (compacte notatie van een reeks) huisnummers
 */
abstract class ReeksElement extends EnkelElement{

	/**
	 * @var integer
	 */
	protected $beginIndex;
	/**
	 * @var integer
	 */
	protected $eindeIndex;

	/**
	 * getBegin
	 * @return integer het begin nummer van deze reeks
	 */
	public function getBegin(){
		return $this->getData($this->beginIndex);
	}
	/**
	 * setBegin
	 * @param integer het begin nummer van deze reeks
	 */
	public function setBegin($val){
		return $this->setData($this->beginIndex, $val);
	}
	/**
	 * getEinde
	 * @return integer het laatste nummer van deze reeks
	 */	
	public function getEinde(){
		return $this->getData($this->eindeIndex);
	}
	/**
	 * setEinde
	 * @param integer het laatste nummer van deze reeks
	 */	
	public function setEinde($val){
		return $this->setData($this->eindeIndex, $val);
	}

}
?>
