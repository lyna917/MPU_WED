<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Орлов Игорь Сергеевич 241-353 ЛР?</title>
    <link rel="stylesheet" href="./style.css">
    <style>
        .element-row {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .element-number {
            font-weight: bold;
            min-width: 50px;
            color: #0a2f1f;
        }
        .element-input {
            padding: 8px 12px;
            border: 1px solid #cbdcd2;
            border-radius: 6px;
            font-size: 16px;
            width: 180px;
        }
        .element-input:focus {
            outline: none;
            border-color: #0a2f1f;
            box-shadow: 0 0 0 2px rgba(10, 47, 31, 0.2);
        }
        #elements-table {
            margin: 20px 0;
            width: 100%;
            max-width: 450px;
            margin-left: auto;
            margin-right: auto;
        }
        .selector {
            width: 100%;
            max-width: 350px;
            padding: 10px 15px;
            margin: 15px 0;
            border-radius: 30px;
            border: 1px solid #0a2f1f;
            background: white;
            font-size: 16px;
            cursor: pointer;
        }
        .btn {
            background: #0a2f1f;
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 40px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin: 8px 6px;
            transition: all 0.2s;
        }
        .btn-secondary {
            background: white;
            color: #0a2f1f;
            border: 1px solid #0a2f1f;
        }
        .btn:hover {
            transform: scale(1.02);
            opacity: 0.9;
        }
        .form-card {
            background: white;
            padding: 30px;
            border-radius: 25px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            max-width: 550px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <header>
        <img src="./template_foto.jpeg" alt="Логотип" width="80" height="80" style="object-fit: cover; border-radius: 50%;">
        <span>Орлов Игорь Сергеевич 241-353 ЛР7</span>
    </header>

    <main>
        <h1>📊 Сортировка одномерного массива</h1>
        <div class="form-card">
            <form id="sortForm" action="sort.php" method="POST" target="_blank">
                <!-- Таблица для динамического добавления элементов -->
                <table id="elements-table">
                    <tbody id="elements">
                        <tr>
                            <td class="element-row">
                                <span class="element-number">[0]</span>
                                <input type="text" name="element0" class="element-input" placeholder="Введите число">
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Скрытое поле для хранения количества элементов -->
                <input type="hidden" id="arrLength" name="arrLength" value="1">

                <!-- Селектор выбора алгоритма -->
                <select name="algorithm" class="selector" required>
                    <option value="">-- Выберите алгоритм сортировки --</option>
                    <option value="selection">Сортировка выбором</option>
                    <option value="bubble">Пузырьковый алгоритм</option>
                    <option value="shell">Алгоритм Шелла</option>
                    <option value="gnome">Алгоритм садового гнома</option>
                    <option value="quick">Быстрая сортировка</option>
                    <option value="php">Встроенная функция PHP (sort)</option>
                </select>

                <div>
                    <button type="button" class="btn btn-secondary" id="addElementBtn">➕ Добавить еще один элемент</button>
                    <button type="submit" class="btn">🚀 Сортировать массив</button>
                </div>
            </form>
        </div>
    </main>

    <footer>
        <span>&copy; 2026 Агаев Арслан</span>
        <span>Лабораторная работа №7</span>
    </footer>

    <script>
        // Функция setHTML для кросс-браузерной установки содержимого
        function setHTML(element, htmlContent) {
            if (element.innerHTML !== undefined) {
                element.innerHTML = htmlContent;
            } else {
                var range = document.createRange();
                range.selectNodeContents(element);
                range.deleteContents();
                var fragment = range.createContextualFragment(htmlContent);
                element.appendChild(fragment);
            }
        }

        // Функция обновления номеров элементов и скрытого поля
        function updateElementNumbers() {
            var tbody = document.getElementById('elements');
            var rows = tbody.getElementsByTagName('tr');
            for (var i = 0; i < rows.length; i++) {
                var numberSpan = rows[i].querySelector('.element-number');
                if (numberSpan) {
                    numberSpan.textContent = '[' + i + ']';
                }
                var input = rows[i].querySelector('input');
                if (input) {
                    input.name = 'element' + i;
                }
            }
            // Обновляем скрытое поле с количеством элементов
            document.getElementById('arrLength').value = rows.length;
        }

        // Функция добавления одного элемента (строго по заданию - только одну строку)
        function addElement() {
            var tbody = document.getElementById('elements');
            var currentCount = tbody.rows.length;
            
            // Создаем новую строку
            var newRow = tbody.insertRow();
            var newCell = newRow.insertCell(0);
            newCell.className = 'element-row';
            
            // Формируем содержимое ячейки
            var cellContent = '<span class="element-number">[' + currentCount + ']</span>' +
                              '<input type="text" name="element' + currentCount + '" class="element-input" placeholder="Введите число">';
            
            setHTML(newCell, cellContent);
            
            // Обновляем нумерацию всех элементов
            updateElementNumbers();
        }

        // Привязываем обработчик к кнопке после загрузки DOM
        document.addEventListener('DOMContentLoaded', function() {
            var addBtn = document.getElementById('addElementBtn');
            if (addBtn) {
                addBtn.addEventListener('click', addElement);
            }
            
            // Дополнительная валидация перед отправкой формы
            var form = document.getElementById('sortForm');
            form.addEventListener('submit', function(e) {
                var inputs = document.querySelectorAll('#elements input');
                var hasValue = false;
                for (var i = 0; i < inputs.length; i++) {
                    if (inputs[i].value.trim() !== '') {
                        hasValue = true;
                        break;
                    }
                }
                if (!hasValue) {
                    if (!confirm('Массив пуст! Продолжить? (Будет выведено предупреждение)')) {
                        e.preventDefault();
                    }
                }
            });
        });
    </script>
</body>
</html>