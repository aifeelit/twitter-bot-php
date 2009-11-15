<?
// logtail.php
$cmd = "tail -10 /var/www/twitter/logger.log";
exec("$cmd 2>&1", $output);
foreach($output as $outputline) {
echo ("$outputline\n");
}
?>