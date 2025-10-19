
<?php
// MARKER: slett_klasse.php v4
require_once __DIR__ . '/db.php';

$ok = $err = null;

// Funksjon for å hente klasser (slik at vi kan oppdatere listen etter sletting)
function hentKlasser(mysqli $conn): array {
    $rs = $conn->query("SELECT klassekode, klassenavn FROM klasse ORDER BY klassekode");
    return $rs ? $rs->fetch_all(MYSQLI_ASSOC) : [];
}

// Førstegangsvisning
$klasser = hentKlasser($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = trim($_POST['klassekode'] ?? '');

    if ($kode === '') {
        $err = "Velg en klasse å slette.";
    } else {
        // 1️⃣ Først sjekk om det finnes studenter knyttet til denne klassen
        $sjekk = $conn->prepare("SELECT COUNT(*) FROM student WHERE klassekode = ?");
        $sjekk->bind_param("s", $kode);
        $sjekk->execute();
        $sjekk->bind_result($antall);
        $sjekk->fetch();
        $sjekk->close();

        if ($antall > 0) {
            // Vis brukervennlig melding
            $err = "Kan ikke slette: {$antall} student(er) er knyttet til klasse '{$kode}'.";
        } else {
            // 2️⃣ Slett klassen
            $stmt = $conn->prepare("DELETE FROM klasse WHERE klassekode = ?");
            $stmt->bind_param("s", $kode);

            try {
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $ok = "Klasse '{$kode}' ble slettet.";
                    } else {
                        $err = "Fant ikke klassen '{$kode}'.";
                    }
                } else {
                    $err = "Sletting mislyktes. Prøv igjen.";
                }
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() === 1451) {
                    $err = "Kan ikke slette klasse '{$kode}' fordi studenter er knyttet til den.";
                } else {
                    $err = "Teknisk feil: " . htmlspecialchars($e->getMessage());
                }
            }

            $stmt->close();
        }
    }

    // ✅ Oppdater listen etter sletting
    $klasser = hentKlasser($conn);
}
?>
<!doctype html>
<html lang="no">
<head>
  <meta charset="utf-8">
  <title>Slett klasse</title>
  <style>
    body{font-family:system-ui,Segoe UI,Roboto,Arial,sans-serif;margin:2rem;}
    select,button{padding:.6rem;border-radius:8px;border:1px solid #ccc;min-width:360px}
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

  <form method="post" action="slett_klasse.php" onsubmit="return confirm('Slette valgt klasse?')">
    <div class="row">
      <label>Velg klasse<br>
        <select name="klassekode" required>
          <option value="">— Velg klasse —</option>
          <?php foreach ($klasser as $k): ?>
            <option value="<?= htmlspecialchars($k['klassekode']) ?>">
              <?= htmlspecialchars($k['klassekode'].' – '.$k['klassenavn']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </label>
    </div>
    <div class="row"><button type="submit">Slett</button></div>
  </form>
</body>
</html>
