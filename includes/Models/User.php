<?php

class User extends DB {
	
	protected static $table_name="korisnici";

	public $id;
	public $email;
	public $username;
	public $password;
    /*public $created_at;
    public $updated_at;
    public $deleted_at;*/
	
  public function full_name() {
    if(isset($this->ime) && isset($this->prezime)) {
      return $this->ime . " " . $this->prezime;
    } else {
      return "";
    }
  }

	public static function authenticate($username="", $password="") {

    $sql  = "SELECT * FROM ".self::$table_name." ";
    $sql .= "WHERE username = '{$username}' ";
    $sql .= "AND password = '{$password}' ";
    $sql .= "LIMIT 1";
    $stmt = DB::getInstance()->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    return !empty($result) ? array_shift($result) : false;
	}

	//Metode koje su zajedničke za sve klase
    //ovo bi se moglo prebaciti u database klasu

    public function save()
    {
    return isset($this->id) ? $this->update() : $this->create();
    }

    public function create()  {

      /*** prepare the SQL statement ***/
      $stmt = DB::getInstance()->prepare("INSERT INTO ".self::$table_name."(ime, prezime, username, password) VALUES (:ime, :prezime, :username, :password)");

      /*** bind the paramaters ***/
      $stmt->bindParam(':ime', $this->ime, PDO::PARAM_STR);
      $stmt->bindParam(':prezime',$this->prezime,PDO::PARAM_STR);
      $stmt->bindParam(':username', $this->username, PDO::PARAM_STR);
      $stmt->bindParam(':password', $this->password, PDO::PARAM_STR);

      /*** execute the prepared statement ***/
      if($stmt->execute()){
        $this->id = DB::getInstance()->lastInsertId();
      return true;
      } else {
        foreach(DB::getInstance()->errorInfo() as $error)
        {
          echo $error.'<br />';
        }
      return false;
      }

    }

    public function update()
  {
    /*** prepare the SQL statement ***/
    $stmt = DB::getInstance()->prepare("UPDATE ".self::$table_name." SET ime = :ime, prezime = :prezime, username = :username, password = :password WHERE id = :id");

    /*** bind the paramaters ***/
    $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
    $stmt->bindParam(':ime', $this->ime, PDO::PARAM_STR);
    $stmt->bindParam(':prezime',$this->prezime, PDO::PARAM_STR);
    $stmt->bindParam(':username', $this->username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $this->password, PDO::PARAM_STR);

    if($count = $stmt->execute()){
      echo $count;
      return true;
    } else {
      foreach(DB::getInstance()->errorInfo() as $error)
      {
        echo $error.'<br />';
      }
      return false;
    }

  }

    public function delete()
  {

    $stmt = DB::getInstance()->prepare("DELETE FROM ".self::$table_name." WHERE id = :id");

    $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

    if($stmt->execute()){
      return true;
    } else {
      foreach(DB::getInstance()->errorInfo() as $error)
      {
        echo $error.'<br />';
      }
      return false;
    }

    }

}
