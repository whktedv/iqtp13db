#
# Table structure for table 'tx_iqtp13db_domain_model_teilnehmer'
#
CREATE TABLE tx_iqtp13db_domain_model_teilnehmer (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	nachname varchar(255) DEFAULT '' NOT NULL,
	vorname varchar(255) DEFAULT '' NOT NULL,
	strasse varchar(255) DEFAULT '' NOT NULL,
	plz varchar(255) DEFAULT '' NOT NULL,
	ort varchar(255) DEFAULT '' NOT NULL,
	email varchar(255) DEFAULT '' NOT NULL,
	telefon varchar(255) DEFAULT '' NOT NULL,
	geburtsjahr varchar(255) DEFAULT '' NOT NULL,
	geburtsland varchar(255) DEFAULT '' NOT NULL,
	geschlecht int(11) DEFAULT '0' NOT NULL,
	erste_staatsangehoerigkeit varchar(255) DEFAULT '' NOT NULL,
	zweite_staatsangehoerigkeit varchar(255) DEFAULT '' NOT NULL,
	einreisejahr varchar(255) DEFAULT '' NOT NULL,
	wohnsitz_deutschland int(11) DEFAULT '0' NOT NULL,
	wohnsitz_ja_bundesland varchar(255) DEFAULT '' NOT NULL,
	wohnsitz_nein_in varchar(255) DEFAULT '' NOT NULL,
	geplante_einreise varchar(255) DEFAULT '' NOT NULL,
	kontakt_visastelle int(11) DEFAULT '0' NOT NULL,
	visumsantrag varchar(255) DEFAULT '' NOT NULL,
	deutschkenntnisse int(11) DEFAULT '0' NOT NULL,
	zertifikatdeutsch int(11) DEFAULT '0' NOT NULL,
	zertifikat_sprachniveau varchar(255) DEFAULT '' NOT NULL,
	beratungsgespraech_deutsch int(11) DEFAULT '0' NOT NULL,
	beratungsgespraech_sprache varchar(255) DEFAULT '' NOT NULL,
	abschlussart_a tinyint(1) unsigned DEFAULT '0' NOT NULL,
	abschlussart_h tinyint(1) unsigned DEFAULT '0' NOT NULL,
	erwerbsland1 varchar(255) DEFAULT '' NOT NULL,
	dauer_berufsausbildung1 varchar(255) DEFAULT '' NOT NULL,
	abschlussjahr1 varchar(255) DEFAULT '' NOT NULL,
	ausbildungsinstitution1 varchar(255) DEFAULT '' NOT NULL,
	ausbildungsort1 varchar(255) DEFAULT '' NOT NULL,
	abschluss1 varchar(255) DEFAULT '' NOT NULL,
	deutsch_abschlusstitel1 varchar(255) DEFAULT '' NOT NULL,
	berufserfahrung1 text NOT NULL,
	deutscher_referenzberuf1 varchar(255) DEFAULT '' NOT NULL,
	wunschberuf1 varchar(255) DEFAULT '' NOT NULL,
	erwerbsstatus int(11) DEFAULT '0' NOT NULL,
	leistungsbezug varchar(255) DEFAULT '' NOT NULL,
	aufenthaltsstatus int(11) DEFAULT '0' NOT NULL,
	frueherer_antrag int(11) DEFAULT '0' NOT NULL,
	frueherer_antrag_referenzberuf varchar(255) DEFAULT '' NOT NULL,
	frueherer_antrag_institution varchar(255) DEFAULT '' NOT NULL,
	anz_beratungen int(11) DEFAULT '0' NOT NULL,
	weitere_sprachkenntnisse int(11) DEFAULT '0' NOT NULL,
	sprachen varchar(255) DEFAULT '' NOT NULL,
	einwilligung tinyint(1) unsigned DEFAULT '0' NOT NULL,
	erwerbsland2 varchar(255) DEFAULT '' NOT NULL,
	dauer_berufsausbildung2 varchar(255) DEFAULT '' NOT NULL,
	abschlussjahr2 varchar(255) DEFAULT '' NOT NULL,
	ausbildungsinstitution2 varchar(255) DEFAULT '' NOT NULL,
	ausbildungsort2 varchar(255) DEFAULT '' NOT NULL,
	abschluss2 varchar(255) DEFAULT '' NOT NULL,
	deutsch_abschlusstitel2 varchar(255) DEFAULT '' NOT NULL,
	berufserfahrung2 text NOT NULL,
	deutscher_referenzberuf2 varchar(255) DEFAULT '' NOT NULL,
	wunschberuf2 varchar(255) DEFAULT '' NOT NULL,
	original_dokumente_abschluss1 int(11) DEFAULT '0' NOT NULL,
	original_dokumente_abschluss2 int(11) DEFAULT '0' NOT NULL,
	bescheidfrueherer_anerkennungsantrag tinyint(1) unsigned DEFAULT '0' NOT NULL,
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

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),

);

#
# Table structure for table 'tx_iqtp13db_domain_model_beratung'
#
CREATE TABLE tx_iqtp13db_domain_model_beratung (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	chiffre varchar(255) DEFAULT '' NOT NULL,
	prozess int(11) DEFAULT '0' NOT NULL,
	datum datetime DEFAULT '0000-00-00 00:00:00',
	folgekontakt varchar(255) DEFAULT '' NOT NULL,
	ort varchar(255) DEFAULT '' NOT NULL,
	beratungsart int(11) DEFAULT '0' NOT NULL,
	anfrage_durch int(11) DEFAULT '0' NOT NULL,
	anmerkung text NOT NULL,
	ergebnis_weiterleitung varchar(255) DEFAULT '' NOT NULL,
	anmerkung_verfahren text NOT NULL,
	angaben_vereinbarungen text NOT NULL,
	umfang varchar(255) DEFAULT '' NOT NULL,
	beratung_abgeschlossen varchar(255) DEFAULT '' NOT NULL,
	uebertrag_n_i_q varchar(255) DEFAULT '' NOT NULL,
	dokumente_ratsuchender varchar(255) DEFAULT '' NOT NULL,
	dokumente_anhaengen varchar(255) DEFAULT '' NOT NULL,
	folgekontakte int(11) DEFAULT '0' NOT NULL,
	bescheid_gleichwertigkeitspruefung int(11) DEFAULT '0' NOT NULL,
	ergebnis_gleichwertigkeitsfeststellung int(11) DEFAULT '0' NOT NULL,
	zab_bewertung int(11) DEFAULT '0' NOT NULL,
	verweis_an_bildungsdienstleister int(11) DEFAULT '0' NOT NULL,
	empfohlene_qualimassnahme varchar(255) DEFAULT '' NOT NULL,
	welcher_bildungsdienstleister int(11) DEFAULT '0' NOT NULL,
	modul_zuordnung_qualimassnahme int(11) DEFAULT '0' NOT NULL,
	bundesland_qualimassnahme varchar(255) DEFAULT '' NOT NULL,
	anz_dokumente int(11) DEFAULT '0' NOT NULL,
	weg_beratungsstelle int(11) DEFAULT '0' NOT NULL,
	name_beratungsstelle varchar(255) DEFAULT '' NOT NULL,
	teilnehmer int(11) unsigned DEFAULT '0',
	berater int(11) unsigned DEFAULT '0',

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),

);

#
# Table structure for table 'tx_iqtp13db_domain_model_berater'
#
CREATE TABLE tx_iqtp13db_domain_model_berater (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	name varchar(255) DEFAULT '' NOT NULL,
	organisation varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),

);

#
# Table structure for table 'tx_iqtp13db_domain_model_schulung'
#
CREATE TABLE tx_iqtp13db_domain_model_schulung (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	datum datetime DEFAULT '0000-00-00 00:00:00',
	institution varchar(255) DEFAULT '' NOT NULL,
	massnahmebereich int(11) DEFAULT '0' NOT NULL,
	art int(11) DEFAULT '0' NOT NULL,
	andere_art varchar(255) DEFAULT '' NOT NULL,
	modularer_aufbau int(11) DEFAULT '0' NOT NULL,
	kooperation int(11) DEFAULT '0' NOT NULL,
	kooperation_sonstige varchar(255) DEFAULT '' NOT NULL,
	zeit_umfang int(11) DEFAULT '0' NOT NULL,
	organisation int(11) DEFAULT '0' NOT NULL,
	teilnahmeart int(11) DEFAULT '0' NOT NULL,
	teilnehmerkreis varchar(255) DEFAULT '' NOT NULL,
	themen int(11) DEFAULT '0' NOT NULL,
	themen_anderes varchar(255) DEFAULT '' NOT NULL,
	institution_auswahl int(11) DEFAULT '0' NOT NULL,
	institution_andere varchar(255) DEFAULT '' NOT NULL,
	betriebsgroesse int(11) DEFAULT '0' NOT NULL,
	weitere_planung int(11) DEFAULT '0' NOT NULL,
	weitere_planung_andere varchar(255) DEFAULT '' NOT NULL,
	anz_dokumente int(11) DEFAULT '0' NOT NULL,
	berater int(11) unsigned DEFAULT '0',

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),

);

#
# Table structure for table 'tx_iqtp13db_domain_model_dokument'
#
CREATE TABLE tx_iqtp13db_domain_model_dokument (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	name varchar(255) DEFAULT '' NOT NULL,
	pfad varchar(255) DEFAULT '' NOT NULL,
	beratung int(11) unsigned DEFAULT '0',
	schulung int(11) unsigned DEFAULT '0',

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),

);
