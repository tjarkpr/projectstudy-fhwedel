<?php
include_once('./private/Database.php');
/**
 * AdminVerlaufsplanTools
 * Die Klasse beinhaltet alle wichtigen Operationen, die an einem Studienverlaufplan als JSON-Datei gemacht werden können.
 * @author Tjark, Fynn, Niclas, Kjell
 */
class AdminToolsEditor {
  /*-----GENERELL-----*/
  const SUCCESS = 'Success.';
  const ERROR_NOTFOUND = 'Error: In Datenbank nicht gefunden.';
  const HINT_CHANGED = 'Info: Es mussten Änderungen vorgenommen werden.';
  public static function getProgramPlanJSON($sid) {
    $returndata = Database::select('Studienverlaufsplaner','*','Studiengang','S-ID','\''.$sid.'\'');
    if (is_null($returndata)) {return AdminToolsEditor::ERROR_NOTFOUND;}
    $row = $returndata->fetch_assoc();
    return json_decode($row['Curriculum']);
  }
  public static function getElement($id,$plan) {
    $element = null; $found = false;
    foreach ($plan->{'Semester'} as $semester) {
      for ($i = 0;!$found&&$i < count($semester->{'Prüfungen'}); $i++) {
        if ((isset($semester->{'Prüfungen'}[$i]->{'P-ID'})&&$semester->{'Prüfungen'}[$i]->{'P-ID'} == $id)||(isset($semester->{'Prüfungen'}[$i]->{'W-ID'})&&$semester->{'Prüfungen'}[$i]->{'W-ID'} == $id)) {
          $element = $semester->{'Prüfungen'}[$i];
          $found = true;
        }
      }
    }
    unset($semester);
    if (is_null($element)) {return AdminToolsEditor::ERROR_NOTFOUND;}
    return $element;
  }
  public static function checkPlan($sid,$plan) {
    $changed = false;
    for ($i = 0; $i < count($plan->{'Semester'}); $i++) {
      $fächer = $plan->{'Semester'}[$i]->{'Prüfungen'};
      for ($j=0; $j < count($fächer); $j++) {
        if ($fächer[$j]->{'Typ'} != "S_WAHL") {
          if (!AdminToolsEditor::givenPrecondition($plan,$fächer[$j],$i+1)) {$changed = true;AdminToolsEditor::delete($sid,$plan,$fächer[$j]->{'P-ID'},true);}
          if (!AdminToolsEditor::isPlacedRight($sid,$fächer[$j],$i+1)) {$changed = true;AdminToolsEditor::delete($sid,$plan,$fächer[$j]->{'P-ID'},true);}
        } else {
          foreach ($fächer[$j]->{'P-IDs'} as $prüfung) {
            if (!AdminToolsEditor::givenPrecondition($plan,$prüfung,$i+1)) {$changed = true;AdminToolsEditor::delete($sid,$plan,$fächer[$j]->{'W-ID'},true);}
          }
          unset($prüfung);
        }
      }
    }
    if ($changed) {return AdminToolsEditor::HINT_CHANGED;}
    return AdminToolsEditor::SUCCESS;
  }
  /*-----GET-----*/
  public static function getAdd($plan) {
    $result = '<ul class="subPartMenu" id="upperSub">
      <li id="lectureButton" class="toggleSubMenuPart" onclick="openLecture();closeChooser();"><span>Prüfung</span></li>
      <li id="chooserButton" onclick="openChooser();closeLecture();"><span>Wahlbereich</span></li>
    </ul>
    <div class="subPartPage toggleBlock" id="lecturePage">
    <br><span>Semester*</span><br><input id="addSemesterL" type="number" min="1" value="1" max="'.(count($plan->{'Semester'})+1).'" name="Semester"><br><br>
    <span>Einheiten*</span><br><input id="addEinheitenL" type="number" min="1" value="1" name="Einheiten"><br><br>
    <span>Prüfung*</span><br><div class="addPageLectures"><table>';
    $result = $result.AdminToolsEditor::getAllLecture('selectLectureAdd(this,\'lecturePage\')');
    $result = $result.'</table></div></div>
    <div class="subPartPage" id="chooserPage">
    <br><span>Semester*</span><br><input id="addSemesterW" type="number" min="1" value="1" max="'.(count($plan->{'Semester'})+1).'" name="Semester"><br><br>
    <span>Einheiten*</span><br><input id="addEinheitenW" type="number" min="1" value="1" name="Einheiten"><br><br>
    <span>Prüfungen (Bestandteile im Wahlblock)*</span><br><div class="addPageLectures"><table>';
    $result = $result.AdminToolsEditor::getAllLecture('select(this)');
    $result = $result.'</table></div></div>
    <ul class="subPartMenu" id="lowerSub">
      <li onclick="toggleAdd();"><span>Abbrechen</span></li>
      <li onclick="addToPlan();toggleAdd();"><span>Hinzufügen</span></li>
    </ul>';
    return $result;
  }
  public static function getMove($plan) {
    $result = '<h3 class="subPartMenu" id="upperSub">Auswahl verschieben</h3>
    <div class="subPartPage toggleBlock">
    <br><span>Semester*</span><br><input id="moveSemester" type="number" min="1" value="1" max="'.(count($plan->{'Semester'})+1).'" name="Semester"><br><br></div>
    <ul class="subPartMenu" id="lowerSub">
      <li onclick="toggleMove();"><span>Abbrechen</span></li>
      <li onclick="moveToPlan();toggleMove();"><span>Verschieben</span></li>
    </ul>';
    return $result;
  }
  public static function getAll($sid,$plan) {
    $maxRowCount = AdminToolsEditor::getMaxRowCount($plan);
    $offset = AdminToolsEditor::getNewOffset(count($plan->{'Semester'}));
    $offseti = AdminToolsEditor::getNewOffset(count($plan->{'Semester'}));
    $result='<table class="mainTable">';
    for ($i = 0; $i < $maxRowCount; $i++) {
      $result = $result.'<tr><th>'.($i + 1).'</th>';
      for ($j = 0; $j < count($plan->{'Semester'}); $j++) {
        if ($offset[$j] > 0) { $offset[$j]--;}
        else {
          $fächer = $plan->{'Semester'}[$j]->{'Prüfungen'};
          if (isset($fächer[$i-$offseti[$j]]) && property_exists($fächer[$i-$offseti[$j]],'Typ')) {
            if ($fächer[$i-$offseti[$j]]->{'Typ'} != 'S_WAHL') {
              $name = AdminToolsEditor::getLectureName($fächer[$i-$offseti[$j]]->{'P-ID'});
                if (!property_exists($fächer[$i-$offseti[$j]],'Länge')) {
                  $result = $result.'<td onclick="selectEditor(this)"><strong>'.$fächer[$i-$offseti[$j]]->{'P-ID'}.'</strong><br>'.$name.'</td>';
                } else {
                  $result = $result.'<td onclick="selectEditor(this)" rowspan="'.$fächer[$i-$offseti[$j]]->{'Länge'}.'"><strong>'.$fächer[$i-$offseti[$j]]->{'P-ID'}.'</strong><br>'.$name.'</td>';
                  $offset[$j] = $fächer[$i-$offseti[$j]]->{'Länge'} - 1;
                  $offseti[$j] += $fächer[$i-$offseti[$j]]->{'Länge'} - 1;
                }
            } else {
              $hint = false;
              $result = $result.'<td onclick="selectEditor(this)" rowspan="'.$fächer[$i-$offseti[$j]]->{'Länge'}.'"><strong>'.$fächer[$i-$offseti[$j]]->{'W-ID'}.'</strong><br>Wahlblock<br><table class="subtable">';
              foreach ($fächer[$i-$offseti[$j]]->{'P-IDs'} as $prüfung) {
                if (!AdminToolsEditor::isPlacedRight($sid,$prüfung,$j+1)) {$hint = true;}
                $result = $result.'<tr><th>'.$prüfung->{'P-ID'}.'</th><td>'.AdminToolsEditor::getLectureName($prüfung->{'P-ID'}).'</td></tr>';
              }
              $result = $result.'</table>';
              if ($hint) {$result = $result.'<br>(Beinhaltet Prüfungen die in einem anderem Semester gehört werden)';}
              $result = $result.'</td>';
              $offset[$j] = $fächer[$i-$offseti[$j]]->{'Länge'} - 1;
              $offseti[$j] += $fächer[$i-$offseti[$j]]->{'Länge'} - 1;
            }
          } else {
            $result = $result.'<td></td>';
          }
        }
      }
      $result = $result.'</tr>';
    }
    return $result.'</table>';
  }
  public static function getParts($sid,$plan) {
    $maxRowCount = AdminToolsEditor::getMaxRowCount($plan); $result = '';
    for ($i = 0; $i < count($plan->{'Semester'}); $i++) {
      $result = $result.'<table class="tablePart" id="t'.$i.'">'.AdminToolsEditor::getPart($sid,$plan->{'Semester'}[$i],$i,$maxRowCount).'</table>';
    }
    return $result;
  }
  private static function getAllLecture($click) {
    $data = Database::selectAll('Studienverlaufsplaner', 'Prüfung');
    if (is_null($data)) {return AdminToolsOverview::ERROR_NOTFOUND;}
    $result = '';
    while ($row = $data->fetch_assoc()) {
     $result = $result."<tr onclick=\"".$click."\"><th>".$row['P-ID']."</th><td>".$row['Name']."</td><td class=\"selectTd\"><img class=\"selectImage\" src=\"./svg/selected.svg\" alt=\"icon\"></td></tr>";
    }
    return $result;
  }
  private static function getAllLecturePlan($plan,$click) {
    $result = '';
    for ($i=0; $i < count($plan->{'Semester'}); $i++) {
      $fächer = $plan->{'Semester'}[$i]->{'Prüfungen'};
      for ($j=0;$j < count($fächer); $j++) {
        if ($fächer[$j]->{'Typ'} != 'S_WAHL') {
          $name = AdminToolsEditor::getLectureName($fächer[$j]->{'P-ID'});
          $result = $result."<tr onclick=\"".$click."\"><th>".$fächer[$j]->{'P-ID'}."</th><td>".$name."</td><td class=\"selectTd\"><img class=\"selectImage\" src=\"./svg/selected.svg\" alt=\"icon\"></td></tr>";
        } else {
          $result = $result."<tr onclick=\"".$click."\"><th>".$fächer[$j]->{'W-ID'}."</th><td>Wahlbereich</td><td class=\"selectTd\"><img class=\"selectImage\" src=\"./svg/selected.svg\" alt=\"icon\"></td></tr>";
        }
      }
    }
    return $result;
  }
  private static function getPart($sid,$semester,$j,$maxRowCount) {
    $result = ''; $offset = 0; $offseti = 0;
    for ($i = 0; $i < $maxRowCount; $i++) {
      $result = $result.'<tr><th>'.($i + 1).'</th>';
      if ($offset > 0) {$offset--;}
      else {
        $fächer = $semester->{'Prüfungen'};
        if (isset($fächer[$i-$offseti]) && property_exists($fächer[$i-$offseti],'Typ')) {
          if ($fächer[$i-$offseti]->{'Typ'} != 'S_WAHL') {
            $name = AdminToolsEditor::getLectureName($fächer[$i-$offseti]->{'P-ID'});
            if (!property_exists($fächer[$i-$offseti],'Länge')) {
              $result = $result.'<td onclick="selectEditor(this)"><strong>'.$fächer[$i-$offseti]->{'P-ID'}.'</strong><br>'.$name.'</td>';
            } else {
              $result = $result.'<td onclick="selectEditor(this)" rowspan="'.$fächer[$i-$offseti]->{'Länge'}.'"><strong>'.$fächer[$i-$offseti]->{'P-ID'}.'</strong><br>'.$name.'</td>';
              $offset = $fächer[$i-$offseti]->{'Länge'} - 1;
              $offseti += $fächer[$i-$offseti]->{'Länge'} - 1;
            }
          } else {
            $hint = false;
            $result = $result.'<td onclick="selectEditor(this)" rowspan="'.$fächer[$i-$offseti]->{'Länge'}.'"><strong>'.$fächer[$i-$offseti]->{'W-ID'}.'</strong><br>Wahlblock<br><table class="subtable">';
            foreach ($fächer[$i-$offseti]->{'P-IDs'} as $prüfung) {
              if (!AdminToolsEditor::isPlacedRight($sid,$prüfung,$j+1)) {$hint = true;}
              $result = $result.'<tr><th>'.$prüfung->{'P-ID'}.'</th><td>'.AdminToolsEditor::getLectureName($prüfung->{'P-ID'}).'</td></tr>';
            }
            $result = $result.'</table>';
            if ($hint) {$result = $result.'<br>(Beinhaltet Prüfungen die in einem anderem Semester gehört werden)';}
            $result = $result.'</td>';
            $offset = $fächer[$i-$offseti]->{'Länge'} - 1;
            $offseti += $fächer[$i-$offseti]->{'Länge'} - 1;
          }
        } else {
          $result = $result.'<td></td>';
        }
      }
      $result = $result.'</tr>';
    }
    return $result;
  }
  private static function getMaxRowCount($plan) {
    $maxRowCount = 0;
    foreach ($plan->{'Semester'} as $semester) {
      $addCount = 0;
      if (!is_null($semester->{'Prüfungen'})) {
        foreach ($semester->{'Prüfungen'} as $prüfung) {
          if (isset($prüfung->{'Länge'})) {$addCount += ($prüfung->{'Länge'} - 1);}
        }
        unset($prüfung);
        $rowCount = count($semester->{'Prüfungen'}) + $addCount;
        if ($rowCount > $maxRowCount) {$maxRowCount = $rowCount;}
      }
    }
    unset($semester);
    return $maxRowCount;
  }
  private static function getNewOffset($length) {
    $result = array();
    for ($i=0;$i<$length;$i++) {array_push($result, 0);}
    return $result;
  }
  private static function getLectureName($id) {
    $data = Database::select('Studienverlaufsplaner','*','Prüfung','P-ID','\''.$id.'\'');
    if (is_null($data)) {return null;}
    $row = $data->fetch_assoc();
    return $row['Name'];
  }
  /*-----DELETE-----*/
  const ERROR_IPRECONDITION = 'Error: Element ist eine Vorbedingung eines anderen Elements.';
  const ERROR_NOTEXIST = 'Error: Element existiert im Plan nicht.';
  public static function delete($sid,$plan,$id,$check) {
    $deleted = false;
    for ($i = 0;!$deleted && $i < count($plan->{'Semester'}); $i++) {
      $fächer = $plan->{'Semester'}[$i]->{'Prüfungen'};
      for ($j = 0;!$deleted && $j < count($fächer); $j++) {
        if ((isset($fächer[$j]->{'P-ID'})&&$fächer[$j]->{'P-ID'} == $id)||(isset($fächer[$j]->{'W-ID'})&&$fächer[$j]->{'W-ID'} == $id)) {
          if ($fächer[$j]->{'Typ'} == 'S_FACH') {
            if ($check&&AdminToolsEditor::isPrecondition($plan,$id,$i) != -1) {return AdminToolsEditor::ERROR_IPRECONDITION;}
          }
          unset($fächer[$j]);
          $fächer = array_values($fächer);
          $deleted = true;
        }
      }
      unset($prüfung);
      if (count($fächer) == 0 && count($plan->{'Semester'}) > 1 && !AdminToolsEditor::foundElementsAfter($plan,$i)) {
        unset($plan->{'Semester'}[$i]);
        $plan->{'Semester'} = array_values($plan->{'Semester'});
      } else {
        $plan->{'Semester'}[$i]->{'Prüfungen'} = $fächer;
      }
    }
    if (!$deleted) {return AdminToolsEditor::ERROR_NOTEXIST;}
    return AdminToolsEditor::SUCCESS;
  }
  private static function foundElementsAfter($plan,$idx) {
    $found = false;
    for ($i = $idx+1;!$found && $i < count($plan->{'Semester'}); $i++) {
      $fächer = $plan->{'Semester'}[$i]->{'Prüfungen'};
      if (count($fächer) != 0) {$found = true;}
    }
    return $found;
  }
  private static function isPrecondition($plan,$id,$semester) {
    $found = -1;
    for ($i = $semester+1;$found == -1 && $i < count($plan->{'Semester'}); $i++) {
      $semester = $plan->{'Semester'}[$i];
      foreach ($semester->{'Prüfungen'} as $prüfung) {
        if ($found == -1 && $prüfung->{'Typ'} != 'S_WAHL') {
          $databaseEntry = Database::selectExtended('Studienverlaufsplaner','*','Prüfung_vorbedingt_Prüfung','P-ID','\''.$prüfung->{'P-ID'}.'\'','P-IDB','\''.$id.'\'');
          $databaseEntry = $databaseEntry->fetch_assoc();
          if (!is_null($databaseEntry)) {$found = $i;}
        } else if ($found == -1 && $prüfung->{'Typ'} == 'S_WAHL') {
          foreach ($prüfung->{'P-IDs'} as $subprüfung) {
            if ($found == -1) {
              $databaseEntry = Database::selectExtended('Studienverlaufsplaner','*','Prüfung_vorbedingt_Prüfung','P-ID','\''.$subprüfung->{'P-ID'}.'\'','P-IDB','\''.$id.'\'');
              $databaseEntry = $databaseEntry->fetch_assoc();
              if (!is_null($databaseEntry)) {$found = $i;}
            }
          }
          unset($subprüfung);
        }
      }
      unset($prüfung);
    }
    return $found;
  }
  /*-----INSERT-----*/
  const ERROR_EXIST = 'Error: Element existiert bereits in dem Verlaufsplan.';
  const ERROR_GPRECONDITION = 'Error: Element besitzt Vorbedingungen, die nicht erfüllt werden können.';
  const ERROR_PLACE = 'Error: Element kann in diesem Semester nicht angelegt werden.';
  const ERROR_ANZ = 'Error: Anzahl Einheiten und Anzahl Wahlfächer stimmen nicht überein.';
  public static function insertChooser($sid,$plan,$element,$semester) {
    if ($element->{'Länge'} > count($element->{'P-IDs'})) {return AdminToolsEditor::ERROR_ANZ;}
    if (AdminToolsEditor::hasDuplicate($plan,$element)) {return AdminToolsEditor::ERROR_EXIST;}
    foreach ($element->{'P-IDs'} as $prüfung) {
      if (AdminToolsEditor::hasDuplicate($plan,$prüfung)) {return AdminToolsEditor::ERROR_EXIST;}
      if (!AdminToolsEditor::givenPrecondition($plan,$prüfung,$semester)) {return AdminToolsEditor::ERROR_GPRECONDITION;}
    }
    unset($prüfung);
    $count = $semester - count($plan->{'Semester'});
    if ($count > 0) {
      array_push($plan->{'Semester'},json_decode('{"Prüfungen":[]}'));
      array_push($plan->{'Semester'}[count($plan->{'Semester'})-1]->{'Prüfungen'},$element);
    } else {
      array_push($plan->{'Semester'}[$semester-1]->{'Prüfungen'},$element);
    }
    return AdminToolsEditor::SUCCESS;
  }
  public static function insertLecture($sid,$plan,$element,$semester) {
    if (AdminToolsEditor::hasDuplicate($plan,$element)) {return AdminToolsEditor::ERROR_EXIST;}
    if (!AdminToolsEditor::givenPrecondition($plan,$element,$semester)) {return AdminToolsEditor::ERROR_GPRECONDITION;}
    if (!AdminToolsEditor::isPlacedRight($sid,$element,$semester)) {return AdminToolsEditor::ERROR_PLACE;}
    $count = $semester - count($plan->{'Semester'});
    if ($count > 0) {
      array_push($plan->{'Semester'},json_decode('{"Prüfungen":[]}'));
      array_push($plan->{'Semester'}[count($plan->{'Semester'})-1]->{'Prüfungen'},$element);
    } else {
      array_push($plan->{'Semester'}[$semester-1]->{'Prüfungen'},$element);
    }
    return AdminToolsEditor::SUCCESS;
  }
  private static function hasDuplicate($plan,$element) {
    $duplicate = false;
    foreach ($plan->{'Semester'} as $semester) {
      if (!$duplicate) {
        foreach ($semester->{'Prüfungen'} as $prüfung) {
          if (!$duplicate) {
            if (isset($prüfung->{'P-ID'})&&isset($element->{'P-ID'})&&$prüfung->{'P-ID'} == $element->{'P-ID'}) {$duplicate = true;}
            else if (isset($prüfung->{'W-ID'})&&isset($element->{'W-ID'})&&$prüfung->{'W-ID'} == $element->{'W-ID'}) {$duplicate = true;}
          }
        }
        unset($prüfung);
      }
    }
    unset($semester);
    return $duplicate;
  }
  private static function givenPrecondition($plan,$element,$semesterZ) {
    $databaseEntry = Database::select('Studienverlaufsplaner','*','Prüfung_vorbedingt_Prüfung','P-ID','\''.$element->{'P-ID'}.'\'');
    while ($row = $databaseEntry->fetch_assoc()) {
      $fulfill = false;
      for ($i = 0;!$fulfill && $i < ($semesterZ-1); $i++) {
        $semester = $plan->{'Semester'}[$i];
        foreach ($semester->{'Prüfungen'} as $prüfung) {
          if ($prüfung->{'Typ'} != "S_WAHL") {
            if (!$fulfill &&(isset($prüfung->{'P-ID'})&&$prüfung->{'P-ID'} == $row['P-IDB'])) {$fulfill=true;}
          } else {
            foreach ($prüfung->{'P-IDs'} as $subprüfung) {
              if (!$fulfill &&(isset($subprüfung->{'P-ID'})&&$subprüfung->{'P-ID'} == $row['P-IDB'])) {$fulfill=true;}
            }
            unset($subprüfung);
          }
        }
        unset($prüfung);
      }
      if (!$fulfill) {return false;}
    }
    return true;
  }
  private static function isPlacedRight($sid,$element,$semester) {
    $databaseEntryPrüfung = Database::select('Studienverlaufsplaner','*','Prüfung','P-ID','\''.$element->{'P-ID'}.'\'');
    $databaseEntryProgram = Database::select('Studienverlaufsplaner','*','Studiengang','S-ID','\''.$sid.'\'');
    $databaseEntryPrüfung = $databaseEntryPrüfung->fetch_assoc();
    $databaseEntryProgram = $databaseEntryProgram->fetch_assoc();
    if ($databaseEntryPrüfung['Angebot']=='E') {return true;}
    $startWS = ($databaseEntryProgram['Startsemester']=='W');
    $prüfungWS = ($databaseEntryPrüfung['Angebot']=='W');
    if ($startWS) {
      return ($prüfungWS == ($startWS && (($semester % 2) != 0)));
    } else {
      return ($prüfungWS == ($startWS || (($semester % 2) == 0)));
    }
  }
  /*-----MOVE-----*/
  public static function moveChooser($sid,$plan,$element,$semester) {
    foreach ($element->{'P-IDs'} as $prüfung) {
      if (AdminToolsEditor::hasDuplicate($plan,$prüfung)) {return AdminToolsEditor::ERROR_EXIST;}
      if (!AdminToolsEditor::givenPrecondition($plan,$prüfung,$semester)) {return AdminToolsEditor::ERROR_GPRECONDITION;}
      if (!AdminToolsEditor::isPlacedRight($sid,$prüfung,$semester)) {return AdminToolsEditor::ERROR_PLACE;}
    }
    unset($prüfung);
    $result = AdminToolsEditor::delete($sid,$plan,$element->{'W-ID'},false);
    if ($result == AdminToolsEditor::SUCCESS) {
      $count = $semester - count($plan->{'Semester'});
      if ($count > 0) {
        array_push($plan->{'Semester'},json_decode('{"Prüfungen":[]}'));
        array_push($plan->{'Semester'}[count($plan->{'Semester'})-1]->{'Prüfungen'},$element);
      } else {
        array_push($plan->{'Semester'}[$semester-1]->{'Prüfungen'},$element);
      }
    }
    return $result;
  }
  public static function moveLecture($sid,$plan,$element,$semester) {
    if (!AdminToolsEditor::givenPrecondition($plan,$element,$semester)) {return AdminToolsEditor::ERROR_GPRECONDITION;}
    if (!AdminToolsEditor::isPlacedRight($sid,$element,$semester)) {return AdminToolsEditor::ERROR_PLACE;}
    $preSemester = AdminToolsEditor::isPrecondition($plan,$element->{'P-ID'},0);
    if ($preSemester != -1 && $semester-1 >= $preSemester) {return AdminToolsEditor::ERROR_IPRECONDITION;}
    $result = AdminToolsEditor::delete($sid,$plan,$element->{'P-ID'},false);
    if ($result == AdminToolsEditor::SUCCESS) {
      $count = $semester - count($plan->{'Semester'});
      if ($count > 0) {
        array_push($plan->{'Semester'},json_decode('{"Prüfungen":[]}'));
        array_push($plan->{'Semester'}[count($plan->{'Semester'})-1]->{'Prüfungen'},$element);
      } else {
        array_push($plan->{'Semester'}[$semester-1]->{'Prüfungen'},$element);
      }
    }
    return $result;
  }
}
?>
