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
// Classes used in this project
//
class queue {
  var $__config, $__status;
  var $items = array ();

  function queue (&$__config, &$__status) {
    $this->__config = $__config;
    $this->__status = $__status;

    $this->__status['raw_queue_text'] = file_get_contents ($this->__config['queue_file']);

    $raw_array = preg_split (
                  '/^' . $this->__config['queue_separator'] . '$/ms',
                  $this->__status['raw_queue_text']);

    $id = 1;
    foreach ($raw_array as $item) {
      if (preg_match('/^\s*$/', $item)) {
        // skip empties
      } else {
        $item_obj = new queue_item ($id, $item);
        array_push ($this->items, $item_obj);
        $id ++;
      }
    }
  }

  function pretty_print () {
    $pretty_txt =  "<div class='queue_item_list'>\n";
    foreach ($this->items as $item_obj) {
      $pretty_txt .= $item_obj->pretty_print();
    }
    $pretty_txt .= "</div> <!-- queue_item_list -->\n";

    return $pretty_txt;
  }
}

class queue_item {
  var $id;
  var $raw_text;
  var $tags = array ();
  var $body = '';
  var $pretty_text = '';

  function queue_item ($id, $raw_text) {
    $this->id = $id;
    $this->raw_text = $raw_text;
    $this->parse_raw_text ();
  }

  function tag_wrap ($tag) {
      $tag_class = preg_replace ('/\W/', '_', $tag);
      return "<div class='queue_item_tag tc_$tag_class'>$tag</div>";
  }

  function parse_raw_text () {
    //
    // Clean up the raw text by stripping white space
    //
    $this->raw_text = preg_replace ('/^\s+/', '', $this->raw_text);
    $this->raw_text = preg_replace ('/\s+$/', '', $this->raw_text);

    //
    // Get tags from the 'Tags:' line
    //
    preg_match ('/^Tags\s*:\s*(.*)$/m', $this->raw_text, $raw_tags);
    $this->tags = preg_split ('/\s*,\s*/', $raw_tags[1]);

    $pretty_tags = implode (" ", array_map (array($this, 'tag_wrap'), $this->tags));

    //
    // Now nuke the 'Tags:' line, and clean up again
    //
    $this->raw_text = preg_replace ('/^Tags\s*:.*$/m', '', $this->raw_text);
    $this->raw_text = preg_replace ('/^\s+/', '', $this->raw_text);
    $this->raw_text = preg_replace ('/\s+$/', '', $this->raw_text);

    $tag_classes = '';
    foreach ($this->tags as $tag) {
      $tag = preg_replace ('/\W/', '_', $tag);
      $tag_classes .= ' tc_' . $tag;
    }

    //
    // Make an appropriate Id
    //
    list($id_txt) = preg_split ('/\n/ms', $this->raw_text);
    if (strlen ($id_txt) > 70) {
      $id_txt = substr ($id_txt, 0, 70) . "...";
    }

    $this->pretty_text = <<<EOD
  <div class='queue_item {$tag_classes}' id='item_{$this->id}'>
    <div class='queue_item_head'>
      <div class='queue_item_id'>{$this->id} : {$id_txt}</div>
      <div class='queue_item_tag_wrap'>
        {$pretty_tags}
      </div>
      <br class='separator'>
    </div> <!-- queue_item_head -->
    <div class='queue_item_body'>
      <pre>$this->raw_text</pre>
    </div> <!-- queue_item_body -->
  </div> <!-- queue_item -->
EOD;
  }

  function pretty_print () {
    return $this->pretty_text;
  }
}

?>
