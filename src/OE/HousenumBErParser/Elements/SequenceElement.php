<?php
/**
 * @copyright 2007-2014 {@link https://www.onroerenderfgoed.be Onroerend Erfgoed}
 * @author Dieter Standaert <dieter.standaert@eds.com> 
 * @author Koen Van Daele <koen.vandaele@rwo.vlaanderen.be>
 * @license MIT
 */

namespace OE\HousenumBErParser\Elements;

/**
 * An element that represents a sequence.
 *
 * Abstracte class for all Sequences (a short notation for a sequence of 
 * housenumbers).
 */
abstract class SequenceElement extends SingleElement{

	/**
	 * @var integer
	 */
	protected $startIndex;
	/**
	 * @var integer
	 */
	protected $EndIndex;

	/**
     * getStart
     *
	 * @return integer or string The first element of the sequence.
	 */
	public function getStart(){
		return $this->getData($this->startIndex);
    }

	/**
     * setStart
     *
     * @param integer or string The first element of the sequence.
	 * @return integer or string The first element of the sequence.
	 */
	public function setStart($val){
		return $this->setData($this->startIndex, $val);
	}
	/**
     * getEnd
     *
	 * @return integer or string The last element of the sequence.
	 */	
	public function getEnd(){
		return $this->getData($this->endIndex);
	}
	/**
     * setEnd
     *
     * @param integer or string The last element of the sequence.
	 * @return integer or string The last element of the sequence.
	 */	
	public function setEnd($val){
		return $this->setData($this->endIndex, $val);
	}

}
?>
