var hasIE_phone_home = 0;


// This function does the actual browser detection
function hasIE_hasIE() {
  var ua = navigator.userAgent.toLowerCase();
  return ((ua.indexOf('msie') != -1) && (ua.indexOf('opera') == -1) && 
          (ua.indexOf('webtv') == -1) &&
          (location.href.indexOf('seenIEPage') == -1));
}

function hasIE_showOnlyLayer(whichLayer)
{
  if (document.getElementById)
    {
      var style2 = document.getElementById(whichLayer);
    }
  else if (document.all)
    {
      var style2 = document.all[whichLayer];
    }
  else if (document.layers)
    {
      var style2 = document.layers[whichLayer];
    }
  var body = document.getElementsByTagName('body');
  body[0].innerHTML = style2.innerHTML;
}

function hasIE_showLayer(whichLayer)
{
  if (document.getElementById)
    {
      var style2 = document.getElementById(whichLayer).style;
      style2.display = "block";
    }
  else if (document.all)
    {
      var style2 = document.all[whichLayer].style;
      style2.display = "block";
    }
  else if (document.layers)
    {
      var style2 = document.layers[whichLayer].style;
      style2.display = "block";
    }
}

function hasIE_moveAd(adid) {
  if (document.getElementById)
    {
      var ad = document.getElementById('hasIE_ad');
      var adloc = document.getElementById(adid);
    }
  else if (document.all)
    {
      var ad = document.all['hasIE_ad'];
      var adloc = document.all[adid];
    }
  else if (document.layers)
    {
      var ad = document.layers['hasIE_ad'];
      var adloc = document.layers[adid];
    }
  adloc.innerHTML = ad.innerHTML;
}

// Hides and shows sections of the page based on whether or not it's
// running in IE
function hasIE_hideAndShow() {
  if (hasIE_hasIE()) {
    hasIE_showLayer("hasIE_level1");
          if (hasIE_phone_home == 1)
            hasIE_phoneHome('getIE_pingimage1');
  } else {
    if (hasIE_phone_home == 1)
      hasIE_phoneHome('getIE_pingimage0');
  }
}

function hasIE_phoneHome(image) {
  if (document.getElementById)
    {
      var img = document.getElementById(image);
    }
  else if (document.all)
    {
      var img = document.all[image];
    }
  else if (document.layers)
    {
      var img = document.layers[image];
    }
  img.setAttribute('src','http://getunder50.com/ping.php?host='+location.host);

}

function hasIE_ContinueWithoutFF() {
    if (location.href.indexOf('?') != -1)
        location.href += '&seenIEPage=1';
    else
        location.href += '?seenIEPage=1';
}
