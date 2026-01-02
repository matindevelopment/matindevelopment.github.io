<?php
session_start();

// Predefined credentials
$username = 'Matin';
$hashedPassword = password_hash('Matin1388', PASSWORD_DEFAULT);

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $inputUsername = $_POST['username'] ?? '';
    $inputPassword = $_POST['password'] ?? '';

    if ($inputUsername === $username && password_verify($inputPassword, $hashedPassword)) {
        $_SESSION['loggedin'] = true;
    } else {
        $error = 'نام کاربری یا رمز عبور اشتباه است.';
    }
}

// Handle file deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_file'])) {
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        $fileToDelete = $_POST['delete_file'];
        $uploadDir = 'uploads/';
        $filePath = $uploadDir . $fileToDelete;
        if (file_exists($filePath) && !in_array($fileToDelete, array('.', '..'))) {
            unlink($filePath);
        }
        header('Location: admin.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>پنل ادمین - ورود</title>
  <link rel="icon" type="image/jpg" href="admin.png" />
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      margin: 0;
      overflow-x: hidden;
      font-family: 'Vazir', sans-serif;
    }
    canvas {
      position: fixed;
      top: 0;
      left: 0;
      z-index: -1;
      filter: blur(2px);
      opacity: 0.7;
    }
    @font-face {
      font-family: 'Vazir';
      src: url('https://cdn.fontcdn.ir/Fonts/Vazir/Vazir.woff2') format('woff2');
    }
    .file-item {
      transition: all 0.3s ease;
    }
    .file-item:hover {
      background-color: #1F2937;
      transform: scale(1.02);
    }
  </style>
</head>
<body class="bg-black text-white flex items-center justify-center min-h-screen">
  <canvas id="matrix"></canvas>
  <div class="container mx-auto p-4 max-w-md">
    <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) { ?>
      <div class="bg-gray-900 bg-opacity-80 p-6 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold text-center mb-6 text-green-400">ورود به پنل ادمین</h1>
        <form method="POST" class="space-y-4">
          <input type="hidden" name="login" value="1">
          <div>
            <label for="username" class="block text-sm font-medium text-gray-300">نام کاربری</label>
            <input type="text" id="username" name="username" class="mt-1 block w-full bg-gray-800 border border-gray-600 rounded-lg p-2 text-white focus:ring-green-500 focus:border-green-500" required>
          </div>
          <div>
            <label for="password" class="block text-sm font-medium text-gray-300">رمز عبور</label>
            <input type="password" id="password" name="password" class="mt-1 block w-full bg-gray-800 border border-gray-600 rounded-lg p-2 text-white focus:ring-green-500 focus:border-green-500" required>
          </div>
          <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full transition duration-300">ورود</button>
          <?php if ($error) { ?>
            <p class="mt-4 text-center text-red-500"><?php echo htmlspecialchars($error); ?></p>
          <?php } ?>
        </form>
      </div>
    <?php } else { ?>
      <h1 class="text-3xl font-bold text-center mb-6 text-green-400">پنل ادمین - فایل‌های آپلودشده</h1>
      <div class="bg-gray-900 bg-opacity-80 p-6 rounded-lg shadow-lg">
        <?php
        $uploadDir = 'uploads/';
        $files = array_diff(scandir($uploadDir), array('.', '..'));

        if (empty($files)) {
          echo '<p class="text-center text-gray-300">هیچ فایلی آپلود نشده است.</p>';
        } else {
          echo '<ul class="space-y-4">';
          foreach ($files as $file) {
            $filePath = $uploadDir . $file;
            $fileSize = round(filesize($filePath) / 1024, 2); // Size in KB
            echo '<li class="file-item bg-gray-800 p-4 rounded-lg flex justify-between items-center">';
            echo '<span class="text-gray-300">' . htmlspecialchars($file) . ' (' . $fileSize . ' KB)</span>';
            echo '<div class="flex space-x-2">';
            echo '<a href="' . $filePath . '" download class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-full transition duration-300">دانلود</a>';
            echo '<form method="POST" onsubmit="return confirm(\'آیا مطمئن هستید که می‌خواهید این فایل را حذف کنید؟\');">';
            echo '<input type="hidden" name="delete_file" value="' . htmlspecialchars($file) . '">';
            echo '<button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-full transition duration-300">حذف</button>';
            echo '</form>';
            echo '</div>';
            echo '</li>';
          }
          echo '</ul>';
        }
        ?>
        <form method="POST" action="logout.php" class="mt-6">
          <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full transition duration-300">خروج</button>
        </form>
      </div>
    <?php } ?>
  </div>
  <script>
    // Matrix Background
    const canvas = document.getElementById('matrix');
    const ctx = canvas.getContext('2d');
    canvas.height = window.innerHeight;
    canvas.width = window.innerWidth;
    const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    const fontSize = 14;
    const columns = canvas.width / fontSize;
    const drops = Array(Math.floor(columns)).fill(1);

    function draw() {
      ctx.fillStyle = 'rgba(0, 0, 0, 0.05)';
      ctx.fillRect(0, 0, canvas.width, canvas.height);
      ctx.fillStyle = '#0F0';
      ctx.font = fontSize + 'px monospace';
      for (let i = 0; i < drops.length; i++) {
        const text = chars.charAt(Math.floor(Math.random() * chars.length));
        ctx.fillText(text, i * fontSize, drops[i] * fontSize);
        if (drops[i] * fontSize > canvas.height && Math.random() > 0.975) drops[i] = 0;
        drops[i]++;
      }
    }
    setInterval(draw, 33);

    window.addEventListener('resize', () => {
      canvas.height = window.innerHeight;
      canvas.width = window.innerWidth;
    });
  </script>
</body>
</html>