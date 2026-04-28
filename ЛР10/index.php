<?php
session_start();

// Инициализация сессии (история + счётчик итераций)
if (!isset($_SESSION['history'])) {
    $_SESSION['history'] = array();
}
if (!isset($_SESSION['iteration'])) {
    $_SESSION['iteration'] = 0;
}
$_SESSION['iteration']++;

// ==================== ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ ====================

// Ручной explode (запрещено использовать встроенный)
function manualExplode($delimiter, $string) {
    $result = array();
    $current = '';
    for ($i = 0; $i < strlen($string); $i++) {
        if ($string[$i] == $delimiter) {
            $result[] = $current;
            $current = '';
        } else {
            $current .= $string[$i];
        }
    }
    $result[] = $current;
    return $result;
}

// Ручной strpos (запрещено использовать встроенный)
function manualStrpos($haystack, $needle) {
    for ($i = 0; $i < strlen($haystack); $i++) {
        if ($haystack[$i] == $needle) {
            return $i;
        }
    }
    return false;
}

// Ручной substr (запрещено использовать встроенный)
function manualSubstr($string, $start, $length = null) {
    $result = '';
    $end = ($length !== null) ? $start + $length : strlen($string);
    for ($i = $start; $i < $end && $i < strlen($string); $i++) {
        $result .= $string[$i];
    }
    return $result;
}

// Функция isnum() из методички (проверка, является ли строка числом)
function isnum($x) {
    if (strlen($x) == 0) return false;
    
    $start = 0;
    // Обработка отрицательных чисел
    if ($x[0] == '-') {
        if (strlen($x) == 1) return false;
        $start = 1;
    }
    
    // Число не может начинаться с точки
    if ($x[$start] == '.') return false;
    // Число не может заканчиваться точкой
    if ($x[strlen($x)-1] == '.') return false;
    
    $point_count = false;
    $has_digit = false;
    
    for ($i = $start; $i < strlen($x); $i++) {
        $c = $x[$i];
        if ($c == '0' || $c == '1' || $c == '2' || $c == '3' ||
            $c == '4' || $c == '5' || $c == '6' || $c == '7' ||
            $c == '8' || $c == '9') {
            $has_digit = true;
            continue;
        }
        if ($c == '.') {
            if ($point_count) return false;
            $point_count = true;
            continue;
        }
        return false;
    }
    return $has_digit;
}

// Проверка корректности расстановки скобок (SqValidator)
function SqValidator($val) {
    $open = 0;
    for ($i = 0; $i < strlen($val); $i++) {
        if ($val[$i] == '(') {
            $open++;
        } elseif ($val[$i] == ')') {
            $open--;
            if ($open < 0) return false;
        }
    }
    return ($open == 0);
}

// Вычисление выражения БЕЗ скобок (calculate)
function calculate($val) {
    if (strlen($val) == 0) return 'Выражение не задано!';
    
    // Убираем пробелы
    $clean = '';
    for ($i = 0; $i < strlen($val); $i++) {
        if ($val[$i] != ' ') $clean .= $val[$i];
    }
    $val = $clean;
    
    // Если строка — число
    if (isnum($val)) return $val;
    
    // Замена ":" на "/" (поддержка двоеточия как знака деления)
    $tmp = '';
    for ($i = 0; $i < strlen($val); $i++) {
        $tmp .= ($val[$i] == ':') ? '/' : $val[$i];
    }
    $val = $tmp;
    
    // 1. Сложение
    $args = manualExplode('+', $val);
    if (count($args) > 1) {
        $sum = 0;
        for ($i = 0; $i < count($args); $i++) {
            $arg = calculate($args[$i]);
            if (!isnum($arg)) return $arg;
            $sum += (float)$arg;
        }
        if ($sum == (int)$sum) return (string)(int)$sum;
        return (string)$sum;
    }
    
    // 2. Вычитание
    $args = manualExplode('-', $val);
    if (count($args) > 1) {
        $first = calculate($args[0]);
        if (!isnum($first)) return $first;
        $sub = (float)$first;
        for ($i = 1; $i < count($args); $i++) {
            $arg = calculate($args[$i]);
            if (!isnum($arg)) return $arg;
            $sub -= (float)$arg;
        }
        if ($sub == (int)$sub) return (string)(int)$sub;
        return (string)$sub;
    }
    
    // 3. Умножение
    $args = manualExplode('*', $val);
    if (count($args) > 1) {
        $prod = 1;
        for ($i = 0; $i < count($args); $i++) {
            $arg = calculate($args[$i]);
            if (!isnum($arg)) return $arg;
            $prod *= (float)$arg;
        }
        if ($prod == (int)$prod) return (string)(int)$prod;
        return (string)$prod;
    }
    
    // 4. Деление
    $args = manualExplode('/', $val);
    if (count($args) > 1) {
        $first = calculate($args[0]);
        if (!isnum($first)) return $first;
        $div = (float)$first;
        for ($i = 1; $i < count($args); $i++) {
            $arg = calculate($args[$i]);
            if (!isnum($arg)) return $arg;
            $divisor = (float)$arg;
            if ($divisor == 0) return 'Деление на ноль!';
            $div /= $divisor;
        }
        if ($div == (int)$div) return (string)(int)$div;
        return (string)$div;
    }
    
    return 'Недопустимые символы в выражении';
}

// Вычисление выражения СО СКОБКАМИ (calculateSq)
function calculateSq($val) {
    // Удаляем пробелы
    $clean = '';
    for ($i = 0; $i < strlen($val); $i++) {
        if ($val[$i] != ' ') $clean .= $val[$i];
    }
    $val = $clean;
    
    if (strlen($val) == 0) return 'Выражение не задано!';
    if (!SqValidator($val)) return 'Неправильная расстановка скобок';
    
    $start = manualStrpos($val, '(');
    if ($start === false) {
        return calculate($val);
    }
    
    // Поиск соответствующей закрывающей скобки
    $end = $start + 1;
    $open = 1;
    while ($open && $end < strlen($val)) {
        if ($val[$end] == '(') $open++;
        if ($val[$end] == ')') $open--;
        $end++;
    }
    
    // Формируем новое выражение
    $left = manualSubstr($val, 0, $start);
    $inner = manualSubstr($val, $start + 1, $end - $start - 2);
    $inner_result = calculateSq($inner);
    
    if (!isnum($inner_result)) return $inner_result;
    
    $new_val = $left . $inner_result . manualSubstr($val, $end);
    return calculateSq($new_val);
}

// ==================== ОБРАБОТКА POST-ЗАПРОСА ====================

$result = '';
$error = '';
$expression = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['val'])) {
    $expression = trim($_POST['val']);
    
    // Защита от повторной отправки (F5) — проверка итерации
    if (isset($_POST['iteration']) && ($_POST['iteration'] + 1) == $_SESSION['iteration']) {
        $res = calculateSq($expression);
        
        if (isnum($res)) {
            $result = $res;
            $_SESSION['history'][] = $_POST['val'] . ' = ' . $res;
        } else {
            $error = $res;
            $_SESSION['history'][] = $_POST['val'] . ' = ' . $res . ' (ошибка)';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Орлов Игорь Сергеевич 241-353 ЛР10</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <header>
        <img src="template_foto.jpeg" alt="Логотип" class="logo">
        <span>Орлов Игорь Сергеевич 241-353 ЛР10</span>
    </header>
    
    <main>
        <h1>Арифметический калькулятор</h1>
        <p class="description">Поддерживаются целые числа и десятичные дроби.<br>
        Операции: + , - , * , / или : , скобки ( ) .</p>
        
        <form method="POST" action="" class="calculator-form">
            <input type="hidden" name="iteration" value="<?php echo $_SESSION['iteration']; ?>">
            <div class="form-row">
                <input type="text" 
                       name="val" 
                       value="<?php echo htmlspecialchars($expression); ?>" 
                       placeholder="Введите выражение, например: 2+3*(4-1)" 
                       required>
                <button type="submit">Вычислить</button>
            </div>
        </form>
        
        <!-- Отображение результата или ошибки -->
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['val']) && isset($_POST['iteration']) && ($_POST['iteration'] + 1) == $_SESSION['iteration']): ?>
            <div class="result-block">
                <?php if ($error): ?>
                    <p class="error-message"><strong>Ошибка:</strong> <?php echo htmlspecialchars($error); ?></p>
                <?php else: ?>
                    <p class="success-message"><strong>Результат:</strong> <?php echo htmlspecialchars($expression); ?> = <?php echo htmlspecialchars($result); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <!-- История вычислений (подвал сайта) -->
        <div class="history-section">
            <h3>История вычислений:</h3>
            <div class="history-list">
                <?php
                // Выводим историю построчно, как в методичке
                if (!empty($_SESSION['history'])) {
                    for ($i = 0; $i < count($_SESSION['history']); $i++) {
                        echo '<div class="history-item">' . htmlspecialchars($_SESSION['history'][$i]) . '</div>';
                    }
                } else {
                    echo '<div class="history-item empty">История пуста</div>';
                }
                ?>
            </div>
        </div>
    </main>
    
    <footer>
        &copy; 2026 
    </footer>
</div>
</body>
</html>