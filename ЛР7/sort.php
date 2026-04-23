<?php
// Устанавливаем неограниченное время выполнения
set_time_limit(0);

// Функция проверки: является ли строка числом (целым или дробным)
function isNumber($str) {
    $str = trim($str);
    if ($str === '') return false;
    // Заменяем запятую на точку для дробных чисел
    $str = str_replace(',', '.', $str);
    return is_numeric($str);
}

// Функция для вывода состояния массива на каждой итерации
function printIteration($iterNum, $array, $comment = '') {
    $formattedArray = '[ ' . implode(' ', $array) . ' ]';
    echo "<div class='iter-line'>";
    echo "<span class='iter-num'>Итерация {$iterNum}</span>";
    echo "<span class='iter-array'>{$formattedArray}</span>";
    if ($comment) {
        echo "<span class='iter-comment'>// {$comment}</span>";
    }
    echo "</div>";
}

// Класс для отслеживания итераций и времени
class SortTracker {
    public $iterationCount = 0;
    public $startTime = 0;
    public $array = [];
    
    public function start(&$arr) {
        $this->array = &$arr;
        $this->startTime = microtime(true);
        $this->iterationCount = 0;
    }
    
    public function logIteration($comment = '') {
        $this->iterationCount++;
        printIteration($this->iterationCount, $this->array, $comment);
    }
    
    public function finish() {
        $elapsed = microtime(true) - $this->startTime;
        echo "<div class='result-summary'>";
        echo "<p>✅ Сортировка завершена, проведено <strong>{$this->iterationCount}</strong> итераций.</p>";
        echo "<p>⏱️ Сортировка заняла <strong>" . number_format($elapsed, 6) . "</strong> секунд.</p>";
        echo "</div>";
    }
}

// Получение данных из POST
$algorithm = isset($_POST['algorithm']) ? $_POST['algorithm'] : '';
$arrLength = isset($_POST['arrLength']) ? (int)$_POST['arrLength'] : 0;

// Сбор элементов массива
$rawElements = [];
for ($i = 0; $i < $arrLength; $i++) {
    $fieldName = 'element' . $i;
    if (isset($_POST[$fieldName])) {
        $value = trim($_POST[$fieldName]);
        if ($value !== '') {
            $rawElements[] = $value;
        }
    }
}

// Названия алгоритмов
$algoNames = [
    'selection' => 'Сортировка выбором',
    'bubble'    => 'Пузырьковый алгоритм',
    'shell'     => 'Алгоритм Шелла',
    'gnome'     => 'Алгоритм садового гнома',
    'quick'     => 'Быстрая сортировка',
    'php'       => 'Встроенная функция PHP (sort)'
];
$algoTitle = isset($algoNames[$algorithm]) ? $algoNames[$algorithm] : 'Неизвестный алгоритм';

// Проверка на наличие данных
$hasData = count($rawElements) > 0;
$allNumbers = true;
$numbersArray = [];

if ($hasData) {
    foreach ($rawElements as $val) {
        if (isNumber($val)) {
            $numbersArray[] = (float)str_replace(',', '.', $val);
        } else {
            $allNumbers = false;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Результат сортировки</title>
    <link rel="stylesheet" href="./style.css">
    <style>
        body {
            background: #eef2f0;
            padding-top: 110px;
            padding-bottom: 80px;
        }
        .result-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        .algo-badge {
            background: #0a2f1f;
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            margin-bottom: 25px;
        }
        .algo-badge h1 {
            color: white;
            margin: 0;
            border-left-color: white;
        }
        .input-block {
            background: #f0f5f2;
            padding: 15px 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            font-family: monospace;
            font-size: 16px;
        }
        .error-block {
            background: #ffebee;
            color: #b71c1c;
            padding: 20px;
            border-radius: 15px;
            border-left: 5px solid #b71c1c;
            margin: 20px 0;
        }
        .iterations-log {
            background: #ffffff;
            border-radius: 20px;
            padding: 10px 0;
            margin: 20px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            max-height: 500px;
            overflow-y: auto;
        }
        .iter-line {
            font-family: 'Courier New', monospace;
            padding: 8px 18px;
            border-bottom: 1px solid #e0e8e2;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: baseline;
        }
        .iter-line:nth-child(even) {
            background: #fafdfb;
        }
        .iter-num {
            font-weight: bold;
            color: #0a2f1f;
            min-width: 110px;
        }
        .iter-array {
            color: #1e3a2a;
            font-weight: 500;
            background: #f0f5f2;
            padding: 4px 12px;
            border-radius: 20px;
            font-family: monospace;
        }
        .iter-comment {
            color: #6b7c73;
            font-style: italic;
            font-size: 13px;
        }
        .result-summary {
            margin-top: 25px;
            padding: 20px;
            background: #e8f0ec;
            border-radius: 20px;
            font-size: 1.1rem;
            text-align: center;
        }
        .back-btn {
            display: inline-block;
            margin-top: 30px;
            background: #0a2f1f;
            color: white;
            padding: 12px 28px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.2s;
        }
        .back-btn:hover {
            opacity: 0.85;
            transform: scale(1.02);
        }
        h3 {
            color: #0a2f1f;
            margin: 15px 0 10px 0;
        }
        .source-array {
            font-family: monospace;
            font-size: 18px;
            font-weight: bold;
            color: #0a2f1f;
        }
    </style>
</head>
<body>
    <header>
        <img src="./template_foto.jpeg" alt="Логотип" width="80" height="80" style="object-fit: cover; border-radius: 50%;">
        <span>Орлов Игорь Сергеевич 241-353 ЛР7</span>
    </header>

    <main>
        <div class="result-container">
            <div class="algo-badge">
                <h1>📊 Алгоритм: <?php echo htmlspecialchars($algoTitle); ?></h1>
            </div>

            <div class="input-block">
                <strong>🔢 Входные данные:</strong>
                <span class="source-array">
                    <?php
                    if ($hasData) {
                        echo '[ ' . implode(' ', array_map('htmlspecialchars', $rawElements)) . ' ]';
                    } else {
                        echo '<em>отсутствуют</em>';
                    }
                    ?>
                </span>
            </div>

            <?php if (!$hasData): ?>
                <div class="error-block">
                    ⚠️ <strong>Ошибка валидации:</strong> Входные данные отсутствуют. Сортировка не выполняется.
                </div>
            <?php elseif (!$allNumbers): ?>
                <div class="error-block">
                    ⚠️ <strong>Ошибка валидации:</strong> Среди элементов массива есть не числа. Сортировка невозможна.
                </div>
            <?php else: ?>
                <div class="input-block" style="background:#e8f0ec;">
                    <strong>✅ Результат валидации:</strong> Все элементы являются числами. Сортировка возможна.
                </div>

                <h3>🔄 Процесс сортировки (пошагово):</h3>
                <div class="iterations-log">
                    <?php
                    $arr = $numbersArray;
                    $n = count($arr);
                    $tracker = new SortTracker();
                    $tracker->start($arr);
                    
                    // Выводим начальное состояние (итерация 0 - исходный массив)
                    printIteration(0, $arr, 'Исходный массив');
                    
                    switch ($algorithm) {
                        // ========== СОРТИРОВКА ВЫБОРОМ ==========
                        case 'selection':
                            for ($i = 0; $i < $n - 1; $i++) {
                                $minIdx = $i;
                                for ($j = $i + 1; $j < $n; $j++) {
                                    if ($arr[$j] < $arr[$minIdx]) {
                                        $minIdx = $j;
                                    }
                                    $tracker->logIteration("сравнение arr[{$j}]={$arr[$j]} с arr[{$minIdx}]={$arr[$minIdx]}");
                                }
                                if ($minIdx != $i) {
                                    $temp = $arr[$i];
                                    $arr[$i] = $arr[$minIdx];
                                    $arr[$minIdx] = $temp;
                                    $tracker->logIteration("меняем местами arr[{$i}] и arr[{$minIdx}]");
                                } else {
                                    $tracker->logIteration("минимальный элемент уже на месте arr[{$i}]");
                                }
                            }
                            break;
                        
                        // ========== ПУЗЫРЬКОВАЯ СОРТИРОВКА ==========
                        case 'bubble':
                            for ($i = 0; $i < $n - 1; $i++) {
                                $swapped = false;
                                for ($j = 0; $j < $n - $i - 1; $j++) {
                                    if ($arr[$j] > $arr[$j + 1]) {
                                        $temp = $arr[$j];
                                        $arr[$j] = $arr[$j + 1];
                                        $arr[$j + 1] = $temp;
                                        $swapped = true;
                                        $tracker->logIteration("меняем arr[{$j}]={$temp} и arr[" . ($j+1) . "]={$arr[$j]}");
                                    } else {
                                        $tracker->logIteration("сравнение: arr[{$j}]={$arr[$j]} ≤ arr[" . ($j+1) . "]={$arr[$j+1]}, без замены");
                                    }
                                }
                                if (!$swapped) {
                                    $tracker->logIteration("массив уже отсортирован, досрочный выход");
                                    break;
                                }
                                $tracker->logIteration("завершен проход " . ($i+1));
                            }
                            break;
                        
                        // ========== АЛГОРИТМ ШЕЛЛА ==========
                        case 'shell':
                            $gap = floor($n / 2);
                            while ($gap > 0) {
                                for ($i = $gap; $i < $n; $i++) {
                                    $temp = $arr[$i];
                                    $j = $i;
                                    while ($j >= $gap && $arr[$j - $gap] > $temp) {
                                        $arr[$j] = $arr[$j - $gap];
                                        $j -= $gap;
                                        $tracker->logIteration("сдвиг: arr[{$j}] = arr[" . ($j+$gap) . "], gap={$gap}");
                                    }
                                    $arr[$j] = $temp;
                                    if ($j != $i) {
                                        $tracker->logIteration("вставка значения {$temp} на позицию {$j}, gap={$gap}");
                                    } else {
                                        $tracker->logIteration("элемент {$temp} уже на месте, gap={$gap}");
                                    }
                                }
                                $gap = floor($gap / 2);
                                if ($gap > 0) {
                                    $tracker->logIteration("уменьшаем шаг до {$gap}");
                                }
                            }
                            break;
                        
                        // ========== АЛГОРИТМ САДОВОГО ГНОМА ==========
                        case 'gnome':
                            $i = 1;
                            $stepBackCounter = 0;
                            while ($i < $n) {
                                if ($arr[$i - 1] <= $arr[$i]) {
                                    $tracker->logIteration("arr[{$i}]={$arr[$i]} ≥ arr[" . ($i-1) . "]={$arr[$i-1]}, шагаем вперед");
                                    $i++;
                                } else {
                                    $temp = $arr[$i];
                                    $arr[$i] = $arr[$i - 1];
                                    $arr[$i - 1] = $temp;
                                    $tracker->logIteration("меняем arr[{$i}] и arr[" . ($i-1) . "], шагаем назад");
                                    $i--;
                                    if ($i == 0) {
                                        $tracker->logIteration("достигнут первый элемент, шагаем вперед");
                                        $i = 1;
                                    }
                                }
                            }
                            break;
                        
                        // ========== БЫСТРАЯ СОРТИРОВКА ==========
                        case 'quick':
                            $quickSortFunc = null;
                            $quickSortFunc = function(&$arr, $low, $high) use (&$quickSortFunc, $tracker) {
                                if ($low < $high) {
                                    $pivot = $arr[$high];
                                    $i = $low - 1;
                                    for ($j = $low; $j < $high; $j++) {
                                        if ($arr[$j] <= $pivot) {
                                            $i++;
                                            $temp = $arr[$i];
                                            $arr[$i] = $arr[$j];
                                            $arr[$j] = $temp;
                                            $tracker->logIteration("разбиение: arr[{$i}] ↔ arr[{$j}], опора={$pivot}");
                                        } else {
                                            $tracker->logIteration("разбиение: arr[{$j}]={$arr[$j]} > опоры {$pivot}, без замены");
                                        }
                                    }
                                    $temp = $arr[$i + 1];
                                    $arr[$i + 1] = $arr[$high];
                                    $arr[$high] = $temp;
                                    $pi = $i + 1;
                                    $tracker->logIteration("опора {$pivot} установлена на позицию {$pi}");
                                    $quickSortFunc($arr, $low, $pi - 1);
                                    $quickSortFunc($arr, $pi + 1, $high);
                                } else {
                                    if ($low == $high) {
                                        $tracker->logIteration("подмассив из одного элемента [{$low}], пропускаем");
                                    }
                                }
                            };
                            $quickSortFunc($arr, 0, $n - 1);
                            break;
                        
                        // ========== ВСТРОЕННАЯ ФУНКЦИЯ PHP ==========
                        case 'php':
                            sort($arr);
                            $tracker->logIteration("встроенная сортировка PHP (sort) выполнена");
                            break;
                        
                        default:
                            echo "<div class='error-block'>Неизвестный алгоритм сортировки</div>";
                    }
                    
                    $tracker->finish();
                    ?>
                </div>
                
                <div style="text-align: center;">
                    <a href="index.php" class="back-btn">← Вернуться к форме ввода</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <span>&copy; 2026 Орлов Игорь Сергеевич / 241-353</span>
        <span>Лабораторная работа №7</span>
    </footer>
</body>
</html>