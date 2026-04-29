#
# Table structure for table 'tx_iqtp13db_domain_model_teilnehmer'
#

CREATE TABLE tx_iqtp13db_domain_model_teilnehmer (

    uid                          INT NOT NULL AUTO_INCREMENT,
    pid                          INT DEFAULT 0 NOT NULL,

    niqidberatungsstelle         INT DEFAULT 12345 NOT NULL,

    beratungsstatus              INT DEFAULT 0 NOT NULL,
    niqchiffre                   VARCHAR(255) DEFAULT '' NOT NULL,
    niqtstamp                    INT UNSIGNED DEFAULT 0 NOT NULL,
    schonberaten                 INT DEFAULT 0 NOT NULL,
    schonberatenvon              VARCHAR(255) DEFAULT '' NOT NULL,
    nachname                     VARCHAR(255) DEFAULT '' NOT NULL,
    vorname                      VARCHAR(255) DEFAULT '' NOT NULL,
    strasse                      VARCHAR(255) DEFAULT '' NOT NULL,
    plz                          VARCHAR(255) DEFAULT '' NOT NULL,
    ort                          VARCHAR(255) DEFAULT '' NOT NULL,
    email                        VARCHAR(255) DEFAULT '' NOT NULL,
    telefon                      VARCHAR(255) DEFAULT '' NOT NULL,
    gebdat                       VARCHAR(255) DEFAULT '' NOT NULL,
    lebensalter                  VARCHAR(255) DEFAULT '' NOT NULL,
    geburtsland                  VARCHAR(255) DEFAULT '' NOT NULL,
    geschlecht                   INT DEFAULT 0 NOT NULL,
    erste_staatsangehoerigkeit   VARCHAR(255) DEFAULT '' NOT NULL,
    zweite_staatsangehoerigkeit  VARCHAR(255) DEFAULT '' NOT NULL,
    einreisejahr                 VARCHAR(255) DEFAULT '' NOT NULL,
    wohnsitz_deutschland         INT DEFAULT 0 NOT NULL,
    wohnsitz_nein_in             VARCHAR(255) DEFAULT '' NOT NULL,
    sonstigerstatus              VARCHAR(255) DEFAULT '' NOT NULL,

    deutschkenntnisse            INT DEFAULT 0 NOT NULL,
    zertifikat_sprachniveau      VARCHAR(255) DEFAULT '' NOT NULL,
    weiteresprachkenntnisse      VARCHAR(255) DEFAULT '' NOT NULL,

    erwerbsstatus                INT DEFAULT 0 NOT NULL,
    leistungsbezugjanein         INT DEFAULT 0 NOT NULL,
    leistungsbezug               VARCHAR(255) DEFAULT '' NOT NULL,

    name_berater_a_a             VARCHAR(255) DEFAULT '' NOT NULL,
    kontakt_berater_a_a          VARCHAR(255) DEFAULT '' NOT NULL,
    kundennummer_a_a             VARCHAR(255) DEFAULT '' NOT NULL,

    einw_anerkstelle             INT DEFAULT 0 NOT NULL,
    einw_anerkstelledatum        VARCHAR(255) DEFAULT '' NOT NULL,
    einw_anerkstellemedium       VARCHAR(255) DEFAULT '' NOT NULL,
    einw_anerkstellename         VARCHAR(255) DEFAULT '' NOT NULL,
    einw_anerkstellekontakt      VARCHAR(255) DEFAULT '' NOT NULL,

    einw_person                  INT DEFAULT 0 NOT NULL,
    einw_persondatum             VARCHAR(255) DEFAULT '' NOT NULL,
    einw_personmedium            VARCHAR(255) DEFAULT '' NOT NULL,
    einw_personname              VARCHAR(255) DEFAULT '' NOT NULL,
    einw_personkontakt           VARCHAR(255) DEFAULT '' NOT NULL,
    einw_personkontaktmail       VARCHAR(255) DEFAULT '' NOT NULL,

    aufenthaltsstatus            INT DEFAULT 0 NOT NULL,
    aufenthaltsstatusfreitext    TEXT NOT NULL,

    name_beratungsstelle         VARCHAR(255) DEFAULT '' NOT NULL,
    notizen                      TEXT NOT NULL,

    einwilligung                 TINYINT UNSIGNED DEFAULT 0 NOT NULL,
    nacherfassung                TINYINT UNSIGNED DEFAULT 0 NOT NULL,
    anonym                       TINYINT UNSIGNED DEFAULT 0 NOT NULL,
    verification_code            VARCHAR(255) DEFAULT '' NOT NULL,
    verification_date            INT UNSIGNED DEFAULT 0 NOT NULL,
    verification_ip              VARCHAR(45) DEFAULT '' NOT NULL,  
    anerkennungszuschussbeantragt VARCHAR(255) DEFAULT '' NOT NULL,
    wieberaten                   VARCHAR(255) DEFAULT '' NOT NULL,
    kooperationgruppe            VARCHAR(255) DEFAULT '' NOT NULL,

    beratungdatum                VARCHAR(255) DEFAULT '' NOT NULL,
    berater                      INT UNSIGNED DEFAULT 0,
    beratungsart                 VARCHAR(255) DEFAULT '' NOT NULL,
    beratungsartfreitext         TEXT NOT NULL,
    beratungsort                 VARCHAR(255) DEFAULT '' NOT NULL,
    beratungsdauer               VARCHAR(255) DEFAULT '' NOT NULL,

    beratungzu                   VARCHAR(255) DEFAULT '' NOT NULL,
    anerkennendestellen          TEXT NOT NULL,
    anerkennungsberatung         VARCHAR(255) DEFAULT '' NOT NULL,
    anerkennungsberatungfreitext TEXT NOT NULL,
    qualifizierungsberatung      VARCHAR(255) DEFAULT '' NOT NULL,
    qualifizierungsberatungfreitext TEXT NOT NULL,

    beratungnotizen              TEXT NOT NULL,
    erstberatungabgeschlossen    VARCHAR(255) DEFAULT '' NOT NULL,
    einwilligunginfo             TINYINT UNSIGNED DEFAULT 0,
    editexternsent               INT UNSIGNED DEFAULT 0 NOT NULL,
    anzloginfehlgeschlagen       SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
    neuedokumente                TINYINT UNSIGNED DEFAULT 0 NOT NULL,
    gruppenberatungen            INT UNSIGNED DEFAULT 0 NOT NULL,

    edittstamp                   INT UNSIGNED DEFAULT 0 NOT NULL,
    edituser                     INT UNSIGNED DEFAULT 0,
    tstamp                       INT UNSIGNED DEFAULT 0 NOT NULL,
    crdate                       INT UNSIGNED DEFAULT 0 NOT NULL,
    cruser_id                    INT UNSIGNED DEFAULT 0 NOT NULL,
    deleted                      TINYINT UNSIGNED DEFAULT 0 NOT NULL,
    hidden                       TINYINT UNSIGNED DEFAULT 0 NOT NULL,
    starttime                    INT UNSIGNED DEFAULT 0 NOT NULL,
    endtime                      INT UNSIGNED DEFAULT 0 NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid),

    -- Composite-Index für TYPO3-Standard-Queries (WHERE pid=? AND deleted=0 AND hidden=0)
    KEY idx_list_view (pid, deleted, hidden),

    -- Mandanten-Filter (alle TN einer Beratungsstelle)
    KEY idx_niqid_beratungsstelle (niqidberatungsstelle),

    -- Status-Filter in Listenansichten
    KEY idx_beratungsstatus (beratungsstatus),

    -- Berater-Zuweisung
    KEY idx_berater (berater),

    -- Reporting / Zeitreihen
    KEY idx_crdate (crdate),

    -- E-Mail-Lookup (Duplikatsprüfung, Verifizierung)
    KEY idx_email (email(191))

) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


#
# Table structure for table 'tx_iqtp13db_domain_model_abschluss'
#
CREATE TABLE tx_iqtp13db_domain_model_abschluss (

    uid                          INT NOT NULL AUTO_INCREMENT,
    pid                          INT DEFAULT 0 NOT NULL,

    teilnehmer                   INT UNSIGNED DEFAULT 0,

    abschlussart                 VARCHAR(255) DEFAULT '' NOT NULL,
    branche                      INT DEFAULT 0 NOT NULL,
    erwerbsland                  VARCHAR(255) DEFAULT '' NOT NULL,
    dauer_berufsausbildung       VARCHAR(255) DEFAULT '' NOT NULL,
    abschlussjahr                VARCHAR(255) DEFAULT '' NOT NULL,
    ausbildungsinstitution       VARCHAR(255) DEFAULT '' NOT NULL,
    ausbildungsort               VARCHAR(255) DEFAULT '' NOT NULL,
    abschluss                    VARCHAR(255) DEFAULT '' NOT NULL,
    berufserfahrung              INT DEFAULT 0 NOT NULL,
    deutscher_referenzberuf      VARCHAR(255) DEFAULT '' NOT NULL,
    referenzberufzugewiesen      VARCHAR(255) DEFAULT '' NOT NULL,
    wunschberuf                  VARCHAR(255) DEFAULT '' NOT NULL,

    sonstigerberuf               VARCHAR(255) DEFAULT '' NOT NULL,
    nregberuf                    VARCHAR(255) DEFAULT '' NOT NULL,

    antragstellungvorher         INT DEFAULT 0 NOT NULL,
    antragstellunggwpvorher      INT DEFAULT 0 NOT NULL,
    antragstellungzabvorher      INT DEFAULT 0 NOT NULL,

    antragstellungerfolgt        INT DEFAULT 0 NOT NULL,
    antragstellunggwpdatum       VARCHAR(255) DEFAULT '' NOT NULL,
    antragstellunggwpergebnis    INT DEFAULT 0 NOT NULL,
    antragstellungzabdatum       VARCHAR(255) DEFAULT '' NOT NULL,
    antragstellungzabergebnis    INT DEFAULT 0 NOT NULL,

    niquebertragung              VARCHAR(255) DEFAULT '' NOT NULL,

    tstamp                       INT UNSIGNED DEFAULT 0 NOT NULL,
    crdate                       INT UNSIGNED DEFAULT 0 NOT NULL,
    cruser_id                    INT UNSIGNED DEFAULT 0 NOT NULL,
    deleted                      TINYINT UNSIGNED DEFAULT 0 NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid),

    -- FK-Index: alle Abschlüsse eines Teilnehmers abrufen
    KEY idx_teilnehmer (teilnehmer),

    -- Branchenfilter für Auswertungen
    KEY idx_branche (branche),

    -- Soft-Delete
    KEY idx_deleted (deleted)

) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


#
# Table structure for table 'tx_iqtp13db_domain_model_folgekontakt'
#
CREATE TABLE tx_iqtp13db_domain_model_folgekontakt (

    uid             INT NOT NULL AUTO_INCREMENT,
    pid             INT DEFAULT 0 NOT NULL,

    datum           VARCHAR(255) DEFAULT '' NOT NULL,
    berater         INT UNSIGNED DEFAULT 0,

    notizen         TEXT NOT NULL,
    beratungsform   INT DEFAULT 0 NOT NULL,
    beratungsdauer  VARCHAR(255) DEFAULT '' NOT NULL,

    teilnehmer      INT UNSIGNED DEFAULT 0,

    tstamp          INT UNSIGNED DEFAULT 0 NOT NULL,
    crdate          INT UNSIGNED DEFAULT 0 NOT NULL,
    cruser_id       INT UNSIGNED DEFAULT 0 NOT NULL,
    deleted         TINYINT UNSIGNED DEFAULT 0 NOT NULL,
    hidden          TINYINT UNSIGNED DEFAULT 0 NOT NULL,
    starttime       INT UNSIGNED DEFAULT 0 NOT NULL,
    endtime         INT UNSIGNED DEFAULT 0 NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY idx_teilnehmer (teilnehmer),
    KEY idx_berater (berater),

    -- Häufigste Abfrage: aktive Folgekontakte eines TN
    KEY idx_teilnehmer_deleted (teilnehmer, deleted)

) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


#
# Table structure for table 'tx_iqtp13db_domain_model_dokument'
#
CREATE TABLE tx_iqtp13db_domain_model_dokument (

    uid         INT NOT NULL AUTO_INCREMENT,
    pid         INT DEFAULT 0 NOT NULL,

    name        VARCHAR(255) DEFAULT '' NOT NULL,
    beschreibung VARCHAR(255) DEFAULT '' NOT NULL,
    pfad        VARCHAR(255) DEFAULT '' NOT NULL,
    teilnehmer  INT UNSIGNED DEFAULT 0,
    tnfreigabe  TINYINT UNSIGNED DEFAULT 0 NOT NULL,

    tstamp      INT UNSIGNED DEFAULT 0 NOT NULL,
    crdate      INT UNSIGNED DEFAULT 0 NOT NULL,
    cruser_id   INT UNSIGNED DEFAULT 0 NOT NULL,
    deleted     TINYINT UNSIGNED DEFAULT 0 NOT NULL,
    hidden      TINYINT UNSIGNED DEFAULT 0 NOT NULL,
    starttime   INT UNSIGNED DEFAULT 0 NOT NULL,
    endtime     INT UNSIGNED DEFAULT 0 NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY idx_teilnehmer (teilnehmer),

    -- Portal-Filter: nur freigegebene Dokumente des TN
    KEY idx_tn_freigabe (teilnehmer, tnfreigabe, deleted)

) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


#
# Table structure for table 'tx_iqtp13db_domain_model_historie'
#
CREATE TABLE tx_iqtp13db_domain_model_historie (

    uid         INT NOT NULL AUTO_INCREMENT,
    pid         INT DEFAULT 0 NOT NULL,

    teilnehmer  INT UNSIGNED DEFAULT 0,
    property    VARCHAR(255) DEFAULT '' NOT NULL,
    oldvalue    TEXT NOT NULL,       
    newvalue    TEXT NOT NULL,
    berater     INT UNSIGNED DEFAULT 0,

    tstamp      INT UNSIGNED DEFAULT 0 NOT NULL,
    crdate      INT UNSIGNED DEFAULT 0 NOT NULL,
    cruser_id   INT UNSIGNED DEFAULT 0 NOT NULL,
    deleted     TINYINT UNSIGNED DEFAULT 0 NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY idx_teilnehmer (teilnehmer),
    KEY idx_property (property),
    KEY idx_crdate (crdate)

) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


#
# Table extension for table 'fe_groups'
#
CREATE TABLE fe_groups (
    niqbid                      VARCHAR(255) DEFAULT '' NOT NULL,
    betafeatures                INT UNSIGNED DEFAULT 0,
    nichtiq                     INT UNSIGNED DEFAULT 0,
    bundesland                  VARCHAR(255) DEFAULT '' NOT NULL,
    generalmail                 VARCHAR(255) DEFAULT '' NOT NULL,
    plzlist                     TEXT NOT NULL,   
    keywordlist                 TEXT NOT NULL,
    beratungsarten              VARCHAR(255) DEFAULT '' NOT NULL,
    einwilligungserklaerungsseite INT DEFAULT 0,
    avadresse                   TEXT NOT NULL,
    custominfotextmail          TEXT NOT NULL,
    custominfotextstart         TEXT NOT NULL,
    customlogourl               VARCHAR(255) DEFAULT '' NOT NULL
);


#
# Table extension for table 'fe_users'
#
CREATE TABLE fe_users (
    company VARCHAR(255) DEFAULT '' NOT NULL
);


#
# Table structure for table 'tx_iqtp13db_domain_model_berufe'
#
CREATE TABLE tx_iqtp13db_domain_model_berufe (
    uid         INT NOT NULL,
    pid         INT DEFAULT 0 NOT NULL,
    berufid     VARCHAR(64) DEFAULT '' NOT NULL,
    titel       VARCHAR(255) DEFAULT '' NOT NULL,
    langisocode CHAR(2) DEFAULT '' NOT NULL, 
    PRIMARY KEY (uid),
    KEY idx_beruf_lang (berufid, langisocode)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


#
# Table structure for table 'tx_iqtp13db_domain_model_staaten'
#
CREATE TABLE tx_iqtp13db_domain_model_staaten (
    uid         INT NOT NULL,
    pid         INT DEFAULT 0 NOT NULL,
    staatid     VARCHAR(64) DEFAULT '' NOT NULL,
    titel       VARCHAR(255) DEFAULT '' NOT NULL,
    langisocode CHAR(2) DEFAULT '' NOT NULL,
    PRIMARY KEY (uid),
    KEY idx_staat_lang (staatid, langisocode)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


#
# Table structure for table 'tx_iqtp13db_domain_model_ort'
#
CREATE TABLE tx_iqtp13db_domain_model_ort (
    uid         INT NOT NULL,
    pid         INT DEFAULT 0 NOT NULL,
    plz         CHAR(5) DEFAULT '' NOT NULL,  
    ort         VARCHAR(255) DEFAULT '' NOT NULL,
    bundesland  VARCHAR(255) DEFAULT '' NOT NULL,
    landkreis   VARCHAR(255) DEFAULT '' NOT NULL,
    lat         VARCHAR(20) DEFAULT NULL, 
    lon         VARCHAR(20) DEFAULT NULL,
    PRIMARY KEY (uid),
    KEY idx_plz (plz),
    KEY idx_bundesland (bundesland)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


#
# Table structure for table 'tx_iqtp13db_domain_model_branche'
#
CREATE TABLE tx_iqtp13db_domain_model_branche (
    uid         INT NOT NULL,
    pid         INT DEFAULT 0 NOT NULL,
    brancheid   INT DEFAULT 0 NOT NULL,
    brancheok   INT DEFAULT 0 NOT NULL,
    titel       VARCHAR(255) DEFAULT '' NOT NULL,
    langisocode CHAR(2) DEFAULT '' NOT NULL,
    PRIMARY KEY (uid),
    KEY idx_branche_lang (brancheid, langisocode)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


#
# Table structure for table 'tx_iqtp13db_domain_model_gruppenberatung'
#
CREATE TABLE tx_iqtp13db_domain_model_gruppenberatung (
    uid                             INT NOT NULL AUTO_INCREMENT,
    pid                             INT DEFAULT 0 NOT NULL,

    titel                           VARCHAR(255) DEFAULT '' NOT NULL,
    beschreibung                    TEXT,
    ort                             VARCHAR(255) DEFAULT '' NOT NULL,
    max_teilnehmer                  INT DEFAULT 0 NOT NULL,

    niqbid                          VARCHAR(255) DEFAULT '' NOT NULL,
    teilnehmer                      INT UNSIGNED DEFAULT 0 NOT NULL,

    beratungdatum                   VARCHAR(255) DEFAULT '' NOT NULL,
    anerkennendestellen             TEXT NOT NULL,
    beratungsarten                  VARCHAR(255) DEFAULT '' NOT NULL,
    berater                         INT UNSIGNED DEFAULT 0,
    beratungsdauer                  VARCHAR(255) DEFAULT '' NOT NULL,
    beratungzu                      VARCHAR(255) DEFAULT '' NOT NULL,
    anerkennungsberatung            VARCHAR(255) DEFAULT '' NOT NULL,
    qualifizierungsberatung         VARCHAR(255) DEFAULT '' NOT NULL,
    anerkennungsberatungfreitext    TEXT NOT NULL,
    qualifizierungsberatungfreitext TEXT NOT NULL,
    erstberatungabgeschlossen       VARCHAR(255) DEFAULT '' NOT NULL,

    tstamp      INT UNSIGNED DEFAULT 0 NOT NULL,
    crdate      INT UNSIGNED DEFAULT 0 NOT NULL,
    deleted     TINYINT UNSIGNED DEFAULT 0 NOT NULL,
    hidden      TINYINT UNSIGNED DEFAULT 0 NOT NULL,
    starttime   INT UNSIGNED DEFAULT 0 NOT NULL,
    endtime     INT UNSIGNED DEFAULT 0 NOT NULL,
    sorting     INT DEFAULT 0 NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY idx_niqbid (niqbid),
    KEY idx_berater (berater),
    KEY idx_list_view (pid, deleted, hidden)

) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


#
# Table structure for table 'tx_iqtp13db_teilnehmer_gruppenberatung_mm'
#
CREATE TABLE tx_iqtp13db_teilnehmer_gruppenberatung_mm (
    uid_local       INT UNSIGNED DEFAULT 0 NOT NULL,
    uid_foreign     INT UNSIGNED DEFAULT 0 NOT NULL,
    sorting         INT UNSIGNED DEFAULT 0 NOT NULL,
    sorting_foreign INT UNSIGNED DEFAULT 0 NOT NULL,

    PRIMARY KEY (uid_local, uid_foreign), 
    KEY idx_foreign (uid_foreign)     

) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE tx_iqtp13db_domain_model_statistik_cache (
    uid             INT NOT NULL AUTO_INCREMENT,
    pid             INT DEFAULT 0 NOT NULL,

    cache_key       VARCHAR(255) DEFAULT '' NOT NULL,
    niqbid          VARCHAR(255) DEFAULT '' NOT NULL,
    bundesland      VARCHAR(255) DEFAULT '' NOT NULL,
    bezugsjahr      SMALLINT UNSIGNED DEFAULT 0 NOT NULL,
    metric          VARCHAR(128) DEFAULT '' NOT NULL,   
    wert_json       JSON NOT NULL,  

    generated_at    INT UNSIGNED DEFAULT 0 NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid),
    UNIQUE KEY idx_cache_key (cache_key),           
    KEY idx_niqbid_zeit (niqbid, bezugsjahr)

) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;