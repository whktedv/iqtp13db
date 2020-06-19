
plugin.tx_iqtp13db_iqtp13dbadmin {
  view {
    templateRootPaths.0 = EXT:iqtp13db/Resources/Private/Templates/
    templateRootPaths.1 = {$plugin.tx_iqtp13db_iqtp13dbadmin.view.templateRootPath}
    partialRootPaths.0 = EXT:iqtp13db/Resources/Private/Partials/
    partialRootPaths.1 = {$plugin.tx_iqtp13db_iqtp13dbadmin.view.partialRootPath}
    layoutRootPaths.0 = EXT:iqtp13db/Resources/Private/Layouts/
    layoutRootPaths.1 = {$plugin.tx_iqtp13db_iqtp13dbadmin.view.layoutRootPath}
  }
  persistence {
    storagePid = {$plugin.tx_iqtp13db_iqtp13dbadmin.persistence.storagePid}
    #recursive = 1
  }
  features {
    #skipDefaultArguments = 1
  }
  mvc {
    #callDefaultActionIfActionCantBeResolved = 1
  }
  
  settings {
        validationMailTemplateFile = 
        ownerConfirmationMailTemplateFile = 
        validationMailSubject =
        sender =
        ownerconfirmationreceiver =
        ownerconfirmationSubject =
    }
}

plugin.tx_iqtp13db_iqtp13dbwebapp {
  view {
    templateRootPaths.0 = EXT:iqtp13db/Resources/Private/Templates/
    templateRootPaths.1 = {$plugin.tx_iqtp13db_iqtp13dbwebapp.view.templateRootPath}
    partialRootPaths.0 = EXT:iqtp13db/Resources/Private/Partials/
    partialRootPaths.1 = {$plugin.tx_iqtp13db_iqtp13dbwebapp.view.partialRootPath}
    layoutRootPaths.0 = EXT:iqtp13db/Resources/Private/Layouts/
    layoutRootPaths.1 = {$plugin.tx_iqtp13db_iqtp13dbwebapp.view.layoutRootPath}
  }
  persistence {
    storagePid = {$plugin.tx_iqtp13db_iqtp13dbwebapp.persistence.storagePid}
    #recursive = 1
  }
  features {
    #skipDefaultArguments = 1
  }
  mvc {
    #callDefaultActionIfActionCantBeResolved = 1
  }
}

plugin.tx_iqtp13db._CSS_DEFAULT_STYLE (
    textarea.f3-form-error {
        background-color:#FF9F9F;
        border: 1px #FF0000 solid;
    }

    input.f3-form-error {
        background-color:#FF9F9F;
        border: 1px #FF0000 solid;
    }

    .tx-iqtp13db table {
        border-collapse:separate;
        border-spacing:10px;
    }

    .tx-iqtp13db table th {
        font-weight:bold;
    }

    .tx-iqtp13db table td {
        vertical-align:top;
    }

    .typo3-messages .message-error {
        color:red;
    }

    .typo3-messages .message-ok {
        color:green;
    }
)

page {
	includeCSS {
		tx_iqtp13db = EXT:iqtp13db/Resources/Public/CSS/customtp13db.css
	}
	
	includeJS {
		tx_iqtp13db = EXT:iqtp13db/Resources/Public/JS/customtp13.js
	}
}

# SPRACHWAHL
# **********************
config {
  sys_language_mode = content_fallback ; 1,0
  sys_language_uid = 0
  linkVars = L
  language = de
  locale_all = de_DE.UTF-8
}

# English language, sys_language.uid = 1
[globalVar = GP:L = 1]
  config.sys_language_uid = 1
  config.language = en
  config.locale_all = en_EN.UTF-8
[global]

# Arabisch
[globalVar = GP:L = 2]
  config.sys_language_uid = 2
  config.language = ar
  config.locale_all = ar_AR.UTF-8
  config.htmlTag_dir = rtl
  config.htmlTag_setParams = lang="" dir="rtl" class="no-js"
  page.includeCSS.customtp13rtl = EXT:iqtp13db/Resources/Public/CSS/customtp13db_rtl.css
[global]

# Farsi/Persisch
[globalVar = GP:L = 3]
  config.sys_language_uid = 3
  config.language = fa
  config.locale_all = fa_FA.UTF-8
  config.htmlTag_dir = rtl
  config.htmlTag_setParams = lang="" dir="rtl" class="no-js"
  page.includeCSS.customtp13rtl = EXT:iqtp13db/Resources/Public/CSS/customtp13db_rtl.css
[global]

# Französisch
[globalVar = GP:L = 4]
  config.sys_language_uid = 4
  config.language = fr
  config.locale_all = fr_FR.UTF-8
[global]

# Polnisch
[globalVar = GP:L = 5]
  config.sys_language_uid = 5
  config.language = po
  config.locale_all = po_PO.UTF-8
[global]

# Rumänisch
[globalVar = GP:L = 6]
  config.sys_language_uid = 6
  config.language = ro
  config.locale_all = ro_RO.UTF-8
[global]

# Russisch
[globalVar = GP:L = 7]
  config.sys_language_uid = 7
  config.language = ru
  config.locale_all = ru_RU.UTF-8
[global]

# Spanisch
[globalVar = GP:L = 8]
  config.sys_language_uid = 8
  config.language = es
  config.locale_all = es_ES.UTF-8
[global]

# Türkisch
[globalVar = GP:L = 9]
  config.sys_language_uid = 9
  config.language = tr
  config.locale_all = tr_TR.UTF-8
[global]



plugin.tx_iqtp13db.settings {

   ### Beratener ###
	
   deutschkenntnisse {
     1 = Deutsch as Muttersprache
     2 = Deutsch as Fremdsprache
   }
   
   bundeslaender {
     1 = -
     2 = Baden-Württemberg
     3 = Bayern
	 4 = Berlin
	 5 = Brandenburg
	 6 = Bremen
	 7 = Hamburg
	 8 = Hessen
	 9 = Mecklenburg-Vorpommern
	10 = Niedersachsen
	11 = Nordrhein-Westfalen
	12 = Rheinland-Pfalz
	13 = Saarland
	14 = Sachsen
	15 = Sachsen-Anhalt
	16 = Schleswig-Holstein
	17 = Thüringen
   }
   
   zertifikatlevel {
   	1 = -
   	2 = A1
   	3 = A2
	4 = B1
	5 = B2
	6 = C1
	7 = C2
	8 = DSH
	9 = Test-DaF
   }
   
   abschlussart {
     1 = Ausbildungsabschluss
     2 = Hochschulabschluss
   }

   erwerbsstatus {
     1 = beitragspflichtig beschäftigt
     2 = geringfügig beschäftigt
     3 = selbständig
     4 = in Aus-/Weiterbildung/Qualifizierung
     5 = nicht erwerbstätig
     6 = im Ausland erwerbstätig
     7 = im Ausland nicht erwerbstätig
     8 = sonstiges
   }

   leistungsbezug {
     1 = ohne Leistungsbezug
     2 = mit (ergänzendem) SGB II-Leistungsbezug
     3 = mit SGB III-Leistungsbezug 
     4 = mit SGB III- und SGB II-Leistungsbezug
     5 = mit (ergänzendem) Asylbewerberleistungsbezug   
   }
 
   aufenthaltsstatus {
     1 = keine Angabe
     2 = Blaue Karte EU (§19a AufenthG)
     3 = Visum (§6 AufenthG)
     4 = Aufenthalt zum Zweck der Ausbildung (§16-17 AufenthG)
     5 = Aufenthalt zum Zweck der Erwerbstätigkeit (§18, 18a, 20, 21 AufenthG)
     6 = Aufenthaltserlaubnis zur Arbeitsplatzsuche (§18c AufenthG)
     7 = Aufenthalt aus völkerrechtlichen, humanitären oder politischen Gründen (§22-26, 104a, 104b AufenthG
     8 = Aufenthalt aus familiären Gründen (§27-36 AufenthG)
     9 = Aufenthalt zum Zwecke einer Anpassungsqualifizierung oder einer Kenntnisprüfung (§ 17a AufenthG)
     10 = Niederlassungserlaubnis (§9 AufenthG)
     11 = Aufenthaltserlaubnis für in anderen Mitgliedsstaaten der Euro-   päischen Union langfristig Aufenthaltsberechtigte (§38a AufenthG)
     12 = Aufenthaltsgestattung (§55 Abs. 1 AsylVfG)
     13 = Duldung (§60a Abs. 4 AufenthG)
     14 = Staatsbürger/in EU/EWR/Schweiz
     15 = Sonstiges
   }
   
   dokumenteabschluss {
    0 = -
   	1 = ja
   	2 = nein
   	3 = teilweise
   	4 = nur Kopien
   	5 = keine Dokumente vorhanden
   }

   ### Beratung ###

   beratungsstelle {
     1 = Agentur für Arbeit
     2 = Jobcenter
     3 = Internet
     4 = Anerkennungsportal BIBB
     5 = Hotline Arbeit und Leben in Deutschland
     6 = Migrationsberatung (MBE)
     7 = Jugendmigrationsdienst (JMD)
     8 = Migrantenselbstorganisation
     9 = Presse
     10 = persönliche Empfehlung
     11 = Sonstiges
   }

   beratungsprozess {
     1 = Anerkennungsberatung
     2 = Qualifizierungsberatung
   }

   beratungsart {
     1 = face-to-face
     2 = Telefon
     3 = Email
   }

   anfragedurch {
     1 = keine Angabe
     2 = Beratene/r selbst
     3 = Jobcenter
     4 = Agentur für Arbeit
     5 = JMD/MBE
     6 = Migrantenorganisation
     7 = Unternehmen
     8 = Bildungsberatungsstelle/-dienstleister
     9 = Soziales Umfeld des/der Beratenen
     10 = Sonstiges
   }
  
   bescheidGleichwertigkeitspruefung {
     1 = k. A.
     2 = ja
     3 = nein, noch kein Antrag gestellt
     4 = nein, Verfahren läuft noch
     5 = nein, da keine Aussicht auf Erfolg (negative Prognose)
     6 = nein, da nicht-reglementierter akademischer Beruf
   }

   ergebnisGleichwertigkeitsfeststellung {
     1 = k. A.
     2 = volle Gleichwertigkeit
     3 = Auflage einer Ausgleichsmaßnahme (reglementiert)
     4 = teilweise Gleichwertigkeit (nicht-reglementiert)
     5 = Ablehnung
   }

   zabBewertung {
     1 = k. A.
     2 = ja, liegt vor
     3 = nein, liegt (noch) nicht vor
   }

   anBildungsdienstleisterweiterverwiesen {
     1 = k. A.
     2 = ja, an einen IQ-internen Bildungsdienstleister
     3 = nein, an einen IQ-externen Bildungsdienstleister
     4 = überhaupt keine passende Maßnahme gefunden
   }

   welcherBildungsdienstleister {
     1 = k. A.
     2 = sonstiger Träger
     3 = staatliche Bildungseinrichtung
     4 = private Bildungseinrichtung
     5 = Kammern (IHK, HWK, sonstige)
     6 = Wirtschafts- und Fachverbände
     7 = Arbeitnehmerorganisationen/Gewerkschaften
     8 = soziale Wohlfahrt/Kirchen
   }

   modulQualifizierungsmassnahme {
     1 = k. A.
     2 = Modul 1: Qualifizierungsmaßnahmen bei reglementierten Berufen
     3 = Modul 2: Anpassungsqualifizierungen im Bereich des dualen Systems
     4 = Modul 3: Brückenmaßnahmen für Akademiker/-innen
     5 = Modul 4: Vorbereitung auf die Externenprüfung bei negativem Ausgang/Prognose des Anerkennungsverfahrens
   }


   ###### Schulung ######

   massnahmebereich {
     1 = Arbeitsmarktdienstleister/kommunale Einrichtung
     2 = Unternehmen
   }
   
   art {
   	 1 = Einzel-Schulung/Training/Workshop
   	 2 = Schulung mit modularem Aufbau
   	 3 = andere Maßnahme Art
   }
   
   modularerAufbau {
     1 = Basistraining
     2 = Vertiefungstraining
     3 = Praxisbegleitung
   }
   
   kooperation {
     1 = Arbeitsagentur
     2 = Jobcenter
     3 = wiss. Einrichtung/Hochschule
     4 = Großbetrieb
     5 = KMU
     6 = Arbeitgeberverband
   }
   
   zeitUmfang {
     1 = 1/2-tägig
     2 = 1-tägig
     3 = 2-tägig
     4 = 3-tägig
   }
   
   organisation {
   	 1 = Inhouse
   	 2 = offenes Angebot
   }
   
   teilnahmeart {
     1 = verpflichtend
     2 = freiwillig
     3 = teils/teils
   }
   
   themen {
   	 1 = Anerkennungs-/Qualifizierungsberatung
   	 2 = Anderes Thema
   }
   
   institutionauswahl {
   	1 = Arbeitsagentur
   	2 = Jobcenter
   	3 = Bildungsträger
   	4 = KMU Handwerk
   	5 = KMU Industrie
   	6 = KMU Dienstleistung
   	7 = andere Institution
   }
   
   betriebsgroesse {
   	1 = 1-9
   	2 = 10-49
   	3 = 50-99
   	4 = 100-199
   	5 = 200-400
   	6 = größer als 400
   	7 = nicht bekannt
   }
   
   weitereplanung {
   	1 = Vertiefung, gleicher Teilnehmerkreis
   	2 = Schulung/Training, gleiche Instituion
   	3 = Beratung/Strategieworkshops, gleiche Institution
   	4 = keine weitere Planung
   	5 = andere Planung
   }  
}

[globalVar = GP:L = 1]
plugin.tx_iqtp13db.settings {

   dokumenteabschluss {
    0 = -
   	1 = yes
   	2 = no
   	3 = partly
   	4 = copies only
   	5 = no documents available
   }
   
   erwerbsstatus {
     1 = liable to pay contributions
     2 = under-employed
     3 = self-employed
     4 = in education / training / qualification
     5 = unemployed
     6 = employed abroad
     7 = not employed abroad
     8 = other
   }
   
   leistungsbezug {
     1 = without allowances
 	 2 = with (additional) SGB II-allowance
	 3 = with SGB III-allowance
	 4 = with SGB III and SGB II-allowance
	 5 = with (additional) asylum seekers allowance
   }
   
   beratungsstelle {
  	 1 = Federal Employment Agency
	 2 = Job Centre
	 3 = Internet
	 4 = Recognition portal BIBB
	 5 = Hotline work and life in Germany
	 6 = Migration counselling (MBE)
	 7 = Youth Migration Service (JMD)
	 8 = Autonomous migrant organisation
	 9 = Press
	10 = Personal recommendation
	11 = Miscellaneous
   }
}   
[global]


[globalVar = GP:L = 2]
plugin.tx_iqtp13db.settings {

   dokumenteabschluss {
    0 = -
   	1 = نعم
   	2 = كلا
   	3 = جزئيا 
   	4 = النسخ فقط
   	5 = لا توجد وثائق
   }
   
	erwerbsstatus {
		1 = عمل مستحق الضريبة
		2 = عامل بدوام جزئي
		3 = عمل حر
		4 = في التعليم/التدريب/التأهيل
		5 = عاطل
		6 = عامل في الخارج
		7 = غير عامل في الخارج
		8 = أخرى
	}
	
	leistungsbezug {
		1 = لا أحصل على إعانات
	 	2 = أحصل على إعانة (تكميلية) حسب القانون SGB II
		3 = أحصل على إعانة  حسب القانون SGB III
		4 = أحصل على إعانة  حسب القانونSGB III  والقانون SGB II
		5 = أحصل على إعانة طالبي اللجوء (تكميلية)
	}
   
    beratungsstelle {
	  	 1 = وكالة العمل
		 2 = مركز التوظيف
		 3 = الإنترنت
		 4 = بوابة الاعتراف بالشهادات BIBB	
		 5 = الخط الساخن للعمل والعيش داخل ألمانيا
		 6 = مركز استشارة الهجرة (MBE)
		 7 = خدمة هجرة الشباب (JMD)
		 8 = مؤسسة إعانة المهاجرين
		 9 = الصحافة
		10 = توصية شخصية
		11 = أخرى
    }
}


[globalVar = GP:L = 3]
plugin.tx_iqtp13db.settings {

   dokumenteabschluss {
    0 = -
   	1 = بله
   	2 = خیر
   	3 = تا حدی
   	4 = تنها نسخه
   	5 = هیچ اسناد و مدارک موجود
   }
   
   erwerbsstatus {
     1 = شاغل به صورت تمام وقت
     2 = شاغل به صورت پاره وقت
     3 = دارای شغل آزاد
     4 = تحت آموزش/ در حال ادامه تحصیل/ در حال کسب مهارت
     5 = بدون شغل (بیکار)
     6 = شاغل در خارج از کشور
     7 = بدون شغل در خارج از کشور
     8 = سایر موارد
   }
   
   leistungsbezug {
     1 = عدم دریافت مزایا
 	 2 = دریافت مزایا (مکمل) طبق جلد دوم کتاب قوانین اجتماعی (SGB II)
	 3 = دریافت مزایا طبق جلد سوم کتاب قوانین اجتماعی (SGB III)
	 4 = دریافت مزایا طبق جلد سوم کتاب قوانین اجتماعی و جلد دوم کتاب قوانین اجتماعی
	 5 = دریافت مزایا (مکمل) برای پناهجویان
   }
   
	beratungsstelle {
		1 = آژانس کار
		2 = مرکز کاریابی
		3 = اینترنت
		4 = پورتال به رسمیت شناختن موسسه آموزش شغلی فدرال (BIBB)
		5 = ارتباط تلفنی مستقیم با (Hotline Arbeit und Leben in Deutschland)
		6 = مرکز مشاوره در امور مهاجرت (MBE)  
		7 = مرکز خدمات امور مهاجرت جوانان (JMD)
		8 = سازمان تأسیس شده توسط مهاجران (MSO)
		9 = مطبوعات
		10 = توصیه‌های شخصی
		11 = سایر موارد
   }
}   
[global]


[globalVar = GP:L = 4]
plugin.tx_iqtp13db.settings {

   dokumenteabschluss {
    0 = -
   	1 = Oui
   	2 = non
   	3 = partiel
   	4 = juste copies
   	5 = Les documents ne sont pas disponible
   }
   
   erwerbsstatus {
     1 = Emploi à temps plein
	 2 = Emploi à temps partiel
	 3 = Indépendant
	 4 = En formation/perfectionnement/qualification
	 5 = sans emploi
	 6 = Emploi à l'étranger
	 7 = Sans emploi à l'étranger
	 8 = Divers
   }
   
   leistungsbezug {
     1 = ne bénéficie pas de prestations
	 2 = prestations SGB II (complémentaires)
	 3 = prestations SGB III
	 4 = prestations SGB III et SGB II
	 5 = prestations (complémentaires) en tant que demandeur d'asile
   }
   
   beratungsstelle {
  	 1 = Agentur für Arbeit
	 2 = Jobcenter
	 3 = Internet
	 4 = Portail de reconnaissance BIBB
	 5 = Hotline Arbeit und Leben in Deutschland
	 6 = Migrationsberatung (MBE)
	 7 = Jugendmigrationsdienst (JMD)
	 8 = Migrantenselbstorganisation
	 9 = Presse
	 10 = Recommandation personnelle
	 11 = Divers
   }
}   
[global]


[globalVar = GP:L = 5]
plugin.tx_iqtp13db.settings {

   dokumenteabschluss {
    0 = -
   	1 = Tak
   	2 = Nie
   	3 = Częściowo
   	4 = Tylko kopie
   	5 = Brak dokumentów
   }
   
   erwerbsstatus {
     1 = podlega obowiązkowi składkowemu zatrudniony
	 2 = zatrudniony w niewielkim wymiarze
	 3 = samodzielna działalność
	 4 = kształcenie/dokształcanie/zdobywanie kwalifikacji
	 5 = nieczynny zawodowo
	 6 = czynny zawodowo za granicą
	 7 = nieczynny zawodowo za granicą
	 8 = inne
   }
   
   leistungsbezug {
     1 = bez pobierania świadczeń
	 2 = z (uzupełniającym) pobieraniem świadczeń wg ustawy socjalnej II
	 3 = z pobieraniem świadczeń wg ustawy socjalnej III
	 4 = z pobieraniem świadczeń wg ustawy socjalnej III i II
	 5 = z (uzupełniającym) pobieraniem świadczeń dla osób starających się o azyl
   }
   
   beratungsstelle {
  	1 = Agencja pracy
	2 = Centrum zatrudnienia
	3 = Internet
	4 = Portal poświęcony uznawaniu kwalifikacji BIBB
	5 = Infolinia Praca i życie w Niemczech
	6 = Doradztwo dla migrantów (MBE)
	7 = Agencja migracji młodzieży (JMD)
	8 = Organizacja migrantów
	9 = Prasa
	10 = Osobiste zalecenie
	11 = Inne
   }
}   
[global]


[globalVar = GP:L = 6]
plugin.tx_iqtp13db.settings {

   dokumenteabschluss {
    0 = -
   	1 = da
   	2 = nu
   	3 = parțial
   	4 = numai copii
   	5 = nu documentelor disponibil
   }
   
   erwerbsstatus {
     1 = Angajat cu obligaţia de a achita contribuţiile sociale
	 2 = Angajat cu contract cu timp de muncă parţial şi plată redusă (minijob)
	 3 = Independent
	 4 = În curs de formare profesională / calificare
	 5 = Neangajat
	 6 = Angajat în străinătate
	 7 = Neangajat în străinătate
	 8 = Altă situaţie
   }
   
   leistungsbezug {
     1 = Fără plăţi / venituri sociale
	 2 = Cu venituri sociale SGB II
	 3 = Cu venituri sociale SGB III
	 4 = Cu venituri sociale SGB III şi SGB II
	 5 = Cu venituri sociale (în completare) pentru solicitanţii de azil 
   }
   
   beratungsstelle {
  	 1 = Agenţia pentru muncă
	 2 = Jobcenter
	 3 = Internet
	 4 = Portalul de recunoaştere a calificărilor BIBB
	 5 = Linia telefonică Munca şi viaţa în Germania (Arbeit und Leben in Deutschland)
	 6 = Consiliere pentru migranți (MBE)
	 7 = Serviciul migrare tineri (JMD)
	 8 = Organizaţia proprie a migranţilor
	 9 = Presa
	 10 = Recomandare personală
	 11 = Altele
   }
}   
[global]


[globalVar = GP:L = 7]
plugin.tx_iqtp13db.settings {

   dokumenteabschluss {
    0 = -
   	1 = да
   	2 = нет
   	3 = частично
   	4 = только копии
   	5 = документов нет
   }
   
   erwerbsstatus {
     1 = Работаете с обязательством уплачивать членские взносы 
	 2 = Работаете на минимальной заработной плате
	 3 = Частный предприниматель
	 4 = Получаете образование/ проходите повышение квалификации/ профессиональную подготовку 
	 5 = Не работаете
	 6 = Работаете за границей 
	 7 = Не работаете за границей 
	 8 = Другое
   }
   
   leistungsbezug {
     1 = Не получаете пособия
	 2 = Получаете (дополнительное) пособие в соответствии с ч. II Социального кодекса Германии 
	 3 = Получаете пособие в соответствии с ч. III Социального кодекса Германии 
	 4 = Получаете пособие в соответствии с ч. II и III Социального кодекса Германии 
	 5 = Получаете (дополнительное) пособие как лицо, претендующее на политическое убежище
   }
   
   beratungsstelle {
	 1 = Агентство занятости
	 2 = Центр занятости
	 3 = Интернет
	 4 = Портал по вопросам признания Федерального института профессионального образования (BIBB)
	 5 = «Горячая» линия «работа и жизнь» в Германии 
	 6 = Центр консультирования мигрантов (MBE)
	 7 = Молодежная миграционная служба (JMD)
	 8 = Независимая организация мигрантов 
	 9 = Пресса 
	 10 = Личная рекомендация
	 11 = Другое
   }
}   
[global]


[globalVar = GP:L = 8]
plugin.tx_iqtp13db.settings {

   dokumenteabschluss {
    0 = -
   	1 = yes
   	2 = no
   	3 = partly
   	4 = copies only
   	5 = no documents available
   }
   
   erwerbsstatus {
     1 = empleado contribuyente 
	 2 = con empleo precario
	 3 = autónomo
	 4 = dedicado a la formación primaria / continuada / cualificada
	 5 = sin empleo 
	 6 = con empleo en el extranjero
	 7 = sin empleo en el extranjero
	 8 = otro
   }
   
   leistungsbezug {
     1 = sin prestaciones
	 2 = con prestaciones (complementarias) SGB II
	 3 = con prestaciones SGB III
	 4 = con prestaciones SGB III y SGB II
	 5 = con prestaciones (complementarias) como solicitante de asilo 
   }
   
   beratungsstelle {
  	 1 = Agencia de colocación 
	 2 = Centro de trabajo (Jobcenter)
	 3 = Internet
	 4 = Portal de reconocimiento BIBB
	 5 = Línea caliente „Trabajar y Vivir en Alemania“ (Hotline Arbeit und Leben in Deutschland)
	 6 = Asesoría de Inmigrantes (Migrationsberatung) (MBE)
	 7 = Servicio para Jóvenes Inmigrantes (Jugendmigrationsdienst) (JMD)
	 8 = ONG de Inmigrantes
	 9 = Prensa
	10 = Recomendación personal
	11 = Otras vías
   }
}   
[global]


[globalVar = GP:L = 9]
plugin.tx_iqtp13db.settings {

   dokumenteabschluss {
    0 = -
   	1 = evet
   	2 = hayır 
   	3 = kismen
   	4 = sadece kopya
   	5 = diploma belgesi mevcut deyil
   }
   
   erwerbsstatus {
     1 = Sigortalı çalışıyorum
	 2 = Az çalışıyorum
	 3 = Serbest meslek
	 4 = Eğitim / öğretim / mesleki yeterlilik eğitimindeyim
	 5 = Meslek sahibi değilim
	 6 = Yurt dışında meslek sahibiyim
	 7 = Yurt dışında meslek sahibi değilim
	 8 = Diğer
   }
   
   leistungsbezug {
     1 = Yardım almıyorum
	 2 = Tamamlayıcı SGB II- yardımı alıyorum
	 3 = SGB III- yardımı alıyorum
	 4 = SGB III- ve SGB II yardımı alıyorum
	 5 = Tamamlayıcı iltica dilekçesi sahibi yardımı alıyorum
   }
   
   beratungsstelle {
  	1 = İş Kurumu
	2 = İş Merkezi 
	3 = Internet
	4 = Diploma tanınması portalı BIBB
	5 = Almanya’da çalışma ve yaşama danışma hattı
	6 = Göç Danışmanlığı (MBE)
	7 = Genç göçmenler servisi (JMD)
	8 = Göçmenler örgütü 
	9 = Basın
	10 = Kişisel tavsiye
	11 = Diğer
   }
}   
[global]
