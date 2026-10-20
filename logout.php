<?php
require_once "session.php";

$_SESSION = array();
$cookie = session_get_cookie_params();
setcookie(session_name(), '', time() - 3600, $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httponly']);
session_destroy();

session_start();
$_SESSION['success'] = "You have been logged out";
header("Location: login.php");
return;
