<?php

/**
 * Created by PhpStorm.
 * User: Sasa
 * Date: 30/06/2015
 * Time: 08:28
 */
class Placanje extends DB
{
    protected static $table_name="placanje";

    public $id;
    public $predlozak_nalog;
    public $predlozak_id;
    public $iznos;
    public $sifra_namjene_id;
    public $opis;
    public $datum_izvrsenja;
    public $sifra_valute_id;
    public $zbrojni_nalog_id;

    public static function placanja($id)
    {
        $stmt = self::getInstance()->query("SELECT *
                                            FROM  placanje pl
                                            WHERE pl.predlozak_id = $id AND pl.predlozak_nalog = false");
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }

    public static function find_by_id2($id) {
        /*$check = get_called_class();
        $check2 = parent::class;*/
        $stmt = self::getInstance()->query("SELECT * FROM placanje WHERE predlozak_id = $id AND predlozak_nalog = false");
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
        return !empty($result) ? array_shift($result) : false;
    }

    public static function nalog($id)
    {
        $sql = ("SELECT p.id, pr.platitelj_id, pr.model_odobrenja_id, pr.broj_odobrenja, pr.broj_zaduzenja, pr.iban_primatelja, p.iznos, p.sifra_namjene_id, p.opis, p.datum_izvrsenja, r.naziv platitelj, r.iban,s.naziv naziv_sifre
                 FROM placanje p
                 LEFT JOIN predlosci pr ON p.predlozak_id = pr.id
                 LEFT JOIN racuni r ON pr.platitelj_id = r.id
                 LEFT JOIN sifre_namjene s ON p.sifra_namjene_id = s.id
                 WHERE p.id = $id AND p.predlozak_nalog = TRUE");
        $result = Placanje::execute_query($sql, "klasa");
        return !empty($result) ? $result : false;
    }
    
    public function save()
    {
        return isset($this->id) ? $this->update() : $this->create();
    }

    public function create()  {

        /*** prepare the SQL statement ***/
        $stmt = Placanje::getInstance()->prepare("INSERT INTO ".self::$table_name."(iznos, sifra_namjene_id, opis, datum_izvrsenja, predlozak_nalog, predlozak_id, sifra_valute_id, zbrojni_nalog_id) VALUES (:iznos, :sifra_namjene_id, :opis, :datum_izvrsenja, :predlozak_nalog, :predlozak_id, :sifra_valute_id, :zbrojni_nalog_id)");

        /*** bind the paramaters ***/

        $stmt->bindParam(':predlozak_id',$this->predlozak_id,PDO::PARAM_INT);
        $stmt->bindParam(':predlozak_nalog',$this->predlozak_nalog,PDO::PARAM_INT);
        $stmt->bindParam(':iznos',$this->iznos,PDO::PARAM_INT);
        $stmt->bindParam(':sifra_valute_id',$this->sifra_valute_id,PDO::PARAM_INT);
        $stmt->bindParam(':sifra_namjene_id',$this->sifra_namjene_id,PDO::PARAM_INT);
        $stmt->bindParam(':opis',$this->opis,PDO::PARAM_STR);
        $stmt->bindParam(':datum_izvrsenja',$this->datum_izvrsenja,PDO::PARAM_INT);
        $stmt->bindParam(':zbrojni_nalog_id',$this->zbrojni_nalog_id,PDO::PARAM_INT);


        /*** execute the prepared statement ***/
        if($stmt->execute()){
            $_SESSION['last_inserted_id'] = Placanje::getInstance()->lastInsertId('placanje_id_seq');
            return true;
        } else {
            foreach(Placanje::getInstance()->errorInfo() as $error)
            {
                echo $error.'<br />';
            }
            return false;
        }

    }

    public function update()
    {
        /*** prepare the SQL statement ***/
        $stmt = Placanje::getInstance()->prepare("UPDATE ".self::$table_name." SET iznos = :iznos, sifra_namjene_id = :sifra_namjene_id, opis = :opis, datum_izvrsenja = :datum_izvrsenja, predlozak_nalog = :predlozak_nalog, predlozak_id=:predlozak_id, sifra_valute_id=:sifra_valute_id, zbrojni_nalog_id=:zbrojni_nalog_id WHERE id = :id");
        /*** bind the paramaters ***/

        $stmt->bindParam(':predlozak_id',$this->predlozak_id,PDO::PARAM_INT);
        $stmt->bindParam(':predlozak_nalog',$this->predlozak_nalog,PDO::PARAM_INT);
        $stmt->bindParam(':iznos',$this->iznos,PDO::PARAM_INT);
        $stmt->bindParam(':sifra_valute_id',$this->sifra_valute_id,PDO::PARAM_INT);
        $stmt->bindParam(':sifra_namjene_id',$this->sifra_namjene_id,PDO::PARAM_INT);
        $stmt->bindParam(':opis',$this->opis,PDO::PARAM_STR);
        $stmt->bindParam(':datum_izvrsenja',$this->datum_izvrsenja,PDO::PARAM_INT);
        $stmt->bindParam(':id',$this->id,PDO::PARAM_INT);
        $stmt->bindParam(':zbrojni_nalog_id',$this->zbrojni_nalog_id,PDO::PARAM_INT);

        if($count = $stmt->execute()){
            return true;
        } else {
            foreach(Placanje::getInstance()->errorInfo() as $error)
            {
                echo $error.'<br />';
            }
            return false;
        }

    }

    public static function delete($id)
    {

        $stmt = Placanje::getInstance()->prepare("DELETE FROM ".self::$table_name." WHERE id = $id");

        //$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        if($stmt->execute()){
            return true;
        } else {
            foreach(Placanje::getInstance()->errorInfo() as $error)
            {
                echo $error.'<br />';
            }
            return false;
        }

    }

}