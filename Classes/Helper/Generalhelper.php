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
     * @param array $recipient recipient of the email in the format array('recipient@domain.tld' => 'Recipient Name')
     * @param array $bcc
     * @param array $sender
     * @param $subject
     * @param $templateName
     * @param array $variables
     * @param $addattachment
     * @return boolean TRUE on success, otherwise false
     */
    function sendTemplateEmail(array $recipient, array $bcc, array $sender, $subject, $templateName, array $variables = array(), $emailView, $controlleruribuilder, $extbaseFrameworkConfiguration)
    {
        $templateRootPath = end($extbaseFrameworkConfiguration['view']['templateRootPaths']);
        $templatePathAndFilename = $templateRootPath . 'Teilnehmer/' . $templateName . '.html';
        
        $emailView->setTemplatePathAndFilename($templatePathAndFilename);
        $emailView->assignMultiple($variables);
        $emailBody = $emailView->render();
        
        $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Mail\MailMessage::class);
        $message->to(new \Symfony\Component\Mime\Address($recipient[0]))->from(new \Symfony\Component\Mime\Address($sender[0]));
        $message->subject($subject);
        if($templateName != 'Mailtoconfirm' && $bcc[0] != '') $message->bcc(new \Symfony\Component\Mime\Address($bcc[0]));
        
        // HTML Email
        $message->html($emailBody);
        
        // Text Part
        // Mail mit Bestätigungslink
        if($templateName == 'Mailtoconfirm') {
            $teilnehmer = $variables['teilnehmer'];
            
            $uriBuilder = $controlleruribuilder;
            $uriBuilder->reset();
            $uriBuilder->uriFor('confirm', array('code' => $teilnehmer->getVerificationcode(), 'askconsent' => $variables['askconsent']), 'Teilnehmer', 'iqtp13db', 'Iqtp13dbwebapp');
            $uriBuilder->setTargetPageUid($variables['registrationpageuid']);
            $uriBuilder->setCreateAbsoluteUri(TRUE);
            $link = $uriBuilder->build();
            
            $mailtext = $variables['confirmmailtext1']."\r\n".
                $link."\r\n\r\n".
                $variables['confirmmailtext2'] .
                "\r\n------------------------------\r\n\r\n".
                $variables['kontaktlabel'] ."\r\n\r\n".
                $variables['datenberatungsstelle'].
                "\r\n------------------------------";
                
                $message->text(str_replace("</p>", "\r\n",(str_replace(["<p>", "<br>"], "", $mailtext))));
        }
        // Mail nach Bestätigung
        if($templateName == 'Mail') {
            $mailtext = $variables['anrede'] ."\r\n".
                $variables['mailtext'] .
                "\r\n------------------------------\r\n\r\n".
                $variables['kontaktlabel'] ."\r\n\r\n".
                $variables['datenberatungsstelle'].
                "\r\n------------------------------";
                
                $message->text(str_replace("</p>", "\r\n",(str_replace(["<p>", "<br>"], "", $mailtext))));
        }
        $message->send();
        
        return $message->isSent();
    }
    
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
    
    public function sanitizeFileFolderName($name)
    {
        /*
        // Remove special accented characters - ie. sí.
        $fileName = strtr($name, array('Š' => 'S','Ž' => 'Z','š' => 's','ž' => 'z','Ÿ' => 'Y','À' => 'A','Á' => 'A','Â' => 'A','Ã' => 'A','Ä' => 'A','Å' => 'A','Ç' => 'C','È' => 'E','É' => 'E','Ê' => 'E','Ë' => 'E','Ì' => 'I','Í' => 'I','Î' => 'I','Ï' => 'I','Ñ' => 'N','Ò' => 'O','Ó' => 'O','Ô' => 'O','Õ' => 'O','Ö' => 'O','Ø' => 'O','Ù' => 'U','Ú' => 'U','Û' => 'U','Ü' => 'U','Ý' => 'Y','à' => 'a','á' => 'a','â' => 'a','ã' => 'a','ä' => 'a','å' => 'a','ç' => 'c','è' => 'e','é' => 'e','ê' => 'e','ë' => 'e','ì' => 'i','í' => 'i','î' => 'i','ï' => 'i','ñ' => 'n','ò' => 'o','ó' => 'o','ô' => 'o','õ' => 'o','ö' => 'o','ø' => 'o','ù' => 'u','ú' => 'u','û' => 'u','ü' => 'u','ý' => 'y','ÿ' => 'y'));
        $fileName = strtr($fileName, array('Þ' => 'TH', 'þ' => 'th', 'Ð' => 'DH', 'ð' => 'dh', 'ß' => 'ss', 'Œ' => 'OE', 'œ' => 'oe', 'Æ' => 'AE', 'æ' => 'ae', 'µ' => 'u'));
        $clean_name = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $fileName);
        */
        // Statt oben einzelne Buchstaben zu konvertieren, nutze die Funktion transliterate, um alle Schritzeichen in lateinische Buchstaben zu konvertieren (auch kyrillische, arabische, farsi Schriftzeichen)
        $fileName = transliterator_transliterate('Any-Latin; Latin-ASCII;', $name);
        $clean_name = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $fileName);
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
    
    public function createFolder($teilnehmer, $storages)
    {
        $pfadname = $teilnehmer->getNachname() . '_' . $teilnehmer->getVorname() . '_' . $teilnehmer->getUid(). '/';
        $niqbid = $teilnehmer->getNiqidberatungsstelle();
        $clean_path = $niqbid. '/' . $this->sanitizeFileFolderName($pfadname);
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
    
    function human_filesize($bytes, $decimals = 2) {
        $factor = floor((strlen($bytes) - 1) / 3);
        if ($factor > 0) $sz = 'KMGT';
        return sprintf("%.{$decimals}f ", $bytes / pow(1024, $factor)) . @$sz[$factor - 1] . 'B';
    }
}
