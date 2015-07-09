<?php

class Komentar extends DB{

    protected static $table_name="komentar_korisnik";

    public $id;
    public $korisnici_id;
    public $zadatak_id;
    public $tekst;
    public $created_at;
    public $updated_at;
    public $deleted_at;

    public static function find_by_user($id=0) {
        $stmt = Komentar::getInstance()->query("SELECT * FROM ".self::$table_name." WHERE $korisnici_id={$id}");
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
        return !empty($result) ? $result : false;
    }

    public static function find_by_zadatak($id=0) {
        $stmt = Komentar::getInstance()->query("SELECT * FROM ".self::$table_name." WHERE $zadatak_id={$id}");
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
        return !empty($result) ? $result : false;
    }

    //Metode koje su zajedničke za sve klase
    //ovo bi se moglo prebaciti u database klasu
    public function save()
    {
        return isset($this->id) ? $this->update() : $this->create();
    }

    public function create()  {

        /*** prepare the SQL statement ***/
        $stmt = Komentar::getInstance()->prepare("INSERT INTO ".self::$table_name."(tekst, zadatak_id, korisnici_id, created_at, updated_at, deleted_at) VALUES (:tekst, :zadatak_id, :korisnici_id, :created_at, :updated_at, :deleted_at)");

        /*** bind the paramaters ***/
        $stmt->bindParam(':tekst',$this->tekst,PDO::PARAM_STR);
        $stmt->bindParam(':zadatak_id', $this->zadatak_id, PDO::PARAM_INT);
        $stmt->bindParam(':korisnici_id',$this->korisnici_id,PDO::PARAM_INT);
        $stmt->bindParam(':created_at', $this->created_at);
        $stmt->bindParam(':updated_at', $this->updated_at, PDO::PARAM_INT);
        $stmt->bindParam(':deleted_at', $this->deleted_at, PDO::PARAM_INT);


        /*** execute the prepared statement ***/
        if($stmt->execute()){
            //$_SESSION['last_inserted_id'] = Komentar::getInstance()->lastInsertId('komentar_korisnik_id_seq');
            return true;
        } else {
            foreach(Komentar::getInstance()->errorInfo() as $error)
            {
                echo $error.'<br />';
            }
            return false;
        }

    }

    public function update()
    {
        /*** prepare the SQL statement ***/
        $stmt = Komentar::getInstance()->prepare("UPDATE ".self::$table_name." SET tekst = :tekst, zadatak_id = :zadatak_id, korisnici_id = :korisnici_id, created_at = :created_at, updated_at = :updated_at, deleted_at = :deleted_at WHERE id = :id");
        /*** bind the paramaters ***/

        $stmt->bindParam(':tekst',$this->tekst,PDO::PARAM_STR);
        $stmt->bindParam(':zadatak_id', $this->zadatak_id, PDO::PARAM_INT);
        $stmt->bindParam(':korisnici_id',$this->korisnici_id,PDO::PARAM_INT);
        $stmt->bindParam(':created_at', $this->created_at);
        $stmt->bindParam(':updated_at', $this->updated_at, PDO::PARAM_INT);
        $stmt->bindParam(':deleted_at', $this->deleted_at, PDO::PARAM_INT);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        if($count = $stmt->execute()){
            return true;
        } else {
            foreach(Komentar::getInstance()->errorInfo() as $error)
            {
                echo $error.'<br />';
            }
            return false;
        }

    }

    public function delete()
    {

        $stmt = Komentar::getInstance()->prepare("DELETE FROM ".self::$table_name." WHERE id = :id");

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        if($stmt->execute()){
            return true;
        } else {
            foreach(Komentar::getInstance()->errorInfo() as $error)
            {
                echo $error.'<br />';
            }
            return false;
        }

    }
} 