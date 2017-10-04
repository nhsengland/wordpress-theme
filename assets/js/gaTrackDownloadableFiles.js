window.gaTrackDownloadableFiles = function() {
  var links = $('a');
  for(var i = 0; i < links.length; i++) {
    if (links[i].href.indexOf('.pdf') != "-1") {
      $(links[i]).attr("onclick","ga('send', 'event', 'Downloads', 'PDF', '"+links[i].href+"');");
    } else if (links[i].href.indexOf('.csv') != "-1") {
      $(links[i]).attr("onclick","ga('send', 'event', 'Downloads', 'CSV', '"+links[i].href+"');");
    } else if (links[i].href.indexOf('.doc') != "-1") {
      $(links[i]).attr("onclick","ga('send', 'event', 'Downloads', 'DOC', '"+links[i].href+"');");
    } else if (links[i].href.indexOf('.ppt') != "-1") {
      $(links[i]).attr("onclick","ga('send', 'event', 'Downloads', 'PPT', '"+links[i].href+"');");
    } else if (links[i].href.indexOf('.rtf') != "-1") {
      $(links[i]).attr("onclick","ga('send', 'event', 'Downloads', 'RTF', '"+links[i].href+"');");
    } else if (links[i].href.indexOf('.xls') != "-1") {
      $(links[i]).attr("onclick","ga('send', 'event', 'Downloads', 'XLS', '"+links[i].href+"');");
    }
  }
  return true;
}
