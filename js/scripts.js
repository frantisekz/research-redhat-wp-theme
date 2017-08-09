ids = [null, null]; /* [city_id, tag_id]*/
default_texts = {university_drop_down: jQuery("#university_drop_down").text(), city_drop_down: jQuery("#city_drop_down").text(), spec_drop_down: jQuery("#spec_drop_down").text()};

function mark_as_active(active_id, inactive_id) {
  if (active_id != 0) {
    jQuery("#trigger-" + active_id).css('background', '#cc0000');
    jQuery("#trigger-" + active_id).css('color', '#fff');
    set_drop_text(active_id);
  }
  if (inactive_id != 0) {
    /* Call reset_drop_text() from parrent function as we don't have enough data that deep! */
    jQuery("#trigger-" + inactive_id).css('background', 'white');
    jQuery("#trigger-" + inactive_id).css('color', 'black');
  }
  if ((active_id == 0) && (inactive_id != 0)) {
    reset_drop_text(inactive_id);
  }
}

function set_drop_text(data_id) {
  text_data = jQuery("#trigger-" + data_id).text();
  field_id = jQuery("#trigger-" + data_id).attr("class");
  jQuery("#" + field_id).html(text_data + '<span class="caret"></span>');
}

function reset_drop_text(data_id) {
  field_id = jQuery("#trigger-" + data_id).attr("class");
  jQuery("#" + field_id).html(default_texts[field_id] + '<span class="caret"></span>');
}

function trigger_box(id, place_trigger) {
  if (place_trigger == 1) {
    /* Code path for city click */
    if (id == ids[0]) {
      /* User selected the same city, assume deselect */
      mark_as_active(0, ids[0]);
      ids[0] = null;
      if (ids[1] != null) {
        mark_as_active(ids[1], 0);
        setTimeout(function() {
          reset_filter(0);
          jQuery('.project-box:visible').not(':has(#' + ids[1] + ')').css('display', 'none');
        }, 5);
      }
      else {
        setTimeout(function() {
          reset_filter(0);
        }, 5);
      }
    }
    else if (id != ids[0]) {
      if (ids[0] != null) {
        mark_as_active(id, ids[0]);
      }
      else {
        mark_as_active(id, 0);
      }
      ids[0] = id;
      setTimeout(function() {
        reset_filter(0);
        jQuery('.project-box').not(':has(#' + ids[0]  + ')').css('display', 'none');
        if (ids[1] != null) {
          jQuery('.project-box').not(':has(#' + ids[1]  + ')').css('display', 'none');
        }
      }, 5);
    }
  }
  else {
    if (id == ids[1]) {
      /* User selected the same tag, assume deselect */
      mark_as_active(0, ids[1]);
      ids[1] = null;
      setTimeout(function() {
        reset_filter(0);
        if (ids[0] != null) {
          jQuery('.project-box').not(':has(#' + ids[0]  + ')').css('display', 'none');
        }
      }, 5);
    }
    else if (id != ids[1]) {
      /* Code path for tag click */
      /* FIXME: If user selected another tag, we sadly do not support that yet, so just pick the new tag */
      mark_as_active(0, ids[1]);
      ids[1] = id;
      mark_as_active(ids[1], 0);
      reset_filter(0);
      if (ids[0] != null) {
        mark_as_active(ids[0], 0);
        jQuery('.project-box').not(':has(#' + ids[0]  + ')').css('display', 'none');
      }
      jQuery('.project-box:visible').not(':has(#' + ids[1] + ')').css('display', 'none');
    }
  }
}

function reset_filter(flush_array) {
  if (flush_array == 1) {
    reset_drop_text(ids[0]);
    reset_drop_text(ids[1]);
    ids = [null, null];
    jQuery('.university_drop_down').css('background', 'white')
    jQuery('.university_drop_down').css('color', 'black')
    jQuery('.city_drop_down').css('background', 'white')
    jQuery('.city_drop_down').css('color', 'black')
    jQuery('.spec_drop_down').css('background', 'white')
    jQuery('.spec_drop_down').css('color', 'black')
  }
  jQuery('.project-box').css('display', 'initial');
}

function events_scrollable() {
  jQuery('.eventswidget').css('max-height', jQuery('.sidebar').height() - jQuery('.all-events').height() - 10);
}

jQuery(document).ready(function() {
  events_scrollable();
  jQuery('#enlrg').click(function() {
    if (jQuery(window).width() > 1920) {
      jQuery('.front-sidebar').animate({right: '15%'});
    }
    else {
      jQuery('.front-sidebar').animate({right: '27%'});
    }
    jQuery('.sidebar').animate({right: '0%'});
    jQuery('#enlrg').css('display', 'none');
    jQuery('#ensml').css('display', 'inherit');
  });

  jQuery('#ensml').click(function() {
    if (jQuery(window).width() < 2560) {
      jQuery('.front-sidebar').animate({right: '0%'});
      jQuery('.sidebar').animate({right: '-27%'});
      jQuery('#enlrg').css('display', 'inherit');
      jQuery('#ensml').css('display', 'none');
    }
  });
});

var eventFired = 0;

jQuery(window).on('resize', function() {
if (!eventFired) {
  events_scrollable();
  if (jQuery(window).width() > 2559) {
      if (jQuery('.arrow').css('display') != 'none') {
        jQuery("#enlrg").simulate("click");
        jQuery('.arrow').css('display', 'none');
      }
  }
  else if (jQuery('.arrow').css('display') != 'inherit') {
    jQuery("#ensml").simulate("click");
    jQuery('.arrow').css('display', 'inherit');
  }
  }
});
