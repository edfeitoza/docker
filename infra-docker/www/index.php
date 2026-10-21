<?php
// Painel da infra: status do MySQL + lista das aplicações em ./www
try {
    $pdo = new PDO(
        sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', getenv('MYSQL_HOST'), getenv('MYSQL_DATABASE')),
        getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'),
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 5]
    );
    $mysql = '✅ conectado (versão ' . $pdo->query('SELECT VERSION()')->fetchColumn() . ')';
} catch (Throwable $e) {
    $mysql = '❌ ' . htmlspecialchars($e->getMessage());
}

$apps = array_filter(glob(__DIR__ . '/*', GLOB_ONLYDIR), fn($d) =>
    is_file("$d/index.php") || is_file("$d/index.html") || is_file("$d/public/index.php"));
?>
<!doctype html>
<html lang="pt-br"><head><meta charset="utf-8"><title>infra-web</title>
<style>body{font-family:sans-serif;max-width:760px;margin:2rem auto}td,th{padding:.4rem .8rem;border-bottom:1px solid #ddd;text-align:left}</style>
</head><body>
  <h1>Infra Docker — infra-web</h1>
  <table>
    <tr><th>PHP</th><td><?= PHP_VERSION ?> (<?= php_sapi_name() ?>)</td></tr>
    <tr><th>Servidor</th><td><?= htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'nginx') ?></td></tr>
    <tr><th>Drivers PDO</th><td><?= implode(', ', PDO::getAvailableDrivers()) ?></td></tr>
    <tr><th>MySQL</th><td><?= $mysql ?></td></tr>
  </table>
  <p>🔎 <a href="/phpinfo.php">phpinfo()</a> · 🗄️ <a href="http://localhost:8081" target="_blank">phpMyAdmin</a></p>

  <h2>Aplicações (<?= count($apps) ?>)</h2>
  <ul>
  <?php foreach ($apps as $d): $n = basename($d);
        $url = is_file("$d/public/index.php") ? "/$n/public/" : "/$n/"; ?>
    <li><a href="<?= $url ?>"><?= htmlspecialchars($n) ?></a></li>
  <?php endforeach; ?>
  </ul>
  <p><small>Para publicar uma aplicação, crie uma pasta em <code>./www/</code> no host.</small></p>
</body></html>
