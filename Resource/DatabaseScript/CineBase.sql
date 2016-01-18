CREATE DATABASE IF NOT EXISTS KinoDaten 
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

USE KinoDaten;

CREATE TABLE IF NOT EXISTS t_Land (
    ID SERIAL PRIMARY KEY,
    Land VARCHAR(150) NOT NULL,
    UNIQUE uk_Land (Land)
);

CREATE TABLE IF NOT EXISTS t_Stadt (
    ID SERIAL PRIMARY KEY,
    PLZ VARCHAR(10) NOT NULL,
    Ort VARCHAR (250) NOT NULL,
    LandID BIGINT UNSIGNED NOT NULL,
    CONSTRAINT fk_StadtLand FOREIGN KEY (LandID) REFERENCES t_Land(ID),
    UNIQUE uk_StadtPLZLand (PLZ, Ort, LandID)
);

CREATE TABLE IF NOT EXISTS t_Kino(
    ID SERIAL PRIMARY KEY,
    Kinoname VARCHAR (100) NOT NULL,
    Strasse VARCHAR(250) NOT NULL,
    StadtID BIGINT UNSIGNED NOT NULL,
    TelNr VARCHAR(25) NOT NULL,
    CONSTRAINT fk_KinoAdresseStadt FOREIGN KEY (StadtID) REFERENCES t_Stadt(ID)
    /*CONSTRAINT fk_KinoAdresse FOREIGN KEY (AdresseID) REFERENCES t_Adresse(ID),*/
    /*UNIQUE uk_CinemaAddress (AddressID)*/
);

CREATE TABLE IF NOT EXISTS t_Saal(
    ID SERIAL PRIMARY KEY,
    KinoID BIGINT UNSIGNED NOT NULL,
    Saalname VARCHAR(50) NOT NULL,
    Reihe INT UNSIGNED NOT NULL,
    Sitze INT UNSIGNED NOT NULL,
    CONSTRAINT fk_KinoSaal FOREIGN KEY (KinoID) REFERENCES t_Kino(ID),
    UNIQUE uk_Saal (Saalname, KinoID)
);

CREATE TABLE IF NOT EXISTS t_Film (
    ID SERIAL PRIMARY KEY,
    Titel VARCHAR(150) NOT NULL,
    Beschreibung TEXT,
    Dauer INT UNSIGNED NOT NULL,
    Preis Decimal(3,2) NOT NULL
);

CREATE TABLE IF NOT EXISTS t_FilmAuffuerung (
    ID SERIAL PRIMARY KEY,
    FilmID BIGINT UNSIGNED NOT NULL,
    SaalID BIGINT UNSIGNED NOT NULL,
    AuffZeit DATETIME NOT NULL,
    CONSTRAINT fk_FilmAuff FOREIGN KEY (FilmID) REFERENCES t_Film(ID),
    CONSTRAINT fk_AuffSaal FOREIGN KEY (SaalID) REFERENCES t_Saal(ID),
    UNIQUE uk_FilmAuffuerung (FilmID, SaalID, AuffZeit)
);

CREATE TABLE IF NOT EXISTS t_Typ (
    ID SERIAL PRIMARY KEY,
    Typ VARCHAR (30) NOT NULL
);

CREATE TABLE IF NOT EXISTS t_User (
    ID SERIAL PRIMARY KEY,
    Benutzername VARCHAR (30) NOT NULL,
    Passwort VARCHAR(500) NOT NULL,
    Vorname VARCHAR (100) NOT NULL,
    Nachname VARCHAR (100) NOT NULL,
    MailAdresse VARCHAR(100) NOT NULL,
    Strasse VARCHAR(250) NOT NULL,
    StadtID BIGINT UNSIGNED NOT NULL,
    TypID BIGINT UNSIGNED NOT NULL,
    CONSTRAINT fk_UserAdresseStadt FOREIGN KEY (StadtID) REFERENCES t_Stadt(ID),
    CONSTRAINT fk_UserTyp FOREIGN KEY (TypID) REFERENCES t_Typ(ID),
    UNIQUE uk_Username (Benutzername),
    UNIQUE uk_MailAdresse (MailAdresse)
);

CREATE TABLE IF NOT EXISTS t_Ticket (
    ID SERIAL PRIMARY KEY,
    AuffuerungID BIGINT UNSIGNED NOT NULL,
    Reihe INT UNSIGNED NOT NULL,
	Platz INT UNSIGNED NOT NULL,
    Verkaufsdatum Date,
    KundeID BIGINT UNSIGNED NOT NULL,
    CONSTRAINT fk_AuffTicket FOREIGN KEY (AuffuerungID) REFERENCES t_FilmAuffuerung(ID),
    CONSTRAINT fk_TicketKunde FOREIGN KEY (KundeID) REFERENCES t_User(ID),
    UNIQUE uk_Ticket (AuffuerungID, Reihe, Platz)
);

CREATE OR REPLACE VIEW v_Account AS 
	SELECT CONVERT(t_User.BenutzerName USING latin1) COLLATE latin1_general_cs AS Username, t_User.Passwort AS Password, t_Typ.Typ AS Typ FROM t_User 
    INNER JOIN t_Typ 
    ON t_User.TypID = t_Typ.ID;

CREATE OR REPLACE VIEW v_KinoOrt AS
	SELECT t_Kino.ID, t_Kino.Kinoname, t_Stadt.Ort FROM t_Kino INNER JOIN t_Stadt ON t_Kino.StadtID = t_Stadt.ID;
    
CREATE OR REPLACE VIEW v_Kino AS
	SELECT t_Kino.ID AS ID, t_Kino.Kinoname AS Kinoname, t_Kino.TelNr AS TelNr, t_Kino.Strasse AS Strasse, t_Stadt.PLZ AS PLZ, t_Stadt.Ort AS Ort FROM t_Kino INNER JOIN t_Stadt ON t_Kino.StadtID = t_Stadt.ID;

/*CREATE OR REPLACE VIEW v_FilmAuffuerung AS
	SELECT ID t_FilmAuffuerung*/
    
DROP PROCEDURE IF EXISTS p_InsertKino;
DELIMITER $$
CREATE PROCEDURE p_InsertKino(IN cid BIGINT UNSIGNED, IN kn VARCHAR(100) , IN str VARCHAR(250), IN city BIGINT UNSIGNED, IN tel VARCHAR(25))
BEGIN
	if cid <= 0 THEN 
        INSERT INTO t_Kino(Kinoname, Strasse, StadtID, TelNr) values (kn, str,city, tel);
	ELSE
		UPDATE t_Kino SET Kinoname=kn, Strasse=str, StadtID=city, TelNr=tel WHERE ID=cid;
	END IF;
END $$
DELIMITER ;
