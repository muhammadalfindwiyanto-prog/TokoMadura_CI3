<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>PHP Error</title></head><body><h1>A PHP Error was encountered</h1><p>Severity: <?= html_escape($severity) ?></p><p>Message: <?= html_escape($message) ?></p><p>Filename: <?= html_escape($filepath) ?></p><p>Line: <?= html_escape($line) ?></p></body></html>
