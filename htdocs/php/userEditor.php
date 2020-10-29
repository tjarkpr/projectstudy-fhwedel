<?php
include_once('./private/Authentificate.php');
include_once('./private/UserToolsEditor.php');
include_once('./private/Database.php');
function paramError() {exit("Error: Fehlender oder nicht bekannter Parameter.");}
function update($userID,$plan) {
  $stmt = 'Curriculum=\''.json_encode($plan,JSON_UNESCAPED_UNICODE).'\'';
  $where = '`B-ID` LIKE '.$userID;
  Database::update('Studienverlaufsplaner','Benutzer',$stmt,$where);
}
session_start();
/**
 * Nur wenn die ID des Benutzers übergeben wird brauchen bestimmte Funktionen
 * den Programmplan als JSON.
 */
if(isset($_POST['userID'])){
    $plan = UserToolsEditor::getProgramPlanJSON($_POST['userID']);
}
/**
 * Steuert den Ablauf und den Aufruf von bestimmten Funktionen.
 * Durch die Übergabe von POST-Variablen, die den aktuellen Mode
 * enthalten, wird die Seite gesteuert.
 */
switch ($_POST['mode']) {
  case 'get':
    switch ($_POST['submode']) {
      case 'all': echo UserToolsEditor::getAll($_POST['userID'],$plan); break;
      case 'part': echo UserToolsEditor::getParts($plan,$_POST['userID']); break;
      case 'add': echo UserToolsEditor::getAdd($plan); break;
      case 'remove': echo UserToolsEditor::getRemove(); break;
      case 'move': echo UserToolsEditor::getMove($plan); break;
      case 'elective': echo UserToolsEditor::getElective($plan,$_POST['wID']); break;
      case 'reset': echo UserToolsEditor::getReset(); break;
      case 'info': echo UserToolsEditor::getInformation($_POST['pId']); break;
      case 'freesubject': echo UserToolsEditor::getFreeSubjects($_POST['userID'], $plan); break;
      default: paramError(); break;
    }
  break;
  case 'set':
      switch ($_POST['submode']) {
      case 'passed':
          if (!isset($_POST['userID'])) {paramError();}
            echo UserToolsEditor::setPassedSubject($_POST['userID'], $_POST['pId'], $plan);
            update($_POST['userID'],$plan);
          break;
      case 'elective':
          echo UserToolsEditor::setElective($_POST['userID'], $_POST['bID'], $plan);
          update($_POST['userID'],$plan);
          break;
      }
  break;
  case 'delete':
    if (!isset($_POST['userID'])) {paramError();}
    echo UserToolsEditor::delete($_POST['userID'],$plan,$_POST['bID'],true);
    update($_POST['userID'],$plan);
  break;
  case 'reset':
    if (!isset($_POST['userID'])) {paramError();}
    update($_POST['userID'],UserToolsEditor::reset($_POST['userID']));
  break;
  case 'insert':
    if (!isset($_POST['element'])||!isset($_POST['semester'])) {paramError();}
    echo UserToolsEditor::insertLecture($_POST['userID'],$plan,json_decode($_POST['element']),$_POST['semester']);
    update($_POST['userID'],$plan);
  break;
  case 'move':
    if (!isset($_POST['userID'])||!isset($_POST['semester'])) {paramError();}
      $element = UserToolsEditor::getElement($_POST['bID'],$plan);
      echo UserToolsEditor::moveLecture($_POST['userID'],$plan,$element,$_POST['semester'],false);
      update($_POST['userID'],$plan);
  break;
  default: paramError(); break;
    }
?>
