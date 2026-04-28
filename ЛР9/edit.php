<?php
/**
 * Модуль edit.php - редактирование записи
 */

function getEditContent($pdo)
{
    $message = '';
    
    // Получаем список всех контактов
    $stmt = $pdo->query("SELECT id, lastname, firstname FROM contacts ORDER BY lastname, firstname");
    $contacts = $stmt->fetchAll();
    
    if (count($contacts) == 0) {
        return '<h2>Редактирование записи</h2><div class="success-message">Нет записей для редактирования</div>';
    }
    
    // Определяем выбранный контакт (первый по умолчанию)
    $selected_id = isset($_GET['edit_id']) ? (int)$_GET['edit_id'] : $contacts[0]['id'];
    
    // Получаем данные выбранного контакта
    $stmt = $pdo->prepare("SELECT * FROM contacts WHERE id = ?");
    $stmt->execute([$selected_id]);
    $selected_contact = $stmt->fetch();
    
    // Обработка обновления
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_contact'])) {
        $id = (int)($_POST['id'] ?? 0);
        $lastname = trim($_POST['lastname'] ?? '');
        $firstname = trim($_POST['firstname'] ?? '');
        $middlename = trim($_POST['middlename'] ?? '');
        $gender = $_POST['gender'] ?? '';
        $birthdate = $_POST['birthdate'] ?? '';
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $comment = trim($_POST['comment'] ?? '');
        
        if ($id > 0 && !empty($lastname) && !empty($firstname)) {
            try {
                $sql = "UPDATE contacts SET lastname=?, firstname=?, middlename=?, gender=?, birthdate=?, phone=?, address=?, email=?, comment=? WHERE id=?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$lastname, $firstname, $middlename, $gender, $birthdate, $phone, $address, $email, $comment, $id]);
                $message = '<div class="success-message">Запись обновлена</div>';
                
                // Обновляем данные
                $stmt = $pdo->prepare("SELECT * FROM contacts WHERE id = ?");
                $stmt->execute([$id]);
                $selected_contact = $stmt->fetch();
            } catch (PDOException $e) {
                $message = '<div class="error-message">Ошибка при обновлении</div>';
            }
        } else {
            $message = '<div class="error-message">Заполните обязательные поля</div>';
        }
    }
    
    $html = '<h2>Редактирование записи</h2>' . $message;
    
    // Список контактов
    $html .= '<ul class="contact-list">';
    foreach ($contacts as $contact) {
        $current_class = ($contact['id'] == $selected_id) ? ' current' : '';
        $html .= '<li><a href="?menu=edit&edit_id=' . $contact['id'] . '" class="' . $current_class . '">' 
               . htmlspecialchars($contact['lastname'] . ' ' . $contact['firstname']) . '</a></li>';
    }
    $html .= '</ul>';
    
    // Форма редактирования
    if ($selected_contact) {
        $html .= '<form method="post" action="?menu=edit&edit_id=' . $selected_id . '">';
        $html .= '<input type="hidden" name="id" value="' . $selected_contact['id'] . '">';
        $html .= '<div class="form-group"><label>Фамилия *</label><input type="text" name="lastname" required value="' . htmlspecialchars($selected_contact['lastname']) . '"></div>';
        $html .= '<div class="form-group"><label>Имя *</label><input type="text" name="firstname" required value="' . htmlspecialchars($selected_contact['firstname']) . '"></div>';
        $html .= '<div class="form-group"><label>Отчество</label><input type="text" name="middlename" value="' . htmlspecialchars($selected_contact['middlename']) . '"></div>';
        $html .= '<div class="form-group"><label>Пол</label>
                  <select name="gender">
                      <option value="Мужской"' . ($selected_contact['gender'] == 'Мужской' ? ' selected' : '') . '>Мужской</option>
                      <option value="Женский"' . ($selected_contact['gender'] == 'Женский' ? ' selected' : '') . '>Женский</option>
                  </select></div>';
        $html .= '<div class="form-group"><label>Дата рождения</label><input type="date" name="birthdate" value="' . htmlspecialchars($selected_contact['birthdate']) . '"></div>';
        $html .= '<div class="form-group"><label>Телефон</label><input type="tel" name="phone" value="' . htmlspecialchars($selected_contact['phone']) . '"></div>';
        $html .= '<div class="form-group"><label>E-mail</label><input type="email" name="email" value="' . htmlspecialchars($selected_contact['email']) . '"></div>';
        $html .= '<div class="form-group"><label>Адрес</label><input type="text" name="address" value="' . htmlspecialchars($selected_contact['address']) . '"></div>';
        $html .= '<div class="form-group"><label>Комментарий</label><textarea name="comment">' . htmlspecialchars($selected_contact['comment']) . '</textarea></div>';
        $html .= '<button type="submit" name="edit_contact" class="submit-btn">Сохранить изменения</button>';
        $html .= '</form>';
    }
    
    return $html;
}
?>