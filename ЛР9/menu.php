<?php
/**
 * Модуль menu.php - формирование меню
 */

function getMenu($active_menu)
{
    $sort_type = isset($_GET['sort']) ? $_GET['sort'] : 'id';
    
    $html = '<div class="menu-container">';
    $html .= '<div class="main-menu">';
    
    // Пункты главного меню
    $menu_items = [
        'view' => 'Просмотр',
        'add' => 'Добавление записи',
        'edit' => 'Редактирование записи',
        'delete' => 'Удаление записи'
    ];
    
    foreach ($menu_items as $key => $label) {
        $active_class = ($active_menu == $key) ? ' active' : '';
        $url = "?menu=" . $key;
        
        if ($key == 'view' && isset($_GET['sort'])) {
            $url .= "&sort=" . urlencode($_GET['sort']);
        }
        if ($key == 'view' && isset($_GET['page'])) {
            $url .= "&page=" . (int)$_GET['page'];
        }
        
        $html .= '<a href="' . htmlspecialchars($url) . '" class="menu-btn' . $active_class . '">' 
               . htmlspecialchars($label) . '</a>';
    }
    
    $html .= '</div>';
    
    // Подменю для сортировки (только при просмотре)
    if ($active_menu == 'view') {
        $html .= '<div class="sub-menu">';
        
        $sort_items = [
            'id' => 'По умолчанию',
            'lastname' => 'По фамилии',
            'birthdate' => 'По дате рождения'
        ];
        
        foreach ($sort_items as $key => $label) {
            $active_class = ($sort_type == $key) ? ' active' : '';
            $url = "?menu=view&sort=" . $key;
            if (isset($_GET['page'])) {
                $url .= "&page=" . (int)$_GET['page'];
            }
            $html .= '<a href="' . htmlspecialchars($url) . '" class="sub-menu-btn' . $active_class . '">' 
                   . htmlspecialchars($label) . '</a>';
        }
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    return $html;
}
?>