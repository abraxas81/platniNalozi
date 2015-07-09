<?php
require_once(LIB_PATH.DS."config.php");

class DB
{
    private static $instance = NULL;

    /**
     *
     * the constructor is set to private so
     * so nobody can create a new instance using new
     *
     */
    function __construct() {
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

} /*** end of class ***/






  


