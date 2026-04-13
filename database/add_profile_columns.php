<?php
require __DIR__ . '/../config/db_connect.php';
$c = db();
$c->query('ALTER TABLE users ADD COLUMN address VARCHAR(255) NULL');
$c->query('ALTER TABLE users ADD COLUMN username VARCHAR(50) NULL');
echo "Schema updated successfully\n";
