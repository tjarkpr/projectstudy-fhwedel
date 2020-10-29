<?php
include_once('./private/Database.php');
/**
 * Authentifikation
 * Nimmt die Authentifikation des Users anhand von einem übergebenen KEY vor.
 * @author Tjark, Fynn, Niclas, Kjell
 */
class Authentification {
  /*----GENERELL----*/
  public static function decrypt($encrypted) {
    $pvkey = Authentification::getPrivateKey();
    openssl_private_decrypt(base64_decode($encrypted),$decrypted,$pvkey);
    return $decrypted;
  }
  private static function getPrivateKey() {
    return Authentification::getFile("./private/rsa_1024_priv.pem");
  }
  private static function getFile($source) {
    $fp = fopen($source,"r");
    $data = fread($fp,8192);
    fclose($fp);
    return $data;
  }
  /*----LOGIN----*/
  public static function login($encryptedUsername, $encryptedPassword) {
    $password = Authentification::decrypt($encryptedPassword);
    $username = Authentification::decrypt($encryptedUsername);
    $data = Database::selectAll('Studienverlaufsplaner', 'Benutzer');
    $found = false; $return = '';
    while(!$found && $row = $data->fetch_assoc()) {$found = ($row['Benutzername'] == $username && password_verify($password, $row['Passwort']));}
    if ($found) {
      if (is_null($row['S-ID'])) {$return = 'init';} 
      else {$return = 'user'.$row['B-ID'];}
    }
    $data = Database::selectAll('Studienverlaufsplaner', 'Administrator');
    while(!$found && $row = $data->fetch_assoc()) {$found = ($row['Benutzername'] == $username && password_verify($password, $row['Passwort']));}
    if ($found && $return == '') {$return = 'admin';}
    if ($found) {$_SESSION['KEY'] = Authentification::getUserKey($username);}
    else {return 'Error: Die eingegebenen Daten sind nicht korrekt. Bitte probieren Sie es erneut.';}
    return $return;
  }
  private static function getUserKey($username) {
    return crypt($_SERVER['REMOTE_ADDR'],$username);
  }
  /*----AUTH----*/
  public static function auth($key) {
    $data = Database::selectAll('Studienverlaufsplaner', 'Benutzer');
    $found = false; $return = '';
    while(!$found && $row = $data->fetch_assoc()) {$found = Authentification::authSingleUser($key, $row['Benutzername']);}
    if ($found) {$return = 'user';}
    $data = Database::selectAll('Studienverlaufsplaner', 'Administrator');
    while(!$found && $row = $data->fetch_assoc()) {$found = Authentification::authSingleUser($key, $row['Benutzername']);}
    if ($found && $return == '') {$return = 'admin';}
    if (!$found) {return 'Error: Authorisierung nicht möglich.';}
    return $return;
  }
  private static function authSingleUser($key,$username) {
    $tmpKey = crypt($_SERVER['REMOTE_ADDR'],$username);
    return hash_equals($key,$tmpKey);
  }
  /*----GETPBKEY----*/
  public static function getPublicKey() {
    return Authentification::getFile("./private/rsa_1024_pub.pem");
  }
  /*----PWHASH----*/
  public static function hashPW($encryptedPassword) {
    $decryptedPassword = Authentification::decrypt($encryptedPassword);
    return password_hash($decryptedPassword, PASSWORD_DEFAULT);
  }
}
?>
