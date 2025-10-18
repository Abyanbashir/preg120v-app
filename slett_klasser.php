?php
require_once __DIR__ . '/db.php';

$ok = null; $errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = $_POST['klassekode'] ?? '';
    if ($kode === '') {
        $errors[] = "Velg klasse å slette.";
    } else {
        $stmt = $conn->prepare("DELETE FROM klasse WHERE klassekode = ?");
        $stmt->bind_param("s", $kode);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $ok = "Klasse slettet.";
            } else {
                $errors[] = "Fant ikke klassen.";
            }
        } else {
            // Sannsynlig utenlandsnøkkel-feil (studenter peker på klassen)
            $errors[] = "Kan ikke slette. Finnes det studenter som peker på denne klassen?";
        }
        $stmt->close();
    }
}

$res = $conn->query("SELECT klassekode, klassenavn FROM klasse ORDER BY klassekode");
$klasser = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<!doctype html>
<html lang="no">
<head>
  <meta charset="utf-8">
  <title>Slett klasse</title>
</head>
<body>
  <p><a href="index.php">← Tilbake til hovedsiden</a></p>
  <h1>Slett klasse</h1>

  <?php if ($ok): ?><p style="color:green"><?= htmlspecialchars($ok) ?></p><?php endif; ?>
  <?php if ($errors): ?>
    <ul style="color:#b00020"><?php foreach ($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul>
  <?php endif; ?>

  <form method="post" onsubmit="return confirm('Slette valgt klasse?');">
    <label>Velg klasse</label><br>
    <select name="klassekode" required>
      <option value="">— Velg klasse —</option>
      <?php foreach ($klasser as $k): ?>
        <option value="<?= htmlspecialchars($k['klassekode']) ?>">
          <?= htmlspecialchars($k['klassekode'].' – '.$k['klassenavn']) ?>
        </option>
      <?php endforeach; ?>
    </select><br><br>
    <button type="submit">Slett</button>
  </form>
</body>
</html>