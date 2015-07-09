<?php

/**
 * Created by PhpStorm.
 * User: Sasa
 * Date: 29/06/2015
 * Time: 10:43
 */
class Predlozak extends DB
{
    protected static $table_name="predlosci";

    public $id;
    public $naziv;
    public $platitelj_id;
    public $model_odobrenja_id;
    public $broj_odobrenja;
    public $primatelj_id;
    public $iban_primatelja;
    public $model_zaduzenja_id;
    public $broj_zaduzenja;
    public $zbrojni_nalog_id;

    public static function korisnik()
    {
        $sql = ("SELECT p.id, p.naziv, r.naziv, r2.naziv naziv2, p.iznos, sn.sifra
                 FROM predlosci p
                 LEFT JOIN racuni r ON p.platitelj_id = r.id
                 LEFT JOIN racuni r2 ON p.primatelj_id = r.id
                 LEFT JOIN sifre_namjene sn ON p.sifra_namjene_id = sn.id");
        $result = Racun::execute_query($sql,"klasa");

        return !empty($result) ? $result : false;
    }

       public static function all($id)
    {
        $sql = ("SELECT  p.id pid, p.naziv naziv_predloska, r.naziv platitelj, r2.naziv primatelj, pl.iznos, pl.sifra_namjene_id sifra, pl.opis, pl.datum_izvrsenja
                 FROM  predlosci p
                 LEFT JOIN placanje pl ON pl.predlozak_id = p.id
                 LEFT JOIN racuni r ON p.platitelj_id = r.id
                 LEFT JOIN racuni r2 ON p.primatelj_id = r.id
                 WHERE platitelj_id = $id AND predlozak_nalog = FALSE");
        $result = Predlozak::execute_query($sql, "klasa");
        return !empty($result) ? $result : false;
    }

    public static function predlosci()
    {
        $sql = ("SELECT *, p.id pid
                 FROM  predlosci p
                 LEFT JOIN placanje pl ON pl.predlozak_id = p.id
                 WHERE pl.predlozak_nalog = FALSE ");
        $result = Predlozak::execute_query($sql, "klasa");
        return !empty($result) ? $result : false;
    }

    public static function nalozi($id = 0, $bool='TRUE')
    {
        $sql = "SELECT  p.id pid, p.naziv naziv_predloska, r.naziv platitelj,r.iban, r2.naziv primatelj, p.iban_primatelja, pl.iznos, pl.sifra_namjene_id sifra, pl.opis, pl.datum_izvrsenja, s.naziv naziv_sifre
                 FROM  predlosci p
                 LEFT JOIN placanje pl ON pl.predlozak_id = p.id
                 LEFT JOIN racuni r ON p.platitelj_id = r.id
                 LEFT JOIN racuni r2 ON p.primatelj_id = r2.id
                 LEFT JOIN sifre_namjene s ON pl.sifra_namjene_id = s.id WHERE ";
        if($id){
            $sql.=   "platitelj_id = $id  AND ";
        }
        $sql.=   "predlozak_nalog = $bool";
        $result = Predlozak::execute_query($sql, "klasa");
        return !empty($result) ? $result : false;
    }

    public static function find_by_id($id)
    {
        $sql = ("SELECT *,p.id prid
                 FROM  predlosci p
                 LEFT JOIN placanje pl ON pl.predlozak_id = p.id
                 WHERE pl.id = $id");
        $result = DB::execute_query($sql, "objekt");
        return !empty($result) ? $result : false;
    }

    public static function find_by_id3($id)
    {
        $sql = ("SELECT *,p.id prid
                 FROM  predlosci p
                 LEFT JOIN placanje pl ON pl.predlozak_id = p.id
                 WHERE p.id = $id");
        $result = DB::execute_query($sql, "objekt");
        return !empty($result) ? $result : false;
    }

    public static function find_by_id2($id) {
        /*$check = get_called_class();
        $check2 = parent::class;*/
        $stmt = self::getInstance()->query("SELECT * FROM ".static::$table_name." WHERE id={$id}");
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
        return !empty($result) ? array_shift($result) : false;
    }

    public static function placanja($id)
    {
        $sql = ("SELECT *
                 FROM  placanje pl
                 WHERE pl.predlozak_id = $id AND pl.predlozak_nalog = false");
        $result = Predlozak::execute_query($sql, "objekt");
        return !empty($result) ? $result : false;
    }


    public function save()
    {
        return isset($this->id) ? $this->update() : $this->create();
    }

    public function create()  {

        /*** prepare the SQL statement ***/
        $stmt = Predlozak::getInstance()->prepare("INSERT INTO ".self::$table_name."(naziv, platitelj_id, model_odobrenja_id, broj_odobrenja,model_zaduzenja_id, broj_zaduzenja, iban_primatelja) VALUES (:naziv, :platitelj_id, :model_odobrenja_id, :broj_odobrenja, :model_zaduzenja_id, :broj_zaduzenja, :iban_primatelja)");

        /*** bind the paramaters ***/
        $stmt->bindParam(':naziv', $this->naziv, PDO::PARAM_STR);
        $stmt->bindParam(':platitelj_id',$this->platitelj_id,PDO::PARAM_INT);
        $stmt->bindParam(':model_odobrenja_id',$this->model_odobrenja_id,PDO::PARAM_INT);
        $stmt->bindParam(':broj_odobrenja',$this->broj_odobrenja,PDO::PARAM_INT);
        //$stmt->bindParam(':primatelj_id',$this->primatelj_id,PDO::PARAM_INT);
        $stmt->bindParam(':model_zaduzenja_id',$this->model_zaduzenja_id,PDO::PARAM_INT);
        $stmt->bindParam(':broj_zaduzenja',$this->broj_zaduzenja,PDO::PARAM_INT);
        $stmt->bindParam(':iban_primatelja',$this->iban_primatelja);
/*        $stmt->bindParam(':iznos',$this->iznos,PDO::PARAM_INT);
        $stmt->bindParam(':sifra_namjene_id',$this->sifra_namjene_id,PDO::PARAM_INT);
        $stmt->bindParam(':opis_placanja',$this->opis_placanja,PDO::PARAM_STR);
        $stmt->bindParam(':datum_izvrsenja',$this->datum_izvrsenja,PDO::PARAM_INT);*/

        /*** execute the prepared statement ***/
        if($stmt->execute()){
            $_SESSION['last_inserted_id'] = Predlozak::getInstance()->lastInsertId('predlosci_id_seq');
            return true;
        } else {
            foreach(Predlozak::getInstance()->errorInfo() as $error)
            {
                echo $error.'<br />';
            }
            return false;
        }

    }

    public function update()
    {
        /*** prepare the SQL statement ***/
        $stmt = Predlozak::getInstance()->prepare("UPDATE ".self::$table_name." SET naziv = :naziv, platitelj_id = :platitelj_id, model_odobrenja_id = :model_odobrenja_id, broj_odobrenja =:broj_odobrenja, iban_primatelja=:iban_primatelja ,model_zaduzenja_id = :model_zaduzenja_id, broj_zaduzenja=:broj_zaduzenja WHERE id = :id");
        /*** bind the paramaters ***/

        $stmt->bindParam(':naziv', $this->naziv, PDO::PARAM_STR);
        $stmt->bindParam(':platitelj_id',$this->platitelj_id,PDO::PARAM_INT);
        $stmt->bindParam(':model_odobrenja_id',$this->model_odobrenja_id,PDO::PARAM_INT);
        $stmt->bindParam(':broj_odobrenja',$this->broj_odobrenja,PDO::PARAM_INT);
        //$stmt->bindParam(':primatelj_id',$this->primatelj_id,PDO::PARAM_INT);
        $stmt->bindParam(':model_zaduzenja_id',$this->model_zaduzenja_id,PDO::PARAM_INT);
        $stmt->bindParam(':broj_zaduzenja',$this->broj_zaduzenja,PDO::PARAM_INT);
        $stmt->bindParam(':iban_primatelja',$this->iban_primatelja);
        $stmt->bindParam(':id',$this->id,PDO::PARAM_INT);


        if($count = $stmt->execute()){
            return true;
        } else {
            foreach(Predlozak::getInstance()->errorInfo() as $error)
            {
                echo $error.'<br />';
            }
            return false;
        }

    }

    public static function delete($id)
    {

        $stmt = Predlozak::getInstance()->prepare("DELETE FROM ".self::$table_name." WHERE id = $id");

        //$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        if($stmt->execute()){
            return true;
        } else {
            foreach(Predlozak::getInstance()->errorInfo() as $error)
            {
                echo $error.'<br />';
            }
            return false;
        }

    }
}

