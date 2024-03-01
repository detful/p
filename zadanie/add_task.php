<?php
// Dane połączeniowe do bazy danych
$host = "localhost";
$db_name = "harmonogram";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    // Ustawienie trybu błędu PDO na wyjątek
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Przygotowanie zapytania SQL
    $sql = "INSERT INTO tasks (title, description, priority, execution_date, responsible_person) VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    // Przypisanie wartości
    $stmt->bindParam(1, $_POST['title']);
    $stmt->bindParam(2, $_POST['description']);
    $stmt->bindParam(3, $_POST['priority']);
    $stmt->bindParam(4, $_POST['execution_date']);
    $stmt->bindParam(5, $_POST['responsible_person']);

    // Wykonanie zapytania
    $stmt->execute();

    // Przekierowanie z powrotem do strony głównej po dodaniu zadania
    header("Location: index.html");
} catch(PDOException $e) {
    echo "Błąd połączenia: " . $e->getMessage();
}

// Zamknięcie połączenia
$conn = null;
?>


