<?php
namespace Ud\Iqtp13db\Helper;
use \Datetime;

/***
 *
 * This file is part of the "IQ Webapp Anerkennungserstberatung" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2022 Uli Dohmen <edv@whkt.de>, WHKT
 *
 ***/

/**
 * Generalhelper
 */
class Generalhelper
{
    //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($post);
    //die();
    
    /**
     *  Aus den eingegebenen Parametern des Ratsuchenden die zugeordnete Beratungsstellen-ID zuweisen
     */
    function getNiqberatungsstellenid(\Ud\Iqtp13db\Domain\Model\Teilnehmer $teilnehmer, $usergroups, $standardniqbid) {

        if($teilnehmer->getSonstigerstatus()[0] == '1' || $teilnehmer->getSonstigerstatus()[0] == '2') return $standardniqbid;
        
        foreach ($usergroups as $group) {
            // Zuerst Keywords checken:
            $keywordarray = $group->getKeywordlist();
            foreach($keywordarray as $keyword) {
                if($keyword != '') if(strpos($teilnehmer->getNachname(), $keyword)) return $group->getNiqbid();
            }
            
            // Wenn kein Treffer, dann PLZ checken:
            $plzlistarray = $group->getPlzlist();
            foreach($plzlistarray as $plz) {
                if($plz == trim($teilnehmer->getPlz())) return $group->getNiqbid();
            }           
        }        
        
        return $standardniqbid;
    }
    
    
    /**
     *  Aus den eingegebenen Parametern des Ratsuchenden die zugeordnete Beratungsstellenmail zuweisen
     */
    function getGeneralmailBeratungsstelle($niqbid, $usergroups, $standardbccmail) {
        foreach ($usergroups as $group) {
            if($group->getNiqbid() == $niqbid) {
                return $group->getGeneralmail() == '' ? $standardbccmail : $group->getGeneralmail();                        
            }
        }        
        return $standardbccmail;
    }
    
    public function sanitizeFileFolderName($name)
    {
        /*
        // Remove special accented characters - ie. sí.
        $fileName = strtr($name, array('Š' => 'S','Ž' => 'Z','š' => 's','ž' => 'z','Ÿ' => 'Y','À' => 'A','Á' => 'A','Â' => 'A','Ã' => 'A','Ä' => 'A','Å' => 'A','Ç' => 'C','È' => 'E','É' => 'E','Ê' => 'E','Ë' => 'E','Ì' => 'I','Í' => 'I','Î' => 'I','Ï' => 'I','Ñ' => 'N','Ò' => 'O','Ó' => 'O','Ô' => 'O','Õ' => 'O','Ö' => 'O','Ø' => 'O','Ù' => 'U','Ú' => 'U','Û' => 'U','Ü' => 'U','Ý' => 'Y','à' => 'a','á' => 'a','â' => 'a','ã' => 'a','ä' => 'a','å' => 'a','ç' => 'c','è' => 'e','é' => 'e','ê' => 'e','ë' => 'e','ì' => 'i','í' => 'i','î' => 'i','ï' => 'i','ñ' => 'n','ò' => 'o','ó' => 'o','ô' => 'o','õ' => 'o','ö' => 'o','ø' => 'o','ù' => 'u','ú' => 'u','û' => 'u','ü' => 'u','ý' => 'y','ÿ' => 'y'));
        $fileName = strtr($fileName, array('Þ' => 'TH', 'þ' => 'th', 'Ð' => 'DH', 'ð' => 'dh', 'ß' => 'ss', 'Œ' => 'OE', 'œ' => 'oe', 'Æ' => 'AE', 'æ' => 'ae', 'µ' => 'u'));
        $clean_name = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $fileName);
        */
        // Statt oben einzelne Buchstaben zu konvertieren, nutze die Funktion transliterate, um alle Schritzeichen in lateinische Buchstaben zu konvertieren (auch kyrillische, arabische, farsi Schriftzeichen)
        $clean_name = transliterator_transliterate('Any-Latin; Latin-ASCII;', $name);
        
        return $clean_name;
    }
    
    function getFolderSize($folderpath) {
        $io = popen ( '/usr/bin/du -sk ' . $folderpath, 'r' );
        $size = fgets ( $io, 4096);
        $size = substr ( $size, 0, strpos ( $size, "\t" ) );
        pclose ( $io );
        
        return $size;
    }
    
    
    function getTP13Storage($storages) {        
        // Speicher 'iqwebappdata' muss im Typo3-Backend auf der Root-Seite als "Dateispeicher" angelegt sein!
        // wenn der Speicher mal nicht verfügbar war (temporär), muss er im Backend im Bereich "Dateispeicher" manuell wieder "online" geschaltet werden mit der Checkbox "ist online?" in den Eigenschaften des jeweiligen Dateispeichers        
        foreach ( $storages as $s ) {
            $storageObject = $s;
            $storageRecord = $storageObject->getStorageRecord ();
            
            if ($storageRecord ['name'] == 'iqwebappdata') {
                $storage = $s;
                break;
            } 
        }
        
        return $storage;
    }
    
    public function createFolder($teilnehmer, $standardniqidberatungsstelle, $allusergroups, $storages)
    {
        $pfadname = $teilnehmer->getNachname() . '_' . $teilnehmer->getVorname() . '_' . $teilnehmer->getUid(). '/';
        $niqbid = $teilnehmer->getNiqidberatungsstelle();
        $beratungsstellenfolder = $niqbid == '10143' ? 'Beratene' : $niqbid;
        $clean_path = $beratungsstellenfolder. '/' . $this->sanitizeFileFolderName($pfadname);
        $storage = $this->getTP13Storage($storages);
        
        if (!$storage->hasFolder($clean_path)) {
            $targetFolder = $storage->createFolder($clean_path);
        } else {
            $targetFolder = $storage->getFolder($clean_path);
        }
        
        return $targetFolder;
    }
  
    function validateDate($date, $format = 'd.m.Y')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    
    function validateDateYmd($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    
}
