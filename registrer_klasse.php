<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="UTF-8">
  <title>Registrer klasse</title>
</head>
<body>
  <h2>Registrer ny klasse</h2>

  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $klassekode = $_POST["klassekode"];
      $klassenavn = $_POST["klassenavn"];
      $studiumkode = $_POST["studiumkode"];

      if (!empty($klassekode) && !empty($klassenavn) && !empty($studiumkode)) {
          $sql = "INSERT INTO klasse (klassekode, klassenavn, studiumkode)
                  VALUES ('$klassekode', '$klassenavn', '$studiumkode')";
          if ($conn->query($sql) === TRUE) {
              echo "<p>✅ Klassen ble registrert!</p>";
          } else {
              echo "<p>⚠️ Feil: " . $conn->error . "</p>";
          }
      } else {
          echo "<p>⚠️ Fyll ut alle felt!</p>";
      }
  }
  ?>

  <form method="post">
    <label>Klassekode:</label><br>
    <input type="text" name="klassekode" maxlength="5" required><br><br>

    <label>Klassenavn:</label><br>
    <input type="text" name="klassenavn" maxlength="50" required><br><br>

    <label>Studiumkode:</label><br>
    <input type="text" name="studiumkode" maxlength="50" required><br><br>

    <button type="submit">Registrer</button>
  </form>

  <p><a href="index.php">Tilbake til hovedsiden</a></p>
</body>
</html>
