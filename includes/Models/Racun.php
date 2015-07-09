<?php
class Racun extends DB
{
    protected static $table_name="racuni";

    public $id;
    public $naziv;
    public $iban;
    public $korisnik_id;

    public static function korisnik($id)
    {
        $sql = ("SELECT * FROM racuni WHERE korisnik_id = $id");
        $result = Racun::execute_query($sql,"klasa");

        return $result;
    }

    public function save()
    {
        return isset($this->id) ? $this->update() : $this->create();
    }

    public function create()  {

        /*** prepare the SQL statement ***/
        $stmt = Racun::getInstance()->prepare("INSERT INTO ".self::$table_name."(naziv, iban, korisnik_id) VALUES (:naziv, :iban, :korisnik_id)");

        /*** bind the paramaters ***/
        $stmt->bindParam(':naziv', $this->naziv, PDO::PARAM_STR);
        $stmt->bindParam(':iban',$this->iban,PDO::PARAM_STR);
        $stmt->bindParam(':korisnik_id',$this->korisnik_id,PDO::PARAM_INT);

        /*** execute the prepared statement ***/
        if($stmt->execute()){
            $_SESSION['last_inserted_id'] = Racun::getInstance()->lastInsertId('racuni_id_seq');
            return true;
        } else {
            foreach(Racun::getInstance()->errorInfo() as $error)
            {
                echo $error.'<br />';
            }
            return false;
        }

    }

    public function update()
    {
        /*** prepare the SQL statement ***/
        $stmt = Racun::getInstance()->prepare("UPDATE ".self::$table_name." SET naziv = :naziv, iban = :iban, korisnik_id = :korisnik_id WHERE id = :id");
        /*** bind the paramaters ***/

        $stmt->bindParam(':naziv', $this->naziv, PDO::PARAM_STR);
        $stmt->bindParam(':iban',$this->iban,PDO::PARAM_STR);
        $stmt->bindParam(':korisnik_id',$this->korisnik_id,PDO::PARAM_INT);
        $stmt->bindParam(':id',$this->id, PDO::PARAM_INT);

        if($count = $stmt->execute()){
            return true;
        } else {
            foreach(Racun::getInstance()->errorInfo() as $error)
            {
                echo $error.'<br />';
            }
            return false;
        }

    }

    public function delete($id)
    {
        $stmt = Racun::getInstance()->prepare("DELETE FROM ".self::$table_name." WHERE id = $id");

        //$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        if($stmt->execute()){
            return true;
        } else {
            foreach(Racun::getInstance()->errorInfo() as $error)
            {
                echo $error.'<br />';
            }
            return false;
        }

    }
}

