        }
        $stmt->close();
    }
    // Oppdater listen etter sletting
    $res = $conn->query($sql);
    $studenter = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
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

  <form method="post" onsubmit="return confirm('Slette valgt student?')">
    <div class="row">
      <select name="brukernavn" required>
        <option value="">— Velg student —</option>
        <?php foreach ($studenter as $s): ?>
          <option value="<?= htmlspecialchars($s['brukernavn']) ?>">
            <?= htmlspecialchars($s['brukernavn'].' – '.$s['fornavn'].' '.$s['etternavn']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="row"><button type="submit">Slett</button></div>
  </form>
</body>
</html>
