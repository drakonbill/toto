// JavaScript Document

// form validation function //
function validate(form) {
  var psuedo = form.psuedo2.value;
  var email = form.email.value;
  var etes = form.etes.value;
  var passe = form.passe.value;
  var confirmation = form.confirmation.value;
  var naissance = form.naissance.value;
  var pays = form.pays.value;
  var postal = form.postal.value;
  var nameRegex = /^[a-zA-Z]+(([\'\,\.\- ][a-zA-Z ])?[a-zA-Z]*)*$/;
  var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
  var messageRegex = new RegExp(/<\/?\w+((\s+\w+(\s*=\s*(?:".*?"|'.*?'|[^'">\s]+))?)+\s*|\s*)\/?>/gim);
  if(psuedo == "") {
    inlineMsg('psuedo2','Vous devez écrire votre nom.',2);
    return false;
  }
  if(!psuedo.match(nameRegex)) {
    inlineMsg('psuedo2','Vous avez écrit un nom inadmissible.',2);
    return false;
  }
  if(passe == "") {
    inlineMsg('passe','Vous devez entrer votre mot de passe.');
    return false;
  }
  if(confirmation == "") {
    inlineMsg('confirmation','Vous devez écrire votre nom.',2);
    return false;
  }
  if(email == "") {
    inlineMsg('email','<strong>Error</strong><br />Vous devez entrer dans votre email.',2);
    return false;
  }
  if(!email.match(emailRegex)) {
    inlineMsg('email','<strong>Error</strong><br />vous êtes entré dans un email inadmissible.',2);
    return false;
  }
  if(naissance == '') {
    inlineMsg('naissance','Vous devez écrire une date de naissance.');
    return false;
  }
  if(pays == '') {
    inlineMsg('pays','You have entered an invalid message.');
    return false;
  }
  if(postal == '') {
    inlineMsg('postal','Vous avez écrit un code postal inadmissible.');
    return false;
  }
  if(etes == "") {
    inlineMsg('etes','<strong>Error</strong><br />Vous devez choisir votre genre.',2);
    return false;
  }
  return true;
}

// START OF MESSAGE SCRIPT //

var MSGTIMER = 20;
var MSGSPEED = 5;
var MSGOFFSET = 3;
var MSGHIDE = 3;

// build out the divs, set attributes and call the fade function //
function inlineMsg(target,string,autohide) {
  var msg;
  var msgcontent;
  if(!document.getElementById('msg')) {
    msg = document.createElement('div');
    msg.id = 'msg';
    msgcontent = document.createElement('div');
    msgcontent.id = 'msgcontent';
    document.body.appendChild(msg);
    msg.appendChild(msgcontent);
    msg.style.filter = 'alpha(opacity=0)';
    msg.style.opacity = 0;
    msg.alpha = 0;
  } else {
    msg = document.getElementById('msg');
    msgcontent = document.getElementById('msgcontent');
  }
  msgcontent.innerHTML = string;
  msg.style.display = 'block';
  var msgheight = msg.offsetHeight;
  var targetdiv = document.getElementById(target);
  targetdiv.focus();
  var targetheight = targetdiv.offsetHeight;
  var targetwidth = targetdiv.offsetWidth;
  var topposition = topPosition(targetdiv) - ((msgheight - targetheight) / 2);
  var leftposition = leftPosition(targetdiv) + targetwidth + MSGOFFSET;
  msg.style.top = topposition + 'px';
  msg.style.left = leftposition + 'px';
  clearInterval(msg.timer);
  msg.timer = setInterval("fadeMsg(1)", MSGTIMER);
  if(!autohide) {
    autohide = MSGHIDE;  
  }
  window.setTimeout("hideMsg()", (autohide * 1000));
}

// hide the form alert //
function hideMsg(msg) {
  var msg = document.getElementById('msg');
  if(!msg.timer) {
    msg.timer = setInterval("fadeMsg(0)", MSGTIMER);
  }
}

// face the message box //
function fadeMsg(flag) {
  if(flag == null) {
    flag = 1;
  }
  var msg = document.getElementById('msg');
  var value;
  if(flag == 1) {
    value = msg.alpha + MSGSPEED;
  } else {
    value = msg.alpha - MSGSPEED;
  }
  msg.alpha = value;
  msg.style.opacity = (value / 100);
  msg.style.filter = 'alpha(opacity=' + value + ')';
  if(value >= 99) {
    clearInterval(msg.timer);
    msg.timer = null;
  } else if(value <= 1) {
    msg.style.display = "none";
    clearInterval(msg.timer);
  }
}

// calculate the position of the element in relation to the left of the browser //
function leftPosition(target) {
  var left = 0;
  if(target.offsetParent) {
    while(1) {
      left += target.offsetLeft;
      if(!target.offsetParent) {
        break;
      }
      target = target.offsetParent;
    }
  } else if(target.x) {
    left += target.x;
  }
  return left;
}

// calculate the position of the element in relation to the top of the browser window //
function topPosition(target) {
  var top = 0;
  if(target.offsetParent) {
    while(1) {
      top += target.offsetTop;
      if(!target.offsetParent) {
        break;
      }
      target = target.offsetParent;
    }
  } else if(target.y) {
    top += target.y;
  }
  return top;
}

// preload the arrow //
if(document.images) {
  arrow = new Image(7,80); 
  arrow.src = "images/msg_arrow.gif"; 
}