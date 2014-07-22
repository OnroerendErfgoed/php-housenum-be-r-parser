<?php
/**
 * @copyright 2014 {@link https://www.onroerenderfgoed.be Onroerend Erfgoed}
 * @author Dieter Standaert <dieter.standaert@eds.com> 
 * @author Koen Van Daele <koen.vandaele@rwo.vlaanderen.be>
 * @license MIT
 */

namespace OE\HousenumBErParser;

use OE\HousenumBErParser\Elements\HousenumberSequence;
use OE\HousenumBErParser\Elements\BisnumberSequence;
use OE\HousenumBErParser\Elements\BusnumberSequence;
use OE\HousenumBErParser\Elements\BisletterSequence;
use OE\HousenumBErParser\Elements\BusletterSequence;

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
		while(($this->next() == "OE\HousenumBErParser\Elements\Housenumber")&&($this->content()->getHousenumber() == ($reeks->getEnd() +2)))
            $reeks->setEnd($reeks->getEnd() +2);
		return $reeks;
	}
	/**
	 * readVolgReeks
	 *  Leest een reeks van huisnummers, waar de nummers elkaar opvolgen.
	 * @param KVDUtil_HnrHuisnummerReeks de reeks tot nu toe
	 * @return KVDUtil_HnrHuisnummerReeks de volledige reeks
	 */	
    private function readVolgReeks($reeks){
		while(($this->next() == "OE\HousenumBErParser\Elements\Housenumber")&&($this->content()->getHousenumber() == ($reeks->getEnd() +1)))
            $reeks->setEnd($reeks->getEnd() +1);
		return $reeks;
	}
	/**
	 * readHuisnummerReeks
	 *  Leest een reeks van huisnummers
	 * @param KVDUtil_HnrHuisnummer eerste element van de reeks
	 * @return KVDUtil_HnrHuisnummerReeks de volledige reeks
	 */
    private function readHuisnummerReeks($huisnummer){
		$reeks = new HousenumberSequence($huisnummer->getHousenumber(),$huisnummer->getHousenumber());
		if($this->next() != "OE\HousenumBErParser\Elements\Housenumber") return $huisnummer;
        $nummer = $this->content()->getHousenumber();
        if($nummer == ($reeks->getEnd()+1)) {
			$reeks->setSprong(false);
			$reeks->setEnd($nummer);
			return $this->readVolgReeks($reeks);
		}
		if ($nummer == ($reeks->getEnd()+2)) {
			$reeks->setEnd($nummer);
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
        $reeks = new BisnumberSequence(
            $bisnummer->getHousenumber(),
            $bisnummer->getBisnumber(),
            $bisnummer->getBisnumber()
        );
		while(($this->next() == "OE\HousenumBErParser\Elements\Bisnumber")&&($this->content()->getBisnumber() == ($reeks->getEnd() +1)))
			$reeks->setEnd($reeks->getEnd() +1);
		if($reeks->getStart() == $reeks->getEnd()) return $bisnummer;
		else return $reeks;
	}

	/**
	 * readBusnummerReeks
	 *  Leest een reeks van busnummers
	 * @param KVDUtil_HnrBusnummer eerste nummer van de reeks
	 * @return KVDUtil_HnrBusnummerReeks de volledige reeks
	 */
	private function readBusnummerReeks($busnummer){;
        $reeks = new BusnumberSequence(
            $busnummer->getHousenumber(),
            $busnummer->getBusnumber(),
            $busnummer->getBusnumber()
        );
		while(($this->next() == "OE\HousenumBErParser\Elements\Busnumber")&&($this->content()->getBusnumber() == ($reeks->getEnd() +1)))
			$reeks->setEnd($reeks->getEnd() +1);
        if($reeks->getStart() == $reeks->getEnd()) {
            return $busnummer;
        } else {
            return $reeks;
        }
	}

	/**
	 * readBusletterReeks
	 *  Leest een reeks van busletters
	 * @param KVDUtil_HnrBusletter eerste nummer van de reeks
	 * @return KVDUtil_HnrBusletterReeks de volledige reeks
	 */
	private function readBusletterReeks($busletter){;
		$reeks = new BusletterSequence($busletter->getHousenumber(), $busletter->getBusletter(), $busletter->getBusletter());
		$einde = $reeks->getEnd();
		while(($this->next() == "OE\HousenumBErParser\Elements\Busletter")&&($this->content()->getBusletter() == ++$einde))
			$reeks->setEnd($einde);
        if ($reeks->getStart() == $reeks->getEnd()) {
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
		$reeks = new BisletterSequence($bisletter->getHousenumber(), $bisletter->getBisletter(), $bisletter->getBisletter());
		$einde = $reeks->getEnd();
		while(($this->next() == "OE\HousenumBErParser\Elements\Bisletter")&&($this->content()->getBisletter() == (++$einde)))
			$reeks->setEnd($einde);
		if($reeks->getStart() == $reeks->getEnd()) return $bisletter;
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
            case "OE\HousenumBErParser\Elements\Housenumber":
                return $this->readHuisnummerReeks($this->content());
            case "OE\HousenumBErParser\Elements\Bisnumber":
                return $this->readBisnummerReeks($this->content());
            case "OE\HousenumBErParser\Elements\Busnumber":
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
