<?php
//  Copyright 2008 Mahesh Asolkar
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

//
// Include library of functions
//
include 'classes.php';

$__config['queue_file'] = 'Sata.queue';
$__config['queue_separator'] = '------ Item ------';

$__status['page_title'] = 'My Task Queue';
$__status['raw_queue_text'] = '';

$__queue = new queue($__config, $__status);

//
// Functions
//
function http_doc_type() {
  echo '<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
}

?>

<?php http_doc_type() ?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="StyleSheet" href="style.css" type="text/css" title="Design Style">
<title><?php echo "{$__status['page_title']}" ?></title>

</head>
<body>
<div id='shrink_wrapper_shell'>
<div id='shrink_wrapper'>
<h1>My Task Queue</h1>
<input type="button" class="queue_button" id="show_all_botton" name="show_all" value="Show All" />
<input type="button" class="queue_button" id="collapse_all_botton" name="collapse_all" value="Collapse All" />
<input type="button" class="queue_button" id="expand_all_botton" name="expand_all" value="Expand All" />
<?php

echo $__queue->pretty_print();

?>

</div> <!-- shrink_wrapper -->
</div> <!-- shrink_wrapper_shell -->
</body>

<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
//<![CDATA[
var invisible_queue_items = 0;
var collapsed_queue_items = 0;
var expanded_queue_items = 0;

function update_button_properties () {
  invisible_queue_items = $(".queue_item").filter(function(){
    return ($(this).css("display") == "none");
  }).length;
  collapsed_queue_items = $(".queue_item_body").filter(function(){
    return ($(this).css("display") == "none");
  }).length;
  expanded_queue_items = $(".queue_item_body").filter(function(){
    return ($(this).css("display") != "none");
  }).length;

  if (invisible_queue_items == 0) {
    $("input#show_all_botton").attr("disabled", "disabled");
  } else {
    $("input#show_all_botton").removeAttr("disabled");
  }
  if (expanded_queue_items > 0) {
    $("input#collapse_all_botton").removeAttr("disabled");
  } else {
    $("input#collapse_all_botton").attr("disabled", "disabled");
  }
  if (collapsed_queue_items > 0) {
    $("input#expand_all_botton").removeAttr("disabled");
  } else {
    $("input#expand_all_botton").attr("disabled", "disabled");
  }
  // console.log ("Invisible items = " + invisible_queue_items);
  // console.log ("Collapsed items = " + collapsed_queue_items);
  // console.log ("Expanded items = " + expanded_queue_items);
}

//
// jQuery stuff
//
$(document).ready(function(){
  $(".queue_item_tag").click (function(){
    var class_list = $(this).attr('class');
    var selected_class = class_list.substr(class_list.indexOf("tc_"));

    $(".queue_item").hide();
    $(".queue_item." + selected_class).show('fast');
    update_button_properties();
  });
  $(".queue_item_id").click (function(){
    $(this).parent().siblings().toggle('fast');
    update_button_properties();
  });
  $("#show_all_botton").click (function(){
    $(".queue_item").show('fast');
    update_button_properties();
  });
  $("#collapse_all_botton").click (function(){
    $(".queue_item_body").hide('fast');
    update_button_properties();
  });
  $("#expand_all_botton").click (function(){
    $(".queue_item_body").show('fast');
    update_button_properties();
  });
  // $("body").click (function(){
  //   update_button_properties();
  // });
});


//]]>
</script>

</html>

<?php

?>
