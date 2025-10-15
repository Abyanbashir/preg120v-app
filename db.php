<?php
$host = "b-studentsql-1.usn.no";
$user = "abali4926"; // brukernavnet ditt
$pass = "DITT_PASSORD"; // skriv inn passordet ditt her
$db   = "abali4926"; // databasenavn (samme som brukernavnet ditt)

// Koble til databasen
$conn = new mysqli($host, $user, $pass, $db);

// Sjekk om tilkoblingen fungerer
if ($conn->connect_error) {
    die("Tilkoblingsfeil: " . $conn->connect_error);
}

// Hvis du vil teste at alt virker:
// echo "Koblet til databasen!";
?>
