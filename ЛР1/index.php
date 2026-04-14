<?php
date_default_timezone_set("Europe/Moscow");
$page_title = "Главная страница, Орлов Игорь Сергеевич, 241-353, ЛР1";
$page_name = "Главная";
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
        <a href="<?php echo "./index.php"; ?>" <?php echo ($page_name=="Главная") ? 'class="selected_menu"' : ""; ?>> Главная страница</a>
        <a href="<?php echo "./page1.php"; ?>" <?php echo ($page_name=="Первая") ? 'class="selected_menu"' : ""; ?>>Первая страница</a>
        <a href="<?php echo "./page2.php"; ?>" <?php echo ($page_name=="Вторая") ? 'class="selected_menu"' : ""; ?>>Вторая страница</a>
</header>

<main>
    <h1>Главная страница</h1>
    
    <h2>О нас</h2>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
    <p>Дополнительный текст для увеличения объёма контента более 1 КБ. Здесь описываются цели и задачи сайта, преимущества работы с нами и другая полезная информация для посетителей.</p>

    <h2>Пример таблицы</h2>
    <table>
        <?php
        echo "<tr>
            <th>Название</th>
            <th>Описание</th>
            <th>Количество</th>
        </tr>";?>
        <tr>
            <td><?php echo "Динамика"; ?></td>
            <td><?php echo "Формируется PHP"; ?></td>
            <td><?php echo "100"; ?></td>
        </tr>
        <tr>
            <td>Статика</td>
            <td>HTML-код</td>
            <td>200</td>
        </tr>
    </table>

    <h2>Фотогалерея</h2>
    <?php
    if (date('s') % 2 === 0) {
        echo '<img src="fotos/foto1.jpg" alt="Фото 1" style="width: 300px; margin: 10px;">';
    } else {
        echo '<img src="fotos/foto2.jpg" alt="Фото 2" style="width: 300px; margin: 10px;">';
    }
    
    if ((date('s') + 1) % 2 === 0) {
        echo '<img src="fotos/foto1.jpg" alt="Фото 1" style="width: 300px; margin: 10px;">';
    } else {
        echo '<img src="fotos/foto2.jpg" alt="Фото 2" style="width: 300px; margin: 10px;">';
    }
    ?>
    <p>Фотографии меняются в зависимости от чётности секунды.</p>
</main>

<footer>
    Сформировано <?php echo date("d.m.Y в H:i:s"); ?>
</footer>

</body>
</html>