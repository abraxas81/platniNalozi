<?php

/**
 * Created by PhpStorm.
 * User: Sasa
 * Date: 16/06/2015
 * Time: 15:30
 */
class ZadatakKorisnik extends DB {

    protected static $table_name="korisnik_zadatak";

    public $id;
    public $korisnik_id;
    public $zadatak_id;
    public $rijesen;
    public $created_at;
    public $expire_at;
    public $updated_at;
    public $deleted_at;

    //Metode koje su zajedničke za sve klase
    //ovo bi se moglo prebaciti u database klasu
    public static function all() {
        $result = ZadatakKorisnik::execute_query("SELECT k.ime,k.prezime,z.naziv,kz.id, kz.created_at,kz.updated_at,kz.expire_at
                                                  FROM ".self::$table_name." kz
                                                  LEFT JOIN korisnici k ON kz.korisnik_id = k.id
                                                  LEFT JOIN zadaci z ON kz.zadatak_id = z.id
                                                  ORDER BY kz.id","klasa");
        return $result;
    }

    public static function find_by_id($id) {
        $result = ZadatakKorisnik::execute_query("SELECT k.ime,k.prezime,z.naziv,kz.id, kz.created_at,kz.updated_at,kz.expire_at
                                                  FROM ".self::$table_name." kz
                                                  LEFT JOIN korisnici k ON kz.korisnik_id = k.id
                                                  LEFT JOIN zadaci z ON kz.zadatak_id = z.id
                                                  WHERE kz.zadatak_id = {$id}","klasa");
        return $result;
    }

    public static function groupByZadatakId() {
        $result = ZadatakKorisnik::execute_query("SELECT z.id, z.naziv, z.created_at, z.expire_at, COUNT(kz.zadatak_id) brojzadataka
                                                  FROM zadaci z
                                                  LEFT JOIN korisnik_zadatak kz ON kz.zadatak_id = z.id
                                                  GROUP BY kz.zadatak_id, z.id, z.naziv, z.created_at, z.expire_at","klasa");
        return $result;
    }

    public static function komentari($id) {
        $result = ZadatakKorisnik::execute_query("SELECT k.tekst,k.created_at,u.ime,u.prezime
                                                  FROM komentar_korisnik k
                                                  LEFT JOIN korisnici u ON k.korisnici_id = u.id
                                                  WHERE k.zadatak_id = {$id}","klasa");
        return $result;
    }

    public static function find_by_user($id=0) {
        $stmt = ZadatakKorisnik::getInstance()->query("SELECT * FROM ".self::$table_name." WHERE korisnik_id={$id}");
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
        return !empty($result) ? $result : false;
    }

    public static function find_by_user_task($id=0,$task) {
        $stmt = ZadatakKorisnik::getInstance()->query("SELECT * FROM ".self::$table_name." WHERE korisnik_id={$id} AND zadatak_id={$task}");
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return !empty($result) ? $result : false;
    }

    public static function find_by_task($id=0) {
        $stmt = ZadatakKorisnik::getInstance()->query("SELECT * FROM ".self::$table_name." WHERE zadatak_id={$id}");
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
        return !empty($result) ? $result : false;
    }
    //izvedeno je ovako tako da bi se prije kreiranja
    //ili updejtanja mogla izvršiti validacija ako to želimo

    public static function find_users_by_task($id)
    {
        $result = ZadatakKorisnik::execute_query("SELECT k.id, k.ime,k.prezime,kz.created_at FROM ".self::$table_name." kz LEFT JOIN korisnici k ON kz.korisnik_id = k.id WHERE kz.zadatak_id = {$id};","klasa");

        return $result;
    }

    public function save()
    {
        return isset($this->id) ? $this->update() : $this->create();
    }

    public function create()  {

        /*** prepare the SQL statement ***/
        $stmt = DB::getInstance()->prepare("INSERT INTO ".self::$table_name."(korisnik_id, zadatak_id, created_at, expire_at ,prioritet_id) VALUES (:korisnik_id, :zadatak_id, :created_at,:expire_at,:prioritet_id)");

        /*** bind the paramaters ***/
        $stmt->bindParam(':korisnik_id', $this->korisnik_id, PDO::PARAM_INT);
        $stmt->bindParam(':zadatak_id',$this->zadatak_id,PDO::PARAM_INT);
        $stmt->bindParam(':created_at',$this->created_at);
        $stmt->bindParam(':expire_at', $this->expire_at);
        $stmt->bindParam(':prioritet_id', $this->prioritet_id, PDO::PARAM_INT);



        /*** execute the prepared statement ***/
        if($stmt->execute()){
            $_SESSION['last_inserted_id_zk'] = ZadatakKorisnik::getInstance()->lastInsertId('korisnik_zadatak_id_seq');
            return true;
        } else {
            foreach(ZadatakKorisnik::getInstance()->errorInfo() as $error)
            {
                echo $error.'<br />';
            }
            return false;
        }

    }

    public function update()
    {
        /*** prepare the SQL statement ***/
        $stmt = DB::getInstance()->prepare("UPDATE ".self::$table_name." SET korisnik_id = :korisnik_id, zadatak_id = :zadatak_id, created_at = :created_at,expire_at = :expire_at WHERE zadatak_id = :zadatak_id");

        /*** bind the paramaters ***/

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':korisnik_id', $this->korisnik_id, PDO::PARAM_INT);
        $stmt->bindParam(':zadatak_id',$this->zadatak_id,PDO::PARAM_INT);
        $stmt->bindParam(':created_at',$this->created_at,PDO::PARAM_INT);
        $stmt->bindParam(':expire_at', $this->expire_at, PDO::PARAM_INT);

        if($count = $stmt->execute()){
            return true;
        } else {
            foreach(DB::getInstance()->errorInfo() as $error)
            {
                echo $error.'<br />';
            }
            return false;
        }

    }

    public static function update2($zadatak_id, $id ,$updated_at)
    {
        /*** prepare the SQL statement ***/
        $stmt = DB::getInstance()->prepare("UPDATE ".self::$table_name." SET updated_at = :updated_at WHERE korisnik_id = :korisnik_id AND zadatak_id = :zadatak_id");

        /*** bind the paramaters ***/

        $stmt->bindParam(':korisnik_id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':updated_at', $updated_at, PDO::PARAM_INT);
        //$stmt->bindParam(':expire_at', $expire_at, PDO::PARAM_INT);
        $stmt->bindParam(':zadatak_id', $zadatak_id, PDO::PARAM_INT);


        if($count = $stmt->execute()){
            return true;
        } else {
            foreach(ZadatakKorisnik::getInstance()->errorInfo() as $error)
            {
                echo $error.'<br />';
            }
            return false;
        }

    }

    public static function delete($id)
    {

        $stmt = ZadatakKorisnik::getInstance()->prepare("DELETE FROM ".self::$table_name." WHERE id = $id");

        if($stmt->execute()){
            return true;
        } else {
            foreach(ZadatakKorisnik::getInstance()->errorInfo() as $error)
            {
                echo $error.'<br />';
            }
            return false;
        }

    }

}