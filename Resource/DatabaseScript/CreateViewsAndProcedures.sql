USE KinoDaten;

CREATE OR REPLACE VIEW v_Account AS 
	SELECT CONVERT(t_User.BenutzerName USING latin1) COLLATE latin1_general_cs AS Username, t_User.Passwort AS Password, t_Typ.Typ AS Typ FROM t_User 
    INNER JOIN t_Typ 
    ON t_User.TypID = t_Typ.ID;

CREATE OR REPLACE VIEW v_KinoOrt AS
	SELECT t_Kino.ID, t_Kino.Kinoname, t_Stadt.Ort FROM t_Kino INNER JOIN t_Stadt ON t_Kino.StadtID = t_Stadt.ID;
    
CREATE OR REPLACE VIEW v_Kino AS
	SELECT t_Kino.ID AS ID, t_Kino.Kinoname AS Kinoname, t_Kino.TelNr AS TelNr, t_Kino.Strasse AS Strasse, t_Stadt.PLZ AS PLZ, t_Stadt.Ort AS Ort FROM t_Kino INNER JOIN t_Stadt ON t_Kino.StadtID = t_Stadt.ID;
    
CREATE OR REPLACE VIEW v_Mitarbeiter AS
	SELECT t_User.ID AS ID, t_User.Benutzername AS BN, t_User.Vorname AS VN, t_User.MailAdresse AS Mail, t_User.Nachname AS NN, t_User.Strasse AS STR , t_Stadt.PLZ AS PLZ, t_Stadt.Ort AS Ort, t_Typ.Typ FROM t_User INNER JOIN t_Stadt ON t_User.StadtID = t_Stadt.ID INNER JOIN t_Typ ON t_User.TypID = t_Typ.ID WHERE t_User.TypID = 2 OR t_User.TypID = 1;    

CREATE OR REPLACE VIEW v_FilmAuffuerung AS
	SELECT t_kino.Kinoname AS Kinoname, t_saal.Saalname AS Saalname, t_film.Titel AS Filmname, t_film.Dauer AS Dauer, DATE_FORMAT(t_FilmAuffuerung.AuffZeit, '%b %d %Y &h:%i') AS Filmbeginn FROM t_kino INNER JOIN t_saal ON t_kino.ID = t_saal.KinoID INNER JOIN t_FilmAuffuerung ON t_saal.ID = t_FilmAuffuerung.SaalID INNER JOIN t_film ON t_FilmAuffuerung.FilmID = t_film.ID; 
/*noch nicht getestet*/
	CREATE OR REPLACE VIEW v_FilmAuffuerung AS
	SELECT t_kino.Kinoname AS Kinoname, t_saal.Saalname AS Saalname, t_film.Titel AS Filmname, t_film.Dauer AS Dauer, DATE_FORMAT(t_FilmAuffuerung.AuffZeit, '%d %b %Y') AS Filmbeginndat, DATE_FORMAT(t_FilmAuffuerung.AuffZeit, '%H:%i') AS Filmbeginn FROM t_kino INNER JOIN t_saal ON t_kino.ID = t_saal.KinoID INNER JOIN t_FilmAuffuerung ON t_saal.ID = t_FilmAuffuerung.SaalID INNER JOIN t_film ON t_FilmAuffuerung.FilmID = t_film.ID; 
/*noch nicht getestet*/
/*CREATE OR REPLACE VIEW v_FilmAuffuerung AS
	SELECT t_FilmAuffuerung.ID AS AUFID, t_FilmAuffuerung.SaalID AS SaalID, t_FilmAuffuerung.FilmID AS FilmID, t_FilmAuffuerung.AuffZeit AS Wann FROM t_FilmAuffuerung;
*/
DROP PROCEDURE IF EXISTS p_ManipulateCinema;

DELIMITER $$
CREATE PROCEDURE p_ManipulateCinema(IN kid BIGINT UNSIGNED, IN kn VARCHAR(100) , IN str VARCHAR(250), IN post VARCHAR(10), IN city VARCHAR(250), IN tel VARCHAR(25))
BEGIN
	DECLARE sid BIGINT UNSIGNED;
	SELECT ID FROM t_Stadt WHERE t_Stadt.PLZ = post AND t_Stadt.Ort = city limit 1 INTO sid;
    if(sid > 0) THEN
		if kid <= 0 THEN 
			INSERT INTO t_Kino(Kinoname, Strasse, StadtID, TelNr) values (kn, str,sid, tel);
		ELSE
			UPDATE t_Kino SET Kinoname=kn, Strasse=str, StadtID=sid, TelNr=tel WHERE ID=kid;
		END IF;
	END IF;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS p_DeleteCinema;
DELIMITER $$
CREATE PROCEDURE p_DeleteCinema(IN kid BIGINT UNSIGNED)
BEGIN
	DELETE FROM KinoDaten.t_Kino WHERE ID = kid;
END $$
DELIMITER ;


DROP PROCEDURE IF EXISTS p_ManipulateMovie;


DELIMITER $$
CREATE PROCEDURE p_ManipulateMovie(IN mid BIGINT UNSIGNED, IN mTitel VARCHAR(100) , IN mDauer  int unsigned, IN mPreis  decimal (5,2) UNSIGNED, IN mBeschreibung text)
BEGIN
		if mid <= 0 THEN 
			INSERT INTO t_Film(Titel, Dauer, Preis, Beschreibung) values (mTitel, mDauer,mPreis, mBeschreibung);
		ELSE
			UPDATE t_Film SET Titel=mTitel, Dauer=mDauer, Preis=mPreis, Beschreibung=mBeschreibung WHERE ID=mid;
	END IF;
END $$

DELIMITER ;


DROP PROCEDURE IF EXISTS p_DeleteMovie;
DELIMITER $$
CREATE PROCEDURE p_DeleteMovie(IN fid BIGINT UNSIGNED)
BEGIN
	DELETE FROM KinoDaten.t_Film WHERE ID = fid;
END $$
DELIMITER ;


DROP PROCEDURE IF EXISTS p_ManipulateUser;

DELIMITER $$
CREATE PROCEDURE p_ManipulateUser(IN uid BIGINT UNSIGNED, IN bn VARCHAR(30) , IN str VARCHAR(250), IN post VARCHAR(10), IN city VARCHAR(250), IN tid BIGINT unsigned, IN mail VARCHAR(100), IN vn VARCHAR(100),IN nn VARCHAR(100), IN pass VARCHAR (500))
BEGIN
	DECLARE sid BIGINT UNSIGNED;
	SELECT ID FROM t_Stadt WHERE t_Stadt.PLZ = post AND t_Stadt.Ort = city limit 1 INTO sid;
    if(sid > 0) THEN
		if uid <= 0 THEN 
			INSERT INTO t_User(Benutzername, Passwort, StadtID, TypID, Vorname, Nachname, MailAdresse, Strasse) values (bn, pass,sid, tid, vn, nn, mail, str);
		ELSE
			IF(pass = '')
				THEN UPDATE t_User SET Benutzername=bn, Strasse=str, StadtID=sid, TypID=tid, Vorname=vn, Nachname=nn, MailAdresse = mail WHERE ID=uid;
			ELSE
				UPDATE t_User SET Benutzername=bn, Strasse=str, StadtID=sid, TypID=tid, Vorname=vn, Nachname=nn, MailAdresse = mail, Passwort=pass WHERE ID=uid;
			END IF;
		END IF;
	END IF;
END $$
DELIMITER ;


DROP PROCEDURE IF EXISTS p_DeleteUser;
DELIMITER $$
CREATE PROCEDURE p_DeleteUser(IN uid BIGINT UNSIGNED)
BEGIN
	DELETE FROM KinoDaten.t_User WHERE ID = uid;
END $$
DELIMITER ;




