<!--ASSETS-->
<link rel="stylesheet" href="./../css/masterAdmin.css">
<!--CODE-->
<main>
  <div class="mainPart" id="mainPage">
    <div class="statusPart">
      <div class="partMobile">
        <div class="statusContainer">
          <div class="statusContainerImage"></div>
          <h3>Datenbankstatus</h3>
          <ul class="statusContainerData">
          </ul>
        </div>
      </div>
    </div>
    <div class="choosePart">
      <div class="partMobile">
        <div class="choosePartOption" id="lectureOption" onclick="openOverview('lecture');toggleMainPage();">
          <div class="choosePartOptionImage"></div>
          <div class="choosePartOptionData">
            <h3>Vorlesungsübersicht</h3>
            <span>Übersicht über alle Vorlesungen die in der Datenbank vorliegen.</span>
          </div>
        </div>
        <div class="choosePartOption" id="programOption" onclick="openOverview('program');toggleMainPage();">
          <div class="choosePartOptionImage"></div>
          <div class="choosePartOptionData">
            <h3>Studienordnungsübersicht</h3>
            <span>Übersicht über alle Studienordnungen die in der Datenbank vorliegen.</span>
          </div>
        </div>
      </div>
    </div>
    <div class="infoPart">
      <strong>INFORMATION: </strong><span>Alle Änderungen von Vorlesungen, Studienordnungen und Benutzern, können in den Verlaufsplänen zu Veränderungen führen. Sollten also Änderungen vorgenommen werden, müssen Sie mit automatischen Anpassungen rechnen.</span>
    </div>
    <div class="choosePart">
      <div class="partMobile">
        <div class="choosePartOption" id="userOption" onclick="openOverview('user');toggleMainPage();">
          <div class="choosePartOptionImage"></div>
          <div class="choosePartOptionData">
            <h3>Benutzerübersicht</h3>
            <span>Übersicht über alle Benutzer die in der Datenbank vorliegen.</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="mainPart" id="overviewPage">
    <div class="partMobile">
      <div class="overflowContainer">
        <input type="text" placeholder="Suche..." id="searchbarOverview" onkeyup="searchTable('overviewData')">
        <table class="overviewData"></table>
      </div>
    </div>
  </div>
  <div class="mainPart" id="detailPage">
    <div class="partMobile">
      <div class="overflowContainer">
        <form class="detailData"></form>
      </div>
    </div>
  </div>
</main>
<header>
  <div class="headerPart headerUpper">
    <div class="headerButton backButton">
      <img src="./../svg/back.svg" alt="icon"><span>Zurück</span>
    </div>
    <div class="headerLogo">
      <img src="./../svg/logo.svg" alt="icon"><span>Admin</span>
    </div>
    <div onclick="logout()" class="headerButton" id="logoutButton">
      <img src="./../svg/logout.svg" alt="icon"><span>Abmelden</span>
    </div>
    <ul class="headerButtonList">
      <li onclick="mainDataPrivacy('policy')"><img src="./../svg/secure.svg" alt="icon"><span>Datenschutz</span></li>
      <li onclick="mainDataPrivacy('copyright')"><img src="./../svg/info.svg" alt="icon"><span>Impressum</span></li>
    </ul>
  </div>
  <div class="headerPart headerLower">
    <ul class="headerMenu" id="overviewMenu">
      <li onclick="addFunc()"><img src="./../svg/add.svg" alt="icon"><span>Hinzufügen</span></li>
      <li onclick="editFunc()"><img src="./../svg/change.svg" alt="icon"><span>Bearbeiten</span></li>
      <li onclick="deleteSelected()"><img src="./../svg/delete.svg" alt="icon"><span>Löschen</span></li>
      <li onclick="openEdit()" id="editButton"><img src="./../svg/edit.svg" alt="icon"><span>Editieren</span></li>
    </ul>
    <ul class="headerMenu" id="detailMenu">
      <li onclick="saveDetail()"><img src="./../svg/save.svg" alt="icon"><span>Speichern</span></li>
    </ul>
  </div>
</header>
<div class="popUp hide" id="popUpPolicy"></div>
<div class="popUp hide" id="popUpCopyright"></div>
