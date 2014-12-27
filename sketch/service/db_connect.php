<?php
// db
Config::write('db.host', 'localhost');
Config::write('db.port', '3306');
Config::write('db.basename', 'c_shirt');
Config::write('db.user', 'root');
Config::write('db.password', '');

class Config
{
    static $confArray;

    public static function read($name)
    {
        return self::$confArray[$name];
    }

    public static function write($name, $value)
    {
        self::$confArray[$name] = $value;
    }

}

class dbConnect
{
    public $dbh; // handle of the db connexion
    private static $instance;

    private function __construct()
    {
        // building data source name from config
        $dsn = 'mysql:charset=utf8;host=' . Config::read('db.host') .
               ';dbname='    . Config::read('db.basename') .
               ';port='      . Config::read('db.port') .
               ';connect_timeout=15';
                  
        $user = Config::read('db.user');                  
        $password = Config::read('db.password'); 
        $this->dbh = new PDO($dsn, $user, $password);
        
    }

    public static function getInstance()
    {
        if (!isset(self::$instance))
        {
            $object = __CLASS__;
            self::$instance = new $object;
        }
        return self::$instance;
    }

    // others global functions
}
?>