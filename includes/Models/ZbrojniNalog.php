<?php

/**
 * Created by PhpStorm.
 * User: Sasa
 * Date: 01/07/2015
 * Time: 08:00
 */
class ZbrojniNalog extends DB
{
    protected static $table_name="zbrojni_nalozi";

    public $id;
    public $naziv;

/*    public static function all()
    {
        $sql = ("SELECT *FROM  zbrojni_nalozi zn
        LEFT JOIN placanje pl ON pl.zbrojni_nalog_id = zn.id");
        $result = ZbrojniNalog::execute_query($sql, "klasa");
        return !empty($result) ? $result : false;
    }*/

    public static function all()
    {
        $sql = ("SELECT p.zbrojni_nalog_id id, zn.naziv, COUNT(p.zbrojni_nalog_id) brojnaloga, SUM(p.iznos) iznos
                 FROM placanje p
                 LEFT JOIN zbrojni_nalozi zn  ON p.zbrojni_nalog_id = zn.id
                 WHERE p.zbrojni_nalog_id NOTNULL
                 GROUP BY p.zbrojni_nalog_id, zn.naziv");
        $result = ZbrojniNalog::execute_query($sql, "klasa");
        return !empty($result) ? $result : false;
    }

    public static function grouped()
    {
        $sql = ("SELECT zn.id, zn.naziv, COUNT(p.zbrojni_nalog_id) brojnaloga, SUM(p.iznos) iznos
                 FROM placanje p
                 LEFT JOIN zbrojni_nalozi zn  ON p.zbrojni_nalog_id = zn.id
                 WHERE p.zbrojni_nalog_id NOTNULL
                 GROUP BY zn.id, zn.naziv");
        $result = ZbrojniNalog::execute_query($sql, "klasa");
        return !empty($result) ? $result : false;
    }

    public static function nalozi($id)
    {
        $sql = ("SELECT p.id, pr.platitelj_id, pr.model_odobrenja_id, pr.broj_odobrenja, pr.broj_zaduzenja, pr.iban_primatelja, p.iznos, p.sifra_namjene_id, p.opis, p.datum_izvrsenja, r.naziv platitelj, r.iban,s.naziv naziv_sifre
                 FROM placanje p
                 LEFT JOIN predlosci pr ON p.predlozak_id = pr.id
                 LEFT JOIN racuni r ON pr.platitelj_id = r.id
                 LEFT JOIN sifre_namjene s ON p.sifra_namjene_id = s.id
                 WHERE p.zbrojni_nalog_id = $id AND p.predlozak_nalog = TRUE");
        $result = ZbrojniNalog::execute_query($sql, "klasa");
        return !empty($result) ? $result : false;
    }

    public static function nalog($id)
    {
        $sql = ("SELECT p.id, pr.platitelj_id, pr.model_odobrenja_id, pr.broj_odobrenja, pr.broj_zaduzenja, pr.iban_primatelja, p.iznos, p.sifra_namjene_id, p.opis, p.datum_izvrsenja, r.naziv platitelj, r.iban,s.naziv naziv_sifre
                 FROM placanje p
                 LEFT JOIN predlosci pr ON p.predlozak_id = pr.id
                 LEFT JOIN racuni r ON pr.platitelj_id = r.id
                 LEFT JOIN sifre_namjene s ON p.sifra_namjene_id = s.id
                 WHERE p.id = $id AND p.predlozak_nalog = TRUE");
        $result = ZbrojniNalog::execute_query($sql, "klasa");
        return !empty($result) ? $result : false;
    }

    public function save()
    {
        return isset($this->id) ? $this->update() : $this->create();
    }

    public function create()  {

        /*** prepare the SQL statement ***/
        $stmt = ZbrojniNalog::getInstance()->prepare("INSERT INTO ".self::$table_name."(naziv) VALUES (:naziv)");

        /*** bind the paramaters ***/
        $stmt->bindParam(':naziv', $this->naziv);


        /*** execute the prepared statement ***/
        if($stmt->execute()){
            $_SESSION['last_inserted_id_zn'] = ZbrojniNalog::getInstance()->lastInsertId('zbrojni_nalozi_id_seq');
            return true;
        } else {
            foreach(ZbrojniNalog::getInstance()->errorInfo() as $error)
            {
                echo $error.'<br />';
            }
            return false;
        }

    }

    public function update()
    {
        /*** prepare the SQL statement ***/
        $stmt = ZbrojniNalog::getInstance()->prepare("UPDATE ".self::$table_name." SET naziv = :naziv WHERE id = :id");
        /*** bind the paramaters ***/

        $stmt->bindParam(':naziv', $this->naziv);

        if($count = $stmt->execute()){
            return true;
        } else {
            foreach(ZbrojniNalog::getInstance()->errorInfo() as $error)
            {
                echo $error.'<br />';
            }
            return false;
        }

    }

    public function delete($id)
    {

        $stmt = ZbrojniNalog::getInstance()->prepare("DELETE FROM ".self::$table_name." WHERE id = $id");

        //$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        if($stmt->execute()){
            return true;
        } else {
            foreach(ZbrojniNalog::getInstance()->errorInfo() as $error)
            {
                echo $error.'<br />';
            }
            return false;
        }

    }

}