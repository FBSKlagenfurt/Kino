USE KinoDaten;

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
