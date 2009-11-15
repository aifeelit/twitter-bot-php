/* logtail.js
an ajax log file tailer / viewer
copyright 2007 john minnihan.
http://freepository.com
Released under these terms
1. This script, associated functions and HTML code ("the code") may be used by you ("the recipient") for any purpose.
2. This code may be modified in any way deemed useful by the recipient.
3. This code may be used in derivative works of any kind, anywhere, by the recipient.
4. Your use of the code indicates your acceptance of these terms.
5. This notice must be kept intact with any use of the code to provide attribution.
*/

function getLog(timer) {

var url = "http://localhost/twitter/inc/logtail.php";
request.open("GET", url, true);
request.onreadystatechange = updatePage;
request.send(null);
startTail(timer);
}

function startTail(timer) {
if (timer == "stop") {
stopTail();
} else {
t= setTimeout("getLog()",40);
}
}


// Current Page Reference
// copyright Stephen Chapman, 1st Jan 2005
// you may copy this function but please keep the copyright notice with it
function getURL(uri) {
uri.dir = location.href.substring(0, location.href.lastIndexOf('\/'));
uri.dom = uri.dir; if (uri.dom.substr(0,7) == 'http:\/\/') uri.dom = uri.dom.substr(7);
uri.path = ''; var pos = uri.dom.indexOf('\/'); if (pos > -1) {uri.path = uri.dom.substr(pos+1); uri.dom = uri.dom.substr(0,pos);}
uri.page = location.href.substring(uri.dir.length+1, location.href.length+1);
pos = uri.page.indexOf('?');if (pos > -1) {uri.page = uri.page.substring(0, pos);}
pos = uri.page.indexOf('#');if (pos > -1) {uri.page = uri.page.substring(0, pos);}
uri.ext = ''; pos = uri.page.indexOf('.');if (pos > -1) {uri.ext =uri.page.substring(pos+1); uri.page = uri.page.substr(0,pos);}
uri.file = uri.page;
if (uri.ext != '') uri.file += '.' + uri.ext;
if (uri.file == '') uri.page = 'index';
uri.args = location.search.substr(1).split("?");
return uri;
}






function stopTail() {
clearTimeout(t);
var pause = "The log viewer has been paused. To begin viewing logs again, click the Start Viewer button.";
logDiv = document.getElementById("log");
var newNode=document.createTextNode(pause);
logDiv.replaceChild(newNode,logDiv.childNodes[0]);
}



function updatePage() {
if (request.readyState == 4) {
if (request.status == 200) {
var currentLogValue = request.responseText.split("\n");
eval(currentLogValue);
logDiv = document.getElementById("log");
var logLine = ' ';
for (i=0; i < currentLogValue.length - 1; i++) {
logLine += currentLogValue[i] + '<br/>\n';
}
logDiv.innerHTML=logLine;
} else
documet.write("Error! Request status is " + request.status);
}
}


