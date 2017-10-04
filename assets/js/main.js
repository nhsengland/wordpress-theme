/* globals jQuery, Modernizr*/
'use strict'

require('../../node_modules/media-match/media.match.min.js')
var enquire = require('../../node_modules/enquire.js/dist/enquire.js')
require('../../node_modules/accessible-mega-menu/js/jquery-accessibleMegaMenu.js')
require('../../node_modules/fitvids/jquery.fitvids.js')
require('../../node_modules/parallax/parallax.min.js')
require('../../node_modules/jquery-ui/ui/minified/jquery-ui.min.js')
require('./jquery.bxslider.min.js')
require('./equalheight.js')
require('./markExternalLinks.js')
require('./gaTrackDownloadableFiles.js')
require('./showDownloadableFileIcons.js')

jQuery(function ($) {
  // BX Slider
  $('.bxslider').bxSlider({
    adaptiveHeight: true,
    auto: true,
    pause: 8000
  })

  // Datepicker if don't support html5 date input type
  if (!Modernizr.inputtypes.date) {
    $('input[type=date]').datepicker({
      /* Keep the date consistent */
      dateFormat: 'yy-mm-dd'
    })
  }

  // FitVids
  $(document).ready(function () {
    $('.video-frame').fitVids()
  })

  // Navigation
  $(function () {
    enquire.register('screen and (max-width:780px)', {
      match: function () {
        // Move top navigation above primary navigation on mobile
        $('.secondary-navigation ul.nav-menu li').prependTo('.navigation ul.nav-menu').addClass('top-nav-item')
      },
      unmatch: function () {
        $('.navigation ul.nav-menu li.top-nav-item').appendTo('.secondary-navigation ul.nav-menu').removeClass('top-nav-item')
      }
    })
  })

  $('button.archive-toggle').click(function () {
    $(this).siblings('.toggle-content').toggle().toggleClass('open')
  })

  // Add selected class and aria roles to checked input labels
  $(function () {
    $('input[type=radio],input[type=checkbox]').change(function () {
      $('input[type=radio],input[type=checkbox]').attr('aria-checked', 'false').parent().removeClass('selected')
      $('input[type=radio]:checked,input[type=checkbox]:checked').attr('aria-checked', 'true').parent().addClass('selected')
    })
  })

  // Cookie message
  var cookieValue = document.cookie.replace(/(?:(?:^|.*;\s*)cookieMessage\s*=\s*([^;]*).*$)|^.*$/, '$1')

  var cookieBanner = $('#js-cookie-message')

  cookieBanner.click(function (e) {
    e.preventDefault
    $(this).addClass('hide')
    $(window).trigger('resize')
  })

  if (cookieValue === 'true') {
    cookieBanner.addClass('hide')
  } else {
    cookieBanner.removeClass('hide')
    document.cookie = 'cookieMessage=true'
  }

  $('#js-nav-toggle').click(function () {
    $('nav.navigation ul.nav-menu').toggleClass('opened')
  })

  $('.page-content iframe[src*="www.youtube.com"], .single iframe[src*="www.youtube.com"]').each(function () {
    $(this).wrap('<div class="video-container"></div>')
  })
  // Hidden help text
  $(function () {
    $('.details .panel').addClass('hide')
    $('.anchor').on('click', function (e) {
      e.stopPropagation()
      $(this).parents('.details').toggleClass('open').find('.panel').toggleClass('hide')
    })
    var gotoAnchor = function (hash) {
      if ($(hash).length > 0) {
        $(window).scrollTop($(hash).offset().top)
        $(hash).get(0).click()
      }
    }
    setTimeout(function () {
      if (window.location.hash !== '') {
        gotoAnchor(window.location.hash)
      }
    }, 500)
  })

  $(function () {
    var isInternalLink = new RegExp('/' + window.location.host + '/')
    // Adds external class to outbound links
    $('a').not('[href*="mailto:"]').each(function () {
      if (!isInternalLink.test(this.href)) {
        $(this).addClass('external-link').attr('rel', 'external')
        $(this).parent('.link').addClass('has-external-link')
      }
    })

    // Shows note field if the link is to an external website
    $('.has-external-link').each(function () {
      $(this).find('.note').addClass('disclose')
    })
  })

  // Empty a form of values
  $('#js-form-reset-button').click(function () {
    var $form = $(this).parent('form')
    var $f = $form.find(':input').not(':button, :submit, :reset, :hidden')
    $f.val('').attr('value', '').removeAttr('checked').removeAttr('selected')
    var $dropdowns = $form.find('select option')
    $dropdowns.removeAttr('selected')
  })

  // Change pagination on search results page
  $(function () {
    $('.arrow.previous a').text('Previous').addClass('previous-page')
    $('.arrow.next a').text('Next').addClass('next-page')
    $('body.search-results li.disabled.unavailable').html('<span class="ellipsis">…</span>')
  })

  // Wrap Twitter Hashtag list content in an addtional container for stying purposes
  $(function () {
    $('.component-twitter-sub-sections ul li').wrapInner('<span></span>')
  })

  // End jQuery functions
})
