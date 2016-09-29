<?php

/**
 * Description of Club
 *
 * @author Tissot David
 */
class Database {

    private $_host;
    private $_username;
    private $_password;
    private $_database;
    private $_connexionDb;

    public function __construct($host = null, $user = null, $pwd = null, $database = null) {
        if ($host != null) {
            $this->_host = $host;
            $this->_username = $user;
            $this->_password = $pwd;
            $this->_database = $database;
        } else {
            $this->_host = 'localhost';
            $this->_username = 'client';
            $this->_password = 'client1234';
            $this->_database = 'panier';
        }
        try {
            $this->_connexionDb = new PDO('mysql:host=' . $this->_host . ';dbname=' . $this->_database, $this->_username, $this->_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        } catch (PDOException $ex) {
            die('<h1>Imposible de se connecter a la Base de donnees<h1>');
        }
    }

    public function requete($sql) {
        $req = $this->_connexionDb->prepare($sql);
        $req->execute();
        return $req->fetchALL(PDO::FETCH_OBJ);
        //pour voir le résultat d'une requete, l'afficher dans un var_dump()
    }

    public function reqUpdate($sql) {
        $req = $this->_connexionDb->prepare($sql);
        $req->execute();
    }

}
