<?php
session_start();        // start session
session_unset();        // remove all session variables
session_destroy();      // destroy the session

// redirect to home page
header("Location: index.php");
exit;
?>
