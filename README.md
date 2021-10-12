# Installationsanweisungen Webapp Anerkennungsberatung des IQ Netzwerks NRW
URL: https://www.iq-netzwerk-nrw.de/startseite <br>
Author: Ulrich Dohmen<br>
Mail: udohmen@whkt.de<br>

<h3>Version 2.1.1 </h3>

<h2>Systemvoraussetzung</h2>
<ul>
 <li>TYPO3 9.5+</li>
 <li>ausreichend Speicherplatz für die Anhänge der Beratenen/Ratsuchenden; im Schnitt je Beratene(n) etwa 5 MB</li>
 <li>der Webserver muss E-Mails versenden können</li>
</ul>

<h2>Installation</h2>
<ol>
<li>Für Mehrsprachigkeit müssen in Typo3 auf der Root-Seite die entsprechenden Website-Sprachen angelegt und anschließend im Modul Seitenverwaltung->Seiten in der Seitenkonfiguration im Reiter "Sprachen" der Seite hinzugefügt werden. Ggf. macht es Sinn die Standardsprache (ID=0) auf Deutsch umzustellen, sofern noch nicht erfolgt.<br>Folgende Sprachen werden derzeit von der Extension unterstützt: Englisch, Arabisch, Persisch (Farsi), Französisch, Polnisch, Rumänisch, Russisch, Spanisch, Türkisch. Beim Anlegen der Sprachen daran denken, dass Arabisch und Farsi RTL-Sprachen sind (Right-to-Left) und in der Seitenverwaltung diese Option aktiviert werden muss.<br>
Die Identifizierung der Sprachen erfolgt in Typo3 bzw. der Extension über das 2-Buchstaben-Sprachkürzel (z.B. de für Deutsch oder fa für Farsi).
Alle Schritte hinsichtlich Mehrsprachigkeit folgen sonst den Standard-Regeln zur Einrichtung von mehrsprachigen Webseiten.<br></li>

<li>Zunächst auf der github-Seite oben rechts auf "Clone or Download" und dann auf "Download ZIP" klicken und die Extension als ZIP herunterladen, und in das Verzeichnis typo3conf/ext/ entpacken. Das Verzeichnis muss dann noch in "iqtp13db" umbenannt werden. Die Installation der Extension erfolgt danach per Extension Manager. <br></li>

<li>Nach der Installation muss das statische Template der Extension <b>IQ TP13 Webapp Annerkennungsberatung NRW (iqtp13db)</b> in das Root Template aufgenommen werden.<br></li>

<li>Nun sollte die folgende Seitenstruktur eingerichtet werden: <br>

<b>
* Startseite Webapp<br>
-- Anmeldung<br>
-- Anmeldung versendet<br>
-- Danke<br>
-- Einwilligungsanforderung bestätigt
-- Fehler bei der Anmeldung<br>
* Datenschutz und Einwilligung
* Login interner Bereich<br>
* Interner Bereich<br>
-- Übersicht<br>
-- Angemeldet<br>
-- Erstberatung<br>
-- NIQ Erfassung<br>
-- Archiv<br>
-- Export<br>
-- Berater*in<br>
-- Gelöscht<br>
* [DB] Daten Anerkennungsberatung<br>
* [DB] Benutzer<br>
 </b>

Der System-Ordner <b>[DB] Daten Anerkennungsberatung</b> enthält die Berater, Ratsuchenden (aka Teilnehmer) inkl. Historie, Dokumente und Beratungsvorgänge inkl. Folgekontakte. </li>

<li>Im Typo3-Backend auf der Root-Seite muss "tp13data" als <b>Dateispeicher</b> angelegt sein. Der Pfad muss auf ein Verzeichnis auf dem Server verweisen. Das kann z.B. fileadmin/tp13data/ als relativer Pfad sein, welcher aber geschützt werden muss, damit ein Zugriff von Extern außerhalb der Webapp nicht möglich ist. Dies muss vor Live-Schaltung unbedingt getestet werden.<br>
<i>Zusätzlicher Hinweis zum Speicher: Wenn der Speicher mal nicht verfügbar war (z.B. wenn er auf einem NAS liegt), muss er im Backend im Bereich Dateispeicher manuell wieder "online" geschaltet werden. Die erfolgt mit der Checkbox "ist online?" in den Eigenschaften des jeweiligen Dateispeichers.)</i>
In diesen Dateispeicher werden die Dateien der Ratsuchenden gespeichert, die während des Anmeldevorgangs hochgeladen werden können. Für jeden Ratsuchenden wird ein Verzeichnis erstellt, dass mit dem Format [Nachname]_[Vorname]_[UID] angelegt wird.<br></li>
</ol>
<br>
<h2>Typoscript Template Setup</h2> 
Im Typoscript Template müssen im Setup folgende Werte eingetragen werden:<br>
<br>
<b>plugin.tx_iqtp13db {<br>
&nbsp;&nbsp;settings {<br>
&nbsp;&nbsp;&nbsp;&nbsp;sender = (E-Mail-Adresse des Absenders der automatisch erstellten E-Mails nach der Anmeldung)<br>
&nbsp;&nbsp;&nbsp;&nbsp;bccmail = (BCC-E-Mail-Adresse der Anmeldungsbestätigung)<br>
&nbsp;&nbsp;&nbsp;&nbsp;startseitelink = (Link zur Startseite der Webapp)<br>
&nbsp;&nbsp;&nbsp;&nbsp;logolink = (Link zum Logo der Webapp für den Kopf der E-Mail-Bestätigung)<br>
&nbsp;&nbsp;&nbsp;&nbsp;registrationpageuid = (ID der Anmeldungsseite - wird für die aus dem Backend angestossene Einwilligung benötigt)><br>
&nbsp;&nbsp;}<br>
&nbsp;&nbsp;_LOCAL_LANG.de {
&nbsp;&nbsp;&nbsp;&nbsp;confirmsubject = (Betreffzeile der E-Mail nach versendeter Anmeldung mit der Bitte um Bestätigung der Anmeldung)<br>
&nbsp;&nbsp;&nbsp;&nbsp;confirmmailtext2 = (Grußformel im Text der E-Mail nach versendeter Anmeldung mit der Bitte um Bestätigung der Anmeldung)<br>
&nbsp;&nbsp;&nbsp;&nbsp;subject = (Betreffzeile der E-Mail nach bestätigter Anmeldung)<br>
&nbsp;&nbsp;&nbsp;&nbsp;mailtext = (Text der E-Mail nach bestätigter Anmeldung)<br>
&nbsp;&nbsp;&nbsp;&nbsp;tx_iqtp13db_domain_model_teilnehmer.einwilligungtext = (Text inkl. Link zur Datenschutzseite des Einwilligungshäkchens auf der ersten Seite der Anmeldung)<br>
&nbsp;&nbsp;}<br>
}<br>
</b><br>
<br>

Um die Texte bzw. Beschriftungen im Formular anzupassen, können die entsprechend angepassten Texte im Typoscript Template angepasst werden per:<br>
<b>plugin.tx_iqtp13db._LOCAL_LANG.[sprachcode].[tabelle].[wert] = [text]</b><br>
Hier können auch html-Tags genutzt werden, um z.B. Zeilenumbrüche per < br > einzufügen.<br>
<br>
Bitte Einwilligungstexte für jede Sprache anpassen, da ansonsten immer der Link zu den Datenschutzbestimmungen des IQ Netzwerks NRW angegeben ist.<br>
<br>
Alle verfügbaren Variablen sind in den Language-Files der Extension zu finden. Insbesondere die Texte für die E-Mail an die/den Ratsuchende(n) sollten angepasst werden.<br>


<h2>Hinweise zu den verschiedenen Unterseiten</h2>

Die Startseite enthält nur einen einleitenden Text und einen Link auf die Seite „Anmelden“.<br>

Auf der Seite <b>Anmeldung</b> ist das Plug-In <b>IQ TP13 DB Webapp</b> eingebunden. Als Typ bei Erweiterungsoptionen ist hier "Anmeldung" ausgewählt. Die <b>Anmelden Ende-Seite</b> wird aufgerufen, nachdem die Registrierung vom Ratsuchenden bestätigt wurde; in der oben angegebenen Seitenstruktur wäre das die Seite "Danke". Die Seite "Einwilligungsanforderung bestätigt" wird aufgerufen, wenn im Backend bei einem Teilnehmer per entsprechendem Button eine Einwilligung angefordert wird. Die weiteren Felder beziehen sich auf die E-Mails (Anfrage Registrierungsbestätigung und danach Anmeldebestätigung), die an den Ratsuchenden gehen. Die <b>Seite Bestätigungsmail angefordert</b> wird aufgerufen, nachdem der Ratsuchende in der Webapp Absenden angeklickt hat; in der o.a. Struktur ist das die Seite "Anmeldung versendet". Bei fehlerhafter Validierung (z.B. Link in der E-Mail defekt oder Eintrag nicht mehr vorhanden) erscheint die Seite, die unter <b>Seite Validierung fehlgeschlagen</b> angegeben ist; im Beispiel die Seite "Fehler". Über die Felder <b>Wartung von, Wartung bis</b> und <b>Wartung Text</b> werden genutzt, um die Anmeldung temporär zu deaktivieren und stattdessen den <b>Wartung Text</b> anzuzeigen.<br>
Auf der Seite Datenschutz und Einwilligung sind die entsprechenden Datenschutzbestimmungen als normales Content-Element eingefügt. Unter Datensatzsammlung muss der Sysordner (im Beispiel oben „[DB] Daten Anerkennungsberatung“) ausgewählt werden. In diesen werden alle Daten der Beratenen gespeichert. Damit ist das Plugin konfiguriert und der Fragebogen zur Anmeldung sollte angezeigt werden.<br>

Für das Backend (oder "Interner Bereich") muss eine Login-Seite für interne Nutzer angelegt werden. Diese enthält ein TYPO3-Inhaltselement vom Typ Formular/Anmeldung. Die Berechtigung für den Internen Bereich wird über den Zugriff der Seite gesetzt. Die zugehörigen Backend-User werden im System-Ordner <b>[DB] Benutzer</b> angelegt und sind ganz normale Website-Benutzer. Weitere Informationen dazu findet man, wenn man nach "Typo3 Zugriffsgeschützte Bereiche im Frontend" googelt.
Im Internen Bereich werden Seiten entsprechend der Seitenstruktur angelegt, die jeweils ein Plugin <b>IQ TP13 DB Adminbereich</b> enthalten. Der Typ wird entsprechend dem Seitennamen eingestellt.
Auch hier wird jeweils unter Datensatzsammlung der Sysordner (im Beispiel oben „[DB] Daten Anerkennungsberatung“) ausgewählt. Mindestens ein Datensatz vom Typ <b>Berater</b> <u>muss</u> angelegt sein, damit eine Anmeldung erfolgen kann. Das Kürzel des Beraters muss dem Typo3-Backend Benutzernamen entsprechen. Berater:innen legt man auf der Seite Berater*in an. <br><br>

<h2>Eigene Templates</h2>

Um eigene Templates zu verwenden bzw. die vorhandenen anzupassen, kann das Verzeichnis Resources/Private aus der Extension in ein eigenes Templates-Verzeichnis auf fileadmin kopiert werden. Der Pfad zu den angepassten Template-Dateien wird im Typoscript-Template dann mit den folgenden Zeilen geändert:<br>
<b>
<br>
plugin.tx_iqtp13db_iqtp13dbwebapp.view {<br>
&nbsp;&nbsp;templateRootPaths.10 = <eigener_template_pfad>/Resources/Private/Templates/<br>
&nbsp;&nbsp;partialRootPaths.10 = <eigener_template_pfad>/Resources/Private/Partials/<br>
&nbsp;&nbsp;layoutRootPaths.10 = <eigener_template_pfad>/Resources/Private/Layouts/ <br>
}</b>

