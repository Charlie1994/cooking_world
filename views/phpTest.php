<?php
error_reporting(-1); // reports all errors
ini_set("display_errors", "1"); // shows all errors
ini_set("log_errors", 1);
ini_set("error_log", "/tmp/php-error.log");
date_default_timezone_set("Australia/Brisbane");
//echo preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', "localhost\/asdj//afesj\/index.php?{url}=1");
echo PHP_VERSION;