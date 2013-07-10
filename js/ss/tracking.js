var Tracking;
var _gaq;

Tracking = (function() {

  function Tracking() {
    _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
    g.src='//www.google-analytics.com/ga.js';
    s.parentNode.insertBefore(g,s)}(document,'script'));
  }

  Tracking.prototype.page = function(arr) {
    arr.splice(0, 0, '_trackPageview');
    return _gaq.push(arr);
  };

  Tracking.prototype.event = function(arr) {
    arr.splice(0, 0, '_trackEvent');
    return _gaq.push(arr);
  };

  return Tracking;

})();


// _rollit.track.event ["clickable_link", "controller setup", "Yes"]
// _rollit.track.page ["/mobile non-chrome browser error"]
// _gaq.push(['_trackPageview']); ???