<?php
$tables = [
    "Название*Автор*Год#Война и мир*Толстой*1869#Преступление и наказание*Достоевский*1866#Мастер и Маргарита*Булгаков*1967",
    "Страна*Столица*Континент#Франция*Париж*Европа#Япония*Токио*Азия#Бразилия*Бразилиа*Южная Америка#Египет*Каир*Африка",
    "Язык*Типизация*Рейтинг#Python*Динамическая*5#Java*Статическая*4.5#JavaScript*Динамическая*4.8",
    "Спортсмен*Вид спорта*Страна*Медалей#Майкл Фелпс*Плавание*США*23#Усэйн Болт*Легкая атлетика*Ямайка*8",
    "Фрукт*Цвет*Сезон#Яблоко*Красный*Осень#Банан*Желтый*Лето#Апельсин*Оранжевый*Зима",
    "Операционная система*Разработчик*Год выпуска*Версия#Windows*Microsoft*1985*11#Linux*Linus Torvalds*1991*6.0#macOS*Apple*2001*14",
    "Блюдо*Кухня*Основной ингредиент#Пицца*Итальянская*Тесто#Суши*Японская*Рис#Борщ*Русская*Свекла",
    "Планета*Тип*Спутников*Расстояние до Солнца#Марс*Земная*2*227.9#Юпитер*Газовый*79*778.5#Венера*Земная*0*108.2",
    "Профессия*Зарплата*График#Программист*200000*Удаленный#Дизайнер*150000*Гибкий#Тестировщик*120000*Полный день",
    "Музей*Город*Шедевр#Эрмитаж*Санкт-Петербург*Даная#Прадо*Мадрид*Менины#Орсе*Париж*Звездная ночь"
];

$cols_number = 4;

function getTR($row, $cols_number)
{
    $cells = explode('*', $row);
    
    // Если строка пустая - возвращаем пустую строку
    if (count($cells) == 1 && $cells[0] == "") {
        return "";
    }

    $html = '<tr>';
    for ($i = 0; $i < $cols_number; $i++) {
        if (isset($cells[$i]) && $cells[$i] !== "") {
            $html .= '<td>' . htmlspecialchars($cells[$i]) . '</td>';
        } else {
            $html .= '<td></td>';
        }
    }
    $html .= '</tr>';
    
    return $html;
}

function outTable($structure, $cols_number, $table_number)
{
    $result = '<h2>Таблица №' . $table_number . '</h2>';

    if ($cols_number <= 0) {
        return $result . '<p class="error">Неправильное число колонок</p>';
    }

    $rows = explode('#', $structure);

    if (count($rows) == 0 || (count($rows) == 1 && $rows[0] == "")) {
        return $result . '<p class="error">В таблице нет строк</p>';
    }
    
    $html_table = '<table>';
    $has_valid_rows = false;

    foreach ($rows as $row) {
        $tr_html = getTR($row, $cols_number);
        if ($tr_html !== "") {
            $html_table .= $tr_html;
            $has_valid_rows = true;
        }
    }
    
    $html_table .= '</table>';
    
    // Проверка: есть ли строки с ячейками
    if (!$has_valid_rows) {
        return $result . '<p class="error">В таблице нет строк с ячейками</p>';
    }
    
    return $result . $html_table;
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Орлов Игорь Сергеевич 241-353 ЛР-4 Вариант 7</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <img src="template_foto.jpeg" alt="Фото студента" width="90">
        <div class="header-text">
            <h1>Орлов Игорь Сергеевич</h1>
            <p>Группа 241-353 Лабораторная работа №4 Вариант 7</p>
        </div>
    </header>
    
    <main>
        <div class="task-description">
            <p><strong>Условие:</strong> Вывод таблиц на основе строк формата "С1*С2*С3#С4*С5*С6#..."</p>
            <p><strong>Количество колонок:</strong> <?php echo $cols_number; ?></p>
        </div>
        
        <?php
        $valid_tables_count = 0;
        for ($i = 0; $i < count($tables); $i++) {
            echo outTable($tables[$i], $cols_number, $i + 1);
            echo '<div class="table-separator"></div>';
            $valid_tables_count++;
        }
        
        echo '<div class="stats">';
        echo '<h3>Статистика выполнения</h3>';
        echo '<p>Всего обработано таблиц: ' . $valid_tables_count . '</p>';
        echo '<p>Заданное количество колонок: ' . $cols_number . '</p>';
        echo '<p>Формат данных: Строки разделены "#", ячейки разделены "*"</p>';
        echo '</div>';
        ?>
    </main>
    
    <footer>
        <p>© 2026 Орлов Игорь Сергеевич | Московский Политехнический Университет | Лабораторная работа №4</p>
    </footer>
</body>
</html>