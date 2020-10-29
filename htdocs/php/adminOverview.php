<?php
include_once('./private/Authentificate.php');
include_once('./private/AdminToolsOverview.php');
function paramError() {exit("Error: Fehlender oder nicht bekannter Parameter.");}
session_start();
if (!isset($_SESSION['KEY'])) {exit('Error: Authorisierung nicht möglich.');}
$auth = Authentification::auth($_SESSION['KEY']);
if ($auth != 'admin') {exit($auth);}
switch ($_POST['mode']) {
  case 'get':
    switch ($_POST['submode']) {
      case 'status': echo AdminToolsOverview::getStatus(); break;
      case 'overview':
        switch ($_POST['subsubmode']) {
          case 'lecture': echo AdminToolsOverview::getOverviewLecture(); break;
          case 'program': echo AdminToolsOverview::getOverviewProgram(); break;
          case 'user': echo AdminToolsOverview::getOverviewUser(); break;
          default: paramError(); break;
        }
      break;
      case 'detail':
        switch ($_POST['subsubmode']) {
          case 'lecture':
          if ($_POST['id'] == 'none') {echo AdminToolsOverview::getDetailLecture();}
          else {echo AdminToolsOverview::getDetailLectureFilled($_POST['id']);}
          break;
          case 'program':
          if ($_POST['id'] == 'none') {echo AdminToolsOverview::getDetailProgram();}
          else {echo AdminToolsOverview::getDetailProgramFilled($_POST['id']);}
          break;
          case 'user':
          if ($_POST['id'] == 'none') {echo AdminToolsOverview::getDetailUser();}
          else {echo AdminToolsOverview::getDetailUserFilled($_POST['id']);}
          break;
          default: paramError(); break;
        }
      break;
      default: paramError(); break;
    }
  break;
  case 'delete':
    switch ($_POST['submode']) {
      case 'lecture': echo AdminToolsOverview::deleteLecture($_POST['id']); break;
      case 'program': echo AdminToolsOverview::deleteProgram($_POST['id']); break;
      case 'user': echo AdminToolsOverview::deleteUser($_POST['id']); break;
      default: paramError(); break;
    }
  break;
  case 'save':
    $values = json_decode($_POST['json']);
    switch ($_POST['submode']) {
      case 'lecture':
        if ($_POST['id'] == 'none') {echo AdminToolsOverview::saveLecture($values);}
        else {echo AdminToolsOverview::updateLecture($values);}
      break;
      case 'program':
      if ($_POST['id'] == 'none') {echo AdminToolsOverview::saveProgram($values);}
      else {echo AdminToolsOverview::updateProgram($values);}
      break;
      case 'user':
      if ($values->{'Passwort'} != '') {
        $hashedPW = Authentification::hashPW($values->{'Passwort'});
        $values->{'Passwort'} = $hashedPW;
      }
      if ($_POST['id'] == 'none') {echo AdminToolsOverview::saveUser($values);}
      else {echo AdminToolsOverview::updateUser($values);}
      break;
      default: paramError(); break;
    }
  break;
  default: paramError(); break;
}
?>
