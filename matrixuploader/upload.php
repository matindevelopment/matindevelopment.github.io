<?php
header('Content-Type: application/json');

$uploadDir = 'uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (!isset($_FILES['file'])) {
    echo json_encode(['error' => 'هیچ فایلی انتخاب نشده است.']);
    exit;
}

$file = $_FILES['file'];
$fileName = $file['name'];
$fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

$forbiddenExtensions = ['php', 'html'];
if (in_array($fileExtension, $forbiddenExtensions)) {
    echo json_encode(['error' => 'فایل‌های PHP و HTML مجاز نیستند.']);
    exit;
}

$destination = $uploadDir . uniqid() . '_' . $fileName;

if (move_uploaded_file($file['tmp_name'], $destination)) {
    echo json_encode(['message' => "فایل $fileName با موفقیت آپلود شد!"]);
} else {
    echo json_encode(['error' => 'خطا در آپلود فایل.']);
}
?>