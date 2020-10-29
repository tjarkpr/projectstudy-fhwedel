<?php
/**
 * AdminÜbersichtTools
 * Die Klasse beinhaltet alle wichtigen Operationen, die in der Übersicht gemacht werden können.
 * @author Tjark, Fynn, Niclas, Kjell
 */
class AdminToolsOverview {
/*----LOGINABFRAGEN----*/
public static function getProgramOptions() {
  $data = Database::selectAll('Studienverlaufsplaner', 'Studiengang');
  $result = '';
  while ($row = $data->fetch_assoc()) {
   $result = $result."<option><".$row['S-ID']."> ".$row['Fachrichtung']." ".$row['Studienordnung']." ".$row['Startsemester']."</option>";
  }
  return $result;
}
public static function setProgram($bname,$id) {
  $data = Database::select('Studienverlaufsplaner','*','Studiengang','S-ID',"'".$id."'");
  $row = $data->fetch_assoc();
  $stmt = '`S-ID`='.$id.',Curriculum=\''.$row['Curriculum'].'\'';
  $where = 'Benutzername LIKE \''.$bname.'\'';
  $data = Database::update('Studienverlaufsplaner','Benutzer',$stmt,$where);
  return AdminToolsOverview::SUCCESS;
}
/*----GENERELL----*/
const SUCCESS = 'Success.';
const ERROR_NOTFOUND = 'Error: In Datenbank nicht gefunden.';
/*----GET----*/
public static function getStatus() {
  $data = Database::getStatus('Studienverlaufsplaner');
  if (is_null($data)) {return AdminToolsOverview::ERROR_NOTFOUND;}
  $result = "<li><img src=\"./svg/uptime.svg\" alt=\"icon\"><span>";
  preg_match("/Uptime: [0-9]*/", $data, $m);
  $result = $result.$m[0]."s";
  $result = $result."</span></li><li><img src=\"./svg/threads.svg\" alt=\"icon\"><span>";
  preg_match("/Threads: [0-9]*/", $data, $m);
  $result = $result.$m[0];
  $result = $result."</span></li><li><img src=\"./svg/questions.svg\" alt=\"icon\"><span>";
  preg_match("/Questions: [0-9]*/", $data, $m);
  $result = $result.$m[0];
  $result = $result."</span></li><li><img src=\"./svg/slow.svg\" alt=\"icon\"><span>";
  preg_match("/Slow queries: [0-9]*/", $data, $m);
  $result = $result.$m[0];
  $result = $result."</span></li><li><img src=\"./svg/opens.svg\" alt=\"icon\"><span>";
  preg_match("/Opens: [0-9]*/", $data, $m);
  $result = $result.$m[0];
  $result = $result."</span></li><li><img src=\"./svg/flush.svg\" alt=\"icon\"><span>";
  preg_match("/Flush tables: [0-9]*/", $data, $m);
  $result = $result.$m[0];
  return $result."</span></li>";
}
public static function getOverviewLecture() {
  $data = Database::selectAll('Studienverlaufsplaner', 'Prüfung');
  $result = '';
  while ($row = $data->fetch_assoc()) {
   $result = $result."<tr onclick=\"selectLectureAdd(this,'overviewPage')\"><th>".$row['P-ID']."</th><td class=\"names\">".$row['Name']."</td><td>".$row['Angebot']."</td><td class=\"selectTd\"><img class=\"selectImage\" src=\"./svg/selected.svg\" alt=\"icon\"></td></tr>";
  }
  return $result;
}
public static function getOverviewUser() {
  $data = Database::selectAll('Studienverlaufsplaner', 'Benutzer');
  $result = '';
  while ($row = $data->fetch_assoc()) {
   $result = $result."<tr onclick=\"selectLectureAdd(this,'overviewPage')\"><th>".$row['B-ID']."</th><td>";
   if (is_null($row['S-ID'])) {
     $result = $result."NULL";
   } else {
     $result = $result." S-ID ".$row['S-ID'];
   }
   $result = $result."</td><td class=\"names\">".$row['Benutzername']."</td><td class=\"selectTd\"><img class=\"selectImage\" src=\"./svg/selected.svg\" alt=\"icon\"></td></tr>";
  }
  return $result;
}
public static function getOverviewProgram() {
  $data = Database::selectAll('Studienverlaufsplaner', 'Studiengang');
  $result = '';
  while ($row = $data->fetch_assoc()) {
   $result = $result."<tr onclick=\"selectLectureAdd(this,'overviewPage')\"><th>".$row['S-ID']."</th><td class=\"names\">".$row['Fachrichtung']."</td><td>".$row['Studienordnung']."</td><td class=\"selectTd\"><img class=\"selectImage\" src=\"./svg/selected.svg\" alt=\"icon\"></td></tr>";
  }
  return $result;
}
public static function getDetailUser() {
  $result = "<h3>Allgemeine Informationen</h3>
  <span>Benutzeridentifikation (Interne Nummer)*</span><br><input type=\"text\" name=\"B-ID\"><br><br>
  <span>Benutzername*</span><br><input type=\"text\" name=\"Benutzername\"><br><br>
  <span>Passwort</span><br><input id=\"detailPw\" type=\"password\" name=\"Passwort\">";
  return $result;
}
public static function getDetailLecture() {
  $result = "<h3>Allgemeine Informationen</h3>
  <span>Prüfungsidentifikation (Interne Nummer)*</span><br><input type=\"text\" name=\"P-ID\"><br><br>
  <span>Vorlesungsname*</span><br><input type=\"text\" name=\"Name\"><br><br>
  <span>Angebot (W/S/E)*</span><br><input type=\"text\" name=\"Angebot\"><br><br>
  <span>Vorbedingungen</span><br>
  <div class=\"detailViewFormular_lecturePre\"><table>";
  $result = $result."</table></div><div onclick=\"toggleFormAdd()\" class=\"formButton\"><img src=\"./svg/add.svg\" alt=\"icon\"></div><div onclick=\"deleteDetail()\" class=\"formButton\"><img src=\"./svg/delete.svg\" alt=\"icon\"></div><div class=\"detailViewFormular_lectureAll\"><table>";
  $result = $result.AdminToolsOverview::getPreconditionAll();
  return $result."</table></div>";
}
public static function getDetailProgram() {
  return "<h3>Allgemeine Informationen</h3>
  <span>Studienordnungsidentifikation (Interne Nummer)*</span><br><input type=\"text\" name=\"S-ID\"><br><br>
  <span>Fachrichtung*</span><br><input type=\"text\" name=\"Fachrichtung\"><br><br>
  <span>Startsemester (W/S)*</span><br><input type=\"text\" name=\"Startsemester\"><br><br>
  <span>Studienordnung*</span><br><input type=\"text\" name=\"Studienordnung\"><br><br>
  <span>Obergrenze für Prüfungen des ersten Semesters*</span><br><input type=\"number\" name=\"Obergrenze\">";
}
public static function getDetailUserFilled($bid) {
  $data = Database::select('Studienverlaufsplaner','*','Benutzer','B-ID',"'".$bid."'");
  $row = $data->fetch_assoc();
  if (is_null($row)) {return AdminToolsOverview::ERROR_NOTFOUND;}
  $result = "<h3>Allgemeine Informationen</h3>
  <span>Benutzeridentifikation (Interne Nummer)*</span><br><input value=\"".$row['B-ID']."\" type=\"text\" name=\"B-ID\"><br><br>
  <span>Benutzername*</span><br><input value=\"".$row['Benutzername']."\" type=\"text\" name=\"Benutzername\"><br><br>
  <span>Passwort ändern</span><br><input id=\"detailPw\" type=\"password\" name=\"Passwort\">";
  return $result;
}
public static function getDetailLectureFilled($fid) {
  $data = Database::select('Studienverlaufsplaner','*','Prüfung','P-ID',"'".$fid."'");
  $row = $data->fetch_assoc();
  if (is_null($row)) {return AdminToolsOverview::ERROR_NOTFOUND;}
  $result = "<h3>Allgemeine Informationen</h3>
  <span>Prüfungsidentifikation (Interne Nummer)*</span><br><input value=\"".$row['P-ID']."\" type=\"text\" name=\"P-ID\"><br><br>
  <span>Vorlesungsname*</span><br><input value=\"".$row['Name']."\" type=\"text\" name=\"Name\"><br><br>
  <span>Angebot (W/S/E)*</span><br><input value=\"".$row['Angebot']."\" type=\"text\" name=\"Angebot\"><br><br>
  <span>Vorbedingungen</span><br>
  <div class=\"detailViewFormular_lecturePre\"><table>";
  $result = $result.AdminToolsOverview::getPreconditionSpecific($fid);
  $result = $result."</table></div><div onclick=\"toggleFormAdd()\" class=\"formButton\"><img src=\"./svg/add.svg\" alt=\"icon\"></div><div onclick=\"deleteDetail()\" class=\"formButton\"><img src=\"./svg/delete.svg\" alt=\"icon\"></div><div class=\"detailViewFormular_lectureAll\"><table>";
  $result = $result.AdminToolsOverview::getPreconditionAll();
  return $result."</table></div>";
}
public static function getDetailProgramFilled($sid) {
  $data = Database::select('Studienverlaufsplaner','*','Studiengang','S-ID',"'".$sid."'");
  $row = $data->fetch_assoc();
  if (is_null($row)) {return AdminToolsOverview::ERROR_NOTFOUND;}
  return "<h3>Allgemeine Informationen</h3>
    <span>Studienordnungsidentifikation (Interne Nummer)*</span><br><input value=\"".$row['S-ID']."\" type=\"text\" name=\"S-ID\"><br><br>
    <span>Fachrichtung*</span><br><input value=\"".$row['Fachrichtung']."\" type=\"text\" name=\"Fachrichtung\"><br><br>
    <span>Startsemester (W/S)*</span><br><input value=\"".$row['Startsemester']."\" type=\"text\" name=\"Startsemester\"><br><br>
    <span>Studienordnung*</span><br><input value=\"".$row['Studienordnung']."\" type=\"text\" name=\"Studienordnung\"><br><br>
    <span>Obergrenze für Prüfungen des ersten Semesters*</span><br><input value=\"".$row['Obergrenze']."\" type=\"number\" name=\"Obergrenze\">";
}
private static function getPreconditionAll() {
  $data = Database::selectAll('Studienverlaufsplaner', 'Prüfung');
  $result = '';
  while ($row = $data->fetch_assoc()) {
   $result = $result."<tr onclick=\"addToDetail(this)\"><th>".$row['P-ID']."</th><td>".$row['Name']."</td></tr>";
  }
  return $result;
}
private static function getPreconditionSpecific($fid) {
  $data = Database::select('Studienverlaufsplaner','*','Prüfung_vorbedingt_Prüfung','P-ID',"'".$fid."'");
  $result = '';
  while ($row = $data->fetch_assoc()) {
    $dataTemp = Database::select('Studienverlaufsplaner','*','Prüfung','P-ID',"'".$row['P-IDB']."'");
    $rowTemp = $dataTemp->fetch_assoc();
    $result = $result."<tr onclick=\"select(this)\"><th>".$rowTemp['P-ID']."</th><td>".$rowTemp['Name']."</td><td class=\"selectTd\"><img class=\"selectImage\" src=\"./svg/selected.svg\" alt=\"icon\"></td></tr>";
  }
  return $result;
}
/*----DELETE----*/
const ERROR_PRECONDITION = "Error: Prüfung ist Vorbedingung einer anderen Prüfung.";
public static function deleteLecture($fid) {
  if (AdminToolsOverview::isPrecondition($fid)) {return AdminToolsOverview::ERROR_PRECONDITION;}
  $data = Database::delete('Studienverlaufsplaner', 'Prüfung_vorbedingt_Prüfung', 'P-ID', "'".$fid."'");
  $data = Database::delete('Studienverlaufsplaner', 'Prüfung', 'P-ID', "'".$fid."'");
  return AdminToolsOverview::SUCCESS;
}
public static function deleteProgram($sid) {
  $data = Database::delete('Studienverlaufsplaner', 'Studiengang', 'S-ID', "'".$sid."'");
  return AdminToolsOverview::SUCCESS;
}
public static function deleteUser($bid) {
  $data = Database::delete('Studienverlaufsplaner', 'Benutzer', 'B-ID', "'".$bid."'");
  return AdminToolsOverview::SUCCESS;
}
public static function isPrecondition($fid) {
  $data = Database::select('Studienverlaufsplaner', '*', 'Prüfung_vorbedingt_Prüfung', 'P-IDB', "'".$fid."'");
  return !is_null($data->fetch_assoc());
}
/*----SAVE----*/
const ERROR_FORMAT = 'Error: Falsches Format.';
const ERROR_EXIST = 'Error: Element existiert bereits in dieser Konstellation.';
public static function saveLecture($values) {
  $stmt = '(`P-ID`, Name, Angebot) VALUES (\''.$values->{'P-ID'}.'\', \''.$values->{'Name'}.'\', \''.$values->{'Angebot'}.'\')';
  $data = Database::insert('Studienverlaufsplaner','Prüfung',$stmt);
  for ($i=0; $i < count($values->{'Vorbedingungen'}); $i++) {
    $stmt = '(`P-ID`, `P-IDB`) VALUES (\''.$values->{'P-ID'}.'\', \''.$values->{'Vorbedingungen'}[$i].'\')';
    $data = Database::insert('Studienverlaufsplaner','Prüfung_vorbedingt_Prüfung',$stmt);
  }
  return AdminToolsOverview::SUCCESS;
}
public static function saveProgram($values) {
  if (AdminToolsOverview::proofExist($values)) {return AdminToolsOverview::ERROR_EXIST;}
  $stmt = '(`S-ID`, Fachrichtung, Studienordnung, Startsemester, Obergrenze) VALUES ('.$values->{'S-ID'}.', \''.$values->{'Fachrichtung'}.'\', \''.$values->{'Studienordnung'}.'\', \''.$values->{'Startsemester'}.'\',\''.$values->{'Obergrenze'}.'\')';
  $data = Database::insert('Studienverlaufsplaner','Studiengang',$stmt);
  return AdminToolsOverview::SUCCESS;
}
public static function saveUser($values) {
  if ($values->{'Passwort'} == '') {
    $stmt = '(`B-ID`, Benutzername) VALUES ('.$values->{'B-ID'}.', \''.$values->{'Benutzername'}.'\')';
  } else {
    $link = Database::getConnect('Studienverlaufsplaner');
    $stmt = '(`B-ID`, Benutzername, Passwort) VALUES ('.$values->{'B-ID'}.', \''.$values->{'Benutzername'}.'\', \''.mysqli_real_escape_string($link, $values->{'Passwort'}).'\')';
  }
  $data = Database::insert('Studienverlaufsplaner','Benutzer',$stmt);
  return AdminToolsOverview::SUCCESS;
}
public static function updateLecture($values) {
  $stmt = '`P-ID`=\''.$values->{'P-IDN'}.'\', Name=\''.$values->{'Name'}.'\', Angebot=\''.$values->{'Angebot'}.'\'';
  $where = '`P-ID` LIKE \''.$values->{'P-ID'}.'\'';
  $data = Database::update('Studienverlaufsplaner','Prüfung',$stmt,$where);
  $data = Database::delete('Studienverlaufsplaner', 'Prüfung_vorbedingt_Prüfung', 'P-ID', "'".$values->{'P-ID'}."'");
  for ($i=0; $i < count($values->{'Vorbedingungen'}); $i++) {
    $stmt = '(`P-ID`, `P-IDB`) VALUES (\''.$values->{'P-ID'}.'\', \''.$values->{'Vorbedingungen'}[$i].'\')';
    $data = Database::insert('Studienverlaufsplaner','Prüfung_vorbedingt_Prüfung',$stmt);
  }
  return AdminToolsOverview::SUCCESS;
}
public static function updateProgram($values) {
  if (AdminToolsOverview::proofExist($values)) {return AdminToolsOverview::ERROR_EXIST;}
  $stmt = '`S-ID`='.$values->{'S-IDN'}.', Fachrichtung=\''.$values->{'Fachrichtung'}.'\', Studienordnung=\''.$values->{'Studienordnung'}.'\', Startsemester=\''.$values->{'Startsemester'}.'\', Obergrenze=\''.$values->{'Obergrenze'}.'\'';
  $where = '`S-ID` LIKE '.$values->{'S-ID'};
  $data = Database::update('Studienverlaufsplaner','Studiengang',$stmt,$where);
  return AdminToolsOverview::SUCCESS;
}
public static function updateUser($values) {
  if ($values->{'Passwort'} == '') {
    $stmt = '`B-ID`='.$values->{'B-IDN'}.', Benutzername=\''.$values->{'Benutzername'}.'\'';
  } else {
    $link = Database::getConnect('Studienverlaufsplaner');
    $stmt = '`B-ID`='.$values->{'B-IDN'}.', Passwort=\''.mysqli_real_escape_string($link, $values->{'Passwort'}).'\', Benutzername=\''.$values->{'Benutzername'}.'\'';
  }
  $where = '`B-ID` LIKE '.$values->{'B-ID'};
  $data = Database::update('Studienverlaufsplaner','Benutzer',$stmt,$where);
  return AdminToolsOverview::SUCCESS;
}
private static function proofExist($values) {
  $exist = false;
  $resultName = Database::select('Studienverlaufsplaner','*','Studiengang','Fachrichtung',"'".$values->{'Fachrichtung'}."'");
  while ($row = $resultName->fetch_assoc()) {
    if ($values->{'S-ID'}!=$row['S-ID']&&$row['Studienordnung'] == $values->{'Studienordnung'}&&$row['Startsemester'] == $values->{'Startsemester'}) {
      $exist = true;
    }
  }
  return $exist;
}
}
?>
