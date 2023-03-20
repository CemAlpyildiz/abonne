<?php

namespace Pdo;

use PDO;

class Connexion{

    private string $host;
    private string $dbname;
    private string $username;
    private string $password;

    public function __construct(string $host, string $dbname, string $username, string $password)
    {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
    }

    public function connexion(){
        return new PDO('mysql:host=' . $this->host . ';dbname='.$this->dbname .';charset=utf8', $this->username, $this->password);
    }
}