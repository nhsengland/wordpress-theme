// External Links called outside jQuery function - this might be surplus to
// requirements since the thing up there does the same
window.markExternalLinks = function() {
  var links = $('a[target="_blank"]');
  for(var i = 0; i < links.length; i++) {
    $(links[i]).addClass("external-link");
  }
  return true;
}
