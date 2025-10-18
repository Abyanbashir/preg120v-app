<?php
require_once __DIR__ . '/db.php';

$ok = $err = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = $_POST['klassekode'] ?? '';
    if ($kode === '') {
        $err = "Velg en klasse å slette.";
    } else {
        $stmt = $conn->prepare("DELETE FROM klasse WHERE klassekode = ?");
        $stmt->bind_param("s", $kode);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) $ok = "Klasse slettet.";
            else $err = "Fant ikke klassen.";
        } else {
            // mest sannsynlig pga. fremmednøkkel fra student
            $err = "Kan ikke slette: finnes det studenter knyttet til denne klassen?";
        }
        $stmt->close();
    }
}

$list = $conn->query("SELECT klassekode, klassenavn FROM klasse ORDER BY klassekode");
$klasser = $list ? $list->fetch_all(MYSQLI_ASSOC) : [];
?>
<!doctype html>
<html lang="no">
<head>
  <meta charset="utf-8">
  <title>Slett klasse</title>
  <style>
    body{font-family:system-ui,Segoe UI,Roboto,Arial,sans-serif;margin:2rem;}
    select,button{padding:.6rem;border-radius:8px;border:1px solid #ccc;min-width:320px}
    .row{margin:.6rem 0}
    .msg{padding:.7rem;border-radius:8px;margin:.7rem 0;max-width:520px}
    .ok{background:#e8f7ee;border:1px solid #9cd7b4}
    .err{background:#fdeaea;border:1px solid #f2a3a3}
    a{color:#1a1a1a}
  </style>
</head>
<body>
  <p><a href="index.php">← Tilbake</a></p>
  <h1>Slett klasse</h1>

  <?php if ($ok): ?><div class="msg ok"><?= htmlspecialchars($ok) ?></div><?php endif; ?>
  <?php if ($err): ?><div class="msg err"><?= htmlspecialchars($err) ?></div><?php endif; ?>

  <form method="post" onsubmit="return confirm('Slette valgt klasse?')">
    <div class="row">
      <select name="klassekode" required>
        <option value="">— Velg klasse —</option>
        <?php foreach ($klasser as $k): ?>
          <option value="<?= htmlspecialchars($k['klassekode']) ?>">
            <?= htmlspecialchars($k['klassekode'].' – '.$k['klassenavn']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="row"><button type="submit">Slett</button></div>
  </form>
</body>
</html>
