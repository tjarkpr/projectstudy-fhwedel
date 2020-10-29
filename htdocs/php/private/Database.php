<?php
/**
 * Datenbankorganistaionsklasse
 * In dieser Klasse werden alle wichtigen Datenbankoperationen geregelt.
 * @author Tjark, Fynn, Niclas, Kjell
 */
class Database {
  private static function getDatabaseConnection($database) {
    $connection = new mysqli("localhost","root","",$database);
    if ($connection->connect_errno) {exit("Error: Datenbankverbindung fehlgeschlagen.");}
    return $connection;
  }
  private static function executeStatement($connection,$sql) {
    if (!$statement = $connection->prepare($sql)) {exit("Error: ".$connection->error);}
    if (!$statement->execute()) {exit("Error: ".$connection->error);}
    return $statement->get_result();
  }
  public static function getConnect($database) {
    return Database::getDatabaseConnection($database);
  }
  public static function getStatus($database) {
    $connection = Database::getDatabaseConnection($database);
    return mysqli_stat($connection);
  }
  public static function select($database,$selection,$table,$condition,$data) {
    $connection = Database::getDatabaseConnection($database);
    $sql = 'SELECT '.$selection.' FROM `'.$table.'` WHERE `'.$condition.'` LIKE '.$data;
    return Database::executeStatement($connection,$sql);
  }
  public static function selectAll($database,$table) {
    $connection = Database::getDatabaseConnection($database);
    $sql = 'SELECT * FROM '.$table;
    return Database::executeStatement($connection,$sql);
  }
  public static function selectExtended($database,$selection,$table,$condition1,$data1,$condition2,$data2) {
    $connection = Database::getDatabaseConnection($database);
    $sql = 'SELECT '.$selection.' FROM `'.$table.'` WHERE `'.$condition1.'` LIKE '.$data1.' AND `'.$condition2.'` LIKE '.$data2;
    return Database::executeStatement($connection,$sql);
  }
  public static function insert($database,$table,$sqlstatement) {
    $connection = Database::getDatabaseConnection($database);
    $sql = 'INSERT INTO '.$table.' '.$sqlstatement;
    return Database::executeStatement($connection,$sql);
  }
  public static function update($database,$table,$sqlstatement,$condition) {
    $connection = Database::getDatabaseConnection($database);
    $sql = 'UPDATE '.$table.' SET '.$sqlstatement.' WHERE '.$condition;
    return Database::executeStatement($connection,$sql);
  }
  public static function delete($database,$table,$condition,$data) {
    $connection = Database::getDatabaseConnection($database);
    $sql = 'DELETE FROM '.$table.' WHERE `'.$condition.'` LIKE '.$data;
    return Database::executeStatement($connection,$sql);
  }
}
?>
