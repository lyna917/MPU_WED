<?php
date_default_timezone_set('Europe/Moscow');

$html_type = 'table';
if (isset($_GET['html_type'])) {
    if ($_GET['html_type'] == 'TABLE') {
        $html_type = 'table';
    } elseif ($_GET['html_type'] == 'DIV') {
        $html_type = 'block';
    }
}

// Определение содержания таблицы (по умолчанию 'all')
$content = isset($_GET['content']) ? (int)$_GET['content'] : 0;
if ($content < 2 || $content > 9) $content = 0;

// Формирование строки с информацией о содержании
$contentInfo = $content == 0 ? 'Таблица умножения полностью' : "Таблица умножения на {$content}";

// Текущая дата и время
$currentDateTime = date('d.m.Y H:i:s');

// Функция преобразования числа в ссылку
function outNumAsLink($num) {
    if ($num >= 2 && $num <= 9) {
        $params = '';
        if (isset($_GET['html_type'])) {
            $params .= '&html_type=' . $_GET['html_type'];
        }
        return '<a href="?content=' . $num . $params . '">' . $num . '</a>';
    }
    return $num;
}

function outRow($n) {
    for ($i = 2; $i <= 9; $i++) {
        echo '<div class="ttRowCell">';
        echo outNumAsLink($n) . ' × ' . outNumAsLink($i) . ' = ' . outNumAsLink($i * $n);
        echo '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Орлов Игорь Сергеевич 241-353 Лабораторная работа №5 - Таблица умножения</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <!-- ШАПКА С ГЛАВНЫМ МЕНЮ -->
        <header>
            <div class="logo">
                <img src="./template_foto.jpeg" alt="Логотип" width="80">
                <span>Орлов Игорь Сергеевич 241-353 Лабораторная работа №5</span>
            </div>
            <div class="main-menu">
                <span class="menu-label">Тип верстки:</span>
                <?php
                // Пункт меню "Табличная верстка"
                echo '<a href="?html_type=TABLE';
                if ($content != 0) {
                    echo '&content=' . $content;
                }
                echo '"';
                if (isset($_GET['html_type']) && $_GET['html_type'] == 'TABLE') {
                    echo ' class="selected"';
                }
                echo '>Табличная верстка</a>';
                
                // Пункт меню "Блочная верстка"
                echo '<a href="?html_type=DIV';
                if ($content != 0) {
                    echo '&content=' . $content;
                }
                echo '"';
                if (isset($_GET['html_type']) && $_GET['html_type'] == 'DIV') {
                    echo ' class="selected"';
                }
                echo '>Блочная верстка</a>';
                ?>
            </div>
        </header>

        <main>
            <aside class="sidebar">
                <h3>Таблица умножения</h3>
                <nav class="side-menu">
                    <?php
                    // Пункт "Всё"
                    echo '<a href="?';
                    if (isset($_GET['html_type'])) {
                        echo 'html_type=' . $_GET['html_type'];
                    }
                    echo '"';
                    if ($content == 0) {
                        echo ' class="selected"';
                    }
                    echo '>Всё</a>';
                    
                    // Пункты от 2 до 9
                    for ($i = 2; $i <= 9; $i++) {
                        echo '<a href="?content=' . $i;
                        if (isset($_GET['html_type'])) {
                            echo '&html_type=' . $_GET['html_type'];
                        }
                        echo '"';
                        if ($content == $i) {
                            echo ' class="selected"';
                        }
                        echo '>' . $i . '</a>';
                    }
                    ?>
                </nav>
            </aside>

            <!-- ОСНОВНОЙ КОНТЕНТ - ТАБЛИЦА УМНОЖЕНИЯ -->
            <section class="content">
                <h2><?php echo $contentInfo; ?></h2>
                
                <?php if ($html_type == 'table'): ?>
                    <!-- ТАБЛИЧНАЯ ВЕРСТКА -->
                    <table class="multiplication-table">
                        <?php if ($content == 0): ?>
                            <!-- Полная таблица умножения -->
                            <?php for ($row = 2; $row <= 9; $row++): ?>
                                <tr>
                                    <?php for ($col = 2; $col <= 9; $col++): ?>
                                        <td>
                                            <?php echo outNumAsLink($col); ?> × 
                                            <?php echo outNumAsLink($row); ?> = 
                                            <?php echo outNumAsLink($row * $col); ?>
                                        </td>
                                    <?php endfor; ?>
                                </tr>
                            <?php endfor; ?>
                        <?php else: ?>
                            <!-- Один столбец таблицы умножения -->
                            <tr>
                                <td class="single-column">
                                    <?php outRow($content); ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </table>
                    
                <?php else: ?>
                    <!-- БЛОЧНАЯ ВЕРСТКА -->
                    <div class="block-layout">
                        <?php if ($content == 0): ?>
                            <!-- Полная таблица умножения (блочная) -->
                            <?php for ($row = 2; $row <= 9; $row++): ?>
                                <div class="ttRow">
                                    <?php for ($col = 2; $col <= 9; $col++): ?>
                                        <div class="ttRowCell">
                                            <?php echo outNumAsLink($col); ?> × 
                                            <?php echo outNumAsLink($row); ?> = 
                                            <?php echo outNumAsLink($row * $col); ?>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            <?php endfor; ?>
                        <?php else: ?>
                            <!-- Один столбец таблицы умножения (блочная) -->
                            <div class="ttSingleRow">
                                <?php outRow($content); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </section>
        </main>

        <!-- ПОДВАЛ -->
        <footer>
            <div class="footer-info">
                <span>Тип верстки: <?php echo ($html_type == 'table') ? 'Табличная' : 'Блочная'; ?></span>
                <span><?php echo $contentInfo; ?></span>
                <span>Дата и время: <?php echo $currentDateTime; ?></span>
            </div>
        </footer>
    </div>
</body>
</html>