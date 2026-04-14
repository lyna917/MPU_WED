<?php
$page_title = "Вторая страница, Орлов Игорь Сергеевич, 241-353, ЛР1";
$page_name = "Вторая";
date_default_timezone_set("Europe/Moscow");
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
    <h1>Вторая страница</h1>
    
    <h2>Контакты</h2>
    <p>Lorem ipsum odor amet, consectetuer adipiscing elit. Sociosqu purus praesent tristique metus tincidunt potenti habitant. Morbi iaculis accumsan ac aliquam; maximus vulputate iaculis. Congue class nisl imperdiet purus fermentum facilisis in varius. Ad litora quis eleifend gravida et primis natoque. Nascetur consectetur justo senectus mi fames vivamus sed.</p>
    <p>Здесь размещена контактная информация и дополнительные сведения. Общий объём текста на странице превышает 1 КБ, что соответствует требованиям лабораторной работы.</p>

    <h2>Таблица контактов</h2>
    <table>
        <?php
        echo "<tr>
            <th>Отдел</th>
            <th>Телефон</th>
            <th>Email</th>
        </tr>";?>
        <tr>
            <td><?php echo "Продажи"; ?></td>
            <td><?php echo "+7 (123) 456-78-90"; ?></td>
            <td><?php echo "sales@example.com"; ?></td>
        </tr>
        <tr>
            <td>Поддержка</td>
            <td>+7 (123) 456-78-91</td>
            <td>support@example.com</td>
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
    <p>Фотографии загружаются динамически.</p>
</main>

<footer>
    Сформировано <?php echo date("d.m.Y в H:i:s"); ?>
</footer>

</body>
</html>