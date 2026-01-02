<?php
session_start();

$username = 'Matin';
$hashedPassword = password_hash('matindevelopment', PASSWORD_DEFAULT);
$uploadDir = 'uploads/';

$error = '';
$files = [];

/* ---------- Login ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';

    if ($u === $username && password_verify($p, $hashedPassword)) {
        $_SESSION['loggedin'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error = 'نام کاربری یا رمز عبور اشتباه است.';
    }
}

/* ---------- Delete file ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_file'])) {
    if (!empty($_SESSION['loggedin'])) {
        $file = basename($_POST['delete_file']);
        $path = $uploadDir . $file;
        if (file_exists($path)) {
            unlink($path);
        }
        header('Location: admin.php');
        exit;
    }
}

/* ---------- Load files ---------- */
if (!empty($_SESSION['loggedin']) && is_dir($uploadDir)) {
    $files = array_diff(scandir($uploadDir), ['.', '..']);
}

/* ---------- Render HTML ---------- */
include 'admin.html';
