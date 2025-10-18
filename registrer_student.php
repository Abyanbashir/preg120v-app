<?php

<?php if ($ok): ?>
<div class="msg ok"><?= htmlspecialchars($ok) ?></div>
<?php endif; ?>
<?php if ($errors): ?>
<div class="msg err">
<ul>
<?php foreach ($errors as $e): ?>
<li><?= htmlspecialchars($e) ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>

<div class="grid">
<div class="card">
<h2>Registrer ny student</h2>
<form method="post">
<input type="hidden" name="action" value="create">
<div class="row"><input name="brukernavn" maxlength="7" placeholder="Brukernavn (f.eks. gb)" required></div>
<div class="row"><input name="fornavn" maxlength="50" placeholder="Fornavn" required></div>
<div class="row"><input name="etternavn" maxlength="50" placeholder="Etternavn" required></div>
<div class="row">
<select name="klassekode" required>
<option value="">— Velg klasse —</option>
<?php foreach ($klasser as $k): ?>
<option value="<?= htmlspecialchars($k['klassekode']) ?>">
<?= htmlspecialchars($k['klassekode'] . ' – ' . $k['klassenavn']) ?>
</option>
<?php endforeach; ?>
</select>
</div>
<div class="row"><button type="submit">Lagre</button></div>
</form>
</div>

<div class="card">
<h2>Slett student</h2>
<form method="post">
<input type="hidden" name="action" value="delete">
<div class="row">
<select name="del_brukernavn" required>
<option value="">— Velg student —</option>
<?php foreach ($studenter as $s): ?>
<option value="<?= htmlspecialchars($s['brukernavn']) ?>">
<?= htmlspecialchars($s['brukernavn'] . ' – ' . $s['fornavn'] . ' ' . $s['etternavn']) ?>
</option>
<?php endforeach; ?>
</select>
</div>
<div class="row"><button type="submit" onclick="return confirm('Slette valgt student?');">Slett</button></div>
</form>
</div>
</div>

<div class="card">
<h2>Alle studenter</h2>
<table>
<thead><tr><th>Brukernavn</th><th>Fornavn</th><th>Etternavn</th><th>Klassekode</th><th>Klassenavn</th></tr></thead>
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
</div>
</body>
</html>