<?php require 'db.php'; $res=$conn->query("SELECT * FROM klasse ORDER BY klassekode"); ?>
<!doctype html><meta charset="utf-8"><link rel="stylesheet" href="style.css"><h1>Alle klasser</h1>
<p><a href="index.php">Tilbake</a></p>
<table>
  <thead><tr><th>Klassekode</th><th>Klassenavn</th><th>Studiumkode</th></tr></thead>
  <tbody>
    <?php foreach($res as $r): ?>
      <tr><td><?=h($r['klassekode'])?></td><td><?=h($r['klassenavn'])?></td><td><?=h($r['studiumkode'])?></td></tr>
    <?php endforeach; ?>
  </tbody>
</table>
