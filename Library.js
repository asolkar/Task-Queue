//  Copyright 2010 Mahesh Asolkar
//
//  Licensed under the Apache License, Version 2.0 (the "License");
//  you may not use this file except in compliance with the License.
//  You may obtain a copy of the License at
//
//      http://www.apache.org/licenses/LICENSE-2.0
//
//  Unless required by applicable law or agreed to in writing, software
//  distributed under the License is distributed on an "AS IS" BASIS,
//  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//  See the License for the specific language governing permissions and
//  limitations under the License.

var animation_speed = 'fast';
var hide_done = true;

//
// Based on task status, set button status
//
function update_button_properties () {
  // Count invisible items
  var invisible_queue_items = $(".queue_item").filter(function(){
    var match = ($(this).css("display") == "none");

    if ((hide_done == true) && ($(this).hasClass('tc_done') == true)) {
      match = false;
    }

    return match;
  }).length;
  // Count collapsed items
  var collapsed_queue_items = $(".queue_item_body").filter(function(){
    return ($(this).css("display") == "none");
  }).length;
  // Count expanded items
  var expanded_queue_items = $(".queue_item_body").filter(function(){
    return ($(this).css("display") != "none");
  }).length;

  // Disable 'Show All' button if all items are already showing
  if (invisible_queue_items == 0) {
    $("input#show_all_botton").attr("disabled", "disabled");
  } else {
    $("input#show_all_botton").removeAttr("disabled");
  }

  // Disable 'Expand All' button if all items are already expanded
  if (expanded_queue_items > 0) {
    $("input#collapse_all_botton").removeAttr("disabled");
  } else {
    $("input#collapse_all_botton").attr("disabled", "disabled");
  }

  // Disable 'Collapse All' button if all items are already collapsed
  if (collapsed_queue_items > 0) {
    $("input#expand_all_botton").removeAttr("disabled");
  } else {
    $("input#expand_all_botton").attr("disabled", "disabled");
  }

  // Uncomment following for debugging with FireBug
  // console.log ("Invisible items = " + invisible_queue_items);
  // console.log ("Collapsed items = " + collapsed_queue_items);
  // console.log ("Expanded items = " + expanded_queue_items);
}

//
// jQuery stuff
//
$(document).ready(function(){
  //
  // Handle various events
  //

  // Tags : collapse all tasks that don't match selected tag
  $(".queue_item_tag").click (function(){
    var class_list = $(this).attr('class');
    var selected_class = class_list.substr(class_list.indexOf("tc_"));

    $(".queue_item").filter(function(){
      var match = ($(this).hasClass(selected_class) == false);
      if ((hide_done == true) && ($(this).hasClass('tc_done') == true)) {
        match = false;
      }
      return match;
    }).hide(animation_speed, function(){
      update_button_properties()
    });
    $(".queue_item").filter(function(){
      var match = ($(this).hasClass(selected_class) == true);
      if ((hide_done == true) && ($(this).hasClass('tc_done') == true)) {
        match = false;
      }
      return match;
    }).show(animation_speed, function(){
      update_button_properties()
    });
  });

  // Id : Toggle collapse/expand a task when clicked on Id
  $(".queue_item_id").click (function(){
    $(this).parent().siblings().toggle(animation_speed, function(){
      update_button_properties()
    });
  });

  // Show All : Show all tasks
  $("#show_all_botton").click (function(){
    $(".queue_item").filter(function(){
        return !((hide_done == true) && ($(this).hasClass('tc_done') == true));
      }).show(animation_speed, function(){
      update_button_properties()
    });
  });

  // Collapse All : Collapse all visible tasks
  $("#collapse_all_botton").click (function(){
    $(".queue_item_body").hide(animation_speed, function(){
      update_button_properties()
    });
  });

  // Expand All : Expand all visible tasks
  $("#expand_all_botton").click (function(){
    $(".queue_item_body").show(animation_speed, function(){
      update_button_properties()
    });
  });

  // Toggle Done : Toggle the status of done task visibility
  $("#toggle_done_button").click (function(){
    hide_done = !hide_done;
    // console.log ("Hide done = " + hide_done);
    if (hide_done == true) {
      $(this).val('Show Done');
      $(".queue_item").filter(function(){
        return ($(this).hasClass('tc_done') == true);
      }).hide(animation_speed, function(){
        update_button_properties()
      });
    } else {
      $(this).val('Hide Done');
      $(".queue_item").filter(function(){
        return ($(this).hasClass('tc_done') == true);
      }).show(animation_speed, function(){
        update_button_properties()
      });
    }

  });

  //
  // Initially, set button status
  //
  if (hide_done == true) {
    $("#toggle_done_button").val('Show Done');
    $(".queue_item").filter(function(){
      return ($(this).hasClass('tc_done') == true);
    }).hide(animation_speed, function(){
      update_button_properties()
    });
  } else {
    $("#toggle_done_button").val('Hide Done');
    update_button_properties();
  }
});
