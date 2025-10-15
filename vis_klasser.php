
<?php require_once __DIR__ . '/db.php'; ?>
<!doctype html>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">
<h1>Alle klasser</h1>
<p><a href="index.php">Tilbake</a></p>
<?php $rows = $conn->query("SELECT klassekode, klassenavn, studiumkode FROM klasse ORDER BY klassekode"); ?>
<table>
  <thead>
    <tr><th>Klassekode</th><th>Klassenavn</th><th>Studiumkode</th></tr>
  </thead>
  <tbody>
    <?php foreach($rows as $r): ?>
      <tr>
        <td><?= h($r['klassekode']) ?></td>
        <td><?= h($r['klassenavn']) ?></td>
        <td><?= h($r['studiumkode']) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
