Task Queue
==========

This is a simple tool to display task list (queue). It is a read-only too. It does not
edit/update the task list.

Installation
------------

To install, just put all the files in a directory published via a web server. Make
sure `.php` files are bound to PHP5 and `index.php` is treated as the index of the
directory.

Usage
-----

* Task queue items are stored in a text file - say `General.queue`. Each task in the
  queue file is separated by a separator - say `------ Item -----`. Both the file
  name and the separator can be configured in the `config.inc` file.

* `config.inc` must be created at the time of installation. A good starting point
  is the `sample-config.inc` provided in the package.

* A special *Tags:* line can be added to the top of the item to associate tags
  with tasks. This is a comma-separated list of tags. Tags can have multiple words.

* The first non-blank line after the *Tags:* line is used as the task ID. If this
  line is too long, only first 70 characters are used.
