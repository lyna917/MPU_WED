<?php
/**
 * Лабораторная работа №8
 * Анализ текста: подсчет символов, букв, слов, частоты символов и слов
 * Студент: Орлов Игорь Сергеевич 241-353 ЛР8
 */

// Получаем текст из формы
$text_utf8 = isset($_POST['usertext']) ? trim($_POST['usertext']) : '';

// Конвертируем из UTF-8 в CP1251 для корректной обработки
$text_cp1251 = iconv('UTF-8', 'CP1251//TRANSLIT//IGNORE', $text_utf8);

if ($text_cp1251 === false || $text_cp1251 === '') {
    $text_cp1251 = '';
    $has_text = false;
} else {
    $has_text = true;
}

// ========== ФУНКЦИИ ДЛЯ ПРОВЕРКИ СИМВОЛОВ ==========

function is_letter_cp1251($char) {
    $code = ord($char);
    // Русские буквы: А-Я (192-223), а-я (224-255), Ё(168), ё(184)
    // Английские буквы: A-Z (65-90), a-z (97-122)
    return ($code >= 192 && $code <= 255) || 
           $code == 168 || $code == 184 ||
           ($code >= 65 && $code <= 90) ||
           ($code >= 97 && $code <= 122);
}

function is_lowercase_cp1251($char) {
    $code = ord($char);
    return ($code >= 224 && $code <= 255) ||   // а-я
           $code == 184 ||                      // ё
           ($code >= 97 && $code <= 122);       // a-z
}

function is_uppercase_cp1251($char) {
    $code = ord($char);
    return ($code >= 192 && $code <= 223) ||   // А-Я
           $code == 168 ||                      // Ё
           ($code >= 65 && $code <= 90);        // A-Z
}

function is_punctuation_cp1251($char) {
    $punct = ".,!?;:()-\"'«»[]{}…";
    return strpos($punct, $char) !== false;
}

function is_digit_cp1251($char) {
    $code = ord($char);
    return $code >= 48 && $code <= 57;
}

// Разделители слов: пробел, перенос строки, табуляция, знаки препинания
function is_separator_cp1251($char) {
    if ($char == ' ' || $char == "\n" || $char == "\r" || $char == "\t") {
        return true;
    }
    return is_punctuation_cp1251($char);
}

function to_lower_cp1251($char) {
    $code = ord($char);
    if ($code == 168) return chr(184);           // Ё -> ё
    if ($code >= 192 && $code <= 223) return chr($code + 32); // А-Я -> а-я
    if ($code >= 65 && $code <= 90) return chr($code + 32);   // A-Z -> a-z
    return $char;
}

// функция извлечения слов
function extract_words_cp1251($text) {
    $words = [];
    $current_word = '';
    $len = strlen($text);
    
    for ($i = 0; $i < $len; $i++) {
        $char = $text[$i];
        
        // Если символ - буква или цифра
        if (is_letter_cp1251($char) || is_digit_cp1251($char)) {
            $current_word .= to_lower_cp1251($char);
        } 
        // Если символ - разделитель (пробел, запятая, точка и т.д.)
        else {
            if ($current_word !== '') {
                $words[] = $current_word;
                $current_word = '';
            }
        }
    }
    
    // Последнее слово
    if ($current_word !== '') {
        $words[] = $current_word;
    }
    
    return $words;
}
// ========== ПОДСЧЕТ СТАТИСТИКИ ==========

if ($has_text && $text_cp1251 !== '') {
    $total_chars = strlen($text_cp1251);
    $letter_count = 0;
    $lower_count = 0;
    $upper_count = 0;
    $punct_count = 0;
    $digit_count = 0;
    $char_freq = [];
    
    for ($i = 0; $i < $total_chars; $i++) {
        $char = $text_cp1251[$i];
        
        if (is_letter_cp1251($char)) {
            $letter_count++;
            if (is_lowercase_cp1251($char)) {
                $lower_count++;
            } elseif (is_uppercase_cp1251($char)) {
                $upper_count++;
            }
        } elseif (is_punctuation_cp1251($char)) {
            $punct_count++;
        } elseif (is_digit_cp1251($char)) {
            $digit_count++;
        }
        
        $lower_char = to_lower_cp1251($char);
        if (isset($char_freq[$lower_char])) {
            $char_freq[$lower_char]++;
        } else {
            $char_freq[$lower_char] = 1;
        }
    }
    
    ksort($char_freq);
    
    // Подсчет слов
    $words = extract_words_cp1251($text_cp1251);
    $word_count = count($words);
    
    $word_freq = [];
    foreach ($words as $word) {
        if (isset($word_freq[$word])) {
            $word_freq[$word]++;
        } else {
            $word_freq[$word] = 1;
        }
    }
    
    ksort($word_freq);
    
    // Конвертация обратно в UTF-8
    $text_display = iconv('CP1251', 'UTF-8//TRANSLIT//IGNORE', $text_cp1251);
    
    $char_freq_display = [];
    foreach ($char_freq as $char => $count) {
        $char_utf8 = iconv('CP1251', 'UTF-8//TRANSLIT//IGNORE', $char);
        if ($char_utf8 !== false && $char_utf8 !== '') {
            $char_freq_display[$char_utf8] = $count;
        }
    }
    
    $word_freq_display = [];
    foreach ($word_freq as $word => $count) {
        $word_utf8 = iconv('CP1251', 'UTF-8//TRANSLIT//IGNORE', $word);
        if ($word_utf8 !== false && $word_utf8 !== '') {
            $word_freq_display[$word_utf8] = $count;
        }
    }
} else {
    $has_text = false;
    $text_display = '';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Орлов Игорь Сергеевич 241-353 ЛР8 - Результат</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <h1>Результат анализа текста</h1>

        <div class="card result-card">
            <h3>Исходный текст</h3>
            <?php if (!$has_text || $text_display === ''): ?>
                <div class="original-text empty-text">Нет текста для анализа</div>
            <?php else: ?>
                <div class="original-text"><?php echo nl2br(htmlspecialchars($text_display, ENT_QUOTES, 'UTF-8')); ?></div>
            <?php endif; ?>
        </div>

        <?php if ($has_text && $text_display !== ''): ?>
            <div class="card">
                <h3>Статистика текста</h3>
                <table>
                    <thead>
                        <tr><th>Параметр</th><th>Значение</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Количество символов (включая пробелы)</td><td><?php echo $total_chars; ?></td></tr>
                        <tr><td>Количество букв</td><td><?php echo $letter_count; ?></td></tr>
                        <tr><td>Количество строчных букв</td><td><?php echo $lower_count; ?></td></tr>
                        <tr><td>Количество заглавных букв</td><td><?php echo $upper_count; ?></td></tr>
                        <tr><td>Количество знаков препинания</td><td><?php echo $punct_count; ?></td></tr>
                        <tr><td>Количество цифр</td><td><?php echo $digit_count; ?></td></tr>
                        <tr><td>Количество слов</td><td><?php echo $word_count; ?></td></tr>
                    </tbody>
                </table>
            </div>

            <div class="card">
                <h3>Частота символов (без учета регистра)</h3>
                <table>
                    <thead><tr><th>Символ</th><th>Количество вхождений</th></tr></thead>
                    <tbody>
                        <?php foreach ($char_freq_display as $char => $count): ?>
                        <tr><td><?php echo htmlspecialchars($char, ENT_QUOTES, 'UTF-8'); ?></td><td><?php echo $count; ?></td></tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="card">
                <h3>Частота слов (по алфавиту)</h3>
                <table>
                    <thead><tr><th>Слово</th><th>Количество вхождений</th></tr></thead>
                    <tbody>
                        <?php foreach ($word_freq_display as $word => $count): ?>
                        <tr><td><?php echo htmlspecialchars($word, ENT_QUOTES, 'UTF-8'); ?></td><td><?php echo $count; ?></td></tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div class="nav-links">
            <a href="index.html" class="button-link">← Другой анализ</a>
        </div>
    </main>
        <footer>
        <div class="footer-content">
            <span>© 2026 Орлов Игорь Сергеевич</span>
            <span>Результат анализа</span>
        </div>
    </footer>
</body>
</html>