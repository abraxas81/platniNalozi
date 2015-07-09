<?php
require_once(LIB_PATH . DS . "config.php");

class DB
{
    private static $instance = NULL;
    private static $table_name;

    /**
     *
     * the constructor is set to private so
     * so nobody can create a new instance using new
     *
     */
    function __construct() {
        self::$table_name;
        /*** maybe set the db name here later ***/
    }

    /**
     *
     * Return DB instance or create intitial connection
     *
     * @return object (PDO)
     *
     * @access public
     *
     */
    public static function getInstance() {

        if (!self::$instance)
        {
            self::$instance = new PDO("pgsql:host=".DB_SERVER.";dbname=".DB_NAME."", 'postgres', 'bombacla');
            self::$instance-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$instance;
    }

    public static function who() {
        return __CLASS__;
    }

    /**
     *
     * Like the constructor, we make __clone private
     * so nobody can clone the instance
     *
     */
    private function __clone(){
    }

    public function closeConnection()
    {
        if (self::$instance) {
            self::$instance = null;
            unset(self::$instance);

        }
    }

    protected static function execute_query($sql,$case)
    {
        $stmt = DB::getInstance()->query($sql);
        switch($case) {
            case "klasa":
                $result = $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
                break;
            case "objekt":
                $result = $stmt->fetch(PDO::FETCH_OBJ);
                break;
        }
        return !empty($result) ? $result : false;
    }

    public static function find_by_id($id) {
        /*$check = get_called_class();
        $check2 = parent::class;*/
        $stmt = self::getInstance()->query("SELECT * FROM ".static::$table_name." WHERE id={$id}");
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }

    public static function all() {
        $stmt = self::getInstance()->query("SELECT * FROM ".static::$table_name);
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
        return $result;
    }

} /*** end of class ***/






  


