<?php
/**
 * Модуль add.php - добавление записи
 */

function getAddContent($pdo)
{
    $message = '';
    $message_type = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_contact'])) {
        $lastname = trim($_POST['lastname'] ?? '');
        $firstname = trim($_POST['firstname'] ?? '');
        $middlename = trim($_POST['middlename'] ?? '');
        $gender = $_POST['gender'] ?? '';
        $birthdate = $_POST['birthdate'] ?? '';
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $comment = trim($_POST['comment'] ?? '');
        
        if (empty($lastname) || empty($firstname) || empty($gender) || empty($birthdate)) {
            $message = 'Ошибка: запись не добавлена. Заполните обязательные поля.';
            $message_type = 'error';
        } else {
            try {
                $sql = "INSERT INTO contacts (lastname, firstname, middlename, gender, birthdate, phone, address, email, comment) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$lastname, $firstname, $middlename ?: null, $gender, $birthdate, $phone ?: null, $address ?: null, $email ?: null, $comment ?: null]);
                
                $message = 'Запись добавлена';
                $message_type = 'success';
                $_POST = [];
            } catch (PDOException $e) {
                $message = 'Ошибка: запись не добавлена';
                $message_type = 'error';
            }
        }
    }
    
    $html = '<h2>Добавление новой записи</h2>';
    
    if ($message) {
        $class = ($message_type === 'success') ? 'success-message' : 'error-message';
        $html .= '<div class="' . $class . '">' . htmlspecialchars($message) . '</div>';
    }
    
    $html .= '<form method="post" action="?menu=add">';
    $html .= '<div class="form-group"><label>Фамилия *</label><input type="text" name="lastname" required value="' . htmlspecialchars($_POST['lastname'] ?? '') . '"></div>';
    $html .= '<div class="form-group"><label>Имя *</label><input type="text" name="firstname" required value="' . htmlspecialchars($_POST['firstname'] ?? '') . '"></div>';
    $html .= '<div class="form-group"><label>Отчество</label><input type="text" name="middlename" value="' . htmlspecialchars($_POST['middlename'] ?? '') . '"></div>';
    $html .= '<div class="form-group"><label>Пол *</label>
              <select name="gender" required>
                  <option value="Мужской">Мужской</option>
                  <option value="Женский">Женский</option>
              </select></div>';
    $html .= '<div class="form-group"><label>Дата рождения *</label><input type="date" name="birthdate" required value="' . htmlspecialchars($_POST['birthdate'] ?? '') . '"></div>';
    $html .= '<div class="form-group"><label>Телефон</label><input type="tel" name="phone" value="' . htmlspecialchars($_POST['phone'] ?? '') . '"></div>';
    $html .= '<div class="form-group"><label>E-mail</label><input type="email" name="email" value="' . htmlspecialchars($_POST['email'] ?? '') . '"></div>';
    $html .= '<div class="form-group"><label>Адрес</label><input type="text" name="address" value="' . htmlspecialchars($_POST['address'] ?? '') . '"></div>';
    $html .= '<div class="form-group"><label>Комментарий</label><textarea name="comment">' . htmlspecialchars($_POST['comment'] ?? '') . '</textarea></div>';
    $html .= '<button type="submit" name="add_contact" class="submit-btn">Добавить запись</button>';
    $html .= '</form>';
    
    return $html;
}
?>