<?php
// Testa sessão, storage e log persistentes
session_start();
$_SESSION['visitas'] = ($_SESSION['visitas'] ?? 0) + 1;

$dir = '/var/www/storage/teste';
@mkdir($dir, 0775, true);
$arquivo = "$dir/contador.txt";
$total = (int)@file_get_contents($arquivo) + 1;
file_put_contents($arquivo, $total);

error_log("teste-persistencia: acesso #$total");
?>
<!doctype html>
<html lang="pt-br"><head><meta charset="utf-8"><title>teste-persistencia</title></head>
<body style="font-family:sans-serif">
  <h1>Teste de persistência</h1>
  <ul>
    <li>Sessão (<code>/var/lib/php/sessions</code>): você visitou <b><?= $_SESSION['visitas'] ?></b> vez(es)</li>
    <li>Storage (<code><?= $arquivo ?></code>): total geral de acessos <b><?= $total ?></b></li>
    <li>Log (<code>/var/log/php/php_errors.log</code>): acesso registrado</li>
  </ul>
  <p>Rode <code>docker compose down && docker compose up -d</code> e recarregue: os números continuam.</p>
  <p><a href="/">← voltar ao painel</a></p>
</body></html>
