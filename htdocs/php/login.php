<?php
include_once('./private/Authentificate.php');
include_once('./private/AdminToolsOverview.php');
function paramError() {exit("Error: Fehlender oder nicht bekannter Parameter!");}
session_start();
switch ($_POST['mode']) {
  case 'logout':
    $_SESSION['KEY'] = null;
  break;
  case 'pbkey':
    echo Authentification::getPublicKey();
  break;
  case 'login':
    if (!isset($_POST['username']) && !isset($_POST['password'])) {paramError();}
    echo Authentification::login($_POST['username'],$_POST['password']);
  break;
  case 'auth':
    if (!isset($_POST['key'])) {paramError();}
    echo Authentification::auth($_POST['key']);
  break;
  case 'getProgram':
    echo AdminToolsOverview::getProgramOptions();
  break;
  case 'sendProgram':
    echo AdminToolsOverview::setProgram(Authentification::decrypt($_POST['username']),$_POST['id']);
  break;
  default:paramError();break;
}
?>
