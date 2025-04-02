<?php
session_start();
$_SESSION['test'] = 'OK';
echo session_id();
var_dump($_SESSION);
?>