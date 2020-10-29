<link rel="stylesheet" href="./../css/masterUser.css">
<meta name="userID" content=<?php
echo '"'.$_POST['userID'].'"';?>>
<!-- Popups -->
<div id="myModal" class="modal">
  <div class="subPart" id="addPage"></div>
  <div class="subPart" id="movePage"></div>
  <div class="subPart" id="removePage"></div>
  <div class="subPart" id="electivePage"></div>
  <div class="subPart" id="resetPage"></div>
</div>
<main>
  <!-- GRID-System -->
  <div class="container">
    <div class="row">
      <div class="col-6">
        <!-- Tabelle für die freiwilligen Fächer -->
        <div id="boxLeft">
          <div id="divTableModul">
            <p>Auswahl freiwilliger Fächer</p>
            <div id="freeSubjects"></div>
          </div>
        </div>
        <!-- Tabelle für ein Semester/alle Semester -->
        <div id="boxMid">
          <div id="divSemester">
            <p>Übersicht Semester</p>
            <div class="tablePartsMenu"><img onclick="subT()" class="tablePartsMenuItem" id="left" src="./svg/left.svg" alt="icon"><h3>Semester 1</h3><img onclick="addT()" class="tablePartsMenuItem" id="right" src="./svg/right.svg" alt="icon"></div>
            <div id="semesterTableId" class="semesterTable"></div>
            <div class="tableParts"></div>
          </div>
        </div>
        <!-- Tabelle für die Informationen für ein Fach -->
        <div id="boxRight">
          <div id="divInformation">
            <p>Informationen</p>
            <div id="information"></div>
          </div>
        </div>
      </div>
    </div>
</div>
</main>
<header>
  <div class="headerPart headerUpper">
    <div class="headerLogo">
      <img src="./../svg/logo.svg" alt="icon"><span>Benutzer</span>
    </div>
    <div onclick="showTableParts()" class="headerButton" id="viewButton">
      <img src="./svg/ansichtUser.svg" alt="icon"><span>Ansicht</span>
    </div>
    <div onclick="logout()" class="headerButton" id="logoutButton">
      <img src="./svg/logout.svg" alt="icon"><span>Abmelden</span>
    </div>
    <ul class="headerButtonList">
      <li onclick="mainDataPrivacy('policy')"><img src="./../svg/secure.svg" alt="icon"><span>Datenschutz</span></li>
      <li onclick="mainDataPrivacy('copyright')"><img src="./../svg/info.svg" alt="icon"><span>Impressum</span></li>
    </ul>
  </div>
  <div class="headerPart headerLower">
    <div onclick="getFreeSubjects(false)" class="headerButton" id="leftButton">
      <img src="./svg/threads.svg" alt="icon"><span>Prüfungen</span>
    </div>
    <div onclick="getInfo(false)" class="headerButton" id="leftButton">
      <img src="./svg/infoUser.svg" alt="icon"><span>Info</span>
    </div>
    <ul class="headerMenu" id="toolMenu">
      <li onclick="toggleAdd()"><img src="./svg/add.svg" alt="icon"><span>Hinzufügen</span></li>
      <li onclick="toggleRemove()"><img src="./svg/delete.svg" alt="icon"><span>Löschen</span></li>
      <li onclick="toggleMove()"><img src="./svg/move.svg" alt="icon"><span>Verschieben</span></li>
      <li onclick="passedSubject()"><img src="./svg/selected.svg" alt="icon"><span>Bestanden</span></li>
    </ul>
    <div onclick="toggleReset()" class="headerButton" id="rightButton">
      <img src="./svg/slow.svg" alt="icon"><span>Reset</span>
    </div>
  </div>
</header>
<div class="popUp hide" id="popUpPolicy"></div>
<div class="popUp hide" id="popUpCopyright"></div>
