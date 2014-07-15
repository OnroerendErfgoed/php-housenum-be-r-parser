<?php
/**
 * @copyright 2007-2014 {@link https://www.onroerenderfgoed.be Onroerend Erfgoed}
 * @author Dieter Standaert <dieter.standaert@eds.com> 
 * @author Koen Van Daele <koen.vandaele@rwo.vlaanderen.be>
 * @license MIT
 */

namespace OE\HousenumBErParser\Elements;

/**
 * Een huisnummer elements.
 *
 * Dit is de (abstracte) superklasse voor alle output van de huisnummerlezer. 
 * Dit kan een huisnummer, huisnummerreeks, leesfout enz. zijn.
 */
abstract class Element{

	/**
	 * @var array array met alle data van een huisnummer.
	 */
    private $data;

	const HUISNR = 0;
	const BISN = 1;
	const BISL = 2;
	const BUSN = 3;
	const BUSL = 4;
	
	/**
	 * __construct
	 * @param integer huisnummer
	 * @param string bisnummer of bisletter
	 * @param string busnummer of busletter
	 * @param integer laatste huisnummer van de reeks
	 * @param string laatste bisnummer of bisletter van de reeks
	 * @param string laatste busnummer of busletter van de reeks
	 */
	public function __construct(
		$h1		, $bisn1 =-1, $bisl1 =-1, $busn1=-1,  $busl1=-1,
		$h2=-1, $bisn2 =-1, $bisl2 =-1, $busn2=-1,  $busl2=-1)
	{
		$this->data = array($h1, $bisn1, $bisl1, $busn1, $busl1, $h2, $bisn2, $bisl2, $busn2, $busl2);
	}
	
	/**
	 * getDatas
	 *  geeft de data array van dit huisnummer
	 * @return array
	 */
	public function getDatas()
	{
		return $this->data;
	}
	
	/**
	 * getData
	 *  geeft informatie van dit huisnummer
	 * @param integer op te vragen gegeven
	 * @return integer of string met data
	 */
	public function getData($i)
	{
		return $this->data[$i];
	}
	
	/**
	 * setData
	 */
	public function setData($i, $val)
	{
		$this->data[$i] = $val;
	}
	
	/**
	 * comparaTo
	 * @param KVDUtil_HnrElement el
	 * @return integer (-1 if $this < $el ; 0 if $this = $el ; 1 if $this > $el)
	 */
	public function compareTo($el) 
	{
		$i = 0;
		while(($i < 9) &&($this->data[$i] == $el->getData($i))){
			$i++;
		}
		if($this->data[$i] == $el->getData($i)) { return 0; }
		else if($this->data[$i] < $el->getData($i)) { return -1; }
		else { return 1; }
	}
	
	/**
	 * split
	 *  geeft een array met alle huisnummers die dit element
	 *  bevat.
	 * @return array een array met huisnummers
	 */
	abstract function split();
	
	/**
	 * isException
	 * @return bool
	 */
	abstract function isException();
	
	/**
	 * compare
	 * @param KVDUtil_HnrElement $el1
	 * @param KVDUtil_HnrElement $el2
	 * @return integer (-1 if $el1 < $el ; 0 if $el1 = $el ; 1 if $el1 > $el)
	 */
	static function compare($el1, $el2)
	{
		return $el1->compareTo($el2);
	}

}
?>
