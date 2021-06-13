/* function statrtOnLoad() {
} */

function connect(el, ev, f, capture) {
	if (el == document && window.addEventListener)
		return window.addEventListener(ev, f, capture || false);
	if (el.addEventListener)
		return el.addEventListener(ev, f, capture || false);
	else if (el.attachEvent)
		return el.attachEvent('on' + ev, f);
	else
		return false;
}

rolloverOriginalImages = new Array();

function rollover(aId, imgId, overHref) {
	a = document.getElementById(aId);
	img = document.getElementById(imgId);
	
	connect(a, 'mouseout', function (e) {
		img.setAttribute('src', rolloverOriginalImages["" + a + img]);
	});
	connect(a, 'mouseover', function (e) {
		rolloverOriginalImages["" + a + img] = img.getAttribute('src');
		img.setAttribute('src', overHref);
	});
}

connect(document, 'load', function (e) {
	rollover('lien-accueil', 'logo-grunt', 'http://gruntnetwork.com/site/image/logo.png');
	rollover('lien-editer', 'logo-grunt', 'http://gruntnetwork.com/site/image/editer.png');
	rollover('lien-tuxfamily', 'logo-grunt', 'http://gruntnetwork.com/site/image/tuxfamily.png');
});