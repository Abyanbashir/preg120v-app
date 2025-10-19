
<?php
require_once __DIR__ . '/db.php';
$res = $conn->query("SELECT klassekode, klassenavn, studiumkode FROM klasse ORDER BY klassekode");
$klasser = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<!doctype html>
<html lang="no">
<head>
  <meta charset="utf-8">
  <title>Alle klasser</title>
  <style>
    table{border-collapse:collapse} td,th{border:1px solid #ddd;padding:.5rem}
    th{background:#f6f6f6}
  </style>
</head>
<body>
  <p><a href="index.php">← Tilbake til hovedsiden</a></p>
  <h1>Alle klasser</h1>
  <table>
    <thead><tr><th>Klassekode</th><th>Klassenavn</th><th>Studiumkode</th></tr></thead>
    <tbody>
      <?php foreach ($klasser as $k): ?>
      <tr>
        <td><?= htmlspecialchars($k['klassekode']) ?></td>
        <td><?= htmlspecialchars($k['klassenavn']) ?></td>
        <td><?= htmlspecialchars($k['studiumkode']) ?></td>
      </tr>
      <?php endforeach; ?>
      <?php if (!$klasser): ?><tr><td colspan="3">Ingen data.</td></tr><?php endif; ?>
    </tbody>
  </table>
</body>
</html>
