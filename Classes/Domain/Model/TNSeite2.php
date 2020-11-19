<?php
namespace Ud\Iqtp13db\Domain\Model;

/***
 *
 * This file is part of the "IQ TP13 Datenbank Anerkennungserstberatung NRW" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Uli Dohmen <edv@whkt.de>, WHKT
 *
 ***/

/**
 * TNSeite2
 */
class TNSeite2 extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

		/**
	 * Deutschkenntnisse
	 *
	 * @var int
	 */
	protected $deutschkenntnisse = 0;

	/**
	 * Zertifikat Deutschkenntnisse vorhanden?
	 *
	 * @var int
	 */
	protected $zertifikatdeutsch = 0;

	/**
	 * Welches Sprachniveau?
	 *
	 * @var string
	 */
	protected $zertifikatSprachniveau = '';

	/**
	 * Beratungsgespräch auf Deutsch?
	 *
	 * @var int
	 */
	protected $beratungsgespraechDeutsch = 0;

	/**
	 * Sprache Beratungsgespräch
	 *
	 * @var string
	 */
	protected $beratungsgespraechSprache = '';

	/**
	 * Ausbildungsabschluss
	 *
	 * @var bool
	 */
	protected $abschlussartA = FALSE;

	/**
	 * Hochschulabschluss
	 *
	 * @var bool
	 */
	protected $abschlussartH = FALSE;
	
	/**
	 * Erwerbsland
	 *
	 * @var string
	 * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
	 */
	protected $erwerbsland1 = '';

	/**
	 * Dauer der Berufsausbildung
	 *
	 * @var string
	 */
	protected $dauerBerufsausbildung1 = '';

	/**
	 * Abschlussjahr
	 *
	 * @var string
	 * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
	 */
	protected $abschlussjahr1 = '';

	/**
	 * Ausbildungsinstitution
	 *
	 * @var string
	 */
	protected $ausbildungsinstitution1 = '';

	/**
	 * Ausbildungsort
	 *
	 * @var string
	 */
	protected $ausbildungsort1 = '';

	/**
	 * Abschluss
	 *
	 * @var string
	 * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
	 */
	protected $abschluss1 = '';

	/**
	 * Deutsche Übersetzung des Abschlusstitels
	 *
	 * @var string
	 */
	protected $deutschAbschlusstitel1 = '';

	/**
	 * Möglicher deutscher Referenzberuf
	 *
	 * @var string
	 */
	protected $deutscherReferenzberuf1 = '';

	/**
	 * Berufserfahrung
	 *
	 * @var string
	 */
	protected $berufserfahrung1 = '';
	
	/**
	 * Wunschberuf
	 *
	 * @var string
	 */
	protected $wunschberuf1 = '';

	/**
	 * Erwerbsland
	 *
	 * @var string
	 */
	protected $erwerbsland2 = '';
	
	/**
	 * Dauer Berufsausbildung
	 *
	 * @var string
	 */
	protected $dauerBerufsausbildung2 = '';
	
	/**
	 * Abschlussjahr
	 *
	 * @var string
	 */
	protected $abschlussjahr2 = '';
	
	/**
	 * Ausbildungsinstitution
	 *
	 * @var string
	 */
	protected $ausbildungsinstitution2 = '';
	
	/**
	 * Ausbildungsort
	 *
	 * @var string
	 */
	protected $ausbildungsort2 = '';
	
	/**
	 * Abschluss
	 *
	 * @var string
	 */
	protected $abschluss2 = '';
	
	/**
	 * Deutsche Übersetzung des Abschlusstitels
	 *
	 * @var string
	 */
	protected $deutschAbschlusstitel2 = '';
	
	/**
	 * Berufserfahrung
	 *
	 * @var string
	 */
	protected $berufserfahrung2 = '';
	
	/**
	 * Möglicher deutscher Beruf
	 *
	 * @var string
	 */
	protected $deutscherReferenzberuf2 = '';
	
	/**
	 * Wunschberuf
	 *
	 * @var string
	 */
	protected $wunschberuf2 = '';
	
	/**
	 * Liegen Original-Dokumente des Abschlusses vor?
	 *
	 * @var int
	 */
	protected $originalDokumenteAbschluss1 = 0;
	
	/**
	 * Liegen Original-Dokumente des Abschlusses vor?
	 *
	 * @var int
	 */
	protected $originalDokumenteAbschluss2 = 0;
	
	/**
	 * Wenn ja, welche Sprache(n)?
	 *
	 * @var string
	 */
	protected $sprachen = '';
	
	/**
	 * Returns the deutschkenntnisse
	 *
	 * @return int $deutschkenntnisse
	 */
	public function getDeutschkenntnisse() {
		return $this->deutschkenntnisse;
	}

	/**
	 * Sets the deutschkenntnisse
	 *
	 * @param int $deutschkenntnisse
	 * @return void
	 */
	public function setDeutschkenntnisse($deutschkenntnisse) {
		$this->deutschkenntnisse = $deutschkenntnisse;
	}

	/**
	 * Returns the zertifikatdeutsch
	 *
	 * @return int $zertifikatdeutsch
	 */
	public function getZertifikatdeutsch() {
		return $this->zertifikatdeutsch;
	}

	/**
	 * Sets the zertifikatdeutsch
	 *
	 * @param int $zertifikatdeutsch
	 * @return void
	 */
	public function setZertifikatdeutsch($zertifikatdeutsch) {
		$this->zertifikatdeutsch = $zertifikatdeutsch;
	}

	/**
	 * Returns the zertifikatSprachniveau
	 *
	 * @return string $zertifikatSprachniveau
	 */
	public function getZertifikatSprachniveau() {
		return $this->zertifikatSprachniveau;
	}

	/**
	 * Sets the zertifikatSprachniveau
	 *
	 * @param string $zertifikatSprachniveau
	 * @return void
	 */
	public function setZertifikatSprachniveau($zertifikatSprachniveau) {
		$this->zertifikatSprachniveau = $zertifikatSprachniveau;
	}

	/**
	 * Returns the beratungsgespraechDeutsch
	 *
	 * @return int $beratungsgespraechDeutsch
	 */
	public function getBeratungsgespraechDeutsch() {
		return $this->beratungsgespraechDeutsch;
	}

	/**
	 * Sets the beratungsgespraechDeutsch
	 *
	 * @param int $beratungsgespraechDeutsch
	 * @return void
	 */
	public function setBeratungsgespraechDeutsch($beratungsgespraechDeutsch) {
		$this->beratungsgespraechDeutsch = $beratungsgespraechDeutsch;
	}

	/**
	 * Returns the beratungsgespraechSprache
	 *
	 * @return string $beratungsgespraechSprache
	 */
	public function getBeratungsgespraechSprache() {
		return $this->beratungsgespraechSprache;
	}

	/**
	 * Sets the beratungsgespraechSprache
	 *
	 * @param string $beratungsgespraechSprache
	 * @return void
	 */
	public function setBeratungsgespraechSprache($beratungsgespraechSprache) {
		$this->beratungsgespraechSprache = $beratungsgespraechSprache;
	}
		
	/**
	 * Returns the sprachen
	 *
	 * @return string $sprachen
	 */
	public function getSprachen() {
		return $this->sprachen;
	}
	
	/**
	 * Sets the sprachen
	 *
	 * @param string $sprachen
	 * @return void
	 */
	public function setSprachen($sprachen) {
		$this->sprachen = $sprachen;
	}
	
	/**
	 * Returns the abschlussartA
	 *
	 * @return bool abschlussartA
	 */
	public function getAbschlussartA() {
		return $this->abschlussartA;
	}

	/**
	 * Sets the abschlussartA
	 *
	 * @param int $abschlussartA
	 * @return void
	 */
	public function setAbschlussartA($abschlussartA) {
		$this->abschlussartA = $abschlussartA;
	}

	/**
	 * Returns the erwerbsland1
	 *
	 * @return string erwerbsland1
	 */
	public function getErwerbsland1() {
		return $this->erwerbsland1;
	}

	/**
	 * Sets the erwerbsland1
	 *
	 * @param string $erwerbsland1
	 * @return void
	 */
	public function setErwerbsland1($erwerbsland1) {
		$this->erwerbsland1 = $erwerbsland1;
	}

	/**
	 * Returns the dauerBerufsausbildung1
	 *
	 * @return string dauerBerufsausbildung1
	 */
	public function getDauerBerufsausbildung1() {
		return $this->dauerBerufsausbildung1;
	}

	/**
	 * Sets the dauerBerufsausbildung1
	 *
	 * @param string $dauerBerufsausbildung1
	 * @return void
	 */
	public function setDauerBerufsausbildung1($dauerBerufsausbildung1) {
		$this->dauerBerufsausbildung1 = $dauerBerufsausbildung1;
	}

	/**
	 * Returns the abschlussjahr1
	 *
	 * @return string abschlussjahr1
	 */
	public function getAbschlussjahr1() {
		return $this->abschlussjahr1;
	}

	/**
	 * Sets the abschlussjahr1
	 *
	 * @param string $abschlussjahr1
	 * @return void
	 */
	public function setAbschlussjahr1($abschlussjahr1) {
		$this->abschlussjahr1 = $abschlussjahr1;
	}

	/**
	 * Returns the ausbildungsinstitution1
	 *
	 * @return string ausbildungsinstitution1
	 */
	public function getAusbildungsinstitution1() {
		return $this->ausbildungsinstitution1;
	}

	/**
	 * Sets the ausbildungsinstitution1
	 *
	 * @param string $ausbildungsinstitution1
	 * @return void
	 */
	public function setAusbildungsinstitution1($ausbildungsinstitution1) {
		$this->ausbildungsinstitution1 = $ausbildungsinstitution1;
	}

	/**
	 * Returns the ausbildungsort1
	 *
	 * @return string ausbildungsort1
	 */
	public function getAusbildungsort1() {
		return $this->ausbildungsort1;
	}

	/**
	 * Sets the ausbildungsort1
	 *
	 * @param string $ausbildungsort1
	 * @return void
	 */
	public function setAusbildungsort1($ausbildungsort1) {
		$this->ausbildungsort1 = $ausbildungsort1;
	}

	/**
	 * Returns the abschluss1
	 *
	 * @return string abschluss1
	 */
	public function getAbschluss1() {
		return $this->abschluss1;
	}

	/**
	 * Sets the abschluss1
	 *
	 * @param string $abschluss1
	 * @return void
	 */
	public function setAbschluss1($abschluss1) {
		$this->abschluss1 = $abschluss1;
	}

	/**
	 * Returns the deutschAbschlusstitel1
	 *
	 * @return string deutschAbschlusstitel1
	 */
	public function getDeutschAbschlusstitel1() {
		return $this->deutschAbschlusstitel1;
	}

	/**
	 * Sets the deutschAbschlusstitel1
	 *
	 * @param string $deutschAbschlusstitel1
	 * @return void
	 */
	public function setDeutschAbschlusstitel1($deutschAbschlusstitel1) {
		$this->deutschAbschlusstitel1 = $deutschAbschlusstitel1;
	}

	/**
	 * Returns the deutscherReferenzberuf1
	 *
	 * @return string deutscherReferenzberuf1
	 */
	public function getDeutscherReferenzberuf1() {
		return $this->deutscherReferenzberuf1;
	}

	/**
	 * Sets the deutscherReferenzberuf1
	 *
	 * @param string $deutscherReferenzberuf1
	 * @return void
	 */
	public function setDeutscherReferenzberuf1($deutscherReferenzberuf1) {
		$this->deutscherReferenzberuf1 = $deutscherReferenzberuf1;
	}

	/**
	 * Returns the berufserfahrung1
	 *
	 * @return string berufserfahrung1
	 */
	public function getBerufserfahrung1() {
		return $this->berufserfahrung1;
	}

	/**
	 * Sets the berufserfahrung1
	 *
	 * @param string $berufserfahrung1
	 * @return void
	 */
	public function setBerufserfahrung1($berufserfahrung1) {
		$this->berufserfahrung1 = $berufserfahrung1;
	}

	/**
	 * Returns the wunschberuf1
	 *
	 * @return string wunschberuf1
	 */
	public function getWunschberuf1() {
		return $this->wunschberuf1;
	}

	/**
	 * Sets the wunschberuf1
	 *
	 * @param string $wunschberuf1
	 * @return void
	 */
	public function setWunschberuf1($wunschberuf1) {
		$this->wunschberuf1 = $wunschberuf1;
	}

	/**
	 * Returns the abschlussartH
	 *
	 * @return bool $abschlussartH
	 */
	public function getAbschlussartH() {
		return $this->abschlussartH;
	}

	/**
	 * Sets the abschlussartH
	 *
	 * @param bool $abschlussartH
	 * @return void
	 */
	public function setAbschlussartH($abschlussartH) {
		$this->abschlussartH = $abschlussartH;
	}

	/**
	 * Returns the boolean state of abschlussartH
	 *
	 * @return bool
	 */
	public function isAbschlussartH() {
		return $this->abschlussartH;
	}

	/**
	 * Returns the erwerbsland2
	 *
	 * @return string $erwerbsland2
	 */
	public function getErwerbsland2() {
		return $this->erwerbsland2;
	}

	/**
	 * Sets the erwerbsland2
	 *
	 * @param string $erwerbsland2
	 * @return void
	 */
	public function setErwerbsland2($erwerbsland2) {
		$this->erwerbsland2 = $erwerbsland2;
	}

	/**
	 * Returns the dauerBerufsausbildung2
	 *
	 * @return string $dauerBerufsausbildung2
	 */
	public function getDauerBerufsausbildung2() {
		return $this->dauerBerufsausbildung2;
	}

	/**
	 * Sets the dauerBerufsausbildung2
	 *
	 * @param string $dauerBerufsausbildung2
	 * @return void
	 */
	public function setDauerBerufsausbildung2($dauerBerufsausbildung2) {
		$this->dauerBerufsausbildung2 = $dauerBerufsausbildung2;
	}

	/**
	 * Returns the abschlussjahr2
	 *
	 * @return string $abschlussjahr2
	 */
	public function getAbschlussjahr2() {
		return $this->abschlussjahr2;
	}

	/**
	 * Sets the abschlussjahr2
	 *
	 * @param string $abschlussjahr2
	 * @return void
	 */
	public function setAbschlussjahr2($abschlussjahr2) {
		$this->abschlussjahr2 = $abschlussjahr2;
	}

	/**
	 * Returns the ausbildungsinstitution2
	 *
	 * @return string $ausbildungsinstitution2
	 */
	public function getAusbildungsinstitution2() {
		return $this->ausbildungsinstitution2;
	}

	/**
	 * Sets the ausbildungsinstitution2
	 *
	 * @param string $ausbildungsinstitution2
	 * @return void
	 */
	public function setAusbildungsinstitution2($ausbildungsinstitution2) {
		$this->ausbildungsinstitution2 = $ausbildungsinstitution2;
	}

	/**
	 * Returns the ausbildungsort2
	 *
	 * @return string $ausbildungsort2
	 */
	public function getAusbildungsort2() {
		return $this->ausbildungsort2;
	}

	/**
	 * Sets the ausbildungsort2
	 *
	 * @param string $ausbildungsort2
	 * @return void
	 */
	public function setAusbildungsort2($ausbildungsort2) {
		$this->ausbildungsort2 = $ausbildungsort2;
	}

	/**
	 * Returns the abschluss2
	 *
	 * @return string $abschluss2
	 */
	public function getAbschluss2() {
		return $this->abschluss2;
	}

	/**
	 * Sets the abschluss2
	 *
	 * @param string $abschluss2
	 * @return void
	 */
	public function setAbschluss2($abschluss2) {
		$this->abschluss2 = $abschluss2;
	}

	/**
	 * Returns the deutschAbschlusstitel2
	 *
	 * @return string $deutschAbschlusstitel2
	 */
	public function getDeutschAbschlusstitel2() {
		return $this->deutschAbschlusstitel2;
	}

	/**
	 * Sets the deutschAbschlusstitel2
	 *
	 * @param string $deutschAbschlusstitel2
	 * @return void
	 */
	public function setDeutschAbschlusstitel2($deutschAbschlusstitel2) {
		$this->deutschAbschlusstitel2 = $deutschAbschlusstitel2;
	}

	/**
	 * Returns the berufserfahrung2
	 *
	 * @return string $berufserfahrung2
	 */
	public function getBerufserfahrung2() {
		return $this->berufserfahrung2;
	}

	/**
	 * Sets the berufserfahrung2
	 *
	 * @param string $berufserfahrung2
	 * @return void
	 */
	public function setBerufserfahrung2($berufserfahrung2) {
		$this->berufserfahrung2 = $berufserfahrung2;
	}

	/**
	 * Returns the deutscherReferenzberuf2
	 *
	 * @return string $deutscherReferenzberuf2
	 */
	public function getDeutscherReferenzberuf2() {
		return $this->deutscherReferenzberuf2;
	}

	/**
	 * Sets the deutscherReferenzberuf2
	 *
	 * @param string $deutscherReferenzberuf2
	 * @return void
	 */
	public function setDeutscherReferenzberuf2($deutscherReferenzberuf2) {
		$this->deutscherReferenzberuf2 = $deutscherReferenzberuf2;
	}

	/**
	 * Returns the wunschberuf2
	 *
	 * @return string $wunschberuf2
	 */
	public function getWunschberuf2() {
		return $this->wunschberuf2;
	}

	/**
	 * Sets the wunschberuf2
	 *
	 * @param string $wunschberuf2
	 * @return void
	 */
	public function setWunschberuf2($wunschberuf2) {
		$this->wunschberuf2 = $wunschberuf2;
	}

	/**
	 * Returns the originalDokumenteAbschluss1
	 *
	 * @return int $originalDokumenteAbschluss1
	 */
	public function getOriginalDokumenteAbschluss1() {
		return $this->originalDokumenteAbschluss1;
	}

	/**
	 * Sets the originalDokumenteAbschluss1
	 *
	 * @param int $originalDokumenteAbschluss1
	 * @return void
	 */
	public function setOriginalDokumenteAbschluss1($originalDokumenteAbschluss1) {
		$this->originalDokumenteAbschluss1 = $originalDokumenteAbschluss1;
	}

	/**
	 * Returns the originalDokumenteAbschluss2
	 *
	 * @return int $originalDokumenteAbschluss2
	 */
	public function getOriginalDokumenteAbschluss2() {
		return $this->originalDokumenteAbschluss2;
	}

	/**
	 * Sets the originalDokumenteAbschluss2
	 *
	 * @param int $originalDokumenteAbschluss2
	 * @return void
	 */
	public function setOriginalDokumenteAbschluss2($originalDokumenteAbschluss2) {
		$this->originalDokumenteAbschluss2 = $originalDokumenteAbschluss2;
	}

	

}