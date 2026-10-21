<?php
// Aplicação de exemplo — copie esta pasta para criar novas apps
$pdo = new PDO(
    sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', getenv('MYSQL_HOST'), getenv('MYSQL_DATABASE')),
    getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'),
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
$linhas = $pdo->query('SELECT id, status, criado_em FROM healthcheck ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="pt-br"><head><meta charset="utf-8"><title>app-exemplo</title></head>
<body style="font-family:sans-serif">
  <h1>app-exemplo</h1>
  <p>Lendo a tabela <code>app.healthcheck</code> do MySQL:</p>
  <table border="1" cellpadding="6">
    <tr><th>id</th><th>status</th><th>criado_em</th></tr>
    <?php foreach ($linhas as $l): ?>
      <tr><td><?= $l['id'] ?></td><td><?= htmlspecialchars($l['status']) ?></td><td><?= $l['criado_em'] ?></td></tr>
    <?php endforeach; ?>
  </table>
  <p><a href="/">← voltar ao painel</a></p>
</body></html>
