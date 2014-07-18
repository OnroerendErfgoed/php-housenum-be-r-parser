<?php
/**
 * @copyright 2007-2014 {@link https://www.onroerenderfgoed.be Onroerend Erfgoed}
 * @author Dieter Standaert <dieter.standaert@eds.com> 
 * @author Koen Van Daele <koen.vandaele@rwo.vlaanderen.be>
 * @license MIT
 */

namespace OE\HousenumBErParser\Elements;

/**
 * Een leesfout in de huisnummerlezer.
 */
class ReadException extends Element{

	/**
	 * @var string
	 */
    private $error;

	/**
	 * @var string
	 */
	private $input;
	
	public function __construct($error, $input = "")
	{
		parent::__construct(-1);
        $this->error = $error;
        // Remove extra spaces.
		$this->input = trim($input);
	}
	
	public function isException()
	{
		return true;
	}
	
	public function setData($i,$val) {}

	public function split()
	{
		return array($this);
	}
	
	/**
	 * getMessage
	 * @return string error message
	 */
	public function getMessage()
	{
		return $this->error.": '".$this->input."'";
	}
	/**
	 * __toString
	 * string represrntatie van de input
	 */
	public function __toString()
	{
		return $this->input;
	}
}
?>
