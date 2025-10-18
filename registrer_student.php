<?php
require_once __DIR__ . '/db.php';

$ok = $err = null;

// til listeboks
$res = $conn->query("SELECT klassekode, klassenavn FROM klasse ORDER BY klassekode");
$klasser = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bn  = trim($_POST['brukernavn'] ?? '');
    $fn  = trim($_POST['fornavn'] ?? '');
    $en  = trim($_POST['etternavn'] ?? '');
    $kk  = trim($_POST['klassekode'] ?? '');

    if ($bn === '' || strlen($bn) > 7) $err = "Ugyldig brukernavn (maks 7 tegn).";
    elseif ($fn === '') $err = "Fornavn må fylles ut.";
    elseif ($en === '') $err = "Etternavn må fylles ut.";
    elseif ($kk === '') $err = "Velg klassekode.";
    else {
        $stmt = $conn->prepare("INSERT INTO student (brukernavn, fornavn, etternavn, klassekode) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $bn, $fn, $en, $kk);
        if ($stmt->execute()) {
            $ok = "Student registrert.";
        } else {
            $err = "Kunne ikke lagre (duplikat brukernavn eller ugyldig klassekode).";
        }
        $stmt->close();
    }
}
?>
<!doctype html>
<html lang="no">
<head>
  <meta charset="utf-8">
  <title>Registrer student</title>
  <style>
    body{font-family:system-ui,Segoe UI,Roboto,Arial,sans-serif;margin:2rem;}
    input,select,button{padding:.6rem;border-radius:8px;border:1px solid #ccc;width:320px}
    .row{margin:.5rem 0}
    .msg{padding:.7rem;border-radius:8px;margin:.7rem 0;max-width:520px}
    .ok{background:#e8f7ee;border:1px solid #9cd7b4}
    .err{background:#fdeaea;border:1px solid #f2a3a3}
    a{color:#1a1a1a}
  </style>
</head>
<body>
  <p><a href="index.php">← Tilbake</a></p>
  <h1>Registrer student</h1>

  <?php if ($ok): ?><div class="msg ok"><?= htmlspecialchars($ok) ?></div><?php endif; ?>
  <?php if ($err): ?><div class="msg err"><?= htmlspecialchars($err) ?></div><?php endif; ?>

  <form method="post">
    <div class="row"><label>Brukernavn</label><br>
      <input type="text" name="brukernavn" maxlength="7" required></div>
    <div class="row"><label>Fornavn</label><br>
      <input type="text" name="fornavn" maxlength="50" required></div>
    <div class="row"><label>Etternavn</label><br>
      <input type="text" name="etternavn" maxlength="50" required></div>
    <div class="row"><label>Klasse</label><br>
      <select name="klassekode" required>
        <option value="">— Velg klasse —</option>
        <?php foreach ($klasser as $k): ?>
          <option value="<?= htmlspecialchars($k['klassekode']) ?>">
            <?= htmlspecialchars($k['klassekode'].' – '.$k['klassenavn']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="row"><button type="submit">Registrer</button></div>
  </form>
</body>
</html>
