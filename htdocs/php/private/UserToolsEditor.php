<?php
include_once('./private/Authentificate.php');
include_once('./private/Database.php');

    class UserToolsEditor {

      /**
       * Die benötigten Fehlermeldungen.
       */
      const ERROR_NOSELECTED = 'Error: Es wurde kein Fach ausgewählt.';
      const ERROR_NOTEXISTINPLAN = 'Error: Das Fach ist nicht im Verlaufsplan enthalten. Bitte erst hinzufügen.';
      const ERROR_GPRECONDITION = 'Error: Das Fach besitzt Vorbedingungen, die nicht erfüllt werden können.';
      const ERROR_PLACE = 'Error: Das Fach wird in diesem Semester nicht angeboten.';
      const ERROR_EXISTSALREADY = 'Error: Das Fach ist schon im Verlaufsplan enthalten.';
      const ERROR_SEMESTER = 'Error: Ungültige Angabe für das Semester.';
      const ERROR_IPRECONDITION = 'Error: Fach ist eine Vorbedingung eines anderen Elements.';
      const ERROR_NOTEXIST = 'Error: Fach existiert im Plan nicht.';
      const ERROR_NOTFREESUBJECT = 'Error: Dies ist ein Pflichtfach.';
      const ERROR_PASSED = 'Error: Das Fach ist bereits bestanden.';
      const ERROR_RESTRIKTION = 'Error: Beachte die Restriktionen für die Fächer des 1. Semesters.';
      const SUCCESS = 'Success.';

      /**
       * Gibt den Curriculum zur übergebenden User-ID zurück.
       */
      public static function getProgramPlanJSON($userID) {
        $returndata = Database::select('Studienverlaufsplaner','*','Benutzer','B-ID','\''.$userID.'\'');
        if (is_null($returndata)) {return UserToolsEditor::ERROR_NOTFOUND;}
        $row = $returndata->fetch_assoc();
        return json_decode($row['Curriculum']);
      }

      /**
       * Gibt die Studienplan-ID zurück, die der User hat.
       */
      public static function getMajor($userID){
        $returndata = Database::select('Studienverlaufsplaner','*','Benutzer','B-ID','\''.$userID.'\'');
        if (is_null($returndata)) {return UserToolsEditor::ERROR_NOTFOUND;}
        $row = $returndata->fetch_assoc();
        return $row['S-ID'];
      }

      /**
       * Baut die gesamte Übersicht des Studienverlaufsplan zu einer Tabelle zusammen und liefert
       * diese zurück.
       */
      public static function getAll($userID,$plan) {
        $maxRowCount = UserToolsEditor::getMaxRowCount($plan);
        $offset = UserToolsEditor::getNewOffset(count($plan->{'Semester'}));
        $offseti = UserToolsEditor::getNewOffset(count($plan->{'Semester'}));
        $result='<table id="tableAllSubjects">';

        $result = $result.'<tr class="lisemester">';
        for ($x = 0; $x < count($plan->{'Semester'}) + 1; $x++){
          if($x == 0){
            $result = $result.'<th>Semester<br>'.($x).'</th>';
          } else {
            $result = $result.'<td><b>Semester '.($x).'</b></td>';
          }

        }
        $result = $result.'</tr>';

        for ($i = 0; $i < $maxRowCount; $i++) {
          $result = $result.'<tr><th>'.($i + 1).'</th>';
          for ($j = 0; $j < count($plan->{'Semester'}); $j++) {
            if ($offset[$j] > 0) { $offset[$j]--;}
            else {
              $fächer = $plan->{'Semester'}[$j]->{'Prüfungen'};
              if (isset($fächer[$i-$offseti[$j]]) && property_exists($fächer[$i-$offseti[$j]],'Typ') && $fächer[$i-$offseti[$j]]->{'Typ'} != 'S_WAHL_ND') {
                $classname = '';
                if ($fächer[$i-$offseti[$j]]->{'Typ'} != 'S_WAHL' ) {
                  if($fächer[$i-$offseti[$j]]->{'Bestanden'} === true){$classname = 'class=passedSubject';}
                  $name = UserToolsEditor::getLectureName($fächer[$i-$offseti[$j]]->{'P-ID'});
                  if (is_null($name)) {UserToolsEditor::deleteLecture($fächer[$i-$offseti[$j]]->{'P-ID'});}
                  else {
                    if (!property_exists($fächer[$i-$offseti[$j]],'Länge')) {
                      $result = $result.'<td '.$classname.' onclick="selectUserEditor(this)"><strong>'.$fächer[$i-$offseti[$j]]->{'P-ID'}.'</strong><br>'.$name.'</td>';
                    } else {
                      $result = $result.'<td '.$classname.' onclick="selectUserEditor(this)" rowspan="'.$fächer[$i-$offseti[$j]]->{'Länge'}.'"><strong>'.$fächer[$i-$offseti[$j]]->{'P-ID'}.'</strong><br>'.$name.'</td>';
                      $offset[$j] = $fächer[$i-$offseti[$j]]->{'Länge'} - 1;
                      $offseti[$j] += $fächer[$i-$offseti[$j]]->{'Länge'} - 1;
                    }
                  }
                } else {
                  if ($fächer[$i-$offseti[$j]]->{'Länge'} == 0){
                    if (($i-$offseti[$j] + 1) < count($fächer)) {
                    if($fächer[$i-$offseti[$j] + 1]->{'Bestanden'} === true){$classname = 'class=passedSubject';}
                    $name = UserToolsEditor::getLectureName($fächer[($i)-$offseti[$j] + 1]->{'P-ID'});
                    $result = $result.'<td '.$classname.' onclick="selectUserEditor(this)"><strong>'.$fächer[$i-$offseti[$j] + 1]->{'P-ID'}.'</strong><br>'.$name.'</td>';
                  } else {
                    $result = $result.'<td></td>';
                  }
                  } else {
                    $hint = false;
                    foreach ($fächer[$i-$offseti[$j]]->{'P-IDs'} as $prüfung) {
                    if (!UserToolsEditor::isPlacedRight(UserToolsEditor::getMajor($userID),$prüfung,$j+1)) {$hint = true;}
                    }
                       $result = $result.'<td onclick="toggleElective(this);" rowspan="'.$fächer[$i-$offseti[$j]]->{'Länge'}.'"><strong>'.$fächer[$i-$offseti[$j]]->{'W-ID'}.'</strong><br>Wahlblock';
                       if ($hint) {$result = $result.'<br>(Beinhaltet Prüfungen die in einem anderem Semester gehört werden)';}
                       $result = $result.'</td>';

                  }

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

      /**
       * Stellt aus dem Verlaufsplan die einzelnen Semester in Tabellen da und
       * liefert diese zurück.
       */
      public static function getParts($plan, $userID) {
        $maxRowCount = UserToolsEditor::getMaxRowCount($plan); $result = '';
        for ($i = 0; $i < count($plan->{'Semester'}); $i++){
          $result = $result.'<table class="tablePart" id="t'.$i.'">'.UserToolsEditor::getPart($userID,$plan->{'Semester'}[$i],$i,$maxRowCount).'</table>';
        }
        return $result;
      }

      /**
       * Erstellt eine Tabelle mit den Fächern zu einem Semester.
       */
      private static function getPart($userID,$semester,$j,$maxRowCount) {
        $result = ''; $offset = 0; $offseti = 0;
        for ($i = 0; $i < $maxRowCount; $i++) {
          $result = $result.'<tr><th>'.($i + 1).'</th>';
          if ($offset > 0) {$offset--;}
          else {
            $fächer = $semester->{'Prüfungen'};
            if (isset($fächer[$i-$offseti]) && property_exists($fächer[$i-$offseti],'Typ') && $fächer[$i-$offseti]->{'Typ'} != 'S_WAHL_ND') {
              $classname = '';
              if ($fächer[$i-$offseti]->{'Typ'} != 'S_WAHL') {
                if($fächer[$i-$offseti]->{'Bestanden'} === true){$classname = 'class=passedSubject';}
                $name = UserToolsEditor::getLectureName($fächer[$i-$offseti]->{'P-ID'});
                if (is_null($name)) {UserToolsEditor::deleteLecture($fächer[$i-$offseti]->{'P-ID'});}
                else {
                  if (!property_exists($fächer[$i-$offseti],'Länge')) {
                    $result = $result.'<td '.$classname.' onclick="selectUserEditor(this)"><strong>'.$fächer[$i-$offseti]->{'P-ID'}.'</strong><br>'.$name.'</td>';
                  } else {
                    $result = $result.'<td '.$classname.' onclick="selectUserEditor(this)" rowspan="'.$fächer[$i-$offseti]->{'Länge'}.'"><strong>'.$fächer[$i-$offseti]->{'P-ID'}.'</strong><br>'.$name.'</td>';
                    $offset = $fächer[$i-$offseti]->{'Länge'} - 1;
                    $offseti += $fächer[$i-$offseti]->{'Länge'} - 1;
                  }
                }
              } else {
                if ($fächer[$i-$offseti]->{'Länge'} == 0){
                  if($fächer[$i-$offseti + 1]->{'Bestanden'} === true){$classname = 'class=passedSubject';}
                  $name = UserToolsEditor::getLectureName($fächer[($i)-$offseti + 1]->{'P-ID'});
                  $result = $result.'<td '.$classname.' onclick="selectUserEditor(this)"><strong>'.$fächer[$i-$offseti + 1]->{'P-ID'}.'</strong><br>'.$name.'</td>';
                } else {
                $hint = false;
                 foreach ($fächer[$i-$offseti]->{'P-IDs'} as $prüfung) {
                   if (!UserToolsEditor::isPlacedRight(UserToolsEditor::getMajor($userID),$prüfung,$j+1)) {$hint = true;}

                }
                if($fächer[$i-$offseti]->{'Länge'} != 0){
                  $result = $result.'<td onclick="toggleElective(this);" rowspan="'.$fächer[$i-$offseti]->{'Länge'}.'"><strong>'.$fächer[$i-$offseti]->{'W-ID'}.'</strong><br>Wahlblock';
                  if ($hint) {$result = $result.'<br>(Beinhaltet Prüfungen die in einem anderem Semester gehört werden)';}
                    $result = $result.'</td>';
                }

              }
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

      /**
       * Gibt die Informationen in einem HTML-Element wieder.
       */
      public static function getInformation($id) {
        $result = '';

        if($id === 'none'){
          return $result;
        }

        $result = '<span class="heading"><u>Name des Faches:</u></span><br>';
        $result =$result.'<p class="infoText">'.UserToolsEditor::getInfo($id,'Name').'</p>';

        $result =$result.'<span class="heading"><u>Fach-ID:</u></span><br>';
        $result =$result.'<p class="infoText">'.UserToolsEditor::getInfo($id,'P-ID').'</p>';

        $result =$result.'<span class="heading"><u>Angebot:</u></span><br>';
        switch (UserToolsEditor::getInfo($id,'Angebot')){
          case 'E': $result = $result.'<p class="infoText">Sommer- und Wintersemester</p>';break;
          case 'W': $result = $result.'<p class="infoText">Nur im Wintersemester</p>'; break;
          case 'S': $result = $result.'<p class="infoText">Nur im Sommersemester</p>';break;
        }

        $result =$result.'<span class="heading"><u>Vorbedingungen:</u></span><br>';

        if (UserToolsEditor::getPrerequisite($id) === ''){
          $result =$result.'<p class="infoText">-</p>';
        } else {
          $rawdata = Database::selectAll('Studienverlaufsplaner','Prüfung_vorbedingt_Prüfung');
            while ($precondition = $rawdata->fetch_assoc()) {
              if ($precondition['P-ID'] == $id){
                $result =$result.'<p class="infoText">'.UserToolsEditor::getInfo($precondition['P-IDB'], 'Name').'</p>';
              }
          }
        }
        return $result;
      }

      /**
       * Setzt das übergebende Fach im übergebenden Plan als Bestanden.
       */
      public static function setPassedSubject($userId, $pID, $plan){
        if($pID === ''){
          return UserToolsEditor::ERROR_NOSELECTED;
        } else if(!UserToolsEditor::existsElement($pID, $plan)){
          return UserToolsEditor::ERROR_NOTEXISTINPLAN;
        }

        $changePassed = false;
        for ($i = 0; $i < count($plan->{'Semester'}); $i++) {
          $fächer = $plan->{'Semester'}[$i]->{'Prüfungen'};
          for ($j = 0;!$changePassed && $j < count($fächer); $j++) {
            if ((isset($fächer[$j]->{'P-ID'}) && $fächer[$j]->{'P-ID'} == $pID)) {
              if ($fächer[$j]->{'Bestanden'} == true) {
                $fächer[$j]->{'Bestanden'} = false;
              } else {
                $fächer[$j]->{'Bestanden'} = true;
              }
              unset($fächer[$j]);
              $fächer = array_values($fächer);
              $changePassed = true;
            }
          }
        }
      }

      /**
       *  Erstellt eine Tabelle mit allen Fächern, die nicht im Verlaufsplan
       *  enthalten sind, sodass später diese als freiwilligen Fächern hinzugefügt
       *  werden können.
       */
      public static function getFreeSubjects($userID, $plan) {
        $result = '<table id="tableSearch"><thead><tr><th><input type="search" id="search" onkeyup="searchSubjects()" placeholder="Suche..."></th></tr></thead><tbody>';
        $rawdata = Database::selectAll('Studienverlaufsplaner','Prüfung');

        while ($row = $rawdata->fetch_assoc()) {
          if (!UserToolsEditor::existsElement($row["P-ID"], $plan) && is_null(UserToolsEditor::isElectiveSubject($row["P-ID"],$plan))){
            $result = $result.'<tr><td onclick="selectUserEditor(this)"><strong>'.$row["P-ID"] .'</strong><br>'.$row["Name"].'</td></tr>';
          }
        }

        $result = $result.'</tbody></table>';
        return $result;
      }

      /**
       * Initializiert das Popup für den Button 'Entfernen'.
       */
      public static function getRemove() {
        $result = '<p>Möchtest du das Fach wirklich löschen?</p><br>
                  <ul class="subPartMenu" id="lowerSub">
                  <li onclick="toggleRemove();"><span>Abbrechen</span></li>
                  <li onclick="deleteFromPlanUser();toggleRemove();"><span>Entfernen</span></li>
                  </ul>';

        return $result;
      }

      /**
       * Initializiert das Popup für den Button 'Hinzufügen'.
       */
      public static function getAdd($plan) {
        $result = '<p>Wähle ein Semester aus:</p><br>
                  <input id="addSemesterL" type="number" min="1" max="'.(count($plan->{'Semester'})+1).'" name="Semester"><br><br>

                  <ul class="subPartMenu" id="lowerSub">
                  <li onclick="toggleAdd();"><span>Abbrechen</span></li>
                  <li onclick="addToPlanUser();toggleAdd();"><span>Hinzufügen</span></li>
                  </ul>';

        return $result;
      }

      /**
       * Initializiert das Popup für den Button 'Verschieben'.
       */
      public static function getMove($plan) {
        $result = '<p>Wähle ein Semester aus:</p><br>
                  <input id="moveSemesterD" type="number" min="1" max="'.(count($plan->{'Semester'})+1).'" name="Semester"><br><br>
                  <ul class="subPartMenu" id="lowerSub">
                  <li onclick="toggleMove();"><span>Abbrechen</span></li>
                  <li onclick="moveOnPlanUser();toggleMove();"><span>Verschieben</span></li>
                  </ul>';

        return $result;
      }

      /**
       * Initializiert das Popup für den Button 'Zurücksetzen'.
       */
      public static function getReset(){
        $result = '<p>Möchtest du dein Studienverlaufsplan zurücksetzen?</p><br>
                  <ul class="subPartMenu" id="lowerSub">
                  <li onclick="toggleReset();"><span>Abbrechen</span></li>
                  <li onclick="resetPlanUser();toggleReset();"><span>Zurücksetzen</span></li>
                  </ul>';

        return $result;
      }

      /**
       * Initializiert das Popup für die Wahlfächer.
       */
      public static function getElective($plan,$wID) {
        $result = '<div class="subPartPage toggleBlock" id="chooseOnePage">
                  <p>Wähle ein Fach:</p>';
                  $result = $result.UserToolsEditor::getChooseSubjects($plan,$wID);
                  $result = $result.'</div>
                  <ul class="subPartMenu" id="lowerSub">
                  <li onclick="toggleElective(null);"><span>Abbrechen</span></li>
                  <li onclick="selectElectiveUser();toggleElective(null);"><span>Auswählen</span></li>
                  </ul>';

        return $result;
      }

      /**
       * Gibt das Element mit der übergebenden ID zurück, falls dieses im
       * Verlaufsplan enthalten ist.
       */
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
        if (is_null($element)) {return UserToolsEditor::ERROR_NOSELECTED;}
        return $element;
      }

      /**
       * Fügt das übergebende Element in das übergebende Semester an.
       */
      public static function insertLecture($userID,$plan,$element,$semester) {
        if ($element->{'P-ID'} ===''){return UserToolsEditor::ERROR_NOSELECTED;}
        if ($semester === '') {return UserToolsEditor::ERROR_SEMESTER;}
        if (UserToolsEditor::existsElement($element->{'P-ID'},$plan)) {return UserToolsEditor::ERROR_EXISTSALREADY;}
        if (!UserToolsEditor::givenPrecondition($plan,$element,$semester)) {return UserToolsEditor::ERROR_GPRECONDITION;}
        if (!UserToolsEditor::isPlacedRight($userID,$element,$semester,$plan)) {return UserToolsEditor::ERROR_PLACE;}


        if($element->{'Typ'} == 'W_FACH'){
          UserToolsEditor::deleteChooseJson(UserToolsEditor::getElement($element->{'RW-ID'},$plan), $element, $plan);
        }

        $count = $semester - count($plan->{'Semester'});

        if ($count > 0) {
          array_push($plan->{'Semester'},json_decode('{"Prüfungen":[]}'));
          array_push($plan->{'Semester'}[count($plan->{'Semester'})-1]->{'Prüfungen'},$element);
        } else {
          array_push($plan->{'Semester'}[$semester-1]->{'Prüfungen'},$element);
        }


        return UserToolsEditor::SUCCESS;
      }

      /**
       * Löscht das Element mit der übergebenden Fach-ID aus dem Verlaufsplan,
       * falls dieses vorhanden ist.
       */
      public static function delete($sid,$plan,$id,$isMove) {
        if ($id === ""){ return UserToolsEditor::ERROR_NOSELECTED;}
        $deleted = false;
        for ($i = 0;!$deleted && $i < count($plan->{'Semester'}); $i++) {
          $fächer = $plan->{'Semester'}[$i]->{'Prüfungen'};
          for ($j = 0;!$deleted && $j < count($fächer); $j++) {
            if ((isset($fächer[$j]->{'P-ID'})&&$fächer[$j]->{'P-ID'} == $id)) {
                if($isMove) {
                  if ($fächer[$j]->{'Typ'} !== 'FW_FACH' && $fächer[$j]->{'Typ'} !== 'W_FACH'){return UserToolsEditor::ERROR_NOTFREESUBJECT;}
                }
                if (UserToolsEditor::isPrecondition($plan,$id,$i)) {return UserToolsEditor::ERROR_IPRECONDITION;}

                if($fächer[$j]->{'Typ'} == 'W_FACH' && $isMove){
                  UserToolsEditor::insertChooseJson(UserToolsEditor::getElement($fächer[$j]->{'RW-ID'},$plan), $fächer[$j], $plan);
                  $chooser = UserToolsEditor::getElement($fächer[$j]->{'RW-ID'},$plan);
                  $chooser->{'Länge'} = $chooser->{'Länge'} + 1;
                }
              unset($fächer[$j]);
              $fächer = array_values($fächer);
              $deleted = true;
            }
          }
          unset($prüfung);
          if (count($fächer) == 0) {
            unset($plan->{'Semester'}[$i]);
            $plan->{'Semester'} = array_values($plan->{'Semester'});
          } else {
            $plan->{'Semester'}[$i]->{'Prüfungen'} = $fächer;
          }
        }
        if (!$deleted) {return UserToolsEditor::ERROR_NOTEXIST;}
        return UserToolsEditor::SUCCESS;
      }

      /**
       * Verschiebt das Element in das übergebenden Fach, soweit dies
       * möglich ist.(Restriktionen)
       */
      public static function moveLecture($userID,$plan,$element,$semester,$isMove) {
        if ($semester === '') {return UserToolsEditor::ERROR_SEMESTER;}
        if ($element->{'Bestanden'}){return UserToolsEditor::ERROR_PASSED;}
        if (!UserToolsEditor::moveRestriktion($userID,$element,$semester)){return UserToolsEditor::ERROR_RESTRIKTION ;}
        if (!UserToolsEditor::givenPrecondition($plan,$element,$semester)) {return UserToolsEditor::ERROR_GPRECONDITION;}
        if (!UserToolsEditor::isPlacedRight($userID,$element,$semester,$plan)) {return UserToolsEditor::ERROR_PLACE;}
        $result = UserToolsEditor::delete($userID,$plan,$element->{'P-ID'},$isMove);
        if ($result == UserToolsEditor::SUCCESS) {
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

      /**
       * Setzt das ausgewählte Wahlfach in das dafür vorgesehende Semester.
       */
      public static function setElective($userID, $bID, $plan){
        $element = UserToolsEditor::getElementElective($bID, $plan, false);
        $semester = UserToolsEditor::getElementElective($bID, $plan, true);
        if (!UserToolsEditor::givenPrecondition($plan,$element,$semester)) {return UserToolsEditor::ERROR_GPRECONDITION;}
        $choose = UserToolsEditor::isElectiveSubject($bID,$plan);

        $choose->{'Länge'} = ($choose->{'Länge'}) - 1;

        if(!UserToolsEditor::isPlacedRight($userID,$element,$semester,$plan)){
          $semester = ($semester - 1);
        }

        UserToolsEditor::insertLecture($userID,$plan,$element,$semester);

        return UserToolsEditor::SUCCESS;
      }

      /**
       * Setzt den bearbeiteten Studienplan auf die Ausgangssituation zurück.
       */
      public static function reset($userID){
        $sID = UserToolsEditor::getMajor($userID);
        $returndata = Database::select('Studienverlaufsplaner','*','Studiengang','S-ID','\''.$sID.'\'');
        if (is_null($returndata)) {return UserToolsEditor::ERROR_NOTFOUND;}
        $row = $returndata->fetch_assoc();
        return json_decode($row['Curriculum']);
      }


      // --- HILFSFUNKTIONEN ---


      /**
      * Gibt die Länge der längsten Zeile im Verlaufsplan an.
       */
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

      /**
      * Auslesen des Vorlesungsnamen.
      */
      private static function getInfo($id, $column) {
        $rawdata = Database::select('Studienverlaufsplaner','*','Prüfung','P-ID','\''.$id.'\'');
        $rawdata = $rawdata->fetch_assoc();

        return $rawdata[$column];
      }

      /**
       * Auslesen der Vorbedingungen.
       */
      private static function getPrerequisite($id){
        $rawdata = Database::select('Studienverlaufsplaner','*','Prüfung_vorbedingt_Prüfung','P-ID','\''.$id.'\'');

        if($rawdata->num_rows > 0){
             $rawdata = $rawdata->fetch_assoc();
            return UserToolsEditor::getInfo($rawdata['P-IDB'],'Name');
        }

        return '';
      }

      /**
       * Überprüft, ob ein die Fach-ID im Verlaufsplan enthalten ist.
       */
      private static function existsElement($id,$plan) {
        $found = false;
        foreach ($plan->{'Semester'} as $semester) {
          for ($i = 0;!$found&&$i < count($semester->{'Prüfungen'}); $i++) {
            if ((isset($semester->{'Prüfungen'}[$i]->{'P-ID'})&&$semester->{'Prüfungen'}[$i]->{'P-ID'} == $id)||(isset($semester->{'Prüfungen'}[$i]->{'W-ID'})&&$semester->{'Prüfungen'}[$i]->{'W-ID'} == $id)) {
              $found = true;
            }
          }
        }
        unset($semester);
        return $found;
      }

      /**
       * Gibt die Wahlfächer für ein Wahlblock zurück.
       */
      private static function getChooseSubjects($plan, $wID){
        $element = UserToolsEditor::getElement($wID, $plan);
        $result = '<table>';

        foreach($element->{'P-IDs'} as $fach){
          $result = $result.'<tr><td onclick="selectUserEditor(this)"><strong>'.$fach->{'P-ID'}.'</strong><br>'.UserToolsEditor::getLectureName($fach->{'P-ID'}).'</tr></td>';
        }

        $result = $result.'</table>';
        return $result;
      }

      /**
       * Überprüft, ob das übergebende Fach ein Wahlfach ist. Ist dies der Fall
       * gibt die Funktion true zurück.
       */
      private static function isElectiveSubject($bID,$plan){
        $found = false;
        $result = null;
        foreach ($plan->{'Semester'} as $semester) {
          for ($i = 0;!$found&&$i < count($semester->{'Prüfungen'}); $i++) {
            if (($semester->{'Prüfungen'}[$i]->{'Typ'} == 'S_WAHL')) {

              foreach ($semester->{'Prüfungen'}[$i]->{'P-IDs'} as $fach){
                if($fach->{'P-ID'} == $bID){
                  $result = $semester->{'Prüfungen'}[$i];
                  $found = true;
                }
              }
            }
          }
        }
        unset($semester);
        return $result;
      }

      /**
       * Überprüft, ob das Element in das übergebende Semester gehört oder
       * dort nicht angeboten wird. Wenn es richtig platziert ist, gibt die
       * Funktion true zurück.
       */
      private static function isPlacedRight($userID,$element,$semester) {
        $databaseEntryPrüfung = Database::select('Studienverlaufsplaner','*','Prüfung','P-ID','\''.$element->{'P-ID'}.'\'');
        $databaseEntryProgram = Database::select('Studienverlaufsplaner','*','Studiengang','S-ID','\''.UserToolsEditor::getMajor($userID).'\'');
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

      /**
       * Überprüft, ob es zu dem übergebenden Element Vorbedingungen gibt.
       */
      private static function givenPrecondition($plan,$element,$semesterZ) {
        $databaseEntry = Database::select('Studienverlaufsplaner','*','Prüfung_vorbedingt_Prüfung','P-ID','\''.$element->{'P-ID'}.'\'');
        while ($row = $databaseEntry->fetch_assoc()) {
          $fulfill = false;
          for ($i = 0;!$fulfill && $i < ($semesterZ-1); $i++) {
            $semester = $plan->{'Semester'}[$i];
            foreach ($semester->{'Prüfungen'} as $prüfung) {
              if (!$fulfill && isset($prüfung->{'P-ID'}) && $prüfung->{'P-ID'} == $row['P-IDB']) {$fulfill=true;}
            }
            unset($prüfung);
          }
          if (!$fulfill) {return false;}
        }
        return true;
      }

      /**
       * Überprüft, ob das Fach zur ID eine Vorbedingung ist.
       */
      private static function isPrecondition($plan,$id,$semester) {
        $found = false;
        for ($i = $semester+1;!$found && $i < count($plan->{'Semester'}); $i++) {
          $semester = $plan->{'Semester'}[$i];
          foreach ($semester->{'Prüfungen'} as $prüfung) {
            if (!$found && $prüfung->{'Typ'} != 'S_WAHL') {
              $databaseEntry = Database::selectExtended('Studienverlaufsplaner','*','Prüfung_vorbedingt_Prüfung','P-ID','\''.$prüfung->{'P-ID'}.'\'','P-IDB','\''.$id.'\'');
              $databaseEntry = $databaseEntry->fetch_assoc();
              if (!is_null($databaseEntry)) {$found = true;}
            }
          }
          unset($prüfung);
        }
        return $found;
      }

      /**
       * Schreibt ein Wahlfach in den übergebenden Wahlblock.
       */
      private static function insertChooseJson($choose, $element, $plan){
        $insert = false;
        for ($i = 0;!$insert && $i < count($plan->{'Semester'}); $i++) {
          $fächer = $plan->{'Semester'}[$i]->{'Prüfungen'};
          for ($j = 0;!$insert && $j < count($fächer); $j++) {
            if ((isset($fächer[$j]->{'W-ID'})&& $fächer[$j]->{'W-ID'} == $choose->{'W-ID'})) {
                array_push($fächer[$j]->{'P-IDs'},$element);
                $insert = true;
            }
          }
        }
      }

      /**
       * Löscht das übergebenden Wahlfach aus den übergebenden Wahlblock.
       */
      private static function deleteChooseJson($choose, $element, $plan){
        $deleted = false;
        for ($i = 0;!$deleted && $i < count($plan->{'Semester'}); $i++) {
          $fächer = $plan->{'Semester'}[$i]->{'Prüfungen'};
          for ($j = 0;!$deleted && $j < count($fächer); $j++) {
            if ((isset($fächer[$j]->{'W-ID'})&&$fächer[$j]->{'W-ID'} == $choose->{'W-ID'})) {
                for ($x = 0; $x < count($fächer[$j]->{'P-IDs'}); $x++){
                  if($fächer[$j]->{'P-IDs'}[$x]->{'P-ID'} == $element->{'P-ID'}){
                    unset($fächer[$j]->{'P-IDs'}[$x]);
                    $fächer[$j]->{'P-IDs'} = array_values($fächer[$j]->{'P-IDs'});
                    $deleted = true;
                  }
                }
            }
          }
        }
      }

      /**
       * Gibt das Element der übergebenden ID zurück oder liefert das
       * Semester des Wahlblocks.
       */
      private static function getElementElective($bID,$plan,$getSemester){
        $found = false;
        $element = null;
        foreach ($plan->{'Semester'} as $key=>$semester) {
          for ($i = 0;!$found&&$i < count($semester->{'Prüfungen'}); $i++) {
            if ((isset($semester->{'Prüfungen'}[$i]->{'W-ID'}))) {
              foreach ($semester->{'Prüfungen'}[$i]->{'P-IDs'} as $fach){
                if($fach->{'P-ID'} == $bID){
                  if($getSemester){
                    $element = $key+1;
                    /*if($i % 2 == 0 ){
                      $element = ($i + 1);
                    } else {
                      $element = ($i + 3);
                    }*/
                  } else {
                    $element = $fach;
                  }

                  $found = true;
                }
              }
            }
          }
        }
        unset($semester);
        return $element;
      }

      /**
       * Wenn ein Fach verschoben wird, welches zum ersten Semester zählt wird
       * geprüft, ob es über dem Pflichtsemester geschoben wird. Ist dies nicht der
       * Fall gibt die Funktion true zurück.
       */
      private static function moveRestriktion($userID,$element,$semester){
        $sID = UserToolsEditor::getMajor($userID);
        $rawdata = Database::select('Studienverlaufsplaner','*','Studiengang','S-ID','\''.$sID.'\'');
        if (is_null($rawdata)) {return UserToolsEditor::ERROR_NOTFOUND;}
        $row = $rawdata->fetch_assoc();
        $plan = json_decode($row['Curriculum']);

        $found = false;
        $result = true;

        $fächer = $plan->{'Semester'}[0]->{'Prüfungen'};
        for ($j = 0;!$found && $j < count($fächer); $j++) {
          if (isset($fächer[$j]->{'P-ID'}) && $fächer[$j]->{'P-ID'} == $element->{'P-ID'}){
            $found = true;
            //exit ($semester);
            if($semester > UserToolsEditor::getRestriktion($userID)){$result = false;}
          }
        }


        return $result;
      }

      /**
       * Liefert zurück, bis zu welchem Semester die Fächer aus Semester 1 bestanden
       * sein müssen.
       */
      private static function getRestriktion($userID){
        $sID = UserToolsEditor::getMajor($userID);
        $rawdata = Database::select('Studienverlaufsplaner','*','Studiengang','S-ID','\''.$sID.'\'');
        if (is_null($rawdata)) {return UserToolsEditor::ERROR_NOTFOUND;}
        $row = $rawdata->fetch_assoc();
        return $row['Obergrenze'];
      }
}



?>
