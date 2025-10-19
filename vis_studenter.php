
<?php
require_once __DIR__ . '/db.php';

$sql = "SELECT s.brukernavn, s.fornavn, s.etternavn, s.klassekode, k.klassenavn
        FROM student s
        JOIN klasse k ON s.klassekode = k.klassekode
        ORDER BY s.brukernavn";
$res = $conn->query($sql);
$studenter = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<!doctype html>
<html lang="no">
<head>
  <meta charset="utf-8">
  <title>Alle studenter</title>
  <style>
    body{font-family:system-ui,Segoe UI,Roboto,Arial,sans-serif;margin:2rem;}
    table{border-collapse:collapse;width:820px;max-width:100%}
    th,td{border:1px solid #ddd;padding:.5rem;text-align:left}
    th{background:#f6f6f6}
    a{color:#1a1a1a}
  </style>
</head>
<body>
  <p><a href="index.php">← Tilbake</a></p>
  <h1>Alle studenter</h1>
  <table>
    <thead>
      <tr><th>Brukernavn</th><th>Fornavn</th><th>Etternavn</th><th>Klassekode</th><th>Klassenavn</th></tr>
    </thead>
    <tbody>
      <?php foreach ($studenter as $s): ?>
        <tr>
          <td><?= htmlspecialchars($s['brukernavn']) ?></td>
          <td><?= htmlspecialchars($s['fornavn']) ?></td>
          <td><?= htmlspecialchars($s['etternavn']) ?></td>
          <td><?= htmlspecialchars($s['klassekode']) ?></td>
          <td><?= htmlspecialchars($s['klassenavn']) ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$studenter): ?>
        <tr><td colspan="5">Ingen studenter registrert.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>