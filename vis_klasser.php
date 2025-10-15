<?php require 'db.php'; $rows=$conn->query("SELECT klassekode,klassenavn,studiumkode FROM klasse ORDER BY klassekode"); ?>
<h1>Alle klasser</h1><p><a href="index.php">Tilbake</a></p>
<table border="1" cellpadding="6"><tr><th>Klassekode</th><th>Klassenavn</th><th>Studiumkode</th></tr>
<?php foreach($rows as $r): ?>
<tr><td><?=h($r['klassekode'])?></td><td><?=h($r['klassenavn'])?></td><td><?=h($r['studiumkode'])?></td></tr>
<?php endforeach; ?></table>
