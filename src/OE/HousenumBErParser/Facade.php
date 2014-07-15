<?php
/**
 * @copyright 2014 {@link https://www.onroerenderfgoed.be Onroerend Erfgoed}
 * @author Dieter Standaert <dieter.standaert@eds.com> 
 * @author Koen Van Daele <koen.vandaele@rwo.vlaanderen.be>
 * @license MIT
 */

namespace OE\HousenumBErParser;

use OE\HousenumBErParser\Elements\Element;

/**
 * Facade 
 * 
 * Deze class dient om huisnummerlabels uit te splitsen naar de indivduele labels of van
 * individuele labels terug samen te voegen naar een compactere notatie bv.:
 * <code>
 *  $facade = new Facade( );
 *  $huisnummers = $facade->split( '15-21' );
 *  echo $huisnummers[0]; // 15
 *  echo $huisnummers[1]; // 17
 *  echo $huisnummers[2]; // 19
 *  echo $huisnummers[3]; // 21
 *  $reeksen = $facade->merge($huisnummers);
 *  echo $reeksen[0]; // 15-21
 * </code>
 */
class Facade {

	/**
	 * @var OE\HuisnumBErParser\Reader
	 */
	private $reader;
	
	/**
	 * @var OE\HuisnumBErParser\Sequencer
	 */
	private $sequencer;
	
	/**
	 * @var integer
	 */
	private $flag;
	
	/**
	 * __construct
	 * @param integer flag voor errorhandling
	 */
	public function __construct($flag = 1)
	{
        $this->flag = $flag;
		$this->reader = new Reader($flag);
		$this->sequencer = new Sequencer();
	}
		
	/**
	 * stringToNummers
	 * @param string met huisnummers/huisnummerreeksen
	 * @return array met huisnummerobjecten
	 */
	public function stringToNummers($input)
	{
		return $this->reader->readString($input, $this->flag);
	}

	/**
	 * nummersToString
	 * @param array met huisnummerobjecten
	 * @return string met de huisnummers
	 */
	public function nummersToString($inputs)
	{
		$result = "";
		foreach($inputs as $input) $result.=", $input";
		return substr($result, 2);
	}

	/**
	 * sortNummers
	 * @param array (by reference!!) met huisnummerobjecten
	 * @return array met gesorteerde huisnummerobjecten
	 */
	public function sortNummers(&$inputs)
	{
		usort( $inputs ,array("OE\HousenumBErParser\Elements\Element", "compare"));
		return $inputs;
	}
	
	/**
	 * splitNummers
	 * @param array met huisnummerobjecten
	 * @return array met gespliste huisnummerobjecten
	 */
	public function splitNummers($inputs)
	{
		$result = array();
		foreach($inputs as $input)
			foreach($input->split() as $el)
				$result[] = $el;
		return $result;
	}

	/**
	 * mergeNummers
	 * @param array met huisnummerobjecten
	 * @param boolean geeft weer of de lijst eerst gesorteerd moet worden.
	 * @return array met samen gevoegde huisnummerobjecten (reeksen waar het kan)
	 */
	public function mergeNummers($inputs, $sort = true)
	{
		if($sort) {
			$this->sortNummers($inputs);
		}
		return $this->sequencer->read($inputs);
	}
	
	/**
	 * split
	 * @param string met huisnummers (en reeksen)
	 * @return array met individuele huisnummerobjecten
	 */
	public function split($input)
	{
		return $this->splitNummers($this->stringToNummers($input));
	}
	
	/**
	 * speedySplits
	 * @param string inputstring met huisnummers
	 * @return string met huisnummers, waarbij reeksen opgedeeld zijn in hun individuele nummers.
	 */
	public function speedySplit($input)
	{
		return SpeedSplitter::split($input);
	}

	/**
	 * separateEven
	 *
	 */
	public function separateEven($input)
	{
        $even = array();
        $oneven = array();
        foreach($input as $nummer) {
            if(($nummer->getData(0))&1){
                $oneven[] = $nummer;
            } else {
                $even[] = $nummer;
            }
        }
        return array("even"=>$even, "oneven"=>$oneven);
	}
	
	/**
	 * separateMerge
	 * @param string inputstring met huisnummers
	 * @return string met samengevoegde huisnummers tot reeksen, met even en oneven nummers gescheiden
	 */
	public function separateMerge($input) {
		$reeksen = $this->stringToNummers($input);
		$nummers = $this->splitNummers($reeksen);
		$separate = $this->separateEven($nummers);
		$even = $this->mergeNummers($separate["even"]);
		$oneven = $this->mergeNummers($separate["oneven"]);
        $ret = array_merge( $even, $oneven );
		return $this->sortNummers($ret);
	}
	
	/**
	 * straightMerge
	 * @param string inputstring met huisnummers
	 * @return string met samengevoegde huisnummers tot reeksen
	 */
	public function straightMerge($input)
	{
		$reeksen = $this->stringToNummers($input);
		$nummers = $this->splitNummers($reeksen);
		return $this->mergeNummers($nummers);
	}
	
	/**
	 * merge
	 * @param string inputstring met huisnummers
	 * @param boolean seperate geeft aan of even en onever al dan niet gescheiden blijven.
	 * @return string met samengevoegde huisnummers tot reeksen
	 */
	public function merge($input, $separate = true)
	{
        if($separate) { 
            return $this->separateMerge($input);
        } else {
            return $this->straightMerge($input);
        }
	}
}

?>
