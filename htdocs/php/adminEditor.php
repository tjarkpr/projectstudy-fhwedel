<?php
  include_once('./private/Authentificate.php');
  include_once('./private/AdminToolsEditor.php');
  include_once('./private/Database.php');
  function paramError() {exit("Error: Fehlender oder nicht bekannter Parameter.");}
  function semesterError() {exit("Error: Element kann in diesem Semester nicht angelegt werden.");}
  function einheitenError() {exit("Error: Einheitenanzahl kann nicht gegeben werden.");}
  function update($sid,$plan) {
    $stmt = 'Curriculum=\''.json_encode($plan,JSON_UNESCAPED_UNICODE).'\'';
    $where = '`S-ID` LIKE '.$sid;
    Database::update('Studienverlaufsplaner','Studiengang',$stmt,$where);
  }
  session_start();
  if (!isset($_SESSION['KEY'])) {exit('Error: Authorisierung nicht möglich.');}
  $auth = Authentification::auth($_SESSION['KEY']);
  if ($auth != 'admin') {exit($auth);}
  if (!isset($_POST['sid'])) {paramError();}
  $plan = AdminToolsEditor::getProgramPlanJSON($_POST['sid']);
  switch ($_POST['mode']) {
    case 'check':
      echo AdminToolsEditor::checkPlan($_POST['sid'],$plan);
      update($_POST['sid'],$plan);
    break;
    case 'get':
      switch ($_POST['submode']) {
        case 'all': echo AdminToolsEditor::getAll($_POST['sid'],$plan); break;
        case 'part': echo AdminToolsEditor::getParts($_POST['sid'],$plan); break;
        case 'add': echo AdminToolsEditor::getAdd($plan); break;
        case 'move': echo AdminToolsEditor::getMove($plan); break;
        default: paramError(); break;
      }
    break;
    case 'delete':
      if (!isset($_POST['id'])) {paramError();}
      echo AdminToolsEditor::delete($_POST['sid'],$plan,$_POST['id'],true);
      update($_POST['sid'],$plan);
    break;
    case 'insert':
      if (!isset($_POST['element'])||!isset($_POST['semester'])) {paramError();}
      if ($_POST['semester'] <= 0) {semesterError();}
      $values = json_decode($_POST['element']);
      if (isset($values->{'Länge'}) && ($values->{'Länge'} <= 0 || $values->{'Länge'} > 12)) {einheitenError();}
      switch ($_POST['submode']) {
        case 'lecture': echo AdminToolsEditor::insertLecture($_POST['sid'],$plan,$values,$_POST['semester']);; break;
        case 'chooser': echo AdminToolsEditor::insertChooser($_POST['sid'],$plan,$values,$_POST['semester']);; break;
        default: paramError(); break;
      }
      update($_POST['sid'],$plan);
    break;
    case 'move':
      if (!isset($_POST['id'])||!isset($_POST['semester'])) {paramError();}
      if ($_POST['semester'] <= 0) {semesterError();}
      $element = AdminToolsEditor::getElement($_POST['id'],$plan);
      switch ($_POST['submode']) {
        case 'lecture': echo AdminToolsEditor::moveLecture($_POST['sid'],$plan,$element,$_POST['semester']);; break;
        case 'chooser': echo AdminToolsEditor::moveChooser($_POST['sid'],$plan,$element,$_POST['semester']);; break;
        default: paramError(); break;
      }
      update($_POST['sid'],$plan);
    break;
    default: paramError(); break;
  }
?>
