<?php
require 'config/db_connect.php';
try {
    $res = db()->query("SHOW TABLES LIKE 'contact_messages'");
    if ($res->num_rows > 0) {
        echo "TABLE EXISTS";
    } else {
        echo "TABLE MISSING";
    }
} catch (Exception $e) {
    echo "DB ERROR: " . $e->getMessage();
}
