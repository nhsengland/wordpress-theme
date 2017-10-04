window.showDownloadableFileIcons = function() {
  var links = $('a');
  for(var i = 0; i < links.length; i++) {
    if (links[i].href.indexOf('.pdf') != "-1") {
      $(links[i]).addClass("pdf-link");
    } else if (links[i].href.indexOf('.csv') != "-1") {
      $(links[i]).addClass("csv-link");
    } else if (links[i].href.indexOf('.doc') != "-1") {
      $(links[i]).addClass("doc-link");
    } else if (links[i].href.indexOf('.ppt') != "-1") {
      $(links[i]).addClass("ppt-link");
    } else if (links[i].href.indexOf('.rtf') != "-1") {
      $(links[i]).addClass("rtf-link");
    } else if (links[i].href.indexOf('.xls') != "-1") {
      $(links[i]).addClass("xls-link");
    }
  }
  return true;
}
