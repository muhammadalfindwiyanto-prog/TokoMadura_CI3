<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div><h4>A PHP Error was encountered</h4><p>Severity: <?= html_escape($severity) ?></p><p>Message: <?= html_escape($message) ?></p><p>Filename: <?= html_escape($filepath) ?></p><p>Line: <?= html_escape($line) ?></p></div>
