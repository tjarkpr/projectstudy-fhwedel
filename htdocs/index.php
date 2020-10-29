<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="author" content="Tjark, Niclas, Fynn, Kjell">
    <link rel='shortcut icon' type='image/x-icon' href='./favicon.ico'/>
    <link rel="stylesheet" href="./css/master.css">
    <script defer src="./js/master.js" charset="utf-8"></script>
    <script defer src="./js/libraries/jsencrypt.js" charset="utf-8"></script>
    <title>Studienverlaufsplaner FH-WEDEL</title>
  </head>
  <body>
    <!--ASSETS-->
    <link rel="stylesheet" href="./css/masterLogin.css">
    <!--CODE-->
    <div class="mainContainer">
      <div class="centerContainerElement" id="overview">
        <div class="centerContainerElementImage"></div>
        <p>Dein Studienverlaufsplan ist überfüllt, unübersichtlich oder durch
          deine Abweichungen davon unbrauchbar geworden? Kein Problem! Denn ab jetzt
          kannst du deinen Studienverlaufsplan übersichtlich und individuell anpassen und verfolgen,
          sodass deinem erfolgreichen Abschluss nichts mehr im Weg steht.</p>
        <div class="centerContainerElementButton" onclick="openLogin()"><span>Los Geht's</span></div>
      </div>
      <div class="centerContainerElement hide" id="form">
        <div class="centerContainerElementImage"></div>
        Benutzername:<input type="text" name="username" id="username"><br>
        Passwort:<input type="password" name="password" id="password"><br>
        <div class="centerContainerElementButton" onclick="login()"><span>Anmelden</span></div>
      </div>
      <div class="centerContainerElement hide" id="initial">
        <div class="centerContainerElementImage"></div>
        <p>Zum Anfang benötigen wir deinen Studiengang, damit du direkt loslegen kannst.</p>
        Studiengang: <select name="program" id="program"></select><br><br>
        <div class="centerContainerElementButton" onclick="sendInitial()"><span>Start</span></div>
      </div>
    </div>
    <header>
      <div class="headerPart">
        <div class="headerLogo">
          <img src="./svg/logo.svg" alt="icon"><span>Studienverlaufsplaner</span>
        </div>
        <ul class="headerButtonList">
          <li onclick="mainDataPrivacy('policy')"><img src="./svg/secure.svg" alt="icon"><span>Datenschutz</span></li>
          <li onclick="mainDataPrivacy('copyright')"><img src="./svg/info.svg" alt="icon"><span>Impressum</span></li>
        </ul>
      </div>
    </header>
    <div class="popUp hide" id="popUpPolicy"></div>
    <div class="popUp hide" id="popUpCopyright"></div>
  </body>
</html>
