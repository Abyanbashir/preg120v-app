<?php
require_once __DIR__ . '/db.php';

$sql = "SELECT s.brukernavn, s.fornavn, s.etternavn, s.klassekode, k.klassenavn
        FROM student s
        JOIN klasse k ON s.klassekode = k.klassekode
        ORDER BY s.brukernavn";

$result = $conn->query($sql);
$studenter = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

include __DIR__ . '/partials/header.php';
?>
<section class="section">
  <p><a href="index.php">← Tilbake</a></p>
  <h1>Alle studenter</h1>

  <?php if (empty($studenter)): ?>
    <div class="msg err">Ingen studenter registrert.</div>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Brukernavn</th>
          <th>Fornavn</th>
          <th>Etternavn</th>
          <th>Klassekode</th>
          <th>Klassenavn</th>
        </tr>
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
      </tbody>
    </table>
  <?php endif; ?>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>
