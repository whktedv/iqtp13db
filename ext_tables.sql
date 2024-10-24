#
# Table structure for table 'tx_iqtp13db_domain_model_teilnehmer'
#
CREATE TABLE tx_iqtp13db_domain_model_teilnehmer (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
		
	niqidberatungsstelle int(11) DEFAULT '12345' NOT NULL,

	beratungsstatus int(11) DEFAULT '0' NOT NULL,
	niqchiffre varchar(255) DEFAULT '0' NOT NULL,
	niqtstamp int(11) unsigned DEFAULT '0' NOT NULL,
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
	sonstigerstatus varchar(255) DEFAULT '' NOT NULL,
	
	deutschkenntnisse int(11) DEFAULT '0' NOT NULL,
	zertifikat_sprachniveau varchar(255) DEFAULT '' NOT NULL,
	weiteresprachkenntnisse varchar(255) DEFAULT '' NOT NULL,
	
	erwerbsstatus int(11) DEFAULT '0' NOT NULL,
	leistungsbezugjanein int(11) DEFAULT '0' NOT NULL,
	leistungsbezug varchar(255) DEFAULT '' NOT NULL,
	
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
        
    name_beratungsstelle varchar(255) DEFAULT '' NOT NULL,
    notizen text NOT NULL,
    
    einwilligung tinyint(1) unsigned DEFAULT '0' NOT NULL,
    nacherfassung tinyint(1) unsigned DEFAULT '0' NOT NULL,
    anonym tinyint(1) unsigned DEFAULT '0' NOT NULL,
	verification_code varchar(255) DEFAULT '' NOT NULL,
    verification_date int(11) unsigned DEFAULT '0' NOT NULL,
    verification_ip varchar(255) DEFAULT '' NOT NULL,

	anerkennungszuschussbeantragt varchar(255) DEFAULT '' NOT NULL,
    wieberaten varchar(255) DEFAULT '' NOT NULL,
    kooperationgruppe varchar(255) DEFAULT '' NOT NULL,
    
    beratungdatum varchar(255) DEFAULT '' NOT NULL,
	berater int(11) unsigned DEFAULT '0',	
	beratungsart varchar(255) DEFAULT '' NOT NULL,
	beratungsartfreitext text NOT NULL,
	beratungsort varchar(255) DEFAULT '' NOT NULL,
	beratungsdauer varchar(255) DEFAULT '' NOT NULL,
	
	beratungzu varchar(255) DEFAULT '' NOT NULL,
	anerkennendestellen text NOT NULL,
	anerkennungsberatung varchar(255) DEFAULT '' NOT NULL,
	anerkennungsberatungfreitext text NOT NULL,
	qualifizierungsberatung varchar(255) DEFAULT '' NOT NULL,
	qualifizierungsberatungfreitext text NOT NULL,
	
	beratungnotizen text NOT NULL,
	erstberatungabgeschlossen varchar(255) DEFAULT '' NOT NULL,
    einwilligunginfo tinyint(1) unsigned DEFAULT '0',
    
    edittstamp int(11) unsigned DEFAULT '0' NOT NULL,
    edituser int(11) unsigned DEFAULT '0',	
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
# Table structure for table 'tx_iqtp13db_domain_model_abschluss'
#
CREATE TABLE tx_iqtp13db_domain_model_abschluss (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	
	teilnehmer int(11) unsigned DEFAULT '0',
	
	abschlussart varchar(255) DEFAULT '' NOT NULL,
	branche int(11) DEFAULT '0' NOT NULL,
	erwerbsland varchar(255) DEFAULT '' NOT NULL,
	dauer_berufsausbildung varchar(255) DEFAULT '' NOT NULL,
	abschlussjahr varchar(255) DEFAULT '' NOT NULL,
	ausbildungsinstitution varchar(255) DEFAULT '' NOT NULL,
	ausbildungsort varchar(255) DEFAULT '' NOT NULL,
	abschluss varchar(255) DEFAULT '' NOT NULL,
	berufserfahrung int(11) DEFAULT '0' NOT NULL,
	deutscher_referenzberuf varchar(255) DEFAULT '' NOT NULL,
	referenzberufzugewiesen varchar(255) DEFAULT '' NOT NULL,
	wunschberuf varchar(255) DEFAULT '' NOT NULL,
	
	sonstigerberuf varchar(255) DEFAULT '' NOT NULL,
	nregberuf varchar(255) DEFAULT '' NOT NULL,
	
	antragstellungvorher int(11) DEFAULT '0' NOT NULL,
	antragstellunggwpvorher int(11) DEFAULT '0' NOT NULL,
	antragstellungzabvorher int(11) DEFAULT '0' NOT NULL,
	
	antragstellungerfolgt int(11) DEFAULT '0' NOT NULL,
    antragstellunggwpdatum varchar(255) DEFAULT '' NOT NULL,
    antragstellunggwpergebnis int(11) DEFAULT '0' NOT NULL,
    antragstellungzabdatum varchar(255) DEFAULT '' NOT NULL,
    antragstellungzabergebnis int(11) DEFAULT '0' NOT NULL,
    
    niquebertragung varchar(255) DEFAULT '' NOT NULL,
    
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,

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
	
	notizen text NOT NULL,
	beratungsform int(11) DEFAULT '0' NOT NULL,
	beratungsdauer varchar(255) DEFAULT '' NOT NULL,
	
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
# Table structure for table 'tx_iqtp13db_domain_model_dokument'
#
CREATE TABLE tx_iqtp13db_domain_model_dokument (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	name varchar(255) DEFAULT '' NOT NULL,
	beschreibung varchar(255) DEFAULT '' NOT NULL,
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
	oldvalue text DEFAULT '' NOT NULL,
	newvalue text DEFAULT '' NOT NULL,	
	berater int(11) unsigned DEFAULT '0',
	
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table extension for table 'fe_groups'
#
CREATE TABLE fe_groups (
        niqbid varchar(255) DEFAULT '' NOT NULL,
        nichtiq int(11) unsigned DEFAULT '0',
        bundesland varchar(255) DEFAULT '' NOT NULL,
        generalmail varchar(255) DEFAULT '' NOT NULL,
        plzlist text NOT NULL,
        keywordlist text NOT NULL,
        beratungsarten varchar(255) DEFAULT '' NOT NULL,
        einwilligungserklaerungsseite int(11) DEFAULT '0',
        avadresse text NOT NULL,
        custominfotextmail text NOT NULL,
        custominfotextstart text NOT NULL,
        customlogourl varchar(255) DEFAULT '' NOT NULL
);

CREATE TABLE fe_users (
    company varchar(255) DEFAULT '' NOT NULL
);


#
# Table structure for table 'tx_iqtp13db_domain_model_berufe'
#
CREATE TABLE tx_iqtp13db_domain_model_berufe (
    uid int(11) NOT NULL,
    pid int(11) DEFAULT '0' NOT NULL,
    berufid varchar(64) DEFAULT '' NOT NULL,
    titel varchar(255) DEFAULT '' NOT NULL,
    langisocode varchar(2) DEFAULT '' NOT NULL,
    PRIMARY KEY (uid)
);

#
# Table structure for table 'tx_iqtp13db_domain_model_staaten'
#
CREATE TABLE tx_iqtp13db_domain_model_staaten (
    uid int(11) NOT NULL,
    pid int(11) DEFAULT '0' NOT NULL,
    staatid varchar(64) DEFAULT '' NOT NULL,
    titel varchar(255) DEFAULT '' NOT NULL,
    langisocode varchar(2) DEFAULT '' NOT NULL,
    PRIMARY KEY (uid)
);

#
# Table structure for table 'tx_iqtp13db_domain_model_ort'
#
CREATE TABLE tx_iqtp13db_domain_model_ort (
    uid int(11) NOT NULL,
    pid int(11) DEFAULT '0' NOT NULL,
    plz varchar(5) DEFAULT '' NOT NULL,
    ort varchar(255) DEFAULT '' NOT NULL,
    bundesland varchar(255) DEFAULT '' NOT NULL,
    landkreis varchar(255) DEFAULT '' NOT NULL,
    lat varchar(20) DEFAULT '' NOT NULL,
    lon varchar(20) DEFAULT '' NOT NULL,
    PRIMARY KEY (uid)
);

#
# Table structure for table 'tx_iqtp13db_domain_model_branche'
#
CREATE TABLE tx_iqtp13db_domain_model_branche (
    uid int(11) NOT NULL,
    pid int(11) DEFAULT '0' NOT NULL,
    brancheid int(11) DEFAULT '0' NOT NULL,
    brancheok int(11) DEFAULT '0' NOT NULL,
    titel varchar(255) DEFAULT '' NOT NULL,
    langisocode varchar(2) DEFAULT '' NOT NULL,
    PRIMARY KEY (uid)
);