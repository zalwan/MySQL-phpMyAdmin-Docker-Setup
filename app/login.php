<?php
session_start();

// Demo users and hashed passwords
$users = [
    'admin' => '$2y$10$qUU9aWt6N4TOjgtzlYdWk.SWgrLfMLEz19BIsHoLYzJ6du17dq8b6', // password123
    'demo'  => '$2y$10$zbSewM2sP0dbRdqpJuEe3Owgh2BouqwS5jLBTPxl4OYDTd4rP8bVa', // demo1234
];

$error = '';

if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } elseif (isset($users[$username]) && password_verify($password, $users[$username])) {
        $_SESSION['user'] = [
            'username' => $username,
            'login_time' => time(),
        ];

        header('Location: index.php');
        exit;
    } else {
        $error = 'Kredensial tidak valid.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - MySQL Data Viewer</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background: radial-gradient(circle at 10% 20%, #0f172a, #111827 35%, #0b1220);
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center px-4">
  <div class="w-full max-w-md bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-8 shadow-2xl">
    <div class="mb-6 text-center">
      <p class="text-xs uppercase tracking-[0.3em] text-blue-200/80">Session Auth Demo</p>
      <h1 class="text-3xl font-bold text-white mt-2">Masuk ke Data Viewer</h1>
      <p class="text-sm text-gray-300 mt-2">Gunakan akun demo untuk melihat halaman terlindungi.</p>
    </div>

    <?php if ($error !== ''): ?>
      <div class="mb-4 rounded-lg border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm text-red-100">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <form method="post" class="space-y-5">
      <div>
        <label for="username" class="block text-sm font-semibold text-gray-200 mb-2">Username</label>
        <input type="text" id="username" name="username" required
               class="w-full rounded-lg border border-gray-600 bg-gray-800/70 px-4 py-3 text-white placeholder-gray-400 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
               placeholder="admin atau demo"
               value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
      </div>

      <div>
        <label for="password" class="block text-sm font-semibold text-gray-200 mb-2">Password</label>
        <input type="password" id="password" name="password" required
               class="w-full rounded-lg border border-gray-600 bg-gray-800/70 px-4 py-3 text-white placeholder-gray-400 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
               placeholder="password123 atau demo1234">
      </div>

      <button type="submit"
              class="w-full rounded-lg bg-blue-500 hover:bg-blue-600 transition px-4 py-3 font-semibold text-white shadow-lg shadow-blue-500/30">
        Masuk
      </button>
    </form>

    <div class="mt-6 rounded-lg border border-white/10 bg-white/5 p-4 text-sm text-gray-200">
      <p class="font-semibold text-white">Akun Demo</p>
      <div class="mt-2 grid grid-cols-1 gap-2">
        <div class="flex items-center justify-between rounded bg-gray-800/60 px-3 py-2">
          <span>admin</span>
          <code class="text-gray-300">password123</code>
        </div>
        <div class="flex items-center justify-between rounded bg-gray-800/60 px-3 py-2">
          <span>demo</span>
          <code class="text-gray-300">demo1234</code>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
