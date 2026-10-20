<?php
require_once "session.php";

if (!isset($_SESSION['user_id'])) {
    if (!isset($_SESSION['error'])) {
        $_SESSION['error'] = "Please login first";
    }
    header("Location: login.php");
    exit();
}
