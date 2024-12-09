<?php
$host = 'localhost';
$user = 'root'; 
$password = 'root'; 
$dbname = 'student_9';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $surname = $conn->real_escape_string($_POST['surname']);
    $value = $name . ' ' . $surname;
    $action_date = date('Y-m-d H:i:s');

    $sql = "INSERT INTO first_work (value, action_date) VALUES ('$value', '$action_date')";
    if (!$conn->query($sql)) {
        echo "Ошибка: " . $conn->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $id = (int)$_POST['id'];
    $new_value = $conn->real_escape_string($_POST['new_value']);
    $sql = "UPDATE first_work SET value='$new_value' WHERE id=$id";
    if (!$conn->query($sql)) {
        echo "Ошибка: " . $conn->error;
    }
}

$sql = "SELECT * FROM first_work ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Форма</title>
</head>
<body>
    <h1>Форма заполнения данных</h1>
    <form method="post">
        <input type="text" name="name" placeholder="Имя" required>
        <input type="text" name="surname" placeholder="Фамилия" required>
        <button type="submit" name="add">Добавить</button>
    </form>

    <h2>Таблица записей</h2>
    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="5">
            <tr>
                <th>ID</th>
                <th>Значение</th>
                <th>Дата действия</th>
                <th>Действия</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="text" name="new_value" value="<?= htmlspecialchars($row['value']) ?>" required>
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit" name="edit">Изменить</button>
                        </form>
                    </td>
                    <td><?= $row['action_date'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Записей нет.</p>
    <?php endif; ?>
</body>
</html>

<?php
$conn->close();
?>
