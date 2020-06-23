# Installationsanweisungen Webapp Anerkennungsberatung des IQ Netzwerks NRW

<h3>Version 1.0.95</h3>

<h2>Systemvoraussetzung</h2>
-	TYPO3 8.7 oder höher<br>
-	ausreichend Speicherplatz für die Anhänge der Beratenen; im Schnitt je Beratene(r) etwa 5 MB<br>

<h2>Installation</h2>
<ol>
<li>Für Mehrsprachigkeit müssen in Typo3 auf der Root-Seite die entsprechenden Website-Sprachen angelegt werden. Folgende Sprachen werden von der Extension unterstützt und sollten am besten in dieser Reihenfolge angelegt werden: Englisch, Arabisch, Persisch (Farsi), Französisch, Polnisch, Rumänisch, Russisch, Spanisch, Türkisch.<br><br>
Sollten die Sprachen andere IDs haben, sollte das Typoscript ab der Zeile „SPRACHWAHL“ aus der Datei <b>typo3conf/ext/iqtp13db/Configuration/Typoscript/setup.typoscript</b> in das Typoscript-Template der Seite kopiert und entsprechend den eigenen IDs angepasst werden. Die Änderung in der o.g. Datei ist zwar möglich, allerdings muss dieser Schritt bei jedem Update der Extension wiederholt werden.
Alle Schritte hinsichtlich Mehrsprachigkeit folgen sonst den Standard-Regeln zur Einrichtung von mehrsprachigen Webseiten.<br></li>

<li>Zunächst auf der github-Seite oben rechts auf "Clone or Download" und dann auf "Download ZIP" klicken und die Extension als ZIP herunterladen, und in das Verzeichnis typo3conf/ext/ entpacken. Das Verzeichnis muss dann noch in "iqtp13db" umbenannt werden. Die Installation der Extension erfolgt danach per Extension Manager. <br></li>

<li>Nach der Installation muss das statische Template der Extension <b>IQ TP13 Datenbank Annerkennungsberatung NRW (iqtp13db)</b> in das Root Template aufgenommen werden.<br></li>

<li>Nun sollte die folgende Seitenstruktur eingerichtet werden: <br>

<b>
* Startseite Webapp<br>
-- Anmeldung<br>
-- Anmeldung versendet<br>
-- Danke<br>
-- Fehler bei der Anmeldung<br>
* Login interner Bereich<br>
* Interner Bereich<br>
-- Übersicht<br>
-- Beratene<br>
-- Berater<br>
* [DB] Daten Anerkennungsberatung<br>
* [DB] Benutzer<br>
 </b>
 
Der System-Ordner <b>[DB] Daten Anerkennungsberatung</b> enthält die Berater, Beratenen, Dokumente und Beratungsvorgänge. Mindestens ein Datensatz vom Typ <b>Berater</b> muss in diesem Ordner angelegt sein, damit eine Anmeldung erfolgen kann.<br></li>

<li>Im Typo3-Backend auf der Root-Seite muss "tp13data" als <b>Dateispeicher</b> angelegt sein. Der Pfad muss auf ein Verzeichnis auf dem Server verweisen. Das kann z.B. fileadmin/tp13data/ als relativer Pfad sein, welcher aber geschützt werden muss, damit ein Zugriff von Extern außerhalb der Webapp nicht möglich ist. Dies muss vor Live-Schaltung unbedingt getestet werden.<br>
<i>Zusätzlicher Hinweis zum Speicher: Wenn der Speicher mal nicht verfügbar war (z.B. wenn er auf einem NAS liegt), muss er im Backend im Bereich Dateispeicher manuell wieder "online" geschaltet werden. Die erfolgt mit der Checkbox "ist online?" in den Eigenschaften des jeweiligen Dateispeichers.)</i>
In diesen Dateispeicher werden die Dateien der Ratsuchenden gespeichert, die während des Anmeldevorgangs hochgeladen werden können. Für jeden Ratsuchenden wird ein Verzeichnis erstellt, dass mit dem Format [Nachname]_[Vorname]_[UID] angelegt wird.<br></li>
</ol>
<h2>Hinweise zu den verschiedenen Unterseiten</h2>

Die Startseite enthält nur einen einleitenden Text und einen Link auf die Seite „Anmelden“.<br>

Auf der Seite <b>Anmeldung</b> ist das Plug-In <b>IQ TP13 DB Webapp</b> eingebunden. Als Typ bei Erweiterungsoptionen ist hier "Anmeldung" ausgewählt. Die <b>Anmelden Ende-Seite</b> wird aufgerufen, nachdem die Registrierung vom Ratsuchenden bestätigt wurde; in der oben angegebenen Seitenstruktur wäre das die Seite "Danke". Die weiteren Felder beziehen sich auf die E-Mails (Anfrage Registrierungsbestätigung und danach Anmeldebestätigung), die an den Ratsuchenden gehen. Die <b>Seite Bestätigungsmail angefordert</b> wird aufgerufen, nachdem der Ratsuchende in der Webapp Absenden angeklickt hat; in der o.a. Struktur ist das die Seite "Anmeldung versendet". Bei fehlerhafter Validierung (z.B. Link in der E-Mail defekt oder Eintrag nicht mehr vorhanden) erscheint die Seite, die unter <b>Seite Validierung fehlgeschlagen</b> angegeben ist; im Beispiel die Seite "Fehler". Über die Felder <b>Wartung von, Wartung bis</b> und <b>Wartung Text</b> werden genutzt, um die Anmeldung temporär zu deaktivieren und stattdessen den <b>Wartung Text</b> anzuzeigen.<br>
Unter Datensatzsammlung muss der Sysordner (im Beispiel oben „[DB] Daten Anerkennungsberatung“) ausgewählt werden. In diesen werden alle Daten der Beratenen gespeichert. Damit ist das Plugin konfiguriert und der Fragebogen zur Anmeldung sollte angezeigt werden.<br>

Für das Backend (oder "Interner Bereich") muss eine Login-Seite für interne Nutzer angelegt werden. Diese enthält ein TYPO3-Inhaltselement vom Typ Anmeldung. Die zugehörigen Backend-User werden im System-Ordner <b>[DB] Benutzer</b> angelegt und sind ganz normale Website-Benutzer.
Im Internen Bereich werden Seiten entsprechend der Seitenstruktur angelegt, die jeweils ein Plugin IQ TP13 DB Adminbereich enthalten. Der Typ wird entsprechend dem Seitennamen eingestellt.
Auch hier wird jeweils unter Datensatzsammlung der Sysordner (im Beispiel oben „[DB] Daten Anerkennungsberatung“) ausgewählt.<br>

<h2>Texte/Sprachen</h2>

Um die Texte bzw. Beschriftungen im Formular anzupassen, können die entsprechend angepassten Texte im Typoscript  Template angepasst werden per:<br>
<b>plugin.tx_iqtp13db._LOCAL_LANG.[sprachcode].[tabelle].[wert] = [text]</b><br>

Ein Beispiel wäre die Anpassung des deutschen Einwilligungstextes per:<br>
<b>plugin.tx_iqtp13db._LOCAL_LANG.de.tx_iqtp13db_domain_model_teilnehmer.einwilligungtext = Das ist der geänderte Text!</b> (hier können auch html-Tags genutzt werden, um z.B. Zeilenumbrüche per < br > einzufügen)<br>

Die folgenden Texte werden i.d.R. angepasst:<br>
<b>plugin.tx_iqtp13db._LOCAL_LANG.de.mailtext = </b><br>
<b>plugin.tx_iqtp13db._LOCAL_LANG.de.subject = </b><br>
<b>plugin.tx_iqtp13db._LOCAL_LANG.de.confirmsubject = </b><br>

(+ ggf. bei den weitere Sprachen)

Alle verfügbaren Variablen sind in den Language-Files der Extension zu finden.<br>

<h2>Eigene Templates</h2>

Um eigene Templates zu verwenden bzw. die vorhandenen anzupassen, kann das Verzeichnis Resources/Private aus der Extension in ein eigenes Templates-Verzeichnis auf fileadmin kopiert werden. Der Pfad zu den angepassten Template-Dateien wird im Typoscript-Template dann mit den folgenden Zeilen geändert:<br>
<b>
plugin.tx_iqtp13db_iqtp13dbwebapp {<br>
  view {<br>
    templateRootPaths.10 = <eigener_template_pfad>/Resources/Private/Templates/<br>
    partialRootPaths.10 = <eigener_template_pfad>/Resources/Private/Partials/<br>
    layoutRootPaths.10 = <eigener_template_pfad>/Resources/Private/Layouts/ <br>
  }<br>
}</b>

Insbesondere die Templates für die E-Mail an die/den Beratene(n) sollten angepasst werden. Diese liegen unter <b>Resources/Private/Templates/Beratung/</b>.
