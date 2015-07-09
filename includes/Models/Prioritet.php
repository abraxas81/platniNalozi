<?php

/**
 * Created by PhpStorm.
 * User: Sasa
 * Date: 16/06/2015
 * Time: 15:30
 */
class Prioritet extends DB {

    protected static $table_name="prioritet";

    public $id;
    public $naziv;


    //Metode koje su zajedničke za sve klase
    //ovo bi se moglo prebaciti u database klasu

    public static function find_by_user($id=0) {
        $stmt = Prioritet::getInstance()->query("SELECT * FROM ".self::$table_name." WHERE created_by={$id}");
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
        return !empty($result) ? $result : false;
    }

    //izvedeno je ovako tako da bi se prije kreiranja
    //ili updejtanja mogla izvršiti validacija ako to želimo
    public function save()
    {
        return isset($this->id) ? $this->update() : $this->create();
    }

    public function create()  {

        /*** prepare the SQL statement ***/
        $stmt = Prioritet::getInstance()->prepare("INSERT INTO ".self::$table_name."(naziv, tekst, created_by, created_at, expire_at) VALUES (:naziv, :tekst, :created_by, :created_at, :expire_at)");

        /*** bind the paramaters ***/
        $stmt->bindParam(':naziv', $this->naziv, PDO::PARAM_STR);
        $stmt->bindParam(':tekst',$this->tekst,PDO::PARAM_STR);
        $stmt->bindParam(':created_at', $this->created_at, PDO::PARAM_INT);
        $stmt->bindParam(':expire_at', $this->expire_at, PDO::PARAM_INT);
        $stmt->bindParam(':created_by', $this->created_by, PDO::PARAM_INT);


        /*** execute the prepared statement ***/
        if($stmt->execute()){
            $this->id = Prioritet::getInstance()->lastInsertId();
            return true;
        } else {
            foreach(Prioritet::getInstance()->errorInfo() as $error)
            {
                echo $error.'<br />';
            }
            return false;
        }

    }

    public function update()
    {
        /*** prepare the SQL statement ***/
        $stmt = Prioritet::getInstance()->prepare("UPDATE ".self::$table_name." SET naziv = :naziv, tekst = :tekst, created_at = :created_at, expire_at = :expire_at WHERE id = :id");
        echo $this->naziv;echo $this->tekst;
        /*** bind the paramaters ***/

        $stmt->bindParam(':naziv', $this->naziv, PDO::PARAM_STR);
        $stmt->bindParam(':tekst',$this->tekst,PDO::PARAM_STR);
        $stmt->bindParam(':created_at', $this->created_at, PDO::PARAM_INT);
        $stmt->bindParam(':expire_at', $this->expire_at, PDO::PARAM_INT);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        if($count = $stmt->execute()){
            return true;
        } else {
            foreach(Prioritet::getInstance()->errorInfo() as $error)
            {
                echo $error.'<br />';
            }
            return false;
        }

    }

    public function delete()
    {

        $stmt = Prioritet::getInstance()->prepare("DELETE FROM ".self::$table_name." WHERE id = :id");

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        if($stmt->execute()){
            return true;
        } else {
            foreach(Prioritet::getInstance()->errorInfo() as $error)
            {
                echo $error.'<br />';
            }
            return false;
        }

    }

}