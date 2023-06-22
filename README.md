# Installationsanweisungen Webapp Anerkennungsberatung des IQ Netzwerks
URL: https://www.iq-netzwerk-nrw.de<br>
Author: Ulrich Dohmen<br>
Mail: udohmen@whkt.de<br>
 
<h3>Version 5.0.1 </h3>

<h2>Systemvoraussetzung</h2>
<ul>
 <li>TYPO3 10.4.99 - 11.5.99</li>
 <li>ausreichend Speicherplatz für die Anhänge der Ratsuchenden; im Schnitt je Ratsuchenden etwa 5 MB</li>
 <li>der Webserver muss E-Mails versenden können</li>
 <li>TYPO3 Extension lib_jquery (oder alternativ jQuery händisch einbinden)</li>
 <li>TYPO3 Extension web2pdf (oder alternativ mit composer mpdf installieren)</li>
 <li>TYPO3 Extension fal-securedownload (Installationshinweise: https://jweiland.net/typo3/showcase/fal-securedownload.html )</li>
</ul>

<h2>Installation</h2>
Sofern TYPO3 noch nicht installiert ist, zuerst eine leere TYPO3-Installation einrichten. Dann im Modul Erweiterungen bei den vorkonfigurierten Erweiterungen das "Official TYPO3 Introduction Package" installieren. Im Modul Seiten das IntroPackage als Root setzen, dazu den Einstiegspunkt '/', ggf. andere Seite vorher löschen. Außerdem hier den ErrorHandler 403 als Weiterleitung auf die Login-Seite (s.u.) setzen.
<ol>
<li>Für Mehrsprachigkeit müssen in Typo3 auf der Root-Seite die entsprechenden Website-Sprachen angelegt und anschließend im Modul Seitenverwaltung->Seiten in der Seitenkonfiguration im Reiter "Sprachen" der Seite hinzugefügt werden. Für alle Sprachen sollte Englisch als Fallback-Sprache eingerichtet werden. Auf den Seiten "Anmeldung versendet", "Danke", etc. sollte dann zu der deutschen Seite eine englische Übersetzung angelegt werden.<br>Folgende Sprachen werden derzeit von der Extension unterstützt: Englisch, Arabisch, Persisch (Farsi), Französisch, Polnisch, Rumänisch, Russisch, Spanisch, Türkisch, Ukrainisch. Beim Anlegen der Sprachen daran denken, dass Arabisch und Farsi RTL-Sprachen sind (Right-to-Left) und in der Seitenverwaltung diese Option aktiviert werden muss.<br>
Die Identifizierung der Sprachen erfolgt in Typo3 bzw. der Extension über das 2-Buchstaben-Sprachkürzel (z.B. de für Deutsch oder fa für Farsi).
Alle Schritte hinsichtlich Mehrsprachigkeit folgen sonst den Standard-Regeln zur Einrichtung von mehrsprachigen Webseiten.<br></li>

<li>Zunächst auf der github-Seite oben rechts auf "Clone or Download" und dann auf "Download ZIP" klicken und die Extension als ZIP herunterladen, und in das Verzeichnis typo3conf/ext/ entpacken. Das Verzeichnis muss dann noch in "iqtp13db" umbenannt werden. Die Installation der Extension erfolgt danach per Extension Manager. <br></li>

<li>Nach der Installation müssen das statische Template der Extensions <b>IQ Webapp Annerkennungsberatung NRW (iqtp13db)</b>, <b>JS Library: jQuery</b> (sofern installiert, s.o.) und <b>Web2PDF Generator</b> (sofern installiert s.o.) in das Root Template aufgenommen werden.<br></li>

<li>Nun sollte die folgende Seitenstruktur eingerichtet werden: <br>

<b>
* Startseite Webapp<br>
-- Anmeldung<br>
-- Anmeldung versendet<br>
-- Danke<br>
-- Einwilligungsanforderung bestätigt<br>
-- Fehler bei der Anmeldung<br>
* Datenschutz und Einwilligung<br>
* Login interner Bereich<br>
* Interner Bereich<br>
-- Übersicht<br>
-- Anmeldung<br>
-- Erstberatung<br>
-- Archiv<br>
-- Export<br>
-- Berater*in<br>
-- Gelöscht<br>
* [DB] Daten Anerkennungsberatung<br>
* [DB] Benutzer<br>
 </b>

Der System-Ordner <b>[DB] Daten Anerkennungsberatung</b> enthält die Ratsuchenden (aka Teilnehmer) inkl. Historie, Dokumente und Beratungsvorgänge inkl. Folgekontakte. </li>

<li>Im Typo3-Backend auf der Root-Seite muss "iqwebappdata" als <b>Dateispeicher</b> angelegt sein. Der Pfad muss auf ein Verzeichnis auf dem Server verweisen. Das kann z.B. /mnt/iqwebappdata/ als absoluter Pfad oder alternativ ein externer Speicher mit entsprechend angegebenen Mount-Punkt sein. So oder so muss der Pfad aber geschützt werden, damit ein Zugriff von Extern außerhalb der Webapp nicht möglich ist. Dies muss vor Live-Schaltung unbedingt getestet werden.<br>
<i>Zusätzlicher Hinweis zum Speicher: Wenn der Speicher mal nicht verfügbar war (z.B. wenn er auf einem NAS liegt), muss er im Backend im Bereich Dateispeicher manuell wieder "online" geschaltet werden. Die erfolgt mit der Checkbox "ist online?" in den Eigenschaften des jeweiligen Dateispeichers.)</i><br>
In diesem Dateispeicher wird für jede Beratungsstelle ein Ordner angelegt, dessen Name die von f-bb vergebene Beratungsstellen-ID ist. In diesem Ordner werden die Dateien der Ratsuchenden gespeichert, die während des Anmeldevorgangs hochgeladen werden können. Für jeden Ratsuchenden wird ein Verzeichnis erstellt, dass mit dem Format [Nachname]_[Vorname]_[UID] angelegt wird.<br></li>
</ol>
<br>
<h2>Typoscript Template Setup</h2>
Im Typoscript Template müssen im Setup mindestens folgende Werte eingetragen werden:<br>
<br>
<small><b>plugin.tx_iqtp13db {<br>
&nbsp;&nbsp;settings {<br>
&nbsp;&nbsp;&nbsp;&nbsp;sender = (E-Mail-Adresse des Absenders der automatisch erstellten E-Mails nach der Anmeldung)<br>
&nbsp;&nbsp;&nbsp;&nbsp;startseitelink = (Link zur Startseite der Webapp)<br>
&nbsp;&nbsp;&nbsp;&nbsp;logolink = (Link zum Logo der Webapp für den Kopf der E-Mail-Bestätigung)<br>
&nbsp;&nbsp;&nbsp;&nbsp;datenschutzeinwilligungurl = (URL zu den Datenschutzinformationen)<br>
&nbsp;&nbsp;&nbsp;&nbsp;registrationpageuid = (ID der Anmeldungsseite - wird für die aus dem Backend angestossene Einwilligung benötigt)<br>
&nbsp;&nbsp;&nbsp;&nbsp;standardberatungort = (Hier kann der Standard-Standort für die Beratung angegeben werden. Dieser Wert wird an die NIQ-DB übertragen)<br>
&nbsp;&nbsp;&nbsp;&nbsp;beraterstoragepid = (ID der [DB] Berater)<br>
&nbsp;&nbsp;&nbsp;&nbsp;standardbccmail = (Standard-BCC-E-Mail-Adresse der Anmeldebestätigung)<br>
&nbsp;&nbsp;&nbsp;&nbsp;standardniqidberatungsstelle = (Standard NIQ-Beratungsstellen-ID - s.u.)<br>
&nbsp;&nbsp;}<br>
&nbsp;&nbsp;_LOCAL_LANG {<br>
&nbsp;&nbsp;&nbsp;&nbsp;de {<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;confirmsubject = (Betreffzeile der E-Mail nach versendeter Anmeldung mit der Bitte um Bestätigung der Anmeldung)<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;confirmmailtext2 = (Grußformel im Text der E-Mail nach versendeter Anmeldung mit der Bitte um Bestätigung der Anmeldung)<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;subject = (Betreffzeile der E-Mail nach bestätigter Anmeldung)<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;mailtext = (Text der E-Mail nach bestätigter Anmeldung)<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;tx_iqtp13db_domain_model_teilnehmer.einwilligungtext = (Text inkl. Link zur Datenschutzseite des Einwilligungshäkchens auf der ersten Seite der Anmeldung)<br>
&nbsp;&nbsp;&nbsp;&nbsp;}<br>
&nbsp;&nbsp;}<br>
}<br>
</b></small>
Für jede weitere Sprache die genutzt werden soll, müssen die Texte entsprechend angepasst werden. D.h. es muss der Block mit <b>de</b> kopiert und mit dem jeweiligen Länderkürzel mit entsprechenden Texten eingefügt werden. Als Vorlage dienen hier die Standard-Texte in den Language-Dateien der Extension.<br>
<br>
Um weitere Texte bzw. Beschriftungen im Formular zu ändern, können diese nach folgendem Schema angepasst werden:<br>
<b>plugin.tx_iqtp13db._LOCAL_LANG.[sprachcode].[tabelle].[wert] = [text]</b><br>
Hier können auch html-Tags genutzt werden, um z.B. Zeilenumbrüche per < br > einzufügen.<br>
<br>
Alle verfügbaren Variablen sind in den Language-Files der Extension zu finden.<br>

<h2>Hinweise zu den verschiedenen Unterseiten</h2>

Die Startseite enthält nur einen einleitenden Text und einen Link auf die Seite „Anmelden“.<br>

Auf der Seite <b>Anmeldung</b> ist das Plug-In <b>IQ TP13 DB Webapp</b> eingebunden. Als Typ bei Erweiterungsoptionen ist hier "Anmeldung" ausgewählt. <br>
Die <b>Anmelden Ende-Seite</b> wird aufgerufen, nachdem die Registrierung vom Ratsuchenden bestätigt wurde; in der oben angegebenen Seitenstruktur wäre das die Seite "Danke". <br>
Die Seite "Einwilligungsanforderung bestätigt" wird aufgerufen, wenn im Backend bei einem Teilnehmer per entsprechendem Button eine Einwilligung angefordert wird. <br>
<br>
Die weiteren Felder beziehen sich auf die E-Mails (Anfrage Registrierungsbestätigung und danach Anmeldebestätigung), die an den Ratsuchenden gehen. Die <b>Seite Bestätigungsmail angefordert</b> wird aufgerufen, nachdem der Ratsuchende in der Webapp Absenden angeklickt hat; in der o.a. Struktur ist das die Seite "Anmeldung versendet". <br>
Bei fehlerhafter Validierung (z.B. Link in der E-Mail defekt oder Eintrag nicht mehr vorhanden) erscheint die Seite, die unter <b>Seite Validierung fehlgeschlagen</b> angegeben ist; im Beispiel die Seite "Fehler". <br>
Über die Felder <b>Wartung von, Wartung bis</b> und <b>Wartung Text</b> werden genutzt, um die Anmeldung temporär zu deaktivieren und stattdessen den <b>Wartung Text</b> anzuzeigen.<br>
Auf der Seite Datenschutz und Einwilligung sind die entsprechenden Datenschutzbestimmungen als normales Content-Element eingefügt. Unter Datensatzsammlung muss der Sysordner (im Beispiel oben „[DB] Daten Anerkennungsberatung“) ausgewählt werden. In diesen werden alle Daten der Beratenen gespeichert. <br>
Damit ist das Plugin konfiguriert und der Fragebogen zur Anmeldung sollte angezeigt werden.<br>

Für das Backend (oder "Interner Bereich") muss eine Login-Seite für interne Nutzer angelegt werden. Diese enthält ein TYPO3-Inhaltselement vom Typ Formular/Anmeldung. Die Berechtigung für den Internen Bereich wird über den Zugriff der Seite gesetzt. <br>
Die zugehörigen Beratungsstellen und Berater werden als Website-Benutzergruppe und Website-Benutzer im System-Ordner <b>[DB] Benutzer</b> angelegt und sind ganz normale Frontend Gruppen bzw. Benutzer. Je Beratungsstelle wird eine Benutzergruppe angelegt werden. Jede Gruppe muss im Reiter Erweitert des Gruppendatensatzes im Feld "NIQ Beratungsstellen-ID" die von der NIQ bzw. f-bb vergebene Beratungsstellen-ID hinterlegt und im Feld "Allgemeine Mailadresse" die E-Mail-Adresse, an die die Kopien der Anmeldebestätigungen gehen, eingetragen haben. Wenn diese Werte fehlen, werden die Standard-Werte aus den Settings verwendet.<br>
<br>Außerdem können in den Feldern "Liste PLZ..." und "Liste Keywords..." weitere Werte eingetragen werden. Das Feld "Liste PLZ..." enthält eine Komma-separierte Liste von Postleitzahlen, für die diese Beratungsstelle sozusagen zuständig ist. Sofern Ratsuchende das Feld PLZ korrekt ausgefüllt haben, landet die Anfrage automatisch nur bei der entsprechenden Beratungsstellen-Gruppe. 
<br>Die gleiche Funktionalität bietet das Feld "Liste Keywords...", wobei hier auf Schlüsselbegriffe im Feld Nachname geprüft wird. Sofern im Feld "Nachname" also beispielsweise "Mustermann (Sonderstatus)" eingetragen wurde und bei der Beratungsstellengruppe das Keyword "(Sonderstatus)" (inkl. Klammern!) eingetragen ist, landet diese Anfrage automatisch nur bei der entsprechenden Beratungsstellen-Gruppe.<br>
<br>Im Feld "Beratungsarten" werden die von der Beratungsstelle angebotenen Beratungsarten (mit gedrückter STRG- oder CMD-Taste) ausgewählt. Diese können auf Seite 3 der Anmeldung von Ratsuchenden als Option ausgewählt werden.<br><br> 

Im Internen Bereich werden Seiten entsprechend der Seitenstruktur angelegt, die jeweils ein Plugin <b>IQ TP13 DB Adminbereich</b> enthalten. Der Typ wird entsprechend dem Seitennamen eingestellt.<br>
Auch hier wird jeweils unter Datensatzsammlung der Sysordner (im Beispiel oben „[DB] Daten Anerkennungsberatung“) ausgewählt.<br>
Mindestens eine Website-Benutzergruppe und ein Website-Benutzer <u>muss</u> angelegt sein, damit eine Anmeldung erfolgen kann. Weitere Berater:innen legt man auf der Seite Berater*in an.<br><br>

Informationen zum Absichern von Unterseiten findet man, wenn man nach "Typo3 Zugriffsgeschützte Bereiche im Frontend" googelt.<br><br>

<h2>Eigene Templates</h2>

Um eigene Templates zu verwenden bzw. die vorhandenen anzupassen, kann das Verzeichnis Resources/Private aus der Extension in ein eigenes Templates-Verzeichnis auf fileadmin kopiert werden. Der Pfad zu den angepassten Template-Dateien wird im Typoscript-Template dann mit den folgenden Zeilen geändert:<br>
<b>
<br>
plugin.tx_iqtp13db_iqtp13dbwebapp.view {<br>
&nbsp;&nbsp;templateRootPaths.10 = <eigener_template_pfad>/Resources/Private/Templates/<br>
&nbsp;&nbsp;partialRootPaths.10 = <eigener_template_pfad>/Resources/Private/Partials/<br>
&nbsp;&nbsp;layoutRootPaths.10 = <eigener_template_pfad>/Resources/Private/Layouts/ <br>
}</b>
