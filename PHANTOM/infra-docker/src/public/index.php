<?php
function testar(string $nome, callable $fn): string {
    try { return "<li>✅ $nome: " . $fn() . "</li>"; }
    catch (Throwable $e) { return "<li>❌ $nome: " . htmlspecialchars($e->getMessage()) . "</li>"; }
}
$opt = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 5];

$r = testar('MySQL', function () use ($opt) {
    $pdo = new PDO(
        sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', getenv('MYSQL_HOST'), getenv('MYSQL_DATABASE')),
        getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), $opt);
    return 'conectado (versão ' . $pdo->query('SELECT VERSION()')->fetchColumn() . ')';
});

$r .= testar('Oracle', function () use ($opt) {
    if (!extension_loaded('pdo_oci')) throw new RuntimeException('extensão pdo_oci não carregada');
    $pdo = new PDO('oci:dbname=' . getenv('ORACLE_DSN') . ';charset=AL32UTF8',
        getenv('ORACLE_USER'), getenv('ORACLE_PASSWORD'), $opt);
    return 'conectado (' . $pdo->query("SELECT banner FROM v\$version WHERE ROWNUM = 1")->fetchColumn() . ')';
});

$drivers = implode(', ', PDO::getAvailableDrivers());
?>
<!doctype html>
<html lang="pt-br"><head><meta charset="utf-8"><title>infra-web</title></head>
<body style="font-family:sans-serif">
  <h1>Infra Docker OK</h1>
  <p>PHP <?= PHP_VERSION ?> via Nginx + PHP-FPM</p>
  <p>Drivers PDO: <?= $drivers ?></p>
  <ul><?= $r ?></ul>
  <p><a href="http://localhost:8081" target="_blank">Abrir phpMyAdmin</a></p>
</body></html>
