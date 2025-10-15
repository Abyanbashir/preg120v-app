<?php require 'db.php'; $msg=$err=null;
if($_SERVER['REQUEST_METHOD']==='POST'){
  $kode=$_POST['klassekode']??'';
  if($kode===''){ $err="Velg en klasse."; }
  else{
    $chk=$conn->prepare("SELECT COUNT(*) FROM student WHERE klassekode=?");
    $chk->bind_param("s",$kode); $chk->execute(); $chk->bind_result($cnt); $chk->fetch(); $chk->close();
    if($cnt>0){ $err="Kan ikke slette «".h($kode)."» fordi klassen har studenter."; }
    else{
      $del=$conn->prepare("DELETE FROM klasse WHERE klassekode=?");
      $del->bind_param("s",$kode); $del->execute();
      $msg = $del->affected_rows ? "Klasse «".h($kode)."» slettet." : "Fant ikke klassen.";
    }
  }
}
$klasser=$conn->query("SELECT klassekode, klassenavn FROM klasse ORDER BY klassekode")->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html><meta charset="utf-8"><link rel="stylesheet" href="style.css"><h1>Slett klasse</h1>
<p><a href="index.php">Tilbake</a></p>
<?php if($msg) echo "<p class=ok>$msg</p>"; if($err) echo "<p class=err>$err</p>"; ?>
<form method="post" onsubmit="return confirm('Er du sikker på at du vil slette denne klassen?');">
  <label>Velg klasse</label>
  <select name="klassekode" required>
    <option value="">Velg…</option>
    <?php foreach($klasser as $k): ?>
      <option value="<?=h($k['klassekode'])?>"><?=h($k['klassekode'])?> – <?=h($k['klassenavn'])?></option>
    <?php endforeach; ?>
  </select>
  <p><button>Slett</button></p>
</form>
