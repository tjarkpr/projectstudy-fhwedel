<!--ASSETS-->
<meta name="sid" content=<?php echo "\"".$_POST['sid']."\""; ?>/>
<link rel="stylesheet" href="/css/masterAdminEditor.css">
<!--CODE-->
<main>
  <div class="mainPart" id="overviewPage">
    <div class="table"></div>
    <div class="tablePartsMenu"><img onclick="subT()" class="tablePartsMenuItem" id="left" src="./svg/left.svg" alt="icon"><h3>Semester 1</h3><img onclick="addT()" class="tablePartsMenuItem" id="right" src="./svg/right.svg" alt="icon"></div>
    <div class="tableParts"></div>
  </div>
  <div class="subPart" id="addPage">
  </div>
  <div class="subPart" id="movePage">
  </div>
</main>
<header>
  <div class="headerPart headerUpper">
    <div onclick="redirectAdmin(); currentT=0;" class="headerButton backButton">
      <img src="./svg/back.svg" alt="icon"><span>Zurück</span>
    </div>
    <div class="headerLogo">
      <img src="./svg/logo.svg" alt="icon"><span>Admin</span>
    </div>
    <div onclick="logout()" class="headerButton" id="logoutButton">
      <img src="./svg/logout.svg" alt="icon"><span>Abmelden</span>
    </div>
    <ul class="headerButtonList">
      <li onclick="mainDataPrivacy('policy')"><img src="./svg/secure.svg" alt="icon"><span>Datenschutz</span></li>
      <li onclick="mainDataPrivacy('copyright')"><img src="./svg/info.svg" alt="icon"><span>Impressum</span></li>
    </ul>
  </div>
  <div class="headerPart headerLower">
    <ul class="headerMenu" id="overviewMenu">
      <li id="addButton" onclick="toggleAdd();closeMove();"><img src="./svg/add.svg" alt="icon"><span>Hinzufügen</span></li>
      <li id="moveButton" onclick="toggleMove();closeAdd();"><img src="./svg/move.svg" alt="icon"><span>Verschieben</span></li>
      <li onclick="deleteToPlan()"><img src="./svg/delete.svg" alt="icon"><span>Löschen</span></li>
    </ul>
  </div>
</header>
<div class="popUp hide" id="popUpPolicy"></div>
<div class="popUp hide" id="popUpCopyright"></div>
