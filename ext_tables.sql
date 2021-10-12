#
# Table structure for table 'tx_iqtp13db_domain_model_teilnehmer'
#
CREATE TABLE tx_iqtp13db_domain_model_teilnehmer (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
		
	beratungsstatus int(11) DEFAULT '0' NOT NULL,
	niqchiffre varchar(255) DEFAULT '0' NOT NULL,
	schonberaten int(11) DEFAULT '0' NOT NULL,
	schonberatenvon varchar(255) DEFAULT '' NOT NULL,
	
	nachname varchar(255) DEFAULT '' NOT NULL,
	vorname varchar(255) DEFAULT '' NOT NULL,
	plz varchar(255) DEFAULT '' NOT NULL,
	ort varchar(255) DEFAULT '' NOT NULL,
	email varchar(255) DEFAULT '' NOT NULL,
	confirmemail varchar(255) DEFAULT '' NOT NULL,
	telefon varchar(255) DEFAULT '' NOT NULL,
	lebensalter varchar(255) DEFAULT '' NOT NULL,
	geburtsland varchar(255) DEFAULT '' NOT NULL,
	geschlecht int(11) DEFAULT '0' NOT NULL,
	erste_staatsangehoerigkeit varchar(255) DEFAULT '' NOT NULL,
	zweite_staatsangehoerigkeit varchar(255) DEFAULT '' NOT NULL,
	einreisejahr varchar(255) DEFAULT '' NOT NULL,
	wohnsitz_deutschland int(11) DEFAULT '0' NOT NULL,
	wohnsitz_nein_in varchar(255) DEFAULT '' NOT NULL,
	ortskraftafghanistan tinyint(1) unsigned DEFAULT '0' NOT NULL,
		
	deutschkenntnisse int(11) DEFAULT '0' NOT NULL,
	zertifikatdeutsch int(11) DEFAULT '0' NOT NULL,
	zertifikat_sprachniveau varchar(255) DEFAULT '' NOT NULL,
	
	abschlussart1 varchar(255) DEFAULT '' NOT NULL,
	abschlussart2 varchar(255) DEFAULT '' NOT NULL,
	erwerbsland1 varchar(255) DEFAULT '' NOT NULL,
	dauer_berufsausbildung1 varchar(255) DEFAULT '' NOT NULL,
	abschlussjahr1 varchar(255) DEFAULT '' NOT NULL,
	ausbildungsinstitution1 varchar(255) DEFAULT '' NOT NULL,
	ausbildungsort1 varchar(255) DEFAULT '' NOT NULL,
	abschluss1 varchar(255) DEFAULT '' NOT NULL,
	berufserfahrung1 text NOT NULL,
	ausbildungsfremdeberufserfahrung1 text NOT NULL,
	deutscher_referenzberuf1 varchar(255) DEFAULT '' NOT NULL,
	wunschberuf1 varchar(255) DEFAULT '' NOT NULL,
	erwerbsland2 varchar(255) DEFAULT '' NOT NULL,
	dauer_berufsausbildung2 varchar(255) DEFAULT '' NOT NULL,
	abschlussjahr2 varchar(255) DEFAULT '' NOT NULL,
	ausbildungsinstitution2 varchar(255) DEFAULT '' NOT NULL,
	ausbildungsort2 varchar(255) DEFAULT '' NOT NULL,
	abschluss2 varchar(255) DEFAULT '' NOT NULL,
	berufserfahrung2 text NOT NULL,
	ausbildungsfremdeberufserfahrung2 text NOT NULL,
	deutscher_referenzberuf2 varchar(255) DEFAULT '' NOT NULL,
	wunschberuf2 varchar(255) DEFAULT '' NOT NULL,
	
	erwerbsstatus int(11) DEFAULT '0' NOT NULL,
	leistungsbezugjanein int(11) DEFAULT '0' NOT NULL,
	leistungsbezug varchar(255) DEFAULT '' NOT NULL,
	einwilligungdatenan_a_a int(11) DEFAULT '0' NOT NULL,
	einwilligungdatenan_a_adatum varchar(255) DEFAULT '' NOT NULL,
	einwilligungdatenan_a_amedium varchar(255) DEFAULT '' NOT NULL,
	name_berater_a_a varchar(255) DEFAULT '' NOT NULL,
	kontakt_berater_a_a varchar(255) DEFAULT '' NOT NULL,
	kundennummer_a_a varchar(255) DEFAULT '' NOT NULL,
	
	einw_anerkstelle int(11) DEFAULT '0' NOT NULL,
	einw_anerkstelledatum varchar(255) DEFAULT '' NOT NULL,
	einw_anerkstellemedium varchar(255) DEFAULT '' NOT NULL,
	einw_anerkstellename varchar(255) DEFAULT '' NOT NULL,
	einw_anerkstellekontakt varchar(255) DEFAULT '' NOT NULL,
	
	einw_person int(11) DEFAULT '0' NOT NULL,
	einw_persondatum varchar(255) DEFAULT '' NOT NULL,
	einw_personmedium varchar(255) DEFAULT '' NOT NULL,
	einw_personname varchar(255) DEFAULT '' NOT NULL,
	einw_personkontakt varchar(255) DEFAULT '' NOT NULL,
	
	aufenthaltsstatus int(11) DEFAULT '0' NOT NULL,
	aufenthaltsstatusfreitext text NOT NULL,
	
	frueherer_antrag int(11) DEFAULT '0' NOT NULL,
	frueherer_antrag_referenzberuf varchar(255) DEFAULT '' NOT NULL,
	frueherer_antrag_institution varchar(255) DEFAULT '' NOT NULL,
	bescheidfrueherer_anerkennungsantrag tinyint(1) unsigned DEFAULT '0' NOT NULL,
    
    name_beratungsstelle varchar(255) DEFAULT '' NOT NULL,
    notizen text NOT NULL,
        
    einwilligung tinyint(1) unsigned DEFAULT '0' NOT NULL,
	verification_code varchar(255) DEFAULT '' NOT NULL,
    verification_date int(11) unsigned DEFAULT '0' NOT NULL,
    verification_ip varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_iqtp13db_domain_model_beratung'
#
CREATE TABLE tx_iqtp13db_domain_model_beratung (
 
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	datum varchar(255) DEFAULT '' NOT NULL,
	berater int(11) unsigned DEFAULT '0',	
	beratungsart varchar(255) DEFAULT '' NOT NULL,
	beratungsartfreitext text NOT NULL,
	beratungsort varchar(255) DEFAULT '' NOT NULL,
		
	beratungzu varchar(255) DEFAULT '' NOT NULL,
	referenzberufe text NOT NULL,
	anerkennendestellen text NOT NULL,
	anerkennungsberatung varchar(255) DEFAULT '' NOT NULL,
	anerkennungsberatungfreitext text NOT NULL,
	qualifizierungsberatung varchar(255) DEFAULT '' NOT NULL,
	qualifizierungsberatungfreitext text NOT NULL,
	
	notizen text NOT NULL,
	erstberatungabgeschlossen varchar(255) DEFAULT '' NOT NULL,

	teilnehmer int(11) unsigned DEFAULT '0',

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_iqtp13db_domain_model_folgekontakt'
#
CREATE TABLE tx_iqtp13db_domain_model_folgekontakt (
 
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	datum varchar(255) DEFAULT '' NOT NULL,
	berater int(11) unsigned DEFAULT '0',	
	
	antraggestellt int(11) DEFAULT '0' NOT NULL,
	zab_gleichwertigkeit int(11) DEFAULT '0' NOT NULL,
	notizen text NOT NULL,
	
	teilnehmer int(11) unsigned DEFAULT '0',

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_iqtp13db_domain_model_berater'
#
CREATE TABLE tx_iqtp13db_domain_model_berater (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	name varchar(255) DEFAULT '' NOT NULL,
	organisation varchar(255) DEFAULT '' NOT NULL,
	kuerzel varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);


#
# Table structure for table 'tx_iqtp13db_domain_model_dokument'
#
CREATE TABLE tx_iqtp13db_domain_model_dokument (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	name varchar(255) DEFAULT '' NOT NULL,
	pfad varchar(255) DEFAULT '' NOT NULL,
	teilnehmer int(11) unsigned DEFAULT '0',

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);


#
# Table structure for table 'tx_iqtp13db_domain_model_historie'
#
CREATE TABLE tx_iqtp13db_domain_model_historie (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	
	teilnehmer int(11) unsigned DEFAULT '0',
	property varchar(255) DEFAULT '' NOT NULL,
	oldvalue varchar(1023) DEFAULT '' NOT NULL,
	newvalue varchar(1023) DEFAULT '' NOT NULL,	
	berater int(11) unsigned DEFAULT '0',
	
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);
