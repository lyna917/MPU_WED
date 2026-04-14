<?php
$page_title = "Первая страница, Орлов Игорь Сергеевич, 241-353, ЛР1";
$page_name = "Первая";
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
    <h1>Первая страница</h1>
    
    <h2>Наши услуги</h2>
    <p>Lorem ipsum odor amet, consectetuer adipiscing elit. Himenaeos luctus scelerisque curae rhoncus adipiscing nostra. Lectus integer nibh phasellus suspendisse semper himenaeos. Lobortis aptent bibendum nulla facilisis nascetur in. Risus tortor senectus commodo penatibus risus hendrerit sapien. Pulvinar consectetur purus viverra; nibh pretium diam cursus. Adipiscing faucibus blandit aliquet duis pellentesque montes sociosqu aptent.</p>
    <p>Добавляем ещё абзацы, чтобы суммарно набрать килобайт информации. Текст может быть любым, главное — наличие таблицы и двух фото на страницу. Это требование лабораторной работы.</p>

    <h2>Таблица данных</h2>
    <table>
        <?php
        echo "<tr>
            <th>Параметр</th>
            <th>Значение</th>
            <th>Единица</th>
        </tr>";?>
        <tr>
            <td><?php echo "Температура"; ?></td>
            <td><?php echo "22"; ?></td>
            <td><?php echo "°C"; ?></td>
        </tr>
        <tr>
            <td>Давление</td>
            <td>1013</td>
            <td>гПа</td>
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
    <p>Фотографии меняются при каждом обновлении страницы.</p>
</main>

<footer>
    Сформировано <?php echo date("d.m.Y в H:i:s"); ?>
</footer>

</body>
</html>