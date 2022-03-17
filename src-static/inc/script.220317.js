/* * *
 * streamWindowCustom functions
 */

function flip(file, level) {
	event.preventDefault();
	document.getElementById('streamWindowCustomTile').src = file;
	document.getElementById('noteLevel').innerHTML = 'Sea level <span style="font-size: 2em;">'+level+'m</span>';
	document.getElementById('noteLand').innerHTML = seaLevelData[level]['land']+'% Land';
	document.getElementById('noteWater').innerHTML = seaLevelData[level]['water']+'% Water';
	return false;
}

function waitForImages() {
	var ok = true;
	for (var key in customListPreload) {
		if (customListPreload[key].complete || customListPreload[key].complete==null) {
			document.getElementById(key).style.fontWeight = 'bold';
		} else {
			ok = false;
		}
	}

	if (ok == true) {
		clearTimeout(timerIDWaiter);
		document.getElementById('loading2').style.display = 'none';
	} else {
		timerIDWaiter = setTimeout("waitForImages();", 500);
	}
}

/* * *
 * control functions
 */

String.prototype.pad = function(l, s, t){
	return s || (s = " "), (l -= this.length) > 0 ? (s = new Array(Math.ceil(l / s.length)
		+ 1).join(s)).substr(0, t = !t ? l : t == 1 ? 0 : Math.ceil(l / 2))
		+ this + s.substr(0, l - t) : this;
};

var timerID = null;
var timerIDWaiter = null;
var listIndex = new Array();
var listStart = -100;
var listEnd = 2000;
var listInc = 1;
var listSpeed = 20;
var list2Play = new Array();
var list2Cache = new Array();
var list2Point = 0;
var list2CacheCount = 0;

for (i = listStart; i <= listEnd; i = i + listInc) {
	var bit = i;
	if (bit < 0) {
		bit = bit * -1;
	}
	bit = bit.toString();
	bit = bit.pad(5, "0", 0);
	listIndex[i] = "/res/globe/SeaLevel_"+((i<0)?'-':'_')+bit+"m.raw.png";
}

function switchStream() {
	document.getElementById('loading').style.display = 'block';

	list2Play = new Array();
	list2Play.push(listIndex[parseInt(document.getElementById('view-val').value)]);

	cacheStream();
}

function playStream() {
	document.getElementById('loading').style.display = 'block';
	
    list2Play = new Array();

    for (i = parseInt(document.getElementById('sim-from').value); i <= parseInt(document.getElementById('sim-to').value); i++) {
        list2Play.push(listIndex[i]);
    }

	if (list2Play.length > 0) {
		cacheStream();
	} else {
		document.getElementById('loading').style.display = 'none';
	}
}

function cacheStream() {
	list2Cache = new Array();

	for (i=0; i<list2Play.length; i++) {
		list2Cache[i] = new Image();
		list2Cache[i].src = list2Play[i];
	}

	waitStream();
}

function waitStream() {
	var ok = true;
	list2CacheCount = 0;

	for (i=0; i<list2Play.length; i++) {
		if (list2Cache[i].complete || (list2Cache[i].complete == null)) {
			list2CacheCount++;
		} else {
			ok = false;
		}
	}

	if (ok == true) {
		clearTimeout(timerIDWaiter);
		document.getElementById('loadingCount').innerHTML = '';
		document.getElementById('loading').style.display = 'none';

		list2Point = 0;
		streamPoints();
	} else {
		document.getElementById('loadingCount').innerHTML = ' (' + list2CacheCount + ' of ' + list2Play.length + ')';
		timerIDWaiter = setTimeout("waitStream();", 1000);
	}
}

function streamPoints() {
	document.getElementById('streamWindow').src = list2Play[list2Point];

	if (list2Point >= (list2Play.length-1)) {
		clearTimeout(timerID);
	} else {
		list2Point++;
		timerID = setTimeout("streamPoints();", listSpeed);
	}
}

/* * *
 * Tooltip functions
 */

var helpTimerID = null;
var helpOpac = 0;

function setTxt(obj, v) {
	obj = getObject(obj);
	if (obj==null) return;

	if (v != obj.innerHTML) obj.innerHTML = v;
}

var IE = document.all?true:false;
if (!IE) document.captureEvents(Event.MOUSEMOVE);

function getObject(obj) {
	if (document.getElementById) {
		obj = document.getElementById(obj);
	} else if ( document.all ) {
		obj = document.all.item(obj);
	} else {
		obj = null;
	}

	return obj;
}
  
function moveObject(obj, e) {
	var tempX = 0;
	var tempY = 0;
	var offset = 5;
	var objHolder = obj;

	obj = getObject(obj);
	if (obj==null) return;

	if (document.all) {
		tempX = event.clientX + document.body.scrollLeft;
		tempY = event.clientY + document.body.scrollTop;
	} else {
		tempX = e.pageX;
		tempY = e.pageY;
	}

	if (tempX < 0) {tempX = 0;}
	if (tempY < 0) {tempY = 0;}

	obj.style.top  = (tempY + offset) + 'px';
	obj.style.left = (tempX + offset) + 'px';

	displayObject(objHolder, true);
}

function displayObject(objHolder, show) {
	obj = getObject(objHolder);
	if (obj==null) return;

	obj.style.display = show ? 'block' : 'none';
	obj.style.visibility = show ? 'visible' : 'hidden';

	if (show) fadeObject(obj, true);
}

function fadeObject(obj, up) {
	if (obj==null) return;

	if (up) {
		helpOpac = helpOpac + 0.1;
		if (helpOpac >= 0.9) {
			clearTimeout(helpTimerID);
			obj.style.opacity = 0.9;
			helpTimerID = setTimeout("fadeObject(obj, false)", 1500);
		} else {
			obj.style.opacity = helpOpac;
			helpTimerID = setTimeout("fadeObject(obj, true)", 30);
		}
	} else {
		helpOpac = helpOpac - 0.1;
		if (helpOpac <= 0.1) {
			obj.style.display = 'none';
			obj.style.visibility = 'hidden';
			obj.style.opacity = 0;
			clearTimeout(helpTimerID);
		} else {
			obj.style.opacity = helpOpac;
			helpTimerID = setTimeout("fadeObject(obj, false)", 30);
		}
	}
}