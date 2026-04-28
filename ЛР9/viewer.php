<?php
/**
 * Модуль viewer.php - просмотр записей с пагинацией и сортировкой
 */

function getViewContent($pdo, $sort_type, $page)
{
    // Определение сортировки
    switch ($sort_type) {
        case 'lastname':
            $order_by = 'lastname ASC';
            break;
        case 'birthdate':
            $order_by = 'birthdate ASC';
            break;
        default:
            $order_by = 'id ASC';
    }
    
    // Общее количество записей
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM contacts");
    $total_records = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    if ($total_records == 0) {
        return '<div class="success-message">В таблице нет данных</div>';
    }
    
    // Пагинация
    $records_per_page = 10;
    $total_pages = ceil($total_records / $records_per_page);
    
    if ($page < 1) $page = 1;
    if ($page > $total_pages) $page = $total_pages;
    
    $offset = ($page - 1) * $records_per_page;
    
    // Получение записей
    $sql = "SELECT * FROM contacts ORDER BY $order_by LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $contacts = $stmt->fetchAll();
    
    // Формирование таблицы
    $html = '<h2>Просмотр контактов</h2>';
    $html .= '<table>';
    $html .= '<thead><tr>
                <th>№</th><th>Фамилия</th><th>Имя</th><th>Отчество</th>
                <th>Пол</th><th>Дата рождения</th><th>Телефон</th>
                <th>E-mail</th><th>Адрес</th><th>Комментарий</th>
              </tr></thead><tbody>';
    
    foreach ($contacts as $index => $c) {
        $num = $offset + $index + 1;
        $html .= '<tr>';
        $html .= '<td>' . $num . '</td>';
        $html .= '<td>' . htmlspecialchars($c['lastname']) . '</td>';
        $html .= '<td>' . htmlspecialchars($c['firstname']) . '</td>';
        $html .= '<td>' . htmlspecialchars($c['middlename']) . '</td>';
        $html .= '<td>' . htmlspecialchars($c['gender']) . '</td>';
        $html .= '<td>' . htmlspecialchars($c['birthdate']) . '</td>';
        $html .= '<td>' . htmlspecialchars($c['phone']) . '</td>';
        $html .= '<td>' . htmlspecialchars($c['email']) . '</td>';
        $html .= '<td>' . htmlspecialchars($c['address']) . '</td>';
        $html .= '<td>' . htmlspecialchars($c['comment']) . '</td>';
        $html .= '</tr>';
    }
    
    $html .= '</tbody></table>';
    
    // Пагинация
    if ($total_pages > 1) {
        $html .= '<div class="pagination">';
        for ($i = 1; $i <= $total_pages; $i++) {
            $active = ($i == $page) ? ' active' : '';
            $url = '?menu=view&sort=' . urlencode($sort_type) . '&page=' . $i;
            $html .= '<a href="' . htmlspecialchars($url) . '" class="' . $active . '">' . $i . '</a>';
        }
        $html .= '</div>';
    }
    
    return $html;
}
?>