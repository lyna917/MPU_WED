<?php
// Инициализация параметров при первой загрузке
if (!isset($_GET['store'])) {
    $_GET['store'] = '';
}
if (!isset($_GET['clicks'])) {
    $_GET['clicks'] = 0;
}

// Обработка нажатия кнопки
if (isset($_GET['key'])) {
    if ($_GET['key'] === 'reset') {
        // Кнопка СБРОС: очищаем хранилище
        $_GET['store'] = '';
        $_GET['clicks']++;
    } elseif (is_numeric($_GET['key']) && strlen($_GET['key']) === 1 && $_GET['key'] >= 0 && $_GET['key'] <= 9) {
        // Кнопка с цифрой: добавляем цифру в конец строки
        $_GET['store'] .= $_GET['key'];
        $_GET['clicks']++;
    }
}

$current_result = $_GET['store'];
$total_clicks = (int)$_GET['clicks'];
?>
<!doctype html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Орлов Игорь Сергеевич 241-353 ЛР-3</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <img src="./template_foto.jpeg" alt="Логотип" width="90">
        <span>Орлов Игорь Сергеевич 241-353 ЛР-3</span>
    </header>

    <main>
        <div class="calculator-container">
            <!-- Окно просмотра результата (блок div с центровкой текста) -->
            <div class="result-window <?php echo empty($current_result) ? 'empty' : ''; ?>">
                <?php echo htmlspecialchars($current_result); ?>
            </div>

            <!-- Панель кнопок (ссылки с GET-параметрами) -->
            <div class="buttons-grid">
                <?php
                // Генерация кнопок с цифрами от 0 до 9
                for ($i = 0; $i <= 9; $i++):
                    // Формируем ссылку: key=цифра, store=текущий результат, clicks=текущий счётчик
                    $url = "?key={$i}&store=" . urlencode($current_result) . "&clicks=" . urlencode($total_clicks);
                ?>
                    <a href="<?php echo $url; ?>" class="digit-btn"><?php echo $i; ?></a>
                <?php endfor; ?>

                <!-- Кнопка СБРОС -->
                <?php
                // Для сброса: key=reset, store=пусто, clicks=увеличенный счётчик
                $resetUrl = "?key=reset&store=&clicks=" . urlencode($total_clicks + 1);
                ?>
                <a href="<?php echo $resetUrl; ?>" class="reset-btn">СБРОС</a>
            </div>
        </div>
    </main>

    <footer>
        <span>Всего нажатий: <?php echo $total_clicks; ?></span>
    </footer>
</body>

</html>