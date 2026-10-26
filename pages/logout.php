<?php
require_once dirname(__FILE__) . '/../includes/config.php';

// Destroy session
session_destroy();

// Redirect to login page
header("Location: " . SITE_URL . "pages/index.php");
exit();
?>
