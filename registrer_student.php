
<?php
// MARKER: registrer_student.php v3
require_once __DIR__ . '/db.php';

$ok = $err = null;

// Hent klasser til nedtrekksliste
$klRes = $conn->query("SELECT klassekode, klassenavn FROM klasse ORDER BY klassekode");
$klasser = $klRes ? $klRes->fetch_all(MYSQLI_ASSOC) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brukernavn = trim($_POST['brukernavn'] ?? '');
    $fornavn    = trim($_POST['fornavn'] ?? '');
    $etternavn  = trim($_POST['etternavn'] ?? '');
    $klassekode = trim($_POST['klassekode'] ?? '');

    if ($brukernavn === '' || $fornavn === '' || $etternavn === '' || $klassekode === '') {
        $err = "Vennligst fyll ut alle felt.";
    } elseif (strlen($brukernavn) > 7) {
        $err = "Brukernavn kan maks være 7 tegn.";
    } else {
        // 1) Forhåndssjekk om brukernavn finnes fra før (ingen exception her)
        $chk = $conn->prepare("SELECT 1 FROM student WHERE brukernavn = ?");
        $chk->bind_param("s", $brukernavn);
        $chk->execute();
        $chk->store_result();

        if ($chk->num_rows > 0) {
            $err = "Brukernavnet '{$brukernavn}' finnes allerede. Velg et annet.";
        } else {
            // 2) Sikker INSERT med prepared statement
            $stmt = $conn->prepare(
                "INSERT INTO student (brukernavn, fornavn, etternavn, klassekode) VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param("ssss", $brukernavn, $fornavn, $etternavn, $klassekode);

            try {
                if ($stmt->execute()) {
                    $ok = "Student '{$brukernavn}' ble registrert!";
                } else {
                    $err = "Noe gikk galt under registrering. Prøv igjen.";
                }
            } catch (mysqli_sql_exception $e) {
                // 1062 = duplikat PK, 1452 = FK-feil (klassekode finnes ikke)
                if ($e->getCode() === 1062) {
                    $err = "Brukernavnet '{$brukernavn}' finnes allerede. Velg et annet.";
                } elseif ($e->getCode() === 1452) {
                    $err = "Ugyldig klassekode: klassen finnes ikke.";
                } else {
                    $err = "Teknisk feil: " . htmlspecialchars($e->getMessage());
                }
            }

            $stmt->close();
        }
        $chk->close();
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
    input,select,button{padding:.6rem;border-radius:8px;border:1px solid #ccc;min-width:320px}
    .row{margin:.6rem 0}
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

  <form method="post" action="registrer_student.php">
    <div class="row">
      <label>Brukernavn<br>
        <input name="brukernavn" maxlength="7" required>
      </label>
    </div>
    <div class="row">
      <label>Fornavn<br>
        <input name="fornavn" maxlength="50" required>
      </label>
    </div>
    <div class="row">
      <label>Etternavn<br>
        <input name="etternavn" maxlength="50" required>
      </label>
    </div>
    <div class="row">
      <label>Klasse<br>
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
    <div class="row"><button type="submit">Registrer</button></div>
  </form>
</body>
</html>
