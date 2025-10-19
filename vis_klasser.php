

<?php
require_once __DIR__ . '/db.php';

$sql = "SELECT klassekode, klassenavn, studiumkode FROM klasse ORDER BY klassekode";
$res = $conn->query($sql);
$klasser = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<!doctype htmlk
<html lang="no">
<head>
  <meta charset="utf-8">
  <title>Alle klasser</title>
  <style>
    /* Samme enkle stil som på Vis studenter */
    body{font-family:system-ui,Segoe UI,Roboto,Arial,sans-serif;margin:2rem;}
    a{color:#1a1a1a;text-decoration:none}
    a:hover{text-decoration:underline}
    table{border-collapse:collapse;width:100%;max-width:860px}
    th,td{border:1px solid #ddd;padding:.5rem;text-align:left}
    th{background:#fafafa}
  </style>
</head>
<body>
  <p><a href="index.php">← Tilbake</a></p>
  <h1>Alle klasser</h1>

  <?php if (empty($klasser)): ?>
    <p><strong>Ingen klasser registrert.</strong></p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Klassekode</th>
          <th>Klassenavn</th>
          <th>Studiumkode</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($klasser as $k): ?>
          <tr>
            <td><?= htmlspecialchars($k['klassekode']) ?></td>
            <td><?= htmlspecialchars($k['klassenavn']) ?></td>
            <td><?= htmlspecialchars($k['studiumkode']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</body>
</html>
