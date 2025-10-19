<?php
// MARKER: slett_student.php v4
require_once __DIR__ . '/db.php';

$ok = $err = null;

// Funksjon for å hente listen (slik at vi kan bruke den både før og etter sletting)
function hentStudenter(mysqli $conn): array {
    $rs = $conn->query("SELECT brukernavn, fornavn, etternavn FROM student ORDER BY brukernavn");
    return $rs ? $rs->fetch_all(MYSQLI_ASSOC) : [];
}

// Førstegangsvisning
$studenter = hentStudenter($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bn = trim($_POST['brukernavn'] ?? '');

    if ($bn === '') {
        $err = "Velg en student å slette.";
    } else {
        // Sjekk at studenten finnes (for brukervennlig melding)
        $chk = $conn->prepare("SELECT 1 FROM student WHERE brukernavn = ?");
        $chk->bind_param("s", $bn);
        $chk->execute();
        $chk->store_result();

        if ($chk->num_rows === 0) {
            $err = "Fant ikke student med brukernavn '{$bn}'.";
        } else {
            // Slett
            $stmt = $conn->prepare("DELETE FROM student WHERE brukernavn = ?");
            $stmt->bind_param("s", $bn);
            try {
                if ($stmt->execute() && $stmt->affected_rows > 0) {
                    $ok = "Student '{$bn}' ble slettet.";
                } else {
                    $err = "Sletting mislyktes. Prøv igjen.";
                }
            } catch (mysqli_sql_exception $e) {
                $err = "Kunne ikke slette studenten. (Teknisk: " . htmlspecialchars($e->getMessage()) . ")";
            }
            $stmt->close();
        }
        $chk->close();
    }

    // ✅ Oppdater listen etter sletting (her var feilen din – $sql var udefinert)
    $studenter = hentStudenter($conn);
}
?>
<!doctype html>
<html lang="no">
<head>
  <meta charset="utf-8">
  <title>Slett student</title>
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
  <h1>Slett student</h1>

  <?php if ($ok): ?><div class="msg ok"><?= htmlspecialchars($ok) ?></div><?php endif; ?>
  <?php if ($err): ?><div class="msg err"><?= htmlspecialchars($err) ?></div><?php endif; ?>

  <form method="post" action="slett_student.php" onsubmit="return confirm('Slette valgt student?')">
    <div class="row">
      <label>Velg student<br>
        <select name="brukernavn" required>
          <option value="">— Velg student —</option>
          <?php foreach ($studenter as $s): ?>
            <option value="<?= htmlspecialchars($s['brukernavn']) ?>">
              <?= htmlspecialchars($s['brukernavn'].' – '.$s['fornavn'].' '.$s['etternavn']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </label>
    </div>
    <div class="row"><button type="submit">Slett</button></div>
  </form>
</body>
</html>

