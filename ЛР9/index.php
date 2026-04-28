<?php
// ПОДКЛЮЧЕНИЕ С ПОРТОМ 8000
$host = '127.0.0.1';
$port = 8000;
$dbname = 'notebook';
$username = 'root';
$password = 'password123!';  // Если пароль есть - напиши здесь

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage());
}

$menu_active = isset($_GET['menu']) ? $_GET['menu'] : 'view';

require_once 'menu.php';

$content = '';

switch ($menu_active) {
    case 'view':
        require_once 'viewer.php';
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $content = getViewContent($pdo, $sort, $page);
        break;
    case 'add':
        require_once 'add.php';
        $content = getAddContent($pdo);
        break;
    case 'edit':
        require_once 'edit.php';
        $content = getEditContent($pdo);
        break;
    case 'delete':
        require_once 'delete.php';
        $content = getDeleteContent($pdo);
        break;
    default:
        require_once 'viewer.php';
        $content = getViewContent($pdo, 'id', 1);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Орлов Игорь Сергеевич 241-353 ЛР9</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <?php echo getMenu($menu_active); ?>
        <?php echo $content; ?>
    </main>
</body>
</html>