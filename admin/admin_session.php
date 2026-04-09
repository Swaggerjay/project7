<?php
// admin/admin_session.php
require_once __DIR__ . '/../config/session_bootstrap.php';

$adminName = $_SESSION['admin_name'] ?? null;
