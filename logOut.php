<?
session_start();
session_destroy();
$new_url = 'index.php';
header("Location: ".$new_url);
exit;
?>