!function(t){"use strict";function e(t){return null!==t&&t===t.window}function n(t){return e(t)?t:9===t.nodeType&&t.defaultView}function a(t){var e,a,i={top:0,left:0},o=t&&t.ownerDocument;return e=o.documentElement,"undefined"!=typeof t.getBoundingClientRect&&(i=t.getBoundingClientRect()),a=n(o),{top:i.top+a.pageYOffset-e.clientTop,left:i.left+a.pageXOffset-e.clientLeft}}function i(t){var e="";for(var n in t)t.hasOwnProperty(n)&&(e+=n+":"+t[n]+";");return e}function o(t){if(d.allowEvent(t)===!1)return null;for(var e=null,n=t.target||t.srcElement;null!==n.parentElement;){if(!(n instanceof SVGElement||-1===n.className.indexOf("waves-effect"))){e=n;break}if(n.classList.contains("waves-effect")){e=n;break}n=n.parentElement}return e}function r(e){var n=o(e);null!==n&&(c.show(e,n),"ontouchstart"in t&&(n.addEventListener("touchend",c.hide,!1),n.addEventListener("touchcancel",c.hide,!1)),n.addEventListener("mouseup",c.hide,!1),n.addEventListener("mouseleave",c.hide,!1))}var s=s||{},u=document.querySelectorAll.bind(document),c={duration:750,show:function(t,e){if(2===t.button)return!1;var n=e||this,o=document.createElement("div");o.className="waves-ripple",n.appendChild(o);var r=a(n),s=t.pageY-r.top,u=t.pageX-r.left,d="scale("+n.clientWidth/100*10+")";"touches"in t&&(s=t.touches[0].pageY-r.top,u=t.touches[0].pageX-r.left),o.setAttribute("data-hold",Date.now()),o.setAttribute("data-scale",d),o.setAttribute("data-x",u),o.setAttribute("data-y",s);var l={top:s+"px",left:u+"px"};o.className=o.className+" waves-notransition",o.setAttribute("style",i(l)),o.className=o.className.replace("waves-notransition",""),l["-webkit-transform"]=d,l["-moz-transform"]=d,l["-ms-transform"]=d,l["-o-transform"]=d,l.transform=d,l.opacity="1",l["-webkit-transition-duration"]=c.duration+"ms",l["-moz-transition-duration"]=c.duration+"ms",l["-o-transition-duration"]=c.duration+"ms",l["transition-duration"]=c.duration+"ms",l["-webkit-transition-timing-function"]="cubic-bezier(0.250, 0.460, 0.450, 0.940)",l["-moz-transition-timing-function"]="cubic-bezier(0.250, 0.460, 0.450, 0.940)",l["-o-transition-timing-function"]="cubic-bezier(0.250, 0.460, 0.450, 0.940)",l["transition-timing-function"]="cubic-bezier(0.250, 0.460, 0.450, 0.940)",o.setAttribute("style",i(l))},hide:function(t){d.touchup(t);var e=this,n=(1.4*e.clientWidth,null),a=e.getElementsByClassName("waves-ripple");if(!(a.length>0))return!1;n=a[a.length-1];var o=n.getAttribute("data-x"),r=n.getAttribute("data-y"),s=n.getAttribute("data-scale"),u=Date.now()-Number(n.getAttribute("data-hold")),l=350-u;0>l&&(l=0),setTimeout(function(){var t={top:r+"px",left:o+"px",opacity:"0","-webkit-transition-duration":c.duration+"ms","-moz-transition-duration":c.duration+"ms","-o-transition-duration":c.duration+"ms","transition-duration":c.duration+"ms","-webkit-transform":s,"-moz-transform":s,"-ms-transform":s,"-o-transform":s,transform:s};n.setAttribute("style",i(t)),setTimeout(function(){try{e.removeChild(n)}catch(t){return!1}},c.duration)},l)},wrapInput:function(t){for(var e=0;e<t.length;e++){var n=t[e];if("input"===n.tagName.toLowerCase()){var a=n.parentNode;if("i"===a.tagName.toLowerCase()&&-1!==a.className.indexOf("waves-effect"))continue;var i=document.createElement("i");i.className=n.className+" waves-input-wrapper";var o=n.getAttribute("style");o||(o=""),i.setAttribute("style",o),n.className="waves-button-input",n.removeAttribute("style"),a.replaceChild(i,n),i.appendChild(n)}}}},d={touches:0,allowEvent:function(t){var e=!0;return"touchstart"===t.type?d.touches+=1:"touchend"===t.type||"touchcancel"===t.type?setTimeout(function(){d.touches>0&&(d.touches-=1)},500):"mousedown"===t.type&&d.touches>0&&(e=!1),e},touchup:function(t){d.allowEvent(t)}};s.displayEffect=function(e){e=e||{},"duration"in e&&(c.duration=e.duration),c.wrapInput(u(".waves-effect")),"ontouchstart"in t&&document.body.addEventListener("touchstart",r,!1),document.body.addEventListener("mousedown",r,!1)},s.attach=function(e){"input"===e.tagName.toLowerCase()&&(c.wrapInput([e]),e=e.parentElement),"ontouchstart"in t&&e.addEventListener("touchstart",r,!1),e.addEventListener("mousedown",r,!1)},t.Waves=s,document.addEventListener("DOMContentLoaded",function(){s.displayEffect()},!1)}(window);
/*!
 * current-device v0.7.2 - https://github.com/matthewhudson/current-device
 * MIT Licensed
 */
!function(n,o){"object"==typeof exports&&"object"==typeof module?module.exports=o():"function"==typeof define&&define.amd?define([],o):"object"==typeof exports?exports.device=o():n.device=o()}(this,function(){return function(n){function o(t){if(e[t])return e[t].exports;var i=e[t]={i:t,l:!1,exports:{}};return n[t].call(i.exports,i,i.exports,o),i.l=!0,i.exports}var e={};return o.m=n,o.c=e,o.d=function(n,e,t){o.o(n,e)||Object.defineProperty(n,e,{configurable:!1,enumerable:!0,get:t})},o.n=function(n){var e=n&&n.__esModule?function(){return n.default}:function(){return n};return o.d(e,"a",e),e},o.o=function(n,o){return Object.prototype.hasOwnProperty.call(n,o)},o.p="",o(o.s=0)}([function(n,o,e){n.exports=e(1)},function(n,o,e){"use strict";function t(n){return-1!==m.indexOf(n)}function i(n){return w.className.match(new RegExp(n,"i"))}function r(n){var o=null;i(n)||(o=w.className.replace(/^\s+|\s+$/g,""),w.className=o+" "+n)}function a(n){i(n)&&(w.className=w.className.replace(" "+n,""))}function d(){b.landscape()?(a("portrait"),r("landscape"),c("landscape")):(a("landscape"),r("portrait"),c("portrait")),l()}function c(n){for(var o in p)p[o](n)}function u(n){for(var o=0;o<n.length;o++)if(b[n[o]]())return n[o];return"unknown"}function l(){b.orientation=u(["portrait","landscape"])}Object.defineProperty(o,"__esModule",{value:!0});var s="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(n){return typeof n}:function(n){return n&&"function"==typeof Symbol&&n.constructor===Symbol&&n!==Symbol.prototype?"symbol":typeof n},f=window.device,b={},p=[];window.device=b;var w=window.document.documentElement,m=window.navigator.userAgent.toLowerCase(),v=["googletv","viera","smarttv","internet.tv","netcast","nettv","appletv","boxee","kylo","roku","dlnadoc","roku","pov_tv","hbbtv","ce-html"];b.macos=function(){return t("mac")},b.ios=function(){return b.iphone()||b.ipod()||b.ipad()},b.iphone=function(){return!b.windows()&&t("iphone")},b.ipod=function(){return t("ipod")},b.ipad=function(){return t("ipad")},b.android=function(){return!b.windows()&&t("android")},b.androidPhone=function(){return b.android()&&t("mobile")},b.androidTablet=function(){return b.android()&&!t("mobile")},b.blackberry=function(){return t("blackberry")||t("bb10")||t("rim")},b.blackberryPhone=function(){return b.blackberry()&&!t("tablet")},b.blackberryTablet=function(){return b.blackberry()&&t("tablet")},b.windows=function(){return t("windows")},b.windowsPhone=function(){return b.windows()&&t("phone")},b.windowsTablet=function(){return b.windows()&&t("touch")&&!b.windowsPhone()},b.fxos=function(){return(t("(mobile")||t("(tablet"))&&t(" rv:")},b.fxosPhone=function(){return b.fxos()&&t("mobile")},b.fxosTablet=function(){return b.fxos()&&t("tablet")},b.meego=function(){return t("meego")},b.cordova=function(){return window.cordova&&"file:"===location.protocol},b.nodeWebkit=function(){return"object"===s(window.process)},b.mobile=function(){return b.androidPhone()||b.iphone()||b.ipod()||b.windowsPhone()||b.blackberryPhone()||b.fxosPhone()||b.meego()},b.tablet=function(){return b.ipad()||b.androidTablet()||b.blackberryTablet()||b.windowsTablet()||b.fxosTablet()},b.desktop=function(){return!b.tablet()&&!b.mobile()},b.television=function(){for(var n=0;n<v.length;){if(t(v[n]))return!0;n++}return!1},b.portrait=function(){return window.innerHeight/window.innerWidth>1},b.landscape=function(){return window.innerHeight/window.innerWidth<1},b.noConflict=function(){return window.device=f,this},b.ios()?b.ipad()?r("ios ipad tablet"):b.iphone()?r("ios iphone mobile"):b.ipod()&&r("ios ipod mobile"):b.macos()?r("macos desktop"):b.android()?r(b.androidTablet()?"android tablet":"android mobile"):b.blackberry()?r(b.blackberryTablet()?"blackberry tablet":"blackberry mobile"):b.windows()?r(b.windowsTablet()?"windows tablet":b.windowsPhone()?"windows mobile":"windows desktop"):b.fxos()?r(b.fxosTablet()?"fxos tablet":"fxos mobile"):b.meego()?r("meego mobile"):b.nodeWebkit()?r("node-webkit"):b.television()?r("television"):b.desktop()&&r("desktop"),b.cordova()&&r("cordova"),b.onChangeOrientation=function(n){"function"==typeof n&&p.push(n)};var y="resize";Object.prototype.hasOwnProperty.call(window,"onorientationchange")&&(y="onorientationchange"),window.addEventListener?window.addEventListener(y,d,!1):window.attachEvent?window.attachEvent(y,d):window[y]=d,d(),b.type=u(["mobile","tablet","desktop"]),b.os=u(["ios","iphone","ipad","ipod","android","blackberry","windows","fxos","meego","television"]),l(),o.default=b}])});