<?php
$x = -10;               // начальное значение аргумента
$encounting = 25;       // количество вычисляемых значений
$step = 2;              // шаг изменения аргумента
$min_value = -1000;     // минимальное значение функции (остановка)
$max_value = 1000;      // максимальное значение функции (остановка)

$type = 'D';


function calculateFunction($x)
{
    if ($x <= 10) {
        // f(x) = (6/(x-5)) · x - 5
        if ($x == 5) {
            return "error";
        }
        return (6 / ($x - 5)) * $x - 5;
    } elseif ($x < 20) {
        // f(x) = (x-5)/((x²-1)·x+7)
        $denominator = ($x * $x - 1) * $x + 7;
        if ($denominator == 0) {
            return "error";
        }
        return ($x - 5) / $denominator;
    } else {
        // f(x) = (4·x+5)/√x
        if ($x <= 0) {
            return "error";
        }
        return (4 * $x + 5) / sqrt($x);
    }
}
// ============================================

// ============================================
// Вычисление значений с проверкой границ
// ============================================
$results = [];        // массив для хранения [x => y]
$currentX = $x;

for ($i = 0; $i < $encounting; $i++) {
    $y = calculateFunction($currentX);

    if ($y == "error") {
        $results[$currentX] = $y;
        $currentX += $step;
        continue;
    }

    // Проверка на превышение max или min
    if ($y > $max_value || $y < $min_value) {
        break;  // останавливаем вычисления
    }

    $results[$currentX] = round($y, 3);
    $currentX += $step;
}

// ============================================
// 6. Статистика по значениям функции
// ============================================
$values = array_values($results);
$clean_values = array_diff($values, ['error']);
$sum = array_sum($clean_values);
$count = count($clean_values);
$average = $count > 0 ? $sum / $count : 0;
$maxValue = $count > 0 ? max($clean_values) : null;
$minValue = $count > 0 ? min($clean_values) : null;

function renderResults($results, $layoutType)
{
    $output = '';
    $index = 1;

    switch ($layoutType) {
        case 'A': // Простая верстка текстом
            foreach ($results as $x => $y) {
                $output .= "f($x)=$y<br>\n";
            }
            break;

        case 'B': // Маркированный список
            $output .= "<ul>\n";
            foreach ($results as $x => $y) {
                $output .= "  <li>f($x)=$y</li>\n";
            }
            $output .= "</ul>\n";
            break;

        case 'C': // Нумерованный список
            $output .= "<ol>\n";
            foreach ($results as $x => $y) {
                $output .= "  <li>f($x)=$y</li>\n";
            }
            $output .= "</ol>\n";
            break;

        case 'D': // Табличная верстка
            $output .= "<table style='border-collapse: collapse; width: 100%;'>\n";
            $output .= "  <thead>\n";
            $output .= "    <tr style='background-color: #0a2f1f; color: white;'>\n";
            $output .= "      <th style='border: 1px solid black; padding: 8px;'>№</th>\n";
            $output .= "      <th style='border: 1px solid black; padding: 8px;'>Аргумент (x)</th>\n";
            $output .= "      <th style='border: 1px solid black; padding: 8px;'>Значение f(x)</th>\n";
            $output .= "    </tr>\n";
            $output .= "  </thead>\n";
            $output .= "  <tbody>\n";
            foreach ($results as $x => $y) {
                $output .= "    <tr>\n";
                $output .= "      <td style='border: 1px solid black; padding: 8px; text-align: center;'>{$index}</td>\n";
                $output .= "      <td style='border: 1px solid black; padding: 8px;'>{$x}</td>\n";
                $output .= "      <td style='border: 1px solid black; padding: 8px;'>{$y}</td>\n";
                $output .= "    </tr>\n";
                $index++;
            }
            $output .= "  </tbody>\n";
            $output .= "</table>\n";
            break;

        case 'E': // Блочная верстка (горизонтальная)
            $output .= "<div style='display: flex; flex-wrap: wrap; gap: 8px;'>\n";
            foreach ($results as $x => $y) {
                $output .= "  <div style='border: 2px solid red; padding: 10px; margin: 0;'>";
                $output .= "f($x)=$y";
                $output .= "</div>\n";
            }
            $output .= "</div>\n";
            break;

        default:
            $output = "<p style='color: red;'>Ошибка: неизвестный тип вёрстки '$layoutType'</p>";
    }

    return $output;
}
?>

<!doctype html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Орлов Игорь Сергеевич 241-353 ЛР-2 Вариант 7</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <img src="./template_foto.jpeg" alt="Логотип университета" width="90">
    </header>

    <main>
        <h1>Лабораторная работа №2</h1>
        <p>Вычисление значений функции с заданными параметрами</p>

        <!-- Система уравнений для варианта 7 -->
        <div style="background: #f5f5f5; padding: 15px; border-radius: 10px; margin: 15px 0;">
            <h3>Система уравнений (вариант 7):</h3>
            <p>f(x) = (6/(x-5)) · x - 5, &nbsp;&nbsp; при x ≤ 10</p>
            <p>f(x) = (x-5) / ((x²-1)·x + 7), &nbsp;&nbsp; при 10 &lt; x &lt; 20</p>
            <p>f(x) = (4·x + 5) / √x, &nbsp;&nbsp; при x ≥ 20</p>
            <p><strong>Особая точка:</strong> x = 5 (деление на ноль) → выводится "error"</p>
        </div>

        <div class="info-badge">
            Параметры: x₀ = <?= $x ?>, шаг = <?= $step ?>,
            количество = <?= $encounting ?>,
            ограничения: f(x) ∈ [<?= $min_value ?>, <?= $max_value ?>]
        </div>

        <?php if (empty($results)): ?>
            <div style="background: #ffe0b3; padding: 20px; border-radius: 10px;">
                Нет результатов вычислений. Проверьте начальные параметры.
            </div>
        <?php else: ?>
            <!-- Вывод результатов в зависимости от типа вёрстки -->
            <div style="margin: 30px 0;">
                <h2>Результаты вычислений (тип вёрстки: <?= $type ?>)</h2>
                <?= renderResults($results, $type) ?>
            </div>

            <!-- Статистика -->
            <div class="stats">
                <h3>Статистика значений функции</h3>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="label">Максимум</div>
                        <div class="value"><?= round($maxValue, 3) ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Минимум</div>
                        <div class="value"><?= round($minValue, 3) ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Среднее арифметическое</div>
                        <div class="value"><?= round($average, 3) ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="label">Сумма</div>
                        <div class="value"><?= round($sum, 3) ?></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <span>Тип вёрстки: <?= $type ?></span>
    </footer>
</body>

</html>