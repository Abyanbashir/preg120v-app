
<?php
require_once __DIR__ . '/db.php';

$sql = "SELECT klassekode, klassenavn, studiumkode FROM klasse ORDER BY klassekode";
$result = $conn->query($sql);
$klasser = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

include __DIR__ . '/partials/header.php';
?>
<section class="section">
  <p><a href="index.php">← Tilbake</a></p>
  <h1>Alle klasser</h1>

  <?php if (empty($klasser)): ?>
    <div class="msg err">Ingen klasser registrert.</div>
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
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>
