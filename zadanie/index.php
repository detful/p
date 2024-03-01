<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Harmonogram Zadań</title>
</head>
<body>
    <div class="header">Tygodniowy harmonogram pracy</div>

    <div class="dropdownmenu">
        <div class="sec-center">
            <input class="dropdown" type="checkbox" id="dropdown" name="dropdown"/>
            <label class="for-dropdown" for="dropdown">Sort <i class="uil uil-arrow-down"></i></label>
            <div class="section-dropdown">
                <a href="?sort=priority&order=asc">Sort by priority ASC<i class="uil uil-arrow-right"></i></a>
                <a href="?sort=priority&order=desc">Sort by priority DESC<i class="uil uil-arrow-right"></i></a>
                <a href="?sort=date_added&order=asc">Date Sort ASC<i class="uil uil-arrow-right"></i></a>
                <a href="?sort=date_added&order=desc">Date Sort DESC<i class="uil uil-arrow-right"></i></a>
                <a href="?sort=title&order=asc">Sort By name ASC<i class="uil uil-arrow-right"></i></a>
                <a href="?sort=title&order=desc">Sort By name DESC<i class="uil uil-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <div id="list">
        <?php
        $mysqli = new mysqli("localhost", "root", "", "harmonogram");

        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: " . $mysqli->connect_error;
            exit();
        }

        $sort = 'execution_date'; // Default sorting column
        $order = 'DESC'; // Default sorting order

        // Adjust sorting based on query parameters
        if (isset($_GET['sort']) && in_array($_GET['sort'], ['priority', 'date_added', 'title'])) {
            $sort = $_GET['sort'];
        }
        if (isset($_GET['order']) && in_array($_GET['order'], ['asc', 'desc'])) {
            $order = strtoupper($_GET['order']);
        }

        $query = "SELECT * FROM tasks ORDER BY $sort $order";

        $result = $mysqli->query($query);

        while ($row = $result->fetch_assoc()) {
            echo "<div class='task'>";
            echo "<div class='task-header' onclick='toggleDetails(this.parentNode)'>";
            echo "<h3>" . htmlspecialchars($row['title']) . " - Priorytet: " . htmlspecialchars($row['priority']) . "</h3>";
            echo "</div>";
            echo "<div class='details'>";
            echo "<p>Opis: " . nl2br(htmlspecialchars($row['description'])) . "</p>";
            echo "<p>Termin wykonania: " . htmlspecialchars($row['execution_date']) . "</p>";
            echo "<p>Odpowiedzialna osoba: " . htmlspecialchars($row['responsible_person']) . "</p>";
            echo "<p>Data dodania: " . htmlspecialchars($row['date_added']) . "</p>";
            echo "</div></div>";
        }

        $mysqli->close();
        ?>
    </div>

    <div class="addtask">
        <form action="add_task.php" method="post">
            <time id="currentDateTime"></time> <br>
            <input type="date" name="execution_date" id="datawykonania"> Wprowadz deadline wykonania
            <input type="text" name="title" id="title" placeholder="Wpisz tytuł">
            <input type="number" name="priority" id="priority" placeholder="Wprowadź Stopień ważności od 1-5" max="5" min="1">
            <textarea name="description" cols="60" rows="8" maxlength="100" placeholder="Opis"></textarea>
            <input type="text" name="responsible_person" placeholder="Wpisz Imię" maxlength="50">
            <button type="submit">Dodaj</button>
        </form>
    </div>

    <script>
        function updateDateTime() {
            const now = new Date();
            const formattedDateTime = now.toLocaleString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('currentDateTime').textContent = formattedDateTime;
        }

        updateDateTime();
        setInterval(updateDateTime, 1000);

        function toggleDetails(taskElement) {
            const detailsDiv = taskElement.querySelector('.details');
            if (detailsDiv.style.display === "none") {
                detailsDiv.style.display = "block";
            } else {
                detailsDiv.style.display = "none";
            }
        }
    </script>
</body>
</html>
