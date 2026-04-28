<?php
session_start();

// Восстановление ФИО и группы из сессии
$savedFio = $_SESSION['fio'] ?? '';
$savedGroup = $_SESSION['group'] ?? '';

// Генерация случайных чисел (0-100, 2 знака после запятой)
function randomFloat()
{
    return round(mt_rand(0, 10000) / 100, 2);
}

// Инициализация значений по умолчанию
$defaultA = randomFloat();
$defaultB = randomFloat();
$defaultC = randomFloat();

// Обработка данных формы
$showResults = false;
$resultData = [];
$mode = 'view'; // view или print
$emailSent = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mode = $_POST['display_mode'] ?? 'view';

    // Сохраняем ФИО и группу в сессию
    $_SESSION['fio'] = $_POST['fio'] ?? '';
    $_SESSION['group'] = $_POST['group'] ?? '';

    $fio = $_POST['fio'] ?? '';
    $group = $_POST['group'] ?? '';
    $about = $_POST['about'] ?? '';
    $a = (float) str_replace(',', '.', $_POST['a'] ?? 0);
    $b = (float) str_replace(',', '.', $_POST['b'] ?? 0);
    $c = (float) str_replace(',', '.', $_POST['c'] ?? 0);
    $userAnswerRaw = trim($_POST['user_answer'] ?? '');
    $userAnswer = ($userAnswerRaw === '') ? null : (float) str_replace(',', '.', $userAnswerRaw);
    $taskType = $_POST['task_type'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $sendEmail = isset($_POST['send_email']);

    // Вычисление правильного ответа
    $correctAnswer = 0;
    $taskName = '';

    switch ($taskType) {
        case 'area':
            $p = ($a + $b + $c) / 2;
            $correctAnswer = sqrt($p * ($p - $a) * ($p - $b) * ($p - $c));
            $taskName = 'Площадь треугольника';
            break;
        case 'perimeter':
            $correctAnswer = $a + $b + $c;
            $taskName = 'Периметр треугольника';
            break;
        case 'volume':
            $correctAnswer = $a * $b * $c;
            $taskName = 'Объем параллелепипеда';
            break;
        case 'average':
            $correctAnswer = ($a + $b + $c) / 3;
            $taskName = 'Среднее арифметическое';
            break;
        case 'max':
            $correctAnswer = max($a, $b, $c);
            $taskName = 'Максимальное из трех чисел';
            break;
        case 'min':
            $correctAnswer = min($a, $b, $c);
            $taskName = 'Минимальное из трех чисел';
            break;
        case 'hypotenuse':
            $correctAnswer = sqrt($a * $a + $b * $b);
            $taskName = 'Гипотенуза (A и B)';
            break;
        case 'circle_area':
            $radius = ($a + $b + $c) / 3;
            $correctAnswer = pi() * $radius * $radius;
            $taskName = 'Площадь круга (радиус = среднее A,B,C)';
            break;
        default:
            $taskName = 'Не выбрана';
    }

    $correctAnswer = round($correctAnswer, 4);

    // Определение статуса теста (согласно PDF)
    $testStatus = '';
    $isPassed = false;

    if ($userAnswerRaw === '') {
        $testStatus = 'Задача самостоятельно решена не была';
        $isPassed = false;
        $displayAnswer = 'не введен';
    } else {
        $userAnswerRounded = round($userAnswer, 4);
        $isPassed = abs($correctAnswer - $userAnswerRounded) < 0.0001;
        $testStatus = $isPassed ? 'Тест пройден' : 'Ошибка: тест не пройден';
        $displayAnswer = $userAnswer;
    }

    $resultData = [
        'fio' => $fio,
        'group' => $group,
        'about' => nl2br(htmlspecialchars($about)),
        'taskName' => $taskName,
        'a' => $a,
        'b' => $b,
        'c' => $c,
        'userAnswerRaw' => $userAnswerRaw,
        'displayAnswer' => $displayAnswer,
        'correctAnswer' => $correctAnswer,
        'isPassed' => $isPassed,
        'testStatus' => $testStatus,
        'sendEmail' => $sendEmail,
        'email' => $email
    ];

    // Отправка email (подавляем Warning, но сохраняем сообщение об ошибке)
    if ($sendEmail && !empty($email)) {
        $emailText = "ФИО: $fio\n";
        $emailText .= "Группа: $group\n";
        $emailText .= "О себе: " . strip_tags($_POST['about'] ?? '') . "\n";
        $emailText .= "Тип задачи: $taskName\n";
        $emailText .= "Входные данные: A=$a, B=$b, C=$c\n";
        $emailText .= "Ваш ответ: " . $displayAnswer . "\n";
        $emailText .= "Правильный ответ: $correctAnswer\n";
        $emailText .= "Результат: $testStatus\n";

        $subject = '=?UTF-8?B?' . base64_encode('Результат тестирования') . '?=';
        $headers = "From: test@auto.ru\r\n";
        $headers .= "Content-Type: text/plain; charset=utf-8\r\n";

        // Подавляем Warning с помощью @, но сохраняем результат в переменную
        $mailResult = @mail($email, $subject, $emailText, $headers);
        
        if ($mailResult) {
            $emailSent = true;
            $resultData['emailSent'] = true;
        } else {
            $emailSent = false;
            $resultData['emailSent'] = false;
        }
    }

    $showResults = true;
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Орлов Игорь Сергеевич 241-353 ЛР6</title>
    <link rel="stylesheet" href="./style.css">
    <style>
        /* Дополнительные стили для формы и страницы печати */
        .form-container {
            max-width: 700px;
            margin: 40px auto;
            background: white;
            padding: 30px 40px;
            border-radius: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            text-align: left;
        }

        .form-group {
            display: flex;
            align-items: flex-start;
            margin-bottom: 18px;
        }

        .form-group label {
            width: 180px;
            flex-shrink: 0;
            font-weight: 500;
            color: #1a3a2c;
            padding-top: 8px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            flex: 1;
            padding: 10px 14px;
            border: 1px solid #cbdcd2;
            border-radius: 16px;
            font-size: 1rem;
            background: #fdfdfd;
            transition: all 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #0a2f1f;
            outline: none;
            box-shadow: 0 0 0 3px rgba(10, 47, 31, 0.1);
        }

        .checkbox-group {
            margin-left: 180px;
            margin-bottom: 18px;
        }

        .checkbox-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: normal;
            width: auto;
        }

        .btn {
            background: #0a2f1f;
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 40px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            margin-left: 180px;
            margin-top: 10px;
            transition: 0.2s;
            border: 1px solid #0a2f1f;
        }

        .btn:hover {
            background: #1a4a32;
            transform: scale(1.02);
        }

        .btn-link {
            display: inline-block;
            background: #e8f0ec;
            color: #0a2f1f;
            padding: 12px 28px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            border: 1px solid #0a2f1f;
            margin-top: 20px;
            transition: 0.2s;
        }

        .btn-link:hover {
            background: #0a2f1f;
            color: white;
            cursor: pointer;
        }

        .result-box {
            background: #f4f9f6;
            border-radius: 24px;
            padding: 24px;
            margin-top: 20px;
        }

        .result-row {
            display: flex;
            border-bottom: 1px dashed #bcd0c5;
            padding: 10px 0;
        }

        .result-label {
            width: 250px;
            font-weight: 600;
            color: #1e3a2c;
        }

        .result-value {
            flex: 1;
        }

        .success {
            color: #0a2f1f;
            font-weight: bold;
            background: #d4ede2;
            padding: 8px 16px;
            border-radius: 40px;
            display: inline-block;
        }

        .error {
            color: #a12;
            font-weight: bold;
            background: #ffe6e6;
            padding: 8px 16px;
            border-radius: 40px;
            display: inline-block;
        }

        .not-solved {
            color: #e67e22;
            font-weight: bold;
            background: #fef5e7;
            padding: 8px 16px;
            border-radius: 40px;
            display: inline-block;
        }

        #email-field {
            transition: opacity 0.2s;
        }

        .print-only {
            display: none;
        }

        /* Стили для режима печати */
        <?php if ($mode === 'print' && $showResults): ?>
        body {
            background: white;
            padding-top: 20px;
        }

        header,
        footer,
        .btn-link,
        .form-container form {
            display: none !important;
        }

        .form-container {
            box-shadow: none;
            padding: 0;
            margin: 0;
            background: white;
        }

        .result-box {
            background: none;
            border: 1px solid #000;
        }

        .print-only {
            display: block;
            margin-top: 30px;
            font-style: italic;
        }
        <?php endif; ?>
    </style>
</head>

<body>
    <header>
        <img src="./template_foto.jpeg" alt="Логотип" width="90">
        <span>Орлов Игорь Сергеевич 241-353 ЛР6</span>
    </header>
    <main>
        <div class="form-container">
            <?php if (!$showResults): ?>
                <!-- ФОРМА ВВОДА -->
                <h1>Тестирование знаний</h1>
                <form method="post" id="testForm">
                    <div class="form-group">
                        <label>ФИО *</label>
                        <input type="text" name="fio" value="<?= htmlspecialchars($savedFio) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Номер группы *</label>
                        <input type="text" name="group" value="<?= htmlspecialchars($savedGroup) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Значение A</label>
                        <input type="text" name="a" value="<?= $defaultA ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Значение B</label>
                        <input type="text" name="b" value="<?= $defaultB ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Значение C</label>
                        <input type="text" name="c" value="<?= $defaultC ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Ваш ответ</label>
                        <input type="text" name="user_answer" placeholder="0.00 (можно оставить пустым)">
                    </div>
                    <div class="form-group">
                        <label>Немного о себе</label>
                        <textarea name="about" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Тип задачи</label>
                        <select name="task_type" required>
                            <option value="area">Площадь треугольника</option>
                            <option value="perimeter">Периметр треугольника</option>
                            <option value="volume">Объем параллелепипеда</option>
                            <option value="average">Среднее арифметическое</option>
                            <option value="max">Максимальное из трех</option>
                            <option value="min">Минимальное из трех</option>
                            <option value="hypotenuse">Гипотенуза (A и B)</option>
                            <option value="circle_area">Площадь круга (R=среднее)</option>
                        </select>
                    </div>
                    <div class="checkbox-group">
                        <label>
                            <input type="checkbox" name="send_email" id="sendEmailCheck"> Отправить результат теста по e-mail
                        </label>
                    </div>
                    <div class="form-group" id="email-field" style="display: none;">
                        <label>Ваш e-mail</label>
                        <input type="email" name="email" placeholder="example@mail.ru">
                    </div>
                    <div class="form-group">
                        <label>Режим отображения</label>
                        <select name="display_mode">
                            <option value="view">Версия для просмотра в браузере</option>
                            <option value="print">Версия для печати</option>
                        </select>
                    </div>
                    <button type="submit" class="btn">Проверить</button>
                </form>
            <?php else: ?>
                <!-- РЕЗУЛЬТАТЫ -->
                <h1>📋 Результаты тестирования</h1>
                <div class="result-box">
                    <div class="result-row">
                        <span class="result-label">ФИО:</span>
                        <span class="result-value"><?= htmlspecialchars($resultData['fio']) ?></span>
                    </div>
                    <div class="result-row">
                        <span class="result-label">Группа:</span>
                        <span class="result-value"><?= htmlspecialchars($resultData['group']) ?></span>
                    </div>
                    <div class="result-row">
                        <span class="result-label">О себе:</span>
                        <span class="result-value"><?= $resultData['about'] ?: '—' ?></span>
                    </div>
                    <div class="result-row">
                        <span class="result-label">Тип задачи:</span>
                        <span class="result-value"><?= $resultData['taskName'] ?></span>
                    </div>
                    <div class="result-row">
                        <span class="result-label">Входные данные:</span>
                        <span class="result-value">A = <?= $resultData['a'] ?>, B = <?= $resultData['b'] ?>, C = <?= $resultData['c'] ?></span>
                    </div>
                    <div class="result-row">
                        <span class="result-label">Ваш ответ:</span>
                        <span class="result-value"><?= $resultData['displayAnswer'] ?></span>
                    </div>
                    <div class="result-row">
                        <span class="result-label">Правильный ответ:</span>
                        <span class="result-value"><?= $resultData['correctAnswer'] ?></span>
                    </div>
                    <div class="result-row">
                        <span class="result-label">Результат:</span>
                        <span class="result-value">
                            <?php if ($resultData['userAnswerRaw'] === ''): ?>
                                <span class="not-solved">📝 Задача самостоятельно решена не была</span>
                            <?php elseif ($resultData['isPassed']): ?>
                                <span class="success">✅ Тест пройден</span>
                            <?php else: ?>
                                <span class="error">❌ Ошибка: тест не пройден</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <?php if ($resultData['sendEmail'] && !empty($resultData['email'])): ?>
                        <div class="result-row">
                            <span class="result-label">E-mail отправка:</span>
                            <span class="result-value">
                                <?php if ($emailSent): ?>
                                    ✅ Результаты теста были автоматически отправлены на <?= htmlspecialchars($resultData['email']) ?>
                                <?php else: ?>
                                    ⚠️ Не удалось отправить письмо на <?= htmlspecialchars($resultData['email']) ?>
                                <?php endif; ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($mode === 'view'): ?>
                    <a href="index.php" class="btn-link">🔄 Повторить тест</a>
                <?php else: ?>
                    <div class="print-only">Версия для печати — результаты теста</div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>
    <footer>@copyright 2026</footer>

    <script>
        // Управление видимостью поля email
        const checkbox = document.getElementById('sendEmailCheck');
        const emailField = document.getElementById('email-field');
        if (checkbox && emailField) {
            checkbox.addEventListener('change', function() {
                emailField.style.display = this.checked ? 'flex' : 'none';
                if (!this.checked) {
                    const emailInput = emailField.querySelector('input');
                    if (emailInput) emailInput.value = '';
                }
            });
        }

        // Замена запятой на точку перед отправкой
        const form = document.getElementById('testForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const numberInputs = ['a', 'b', 'c', 'user_answer'];
                numberInputs.forEach(name => {
                    const field = form.querySelector(`[name="${name}"]`);
                    if (field && field.value) {
                        field.value = field.value.replace(/,/g, '.');
                    }
                });
            });
        }
    </script>
</body>

</html>