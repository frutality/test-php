<?php

class DB
{
  private static function connect(){
    $user = $_ENV['DB_USERNAME'];
    $password = $_ENV['DB_PASSWORD'];
    $host = $_ENV['DB_HOST'];
    $dbName = $_ENV['DB_DATABASE'];
    $params = [
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];
    
    $connection = new PDO("mysql:dbname=$dbName;host=$host;charset=utf8", $user, $password, $params);
    return $connection;
  }
  
  public static function runQuery($query, $params = []){
    $connection = self::connect();
    $result = $connection->prepare($query);
    $result->execute($params);
  
    return $result;
  }
}