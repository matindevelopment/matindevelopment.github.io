<?php
// پاک کردن تمام کوکی‌ها
if (count($_COOKIE) > 0) {
    foreach ($_COOKIE as $key => $value) {
        // تنظیم زمان انقضا به گذشته برای حذف کوکی
        setcookie($key, '', time() - 3600, '/');
    }
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>در حال خروج از سیستم</title>
</head>
<body>
    <script>
        // نمایش پیام موفقیت در یک پنجره مرورگر
        alert("از سیستم خارج شدید!");
        // هدایت به صفحه اصلی پس از تأیید
        window.location.href = "admin.php"; // صفحه اصلی سایت را اینجا مشخص کنید
    </script>
</body>
</html>