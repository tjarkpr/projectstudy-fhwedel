/*PRIVACY*/
var policy = "";
var copyright = "";

function mainDataPrivacy(data) {
  switch (data) {
    case 'policy':
    if (!document.getElementById('popUpCopyright').classList.contains('hide')) {
      document.getElementById('popUpCopyright').classList.add('hide');
    }
    var element = document.getElementById('popUpPolicy');
    element.classList.toggle('hide');
      if (policy == "") {
        var request = new XMLHttpRequest();
        request.open("GET", "./php/dataPolicy.php", true);
        request.onreadystatechange = function() {
          if (request.readyState == 4 && request.status == 200) {
            policy = request.responseText;
            element.innerHTML = policy;
          }
        }
        request.send();
      } else {element.innerHTML = policy;}
      break;
    case 'copyright':
    if (!document.getElementById('popUpPolicy').classList.contains('hide')) {
      document.getElementById('popUpPolicy').classList.add('hide');
    }
    var element = document.getElementById('popUpCopyright');
    element.classList.toggle('hide');
      if (copyright == "") {
        var request = new XMLHttpRequest();
        request.open("GET", "./php/dataCopyright.php", true);
        request.onreadystatechange = function() {
          if (request.readyState == 4 && request.status == 200) {
            copyright = request.responseText;
            element.innerHTML = copyright;
          }
        }
        request.send();
      } else {element.innerHTML = copyright;}
      break;
  }
}
/*LOGIN*/
var pbKey;
function openLogin() {
  var overview = document.getElementById('overview');
  var form = document.getElementById('form');
  overview.classList.add('hide');
  form.classList.remove('hide');
}
function openInitial() {
  var form = document.getElementById('form');
  var initial = document.getElementById('initial');
  form.classList.add('hide');
  initial.classList.remove('hide');
}
document.getElementById("username").addEventListener("keyup", function(event) {
  if (event.keyCode === 13) {document.getElementById("password").focus();}
});
document.getElementById("password").addEventListener("keyup", function(event) {
  if (event.keyCode === 13) {login();}
});
function login() {
  if (pbKey == null) {
    alert('Error: Verschlüsselte Übertragung nicht möglich!');
  } else {
    var request = new XMLHttpRequest();
    request.open("POST", "./php/login.php", true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.onreadystatechange = function() {
      if (request.readyState == 4 && request.status == 200) {
        if (request.responseText.startsWith('Error:')) {
          alert(request.responseText);
        } else {
          if (request.responseText.startsWith('user')) {
          redirectUser(request.responseText.substr(4));
        } else if (request.responseText.startsWith('admin')) {
          redirectAdmin();
        } else if (request.responseText.startsWith('init')) {
          initialzeInitial();
          openInitial();
        }
        }
      }
    };
    request.send('mode=login&username='+getEncyptedId('username')+'&password='+getEncyptedId('password'));
  }
}
function initialzeInitial() {
  var request = new XMLHttpRequest();
  request.open("POST", "./php/login.php", true);
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  request.onreadystatechange = function() {
    if (request.readyState == 4 && request.status == 200) {
      document.getElementById('program').innerHTML = request.responseText;
    }
  };
  request.send('mode=getProgram');
}
function sendInitial() {
  var initId = document.getElementById('program').value.match(/\<(.*?)\>/g)[0];
  if (initId != null) {
    initId = initId.slice(0, -1).slice(1);
    var request = new XMLHttpRequest();
    request.open("POST", "./php/login.php", true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.onreadystatechange = function() {
      if (request.readyState == 4 && request.status == 200) {
        login();
      }
    };
    request.send('mode=sendProgram&username='+getEncyptedId('username')+'&id='+initId);
  }
}
function redirectAdmin() {
  var request = new XMLHttpRequest();
  request.open("GET", "./page/admin.php", true);
  request.onreadystatechange = function() {
    if (request.readyState == 4 && request.status == 200) {
      document.getElementsByTagName('body')[0].innerHTML = request.responseText;
      initAdmin();
    }
  };
  request.send();
}
function getEncyptedId(mode) {
  var raw = document.getElementById(mode).value;
  if (raw == "") {return raw;}
  var encrypt = new JSEncrypt();
  encrypt.setPublicKey(pbKey);
  return encodeURIComponent(encrypt.encrypt(raw));
}
function initPublicKey() {
  var request = new XMLHttpRequest();
  request.open("POST", "./php/login.php", true);
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  request.onreadystatechange = function() {
    if (request.readyState == 4 && request.status == 200) {
      if (request.responseText.startsWith('Error:')) {
        alert(request.responseText);
      } else {
        pbKey = request.responseText;
      }
    }
  }
  request.send('mode=pbkey');
}
initPublicKey();
/*ADMIN*/
var currentPath = '';
var currentDetail = '';
function initAdmin() {
  initializeStatusContainer();
  toggleMainPage();
  if (currentPath != '') {
    toggleMainPage();
    openOverview(currentPath);
  }
}
function initializeStatusContainer() {
  var request = new XMLHttpRequest();
  request.open("POST", "./php/adminOverview.php", true);
  request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  request.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      if (request.responseText.startsWith('Error:')) {
        alert(request.responseText);
      } else {
       document.getElementsByClassName("statusContainerData")[0].innerHTML = this.responseText;
     }
    }
  };
  request.send("mode=get&submode=status");
}
function initializeOverviewPage(name) {
  var request = new XMLHttpRequest();
  request.open("POST", "./php/adminOverview.php", true);
  request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  request.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      if (request.responseText.startsWith('Error:')) {
        alert(request.responseText);
      } else {
       document.getElementsByClassName("overviewData")[0].innerHTML = this.responseText;
     }
    }
  };
  request.send("mode=get&submode=overview&subsubmode="+name);
}
function initializeDetailPage(name, id) {
  var request = new XMLHttpRequest();
  request.open("POST", "./php/adminOverview.php", true);
  request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  request.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      if (request.responseText.startsWith('Error:')) {
        alert(request.responseText);
      } else {
       document.getElementsByClassName("detailData")[0].innerHTML = this.responseText;
       var all = document.getElementsByClassName("detailData")[0].getElementsByTagName("input");
       for (var i = 0; i < all.length; i++) {
         all[i].addEventListener("keyup", function(event) {
           if (event.keyCode === 13) {saveDetail();}
         });
       }
     }
    }
  };
  request.send("mode=get&submode=detail&subsubmode="+name+"&id="+id);
}
function deleteSelected() {
  var id = getSelectedId();
  if (id != null && id != 'none') {
    if (confirm("Wollen Sie wirklich den Eintrag mit der ID " + id + " löschen?")) {
      var request = new XMLHttpRequest();
      request.open("POST", "./php/adminOverview.php", true);
      request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      request.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          if (request.responseText.startsWith('Error:')) {
            alert(request.responseText);
          } else {
           initializeOverviewPage(currentPath);
         }
        }
      };
      request.send("mode=delete&submode="+currentPath+"&id="+id);
    }
  }
}
function toggleLowerMenu(name) {
  document.getElementsByTagName("main")[0].classList.toggle("toggleMain");
  document.getElementsByTagName("header")[0].classList.toggle("toggleHeader");
  document.getElementsByClassName("headerUpper")[0].classList.toggle("toggleHeaderUpper");
  document.getElementsByClassName("headerLower")[0].classList.toggle("toggleHeaderLower");
  switch (name) {
    case "overviewlecture":
    document.getElementById("overviewMenu").classList.toggle("toggleBlock");
    break;
    case "overviewuser":
    document.getElementById("overviewMenu").classList.toggle("toggleBlock");
    break;
    case "overviewprogram":
    document.getElementById("overviewMenu").classList.toggle("toggleBlock");
    document.getElementById("editButton").classList.toggle("toggleInline");
    break;
    case "detail":
    document.getElementById("detailMenu").classList.toggle("toggleBlock");
    break;
  }
}
function openOverview(name) {
  document.getElementById("overviewPage").classList.toggle("toggleBlock");
  toggleLowerMenu("overview"+name);
  initializeOverviewPage(name);
  toggleBackButton();
  currentPath = name;
  document.getElementsByClassName("backButton")[0].onclick = function() {
    closeOverview();
    initializeStatusContainer();
    toggleMainPage();
    currentPath = '';
  };
}
function searchTable(tablename) {
  var txtValue;
  var filter = document.getElementById("searchbarOverview").value.toUpperCase();
  var table = document.getElementsByClassName(tablename)[0];
  var names = table.getElementsByClassName("names");
  for (var i = 0; i < names.length; i++) {
    txtValue = names[i].textContent || names[i].innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
        names[i].parentNode.style.display = "";
      } else {
        names[i].parentNode.style.display = "none";
      }
  }
}
function closeOverview() {
  document.getElementById("overviewPage").classList.toggle("toggleBlock");
  toggleLowerMenu("overview"+currentPath);
  toggleBackButton();
}
function addFunc() {
  openDetail('none');
  closeOverview();
}
function editFunc() {
  var id = getSelectedId();
  if (id != null && id != 'none') {
    openDetail(id);
    closeOverview();
  }
}
function openDetail(id) {
  document.getElementById("detailPage").classList.toggle("toggleBlock");
  toggleLowerMenu("detail");
  initializeDetailPage(currentPath, id);
  toggleBackButton();
  currentDetail = id;
  document.getElementsByClassName("backButton")[0].onclick = function() {
    closeDetail();
    openOverview(currentPath);
  };
}
function getSelectedId() {
  var elements = document.getElementsByClassName("overviewData")[0].getElementsByTagName("tr");
  var selectedCounter = 0;
  var elementImg, elementID, selectedID;
  for (var i = 0; i < elements.length; i++) {
    elementImg = elements[i].getElementsByTagName("img")[0];
    if (elementImg.classList.contains("toggleBlock")) {
      elementID = elements[i].getElementsByTagName("th")[0];
      selectedID = elementID.innerHTML;
      selectedCounter++;
    }
  }
  if (selectedCounter == 0) {
    return "none";
  } else if (selectedCounter >= 2) {
    return null
  } else {
    return selectedID;
  }
}
function closeDetail() {
  document.getElementById("detailPage").classList.toggle("toggleBlock");
  toggleLowerMenu("detail");
  toggleBackButton();
  currentDetail = '';
}
function toggleMainPage() {
  document.getElementById("mainPage").classList.toggle("toggleBlock");
}
function toggleBackButton() {
  document.getElementsByClassName("backButton")[0].classList.toggle("toggleBlock");
}
function toggleFormAdd() {
  document.getElementsByClassName("detailViewFormular_lectureAll")[0].classList.toggle("toggleBlock");
}
function select(element) {
  element.getElementsByTagName("img")[0].classList.toggle("toggleBlock");
}
function addToDetail(element) {
  var thElement = element.getElementsByTagName("th")[0].innerHTML;
  if (!detailExist(thElement)) {
    var tdElement = element.getElementsByTagName("td")[0].innerHTML;
    var list = document.getElementsByClassName("detailViewFormular_lecturePre")[0].getElementsByTagName("table")[0];
    list.innerHTML += "<tr onclick=\"select(this)\"><th>"+ thElement +"</th><td>"+ tdElement +"</td><td class=\"selectTd\"><img class=\"selectImage\" src=\"./svg/selected.svg\" alt=\"icon\"></td></tr>"
  } else {
    alert("Der Eintrag existiert bereits!")
  }
  toggleFormAdd();
}
function detailExist(th) {
  var elements = document.getElementsByClassName("detailViewFormular_lecturePre")[0].getElementsByTagName("tr");
  var elementTh;
  var found = false;
  for (var i = 0;!found && i < elements.length; i++) {
    elementTh = elements[i].getElementsByTagName("th")[0];
    if (elementTh.innerHTML == th) {
      found = true;
    }
  }
  return found;
}
function deleteDetail() {
  var elements = document.getElementsByClassName("detailViewFormular_lecturePre")[0].getElementsByTagName("tr");
  var elementImg;
  for (var i = 0; i < elements.length; i++) {
    elementImg = elements[i].getElementsByTagName("img")[0];
    if (elementImg.classList.contains("toggleBlock")) {
      elements[i].parentNode.removeChild(elements[i]);
      i--;
    }
  }
}
function saveDetail() {
  var form = document.getElementsByClassName("detailData")[0];
  var json = '{';
  switch (currentPath) {
    case 'lecture':
      if (form.elements['P-ID'].value==''||form.elements['Name'].value==''||
          form.elements['Angebot'].value=='') {
        alert('Error: Pflichtfeld leer gelassen.');
      } else {
        if (currentDetail != '' && currentDetail != 'none') {json += '"P-ID":"' + currentDetail + '",';}
        else {json += '"P-ID":"' + form.elements['P-ID'].value + '",';}
      json += '"P-IDN":"' + form.elements['P-ID'].value + '",';
      json += '"Name":"' + form.elements['Name'].value + '",';
      json += '"Angebot":"' + form.elements['Angebot'].value + '",';
      json += '"Vorbedingungen":[' + getDetailArray();
      json += "]}";
      saveToDB(json);
    }
      break;
    case 'program':
    if (form.elements['S-ID'].value==''||form.elements['Fachrichtung'].value==''||
        form.elements['Startsemester'].value==''||form.elements['Studienordnung'].value==''||
        form.elements['Obergrenze'].value=='') {
      alert('Error: Pflichtfeld leer gelassen.');
    } else {
      if (currentDetail != '' && currentDetail != 'none') {json += '"S-ID":"' + currentDetail + '",';}
      else {json += '"S-ID":"' + form.elements['S-ID'].value + '",';}
      json += '"S-IDN":"' + form.elements['S-ID'].value + '",';
      json += '"Fachrichtung":"' + form.elements['Fachrichtung'].value + '",';
      json += '"Startsemester":"' + form.elements['Startsemester'].value + '",';
      json += '"Studienordnung":"' + form.elements['Studienordnung'].value + '",';
      json += '"Obergrenze":"' + form.elements['Obergrenze'].value + '"';
      json += "}";
      saveToDB(json);
    }
      break;
      case 'user':
      if (form.elements['B-ID'].value==''||form.elements['Benutzername'].value=='') {
        alert('Error: Pflichtfeld leer gelassen.');
      } else {
        if (currentDetail != '' && currentDetail != 'none') {json += '"B-ID":"' + currentDetail + '",';}
        else {json += '"B-ID":"' + form.elements['B-ID'].value + '",';}
        json += '"B-IDN":"' + form.elements['B-ID'].value + '",';
        json += '"Benutzername":"' + form.elements['Benutzername'].value + '",';
        json += '"Passwort":"' + getEncyptedId('detailPw') + '"';
        json += "}";
        saveToDB(json);
      }
        break;
    default:
      alert("Es ist ein Fehler aufgetreten, versuchen Sie es zu einem anderen Zeitpunkt erneut!");
  }
  closeDetail();
  openOverview(currentPath);
}
function getDetailArray() {
  var elements = document.getElementsByClassName("detailViewFormular_lecturePre")[0].getElementsByTagName("tr");
  var elementTh;
  var result = '';
  for (var i = 0; i < elements.length; i++) {
    elementTh = elements[i].getElementsByTagName("th")[0].innerHTML;
    result += "\"" + elementTh + "\"";
    if (i != (elements.length - 1)) {
      result += ',';
    }
  }
  return result;
}
function saveToDB(json) {
  var request = new XMLHttpRequest();
  request.open("POST", "./php/adminOverview.php", true);
  request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  request.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      if (request.responseText.startsWith('Error:')) {
        alert(request.responseText);
      } else {
        initializeOverviewPage(currentPath);
      }
    }
  };
  request.send("mode=save&submode="+currentPath+"&json="+json+"&id="+currentDetail);
}
function logout() {
  var request = new XMLHttpRequest();
  request.open("POST", "./php/login.php", true);
  request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  request.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
       redirectLogin();
    }
  };
  request.send("mode=logout");
}
function redirectLogin() {
  window.location.reload(true);
}
function openEdit() {
  var id = getSelectedId();
  if (id != null && id != "none") {
    var request = new XMLHttpRequest();
    request.open("POST", "./page/adminEditor.php", true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.onreadystatechange = function() {
      if (request.readyState == 4 && request.status == 200) {
        document.getElementsByTagName('body')[0].innerHTML = request.responseText;
        doCheck(id);
        initAdminEditor();
      }
    };
    request.send("sid="+id);
  }
}
/*ADMINEDITOR*/
var currentT = 0;
var sid;
function initAdminEditor() {
  sid = getMeta('sid');
  initializeTable(sid);
  initializeTableParts(sid);
  initializeAddPage(sid);
  initializeMovePage(sid);
}
function getMeta(metaName) {
  const metas = document.getElementsByTagName('meta');
  for (let i = 0; i < metas.length; i++) {
    if (metas[i].getAttribute('name') === metaName) {
      return metas[i].getAttribute('content');
    }
  }
  return '';
}
function doCheck(sid) {
  var request = new XMLHttpRequest();
  request.open("POST", "./php/adminEditor.php", true);
  request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  request.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      if (request.responseText.startsWith('Error:') || request.responseText.startsWith('Info:')) {
        alert(request.responseText);
      }
    }
  };
  request.send("mode=check&sid=" + sid);
}
function initializeAddPage() {
  var request = new XMLHttpRequest();
  request.open("POST", "./php/adminEditor.php", true);
  request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  request.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      if (request.responseText.startsWith('Error:')) {
        alert(request.responseText);
      } else {
       document.getElementById("addPage").innerHTML = this.responseText;
     }
    }
  };
  request.send("mode=get&submode=add&sid=" + sid);
}
function initializeMovePage() {
  var request = new XMLHttpRequest();
  request.open("POST", "./php/adminEditor.php", true);
  request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  request.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      if (request.responseText.startsWith('Error:')) {
        alert(request.responseText);
      } else {
       document.getElementById("movePage").innerHTML = this.responseText;
     }
    }
  };
  request.send("mode=get&submode=move&sid=" + sid);
}
function initializeTable() {
  var request = new XMLHttpRequest();
  request.open("POST", "./php/adminEditor.php", true);
  request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  request.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      if (this.responseText.startsWith('Error:')) {
        alert(request.responseText);
      } else {
       document.getElementsByClassName("table")[0].innerHTML = this.responseText;
     }
    }
  };
  request.send("mode=get&submode=all&sid=" + sid);
}
function initializeTableParts() {
  var request = new XMLHttpRequest();
  request.open("POST", "./php/adminEditor.php", true);
  request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  request.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      if (this.responseText.startsWith('Error:')) {
        alert(request.responseText);
      } else {
       document.getElementsByClassName("tableParts")[0].innerHTML = this.responseText;
       updateTableParts();
     }
    }
  };
  request.send("mode=get&submode=part&sid=" + sid);
}
function updateTableParts() {
  var parts = document.getElementsByClassName("tablePart");
  resetAllTableParts(parts);
  for (var i = 0; i < parts.length; i++) {
    if (parts[i].id == "t"+currentT) {
      parts[i].classList.add('toggleBlock');
    }
  }
  var menu = document.getElementsByClassName("tablePartsMenu")[0];
  resetMenu(menu);
  if (currentT == 0 && currentT != (parts.length - 1)) {
    document.getElementById('right').classList.add('toggleBlock');
    document.getElementById('left').classList.remove('toggleBlock');
  } else if (currentT != 0 && currentT == (parts.length - 1)) {
    document.getElementById('right').classList.remove('toggleBlock');
    document.getElementById('left').classList.add('toggleBlock');
  } else if (currentT == 0 && currentT == (parts.length - 1)) {
    document.getElementById('right').classList.remove('toggleBlock');
    document.getElementById('left').classList.remove('toggleBlock');
  } else {
    document.getElementById('right').classList.add('toggleBlock');
    document.getElementById('left').classList.add('toggleBlock');
  }
  menu.getElementsByTagName('h3')[0].innerHTML = "Semester " + (currentT + 1);
}
function addT() {
  var parts = document.getElementsByClassName("tablePart");
  if (currentT < parts.length) {
    currentT++;
    updateTableParts();
  }
}
function subT() {
  if (currentT >= 0) {
    currentT--;
    updateTableParts();
  }
}
function resetMenu(menu) {
  var imgs = menu.getElementsByTagName('img');
  for (var i = 0; i < imgs.length; i++) {
    if (imgs[i].classList.contains('toggleBlock')) {
      imgs[i].classList.remove('toggleBlock');
    }
  }
}
function resetAllTableParts(parts) {
  for (var i = 0; i < parts.length; i++) {
    if (parts[i].classList.contains('toggleBlock')) {
      parts[i].classList.remove('toggleBlock');
    }
  }
}
function selectEditor(element) {
  var id = element.getElementsByTagName("strong")[0].innerHTML;
  var tds = document.getElementsByTagName("td");
  var strong, sub;
  for (var i = 0; i < tds.length; i++) {
    strong = tds[i].getElementsByTagName("strong")[0];
    if (typeof strong !== 'undefined') {
      sub = tds[i].getElementsByClassName('subtable')[0];
      if (tds[i].getElementsByTagName("strong")[0].innerHTML == id) {
        tds[i].classList.toggle("selectEditor");
        if (typeof sub !== 'undefined') {
          sub.classList.toggle('toggleBlock');
        }
      } else {
        tds[i].classList.remove("selectEditor");
        if (typeof sub !== 'undefined') {
          sub.classList.remove('toggleBlock');
        }
      }
    }
  }
}
function getSelectedPlanId() {
  var result = "";
  var tds = document.getElementsByTagName("td");
  for (var i = 0;result==""&&i < tds.length; i++) {
    if (tds[i].classList.contains("selectEditor")) {
      result = tds[i].getElementsByTagName("strong")[0].innerHTML;
    }
  }
  return result;
}
function deleteToPlan() {
  var id = getSelectedPlanId();
  if (id!="" && confirm("Wollen Sie wirklich den Eintrag mit der ID " + id + " löschen?")) {
    var request = new XMLHttpRequest();
    request.open("POST", "./php/adminEditor.php", true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        if (request.responseText.startsWith('Error:')) {
          alert(request.responseText);
        } else {
         initAdminEditor();
       }
      }
    };
    request.send("mode=delete&id=" + id+"&sid=" + sid);
  }
}
function toggleAdd() {
  document.getElementById("addPage").classList.toggle("toggleBlock");
  document.getElementById("movePage").classList.remove("toggleBlock");
  var modal = document.getElementById("myModal");
  if (modal != null) {
    modal.classList.toggle("toggleBlock");
  }
}
function openLecture() {
  document.getElementById("lecturePage").classList.add("toggleBlock");
  document.getElementById("lectureButton").classList.add("toggleSubMenuPart");
}
function closeLecture() {
  document.getElementById("lecturePage").classList.remove("toggleBlock");
  document.getElementById("lectureButton").classList.remove("toggleSubMenuPart");
}
function openChooser() {
  document.getElementById("chooserPage").classList.add("toggleBlock");
  document.getElementById("chooserButton").classList.add("toggleSubMenuPart");
}
function closeChooser() {
  document.getElementById("chooserPage").classList.remove("toggleBlock");
  document.getElementById("chooserButton").classList.remove("toggleSubMenuPart");
}
function selectLectureAdd(element,name) {
  var tempId,tempImg;
  var id = element.getElementsByTagName("th")[0].innerHTML;
  var all = document.getElementById(name).getElementsByTagName("tr");
  for (var i = 0; i < all.length; i++) {
    tempId = all[i].getElementsByTagName("th")[0].innerHTML;
    tempImg = all[i].getElementsByTagName("img")[0];
    if (tempId != id && tempImg.classList.contains('toggleBlock')) {
      tempImg.classList.remove('toggleBlock');
    }
  }
  select(element);
}
function getAddId() {
  var result = "";
  var trs = document.getElementById("lecturePage").getElementsByTagName("tr");
  var elementImg,elementID;
  for (var i = 0;result==""&&i < trs.length; i++) {
    elementImg = trs[i].getElementsByTagName("img")[0];
    if (elementImg.classList.contains("toggleBlock")) {
      elementID = trs[i].getElementsByTagName("th")[0];
      result = elementID.innerHTML;
    }
  }
  return result;
}
function getAddIds(wid) {
  var result = "";
  var trs = document.getElementById("chooserPage").getElementsByTagName("tr");
  var elementImg,elementID;
  for (var i = 0;i < trs.length; i++) {
    elementImg = trs[i].getElementsByTagName("img")[0];
    if (elementImg.classList.contains("toggleBlock")) {
      elementID = trs[i].getElementsByTagName("th")[0];
      result += "{\"Typ\":\"W_FACH\",";
      result += "\"P-ID\":\""+elementID.innerHTML+"\",";
      result += "\"Bestanden\":false,\"RW-ID\":\""+wid+"\"},";
    }
  }
  return result.slice(0, -1);
}
function getWahlId() {
  var result = "";
  var tempResult;
  for (var i = 0;result==""&&i < 1000; i++) {
    tempResult = "W"+i;
    if (!existInPlan(tempResult)) {
      result = tempResult;
    }
  }
  return result;
}
function existInPlan(id) {
  var result = false;
  var tds = document.getElementsByClassName("table")[0].getElementsByTagName("td");
  for (var i = 0;!result&&i < tds.length; i++) {
    if (typeof tds[i].getElementsByTagName("strong")[0] != 'undefined' && tds[i].getElementsByTagName("strong")[0].innerHTML == id) {
      result = true;
    }
  }
  return result;
}
function addToPlan() {
  var submode = (document.getElementById("lectureButton").classList.contains('toggleSubMenuPart'))?"lecture":"chooser";
  if (submode == "lecture") {
    var semester = document.getElementById("addSemesterL").value;
    var einheiten = document.getElementById("addEinheitenL").value;
    var id = getAddId();
    var element = "{\"Typ\":\"S_FACH\",\"P-ID\":\""+id+"\",\"Bestanden\":false";
    if (einheiten > 1) {
      element += ",\"Länge\":"+einheiten;
    }
  } else {
    var semester = document.getElementById("addSemesterW").value;
    var einheiten = document.getElementById("addEinheitenW").value;
    var id = getWahlId();
    var ids = getAddIds(id);
    var element = "{\"Typ\":\"S_WAHL\",\"W-ID\":\""+id+"\",\"P-IDs\":["+ids+"]";
    if (einheiten >= 1) {
      element += ",\"Länge\":"+einheiten;
    }
  }
  element += "}";
  if (id == ''||ids=='') {alert('Error: Pflichtfeld leer.');} else {
  var request = new XMLHttpRequest();
  request.open("POST", "./php/adminEditor.php", true);
  request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  request.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      if (request.responseText.startsWith('Error:')) {
        alert(request.responseText);
      } else {
        initAdminEditor();
     }
    }
  };
  request.send("mode=insert&submode="+submode+"&element="+element+"&semester="+semester+"&sid=" + sid);
}
}
function toggleMove() {
  document.getElementById("addPage").classList.remove("toggleBlock");
  document.getElementById("movePage").classList.toggle("toggleBlock");
  var modal = document.getElementById("myModal");
  if (modal != null) {
    modal.classList.toggle("toggleBlock");
  }
}
function closeMove() {
  document.getElementById("movePage").classList.remove("toggleBlock")
}
function closeAdd() {
  document.getElementById("addPage").classList.remove("toggleBlock")
}
function moveToPlan() {
  var id = getSelectedPlanId();
  var semester = document.getElementById("moveSemester").value;
  if (id!=""&&semester!=null) {
    var submode = (id.startsWith('W'))?'chooser':'lecture';
    var request = new XMLHttpRequest();
    request.open("POST", "./php/adminEditor.php", true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        if (request.responseText.startsWith('Error:')) {
          alert(request.responseText);
        } else {
          initAdminEditor();
       }
      }
    };
    request.send("mode=move&submode="+submode+"&id="+id+"&semester="+semester+"&sid=" + sid);
  } else {alert('Error: Pflichtfeld leer.');}
}
/*USER*/
function redirectUser(userID) {
  var request = new XMLHttpRequest();
  request.open("POST", "./page/user.php", true);
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      document.getElementsByTagName('body')[0].innerHTML = request.responseText;
      initUser();
      loadFreeSubjects();
    }
  };
  request.send('userID=' + userID);
}

function loadFreeSubjects() {
  var request = new XMLHttpRequest();
  request.open("POST", "./php/userEditor.php", true);
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      document.getElementById('freeSubjects').innerHTML = this.responseText;
    }
  };

  request.send('mode=get&submode=freesubject&userID=' + getMeta('userID'));
}

function initUser() {
  var request = new XMLHttpRequest();
  request.open("POST", "./php/userEditor.php", true);
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      document.getElementById('semesterTableId').innerHTML = this.responseText;

      initializeTablePartsUser();
      initializeAddPageUser();
      initializeRemovePageUser();
      initializeMovePageUser();
      initializeResetPageUser();

    }
  };
  request.send('userID=' + getMeta('userID') + '&mode=get&submode=all');
}

function getSpezificInfo(pId) {
  var request = new XMLHttpRequest();
  request.open("POST", "./php/userEditor.php", true);
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      document.getElementById('information').innerHTML = this.responseText;
    }
  };
  request.send('mode=get&submode=info&pId=' + pId);
}

function passedSubject() {
  var request = new XMLHttpRequest();
  request.open("POST", "./php/userEditor.php", true);
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      if (request.responseText.startsWith('Error:')) {
        alert(request.responseText);
      } else {
        if (tablePartsActiv) {
          initializeTablePartsUser()
        } else {
          initUser();
        }

      }
    }
  };
  request.send('userID=' + getMeta('userID') + '&mode=set&submode=passed&pId=' + getSelectedPlanId());
}

function selectUserEditor(element) {
  var id = element.getElementsByTagName("strong")[0].innerHTML;
  var tds = document.getElementsByTagName("td");
  var strong;
  for (var i = 0; i < tds.length; i++) {

    strong = tds[i].getElementsByTagName("strong")[0];
    if (typeof strong !== 'undefined') {
      if (tds[i].getElementsByTagName("strong")[0].innerHTML == id) {
        tds[i].classList.toggle("selectEditor");
        getSpezificInfo(id);
      } else {
        tds[i].classList.remove("selectEditor");
      }
    }
  }
}

function initializeTablePartsUser() {
  request = new XMLHttpRequest();
  request.open("POST", "./php/userEditor.php", true);
  request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  request.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {

      document.getElementsByClassName("tableParts")[0].innerHTML = this.responseText;
      updateTableParts();

    }
  };
  request.send("mode=get&submode=part&userID=" + getMeta('userID'));
}

var showInfo = false;
var showFaecher = false;
var tablePartsActiv = false;

/**
 * Die bewegende Leiste für die Tools.
*/
function clickTools() {

  var slideDivLeft = document.getElementById("slideSubLeft");
  var slideDivRight = document.getElementById("slideSubRight");
  var submenuLeft = document.getElementById("submenuLeft");
  var submenuRight = document.getElementById("submenuRight");

  if (slideDivLeft.style.height == '40px') {
    slideDivLeft.style.height = '10px';
    submenuLeft.style.visibility = 'hidden';
  } else {

    if (slideDivRight.style.height == '40px') {
      slideDivRight.style.height = '10px';
      submenuRight.style.visibility = 'hidden';
    }

    slideDivLeft.style.height = '40px';
    submenuLeft.style.visibility = 'visible';
  }

}

function clickProfil() {

  var slideDivRight = document.getElementById("slideSubRight");
  var slideDivLeft = document.getElementById("slideSubLeft");
  var submenuRight = document.getElementById("submenuRight");
  var submenuLeft = document.getElementById("submenuLeft");

  if (slideDivRight.style.height == '40px') {
    slideDivRight.style.height = '10px';
    submenuRight.style.visibility = 'hidden';
  } else {

    if (slideDivLeft.style.height == '40px') {
      slideDivLeft.style.height = '10px';
      submenuLeft.style.visibility = 'hidden';
    }
    slideDivRight.style.height = '40px';
    submenuRight.style.visibility = 'visible';
  }

}

/**
 *
 */
function getInfo(mobil) {
  var subMenu = document.getElementById("divInformation");

  if (subMenu.style.right == '0px') {
    if(mobil){
      subMenu.style.right = '-96.8%';
    } else {
      subMenu.style.right = '-22.8%';
    }

    showInfo = false;
  } else {
    if(mobil){
      subMenu.style.width = '97%';
    }
    subMenu.style.right = '0px';
    showInfo = true;
  }
  sizingLeftAndRight(mobil);
}

function getFreeSubjects(mobil) {


  var subMenu = document.getElementById("divTableModul");

  if (subMenu.style.left == '0px') {

    if(mobil){
      subMenu.style.left = '-96.8%';
    } else {
      subMenu.style.left = '-22.8%';
    }
    showFaecher = false;
  } else {
    if(mobil){
      subMenu.style.width = '97%';
    }
    subMenu.style.left = '0px';
    showFaecher = true;
  }

  sizingLeftAndRight(mobil);
}

/**
 *
 * @param {*} show
 */
function showTableParts() {
  var tablePartMenu = document.getElementsByClassName("tablePartsMenu")[0];
  var tableParts = document.getElementsByClassName("tableParts")[0];

  if (!tablePartsActiv) {
    document.getElementById("semesterTableId").classList.toggle("toggleBlockDiv");
    updateTableParts();
    tableParts.style.display = 'block';
    tablePartMenu.style.display = 'block';
    tablePartsActiv = true;
  } else {

    if (showFaecher && showInfo) {
      alert("Error: Die komplette Ansicht kann nicht angezeigt werden.")
    } else {
      document.getElementById("semesterTableId").classList.toggle("toggleBlockDiv");
      initUser();
      tablePartMenu.style.display = 'none';
      tableParts.style = 'none';
      tablePartsActiv = false;

    }


  }

}



/**
 *
 */
function sizingLeftAndRight(mobil) {

  var divLeft = document.getElementById("boxLeft");
  var divMid = document.getElementById("boxMid");

  if(mobil){
    if ((!(showInfo) && showFaecher) || (showInfo && !(showFaecher))) {

      if (!(showInfo) && showFaecher) {
        divLeft.style.width = '97%';
      } else {
        divLeft.style.width = '1%';
      }
      divMid.style.width = '.1%';


    } else if (showInfo && showFaecher) {



      divLeft.style.width = '23%';
      divMid.style.width = '52%';

    } else {
      divLeft.style.width = '1%';
      divMid.style.width = '96%';
    }

  } else {

    if ((!(showInfo) && showFaecher) || (showInfo && !(showFaecher))) {

      if (!(showInfo) && showFaecher) {
        divLeft.style.width = '23%';
      } else {
        divLeft.style.width = '1%';
      }
      divMid.style.width = '74%';


    } else if (showInfo && showFaecher) {

      divLeft.style.width = '23%';
      divMid.style.width = '52%';

      if (!tablePartsActiv) {
        showTableParts();
      }

    } else {
      divLeft.style.width = '1%';
      divMid.style.width = '96%';
    }
  }
}

/**
 *
 */
function initializeAddPageUser() {
  request = new XMLHttpRequest();
  request.open("POST", "./php/userEditor.php", true);
  request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  request.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("addPage").innerHTML = this.responseText;

    }
  };
  request.send("mode=get&submode=add&userID=" + getMeta('userID'));
}

/**
 *
 */
function addToPlanUser() {

  var semester = document.getElementById("addSemesterL").value;
  var element = "{\"Typ\":\"FW_FACH\",\"P-ID\":\"" + getSelectedPlanId() + "\", \"Bestanden\":false}";

  request = new XMLHttpRequest();
  request.open("POST", "./php/userEditor.php", true);
  request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  request.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      if (request.responseText.startsWith('Error:')) {
        alert(request.responseText);
      } else {

        if (tablePartsActiv) {
          initializeTablePartsUser();
        } else {
          initUser();
        }


        loadFreeSubjects();
      }
    }
  };
  request.send("mode=insert&element=" + element + "&semester=" + semester + "&userID=" + getMeta('userID'));
}

function initializeRemovePageUser() {
  request = new XMLHttpRequest();
  request.open("POST", "./php/userEditor.php", true);
  request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  request.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("removePage").innerHTML = this.responseText;

    }
  };
  request.send("mode=get&submode=remove");
}

function toggleRemove() {
  document.getElementById("removePage").classList.toggle("toggleBlock");
  document.getElementById("myModal").classList.toggle("toggleBlock");
}

function deleteFromPlanUser() {
  request = new XMLHttpRequest();
  request.open("POST", "./php/userEditor.php", true);
  request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  request.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      if (request.responseText.startsWith('Error:')) {
        alert(request.responseText);
      } else {

        if (tablePartsActiv) {
          initializeTablePartsUser();
        } else {
          initUser();
        }
        loadFreeSubjects();
      }
    }

  };
  request.send("mode=delete&bID=" + getSelectedPlanId() + "&userID=" + getMeta('userID'));
}


function initializeMovePageUser() {
  request = new XMLHttpRequest();
  request.open("POST", "./php/userEditor.php", true);
  request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  request.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("movePage").innerHTML = this.responseText;

    }
  };
  request.send("mode=get&submode=move&userID=" + getMeta('userID'));
}

function moveOnPlanUser() {
  var semester = document.getElementById("moveSemesterD").value;

  if (getSelectedPlanId() != '') {
    request = new XMLHttpRequest();
    request.open("POST", "./php/userEditor.php", true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {

        if (request.responseText.startsWith('Error:')) {
          alert(request.responseText);
        } else {

          if (tablePartsActiv) {
            initializeTablePartsUser();
          } else {
            initUser();
          }
        }
      }
    };
    request.send("mode=move&bID=" + getSelectedPlanId() + "&semester=" + semester + "&userID=" + getMeta('userID'));
  } else {
    alert('Error: Es wurde kein Fach ausgewählt.');
  }
}

function initializeElectivePageUser(id) {
  request = new XMLHttpRequest();
  request.open("POST", "./php/userEditor.php", true);
  request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  request.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("electivePage").innerHTML = this.responseText;

    }
  };
  request.send("mode=get&submode=elective&userID=" + getMeta('userID') + "&wID=" + id);
}

function toggleElective(element) {
  if (element != null) {
    var id = element.getElementsByTagName("strong")[0].innerHTML;
    initializeElectivePageUser(id);
  }
  document.getElementById("electivePage").classList.toggle("toggleBlock");
  document.getElementById("myModal").classList.toggle("toggleBlock");
}

function selectElectiveUser() {
  if (getSelectedPlanId() != '') {
    request = new XMLHttpRequest();
    request.open("POST", "./php/userEditor.php", true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {

        if (request.responseText.startsWith('Error:')) {
          alert(request.responseText);
        } else {

          if (tablePartsActiv) {
            initializeTablePartsUser();
          } else {
            initUser();
          }
        }
      }
    };
    request.send("mode=set&submode=elective&bID=" + getSelectedPlanId() + "&userID=" + getMeta('userID'));
  } else {
    alert('Error: Es wurde kein Fach ausgewählt.');
  }
}

function openChooserOne() {
  document.getElementById("chooseOnePage").classList.add("toggleBlock");
  document.getElementById("chooseOneButton").classList.add("toggleSubMenuPart");
}
function closeChooserOne() {
  document.getElementById("chooseOnePage").classList.remove("toggleBlock");
  document.getElementById("chooseOneButton").classList.remove("toggleSubMenuPart");
}
function openChooserTwo() {
  document.getElementById("chooseTwoPage").classList.add("toggleBlock");
  document.getElementById("chooseTwoButton").classList.add("toggleSubMenuPart");
}
function closeChooserTwo() {
  document.getElementById("chooseTwoPage").classList.remove("toggleBlock");
  document.getElementById("chooseTwoButton").classList.remove("toggleSubMenuPart");
}



function initializeResetPageUser() {
  request = new XMLHttpRequest();
  request.open("POST", "./php/userEditor.php", true);
  request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  request.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("resetPage").innerHTML = this.responseText;

    }
  };
  request.send("mode=get&submode=reset");
}



function toggleReset() {
  document.getElementById("resetPage").classList.toggle("toggleBlock");
  document.getElementById("myModal").classList.toggle("toggleBlock");
}

function resetPlanUser() {
  request = new XMLHttpRequest();
  request.open("POST", "./php/userEditor.php", true);
  request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  request.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {

      if (request.responseText.startsWith('Error:')) {
        alert(request.responseText);
      } else {

        if (tablePartsActiv) {
          initializeTablePartsUser();
        } else {
          initUser();
        }
      }
    }
  };
  request.send("mode=reset&userID=" + getMeta('userID'));

}

function searchSubjects() {

  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search");
  filter = input.value.toUpperCase();
  table = document.getElementById("tableSearch");
  tr = table.getElementsByTagName("tr");

  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
