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
	private $error_handling;

	/**
     * Create a new Facade.
     *
	 * @param integer Set errorhandling.
	 */
	public function __construct($error_handling = Reader::ERR_IGNORE_INVALID_INPUT)
	{
        $this->error_handling = $error_handling;
		$this->reader = new Reader($error_handling);
		$this->sequencer = new Sequencer();
	}

	/**
     * Transform a string of housenumbers to an array of objects.
     *
	 * @param string String containing huisnummers/huisnummerreeksen
	 * @return array Array of huisnummerobjecten
	 */
	public function stringToNumbers($input)
	{
		return $this->reader->readString($input, $this->error_handling);
	}

	/**
     * Transform an array of objects to a string of housenumbers.
     *
	 * @param array Array met huisnummerobjecten
	 * @return string String met de huisnummers
	 */
	public function numbersToString(array $inputs)
	{
		$result = "";
        foreach($inputs as $input) {
            $result .= ", " . trim($input);
        }
		return substr($result, 2);
	}

	/**
     * Sort an array of housenumber objects.
     *
	 * @param array Array met huisnummerobjecten
	 * @return array Array met gesorteerde huisnummerobjecten
	 */
    public function sortNumbers($inputs)
    {
        usort( $inputs ,array("OE\HousenumBErParser\Elements\Element", "compare"));
        return $inputs;
    }

	/**
     * Split housenumber objects to the smallest units possible.
     *
	 * @param array array met huisnummerobjecten
	 * @return array array met gespliste huisnummerobjecten
	 */
	public function splitNumbers($inputs)
	{
		$result = array();
        foreach($inputs as $input) {
            foreach($input->split() as $el) {
				$result[] = $el;
            }
        }
		return $result;
	}

	/**
     * Merge housenumber object where possible.
     *
	 * @param array Array met huisnummerobjecten
	 * @param boolean Should the objects be sorted before the merge or not?
	 * @return Array with merged houdenumber objects (sequences where possible)
	 */
	public function mergeNumbers($inputs, $sort = true)
	{
		if ($sort) {
			$inputs = $this->sortNumbers($inputs);
		}
		return $this->sequencer->read($inputs);
	}

	/**
     * Split a string of housenumbers.
     *
	 * @param string String met huisnummers (en reeksen)
	 * @return array Array met individuele huisnummerobjecten
	 */
	public function split($input)
	{
		return $this->splitNumbers($this->stringToNumbers($input));
	}
	
	/**
     * Split a string of housenumbers as fast as possible.
     *
	 * @param string inputstring met huisnummers
	 * @return string met huisnummers, waarbij reeksen opgedeeld zijn in hun individuele nummers.
	 */
	public function speedySplit($input)
	{
		return SpeedSplitter::split($input);
	}

	/**
	 * Separate even and uneven sided housenumbers.
     *
     * @param array Array of housenumber objects.
     * @return array An array with two keys: even and uneven.
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
        return array("even"=>$even, "uneven"=>$oneven);
	}

	/**
     * Merge housenumbers, keeping even and uneven separated.
     *
	 * @param string inputstring met huisnummers
	 * @return string met samengevoegde huisnummers tot reeksen, met even en oneven nummers gescheiden
	 */
	public function separateMerge($input) {
		$reeksen = $this->stringToNumbers($input);
		$nummers = $this->splitNumbers($reeksen);
		$separate = $this->separateEven($nummers);
		$even = $this->mergeNumbers($separate["even"]);
		$oneven = $this->mergeNumbers($separate["uneven"]);
        $ret = array_merge( $even, $oneven );
		return $this->sortNumbers($ret);
	}

	/**
     * Merge housenumbers, possibly merging even and uneven sequences.
     *
	 * @param string inputstring met huisnummers
	 * @return string met samengevoegde huisnummers tot reeksen
	 */
	public function straightMerge($input)
	{
		$reeksen = $this->stringToNumbers($input);
		$nummers = $this->splitNumbers($reeksen);
		return $this->mergeNumbers($nummers);
	}

	/**
     * Merge a string of numbers.
     *
	 * @param string inputstring met huisnummers
	 * @param boolean seperate geeft aan of even en onever al dan niet gescheiden blijven.
	 * @return string met samengevoegde huisnummers tot reeksen
	 */
	public function merge($input, $separate = true)
	{
        if ($separate) {
            return $this->separateMerge($input);
        } else {
            return $this->straightMerge($input);
        }
	}
}
?>
