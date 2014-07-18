<?php
/**
 * @copyright 2014 {@link https://www.onroerenderfgoed.be Onroerend Erfgoed}
 * @author Dieter Standaert <dieter.standaert@eds.com> 
 * @author Koen Van Daele <koen.vandaele@rwo.vlaanderen.be>
 * @license MIT
 */

namespace OE\HousenumBErParser;

use OE\HousenumBErParser\Elements\HuisnummerReeks;
use OE\HousenumBErParser\Elements\BisnummerReeks;
use OE\HousenumBErParser\Elements\BusnummerReeks;
use OE\HousenumBErParser\Elements\BisletterReeks;
use OE\HousenumBErParser\Elements\BusletterReeks;

/**
 * Een Sequencer.
 *
 * Klasse die een reeks van huisnummers kan interpreteren en deze rij kan
 *samenvatten. Bijvoorbeeld:
 *		"23 bus 5, 23 bus 6" -> Busnummerreeks "23 bus 5-6"
 *		"23", "24 bus 2" -> Huisnummer "23", Busnummer "24 bus 2"
 *		"25", "26", "27" -> Huisnummerreeks "25, 26-27"
 */
class Sequencer{
	
	/**
	 * @var array array van te verzamelen elementen.
	 */	
	private $input;

	/**
	 * @var integer positie in de input array.
	 */	
	private $pos;

	/**
	 * @var array de verzamelde elementen
	 */	
	private $result;

	/**
	 * readSpringReeks
	 *  Leest een reeks van huisnummers, die telkens een nummer overslaan.
	 * @param KVDUtil_HnrHuisnummerReeks de reeks tot nu toe
	 * @return KVDUtil_HnrHuisnummerReeks de volledige reeks
	 */	
	private function readSpringReeks($reeks){
		while(($this->next() == "OE\HousenumBErParser\Elements\Huisnummer")&&($this->content()->getHuisnummer() == ($reeks->getEinde() +2)))
            $reeks->setEinde($reeks->getEinde() +2);
		return $reeks;
	}
	/**
	 * readVolgReeks
	 *  Leest een reeks van huisnummers, waar de nummers elkaar opvolgen.
	 * @param KVDUtil_HnrHuisnummerReeks de reeks tot nu toe
	 * @return KVDUtil_HnrHuisnummerReeks de volledige reeks
	 */	
    private function readVolgReeks($reeks){
		while(($this->next() == "OE\HousenumBErParser\Elements\Huisnummer")&&($this->content()->getHuisnummer() == ($reeks->getEinde() +1)))
            $reeks->setEinde($reeks->getEinde() +1);
		return $reeks;
	}
	/**
	 * readHuisnummerReeks
	 *  Leest een reeks van huisnummers
	 * @param KVDUtil_HnrHuisnummer eerste element van de reeks
	 * @return KVDUtil_HnrHuisnummerReeks de volledige reeks
	 */
    private function readHuisnummerReeks($huisnummer){
		$reeks = new HuisnummerReeks($huisnummer->getHuisnummer(),$huisnummer->getHuisnummer());
		if($this->next() != "OE\HousenumBErParser\Elements\Huisnummer") return $huisnummer;
        $nummer = $this->content()->getHuisnummer();
        if($nummer == ($reeks->getEinde()+1)) {
			$reeks->setSprong(false);
			$reeks->setEinde($nummer);
			return $this->readVolgReeks($reeks);
		}
		if ($nummer == ($reeks->getEinde()+2)) {
			$reeks->setEinde($nummer);
			return $this->readSpringReeks($reeks);
		}
		return $huisnummer;
	}

	/**
	 * readBisnummerReeks
	 *  Leest een reeks van bisnummers
	 * @param KVDUtil_HnrBisnummer eerste nummer van de reeks
	 * @return KVDUtil_HnrBisnummerReeks de volledige reeks
	 */
	private function readBisnummerReeks($bisnummer){
		$reeks = new BisnummerReeks($bisnummer->getHuisnummer(), $bisnummer->getBisnummer(), $bisnummer->getBisnummer());
		while(($this->next() == "OE\HousenumBErParser\Elements\Bisnummer")&&($this->content()->getBisnummer() == ($reeks->getEinde() +1)))
			$reeks->setEinde($reeks->getEinde() +1);
		if($reeks->getBegin() == $reeks->getEinde()) return $bisnummer;
		else return $reeks;
	}

	/**
	 * readBusnummerReeks
	 *  Leest een reeks van busnummers
	 * @param KVDUtil_HnrBusnummer eerste nummer van de reeks
	 * @return KVDUtil_HnrBusnummerReeks de volledige reeks
	 */
	private function readBusnummerReeks($busnummer){;
		$reeks = new BusnummerReeks($busnummer->getHuisnummer(), $busnummer->getBusnummer(), $busnummer->getBusnummer());
		while(($this->next() == "OE\HousenumBErParser\Elements\Busnummer")&&($this->content()->getBusnummer() == ($reeks->getEinde() +1)))
			$reeks->setEinde($reeks->getEinde() +1);
		if($reeks->getBegin() == $reeks->getEinde()) return $busnummer;
		else return $reeks;
	}

	/**
	 * readBusletterReeks
	 *  Leest een reeks van busletters
	 * @param KVDUtil_HnrBusletter eerste nummer van de reeks
	 * @return KVDUtil_HnrBusletterReeks de volledige reeks
	 */
	private function readBusletterReeks($busletter){;
		$reeks = new BusletterReeks($busletter->getHuisnummer(), $busletter->getBusletter(), $busletter->getBusletter());
		$einde = $reeks->getEinde();
		while(($this->next() == "OE\HousenumBErParser\Elements\Busletter")&&($this->content()->getBusletter() == ++$einde))
			$reeks->setEinde($einde);
        if ($reeks->getBegin() == $reeks->getEinde()) {
            return $busletter;
        } else {
            return $reeks;
        }
	}
	/**
	 * readBisletterReeks
	 *  Leest een reeks van bisletters
	 * @param KVDUtil_HnrBisletter eerste nummer van de reeks
	 * @return KVDUtil_HnrBisletterReeks de volledige reeks
	 */
	private function readBisletterReeks($bisletter){
		$reeks = new BisletterReeks($bisletter->getHuisnummer(), $bisletter->getBisletter(), $bisletter->getBisletter());
		$einde = $reeks->getEinde();
		while(($this->next() == "OE\HousenumBErParser\Elements\Bisletter")&&($this->content()->getBisletter() == (++$einde)))
			$reeks->setEinde($einde);
		if($reeks->getBegin() == $reeks->getEinde()) return $bisletter;
		else return $reeks;
	}

	private function skip(){
		$element = $this->content();
		$this->next();
		return $element;
	}
	/**
	 * readReeks
	 *  Leest een reeks van huisnummers, ongeacht hun type, uit de input array.
	 * @return ReeksElement de volledige reeks
	 */	
    private function readReeks(){
        switch($this->current()) {
            case "OE\HousenumBErParser\Elements\Huisnummer":
                return $this->readHuisnummerReeks($this->content());
            case "OE\HousenumBErParser\Elements\Bisnummer":
                return $this->readBisnummerReeks($this->content());
            case "OE\HousenumBErParser\Elements\Busnummer":
                return $this->readBusnummerReeks($this->content());
            case "OE\HousenumBErParser\Elements\Busletter":
                return $this->readBusletterReeks($this->content());
            case "OE\HousenumBErParser\Elements\Bisletter":
                return $this->readBisletterReeks($this->content());
            case "OE\HousenumBErParser\Elements\ReadException":
                return $this->skip();
            case "":
                return null;
            default: 
                throw new \InvalidArgumentException("Invalid type: ".$this->current().".");
		}
	}
	/**
     * Read Housenumber objets
     *
     * Reads an array of Housenumber elements.
     *
	 * @param array Array of Housenumber Elements.
	 * @return OE\HousenumBErParser\Elements\ReeksElement
	 */
	public function read($in){
		$this->input = $in;
		$this->pos = 0;
		$this->result = array();
        while($this->current() != "") {
		    $r = $this->readReeks();
		    $this->store($r);
		}
		return $this->result;
    }

	/**
	 * next
	 *  geeft het volgende element in de input array terug
	 * @return OE\HousenumBErParser\Elements\ReeksElement
	 */
	 private function next(){
		$this->pos++;
		return $this->current();
     }

	/**
	 * current
	 *  geeft het type van het huidige element in de input array terug
	 * @return OE\HousenumBErParser\Elements\ReeksElement
	 */
	private function current(){
        if ($this->pos >= sizeof($this->input)) {
            return "";
        } else {
            return get_class($this->input[$this->pos]);
        }
    }

	/**
	 * current
	 *  geeft de inhoud van huidige element in de input array terug
	 * @return string
	 */
	private function content(){
		return $this->input[$this->pos];
    }

	/**
	 * store
	 *  slaat het gevormde reeks element op.
	 * @param het resultaat
	 */		
	private function store($content){
		$this->result[] = $content;
	}
}
?>
