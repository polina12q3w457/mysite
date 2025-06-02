<?php
$pdo = new PDO('sqlite:bdbb.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Получаем список секций из базы
$stmtSections = $pdo->query("SELECT DISTINCT section FROM people ORDER BY section ASC");
$sections = $stmtSections->fetchAll(PDO::FETCH_COLUMN);

// Активная секция
$activeSection = $_GET['section'] ?? ($sections[0] ?? '');

// Сообщение для вывода
$message = '';

// Обработка формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $selectedSection = $_POST['section'] ?? '';
    $newSection = trim($_POST['new_section'] ?? '');
    $image = $_FILES['image']['name'] ?? '';

    // Используем новый раздел, если он введён
    $section = $newSection ?: $selectedSection;

    if ($title && $content && $image && $section) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image);

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $sql = "INSERT INTO people (title, content, image, section) VALUES (:title, :content, :image, :section)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':image', $target_file);
            $stmt->bindParam(':section', $section);
            $stmt->execute();

            $message = "Новая запись добавлена!";
            if (!in_array($section, $sections)) {
                $sections[] = $section;
            }
            $activeSection = $section;
        } else {
            $message = "Ошибка загрузки изображения.";
        }
    } else {
        $message = "Пожалуйста, заполните все поля.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Рассказы очевидцев</title>
    <style>
body {
    font-family: Arial, sans-serif;
    padding: 20px;
    max-width: 900px;
    margin: auto;
    background: #1c1c1c;
    color: #f0f0f0;
}

.buttons {
    margin-bottom: 30px;
    text-align: center;
}

.buttons a {
    padding: 10px 20px;
    background: #FF5722;
    color: white;
    margin: 0 10px;
    text-decoration: none;
    border-radius: 8px;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.buttons a.active,
.buttons a:hover {
    background: #D84315;
    transform: translateY(-2px);
}

.person {
    background: #2a2a2a;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.7);
    color: #f0f0f0;
}

form {
    background: #2a2a2a;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.7);
    color: #f0f0f0;
}

form label {
    display: block;
    margin-top: 10px;
    font-weight: bold;
    color: #FF5722;
}

form input, form textarea, form select {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border: 1px solid #444;
    border-radius: 6px;
    background-color: #1c1c1c;
    color: #f0f0f0;
}

form input::placeholder,
form textarea::placeholder {
    color: #bbb;
}

form input[type="submit"] {
    margin-top: 20px;
    background-color: #FF5722;
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
    border-radius: 8px;
}

form input[type="submit"]:hover {
    background-color: #D84315;
    transform: translateY(-2px);
}

.highlight {
    animation: flash 2s ease-in-out;
    border: 2px solid #FF5722;
}

.top-controls a.back-home-btn:hover,
.top-controls button:hover {
    background-color: #D84315;
    color: white;
    transform: translateY(-2px);
}

.top-controls {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 10px;
}

.top-controls a.back-home-btn,
.top-controls button {
    padding: 10px 20px;
    background-color: #FF5722;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
    font-size: 1em;
}

.top-controls a.back-home-btn:hover,
.top-controls button:hover {
    background-color: #D84315;
    transform: translateY(-2px);
}

body.accessibility {
    background-color: #000 !important;
    color: #fff !important;
    font-size: 1.4em;
    line-height: 1.8;
}

body.accessibility img {
    display: none;
}

body.accessibility a {
    color: #0ff !important;
    text-decoration: underline !important;
}

@keyframes flash {
    0% { background-color: #FFB399; }
    100% { background-color: transparent; }
}

#scrollTopBtn {
    position: fixed;
    bottom: 40px;
    right: 40px;
    z-index: 1000;
    background-color: #333;
    color: #fff;
    border: none;
    padding: 10px 15px;
    font-size: 18px;
    border-radius: 50%;
    cursor: pointer;
    display: none;
    box-shadow: 0 4px 6px rgba(0,0,0,0.7);
    transition: background-color 0.3s ease;
}

#scrollTopBtn:hover {
    background-color: #555;
}
    </style>
</head>
<body>
<div class="top-controls">
  <a href="index.html" class="back-home-btn">← На главную</a>
  <button onclick="toggleAccessibility()" id="accessibilityBtn">Версия для слабовидящих</button>
  <div id="google_translate_element"></div>
</div>
<h1>Рассказы очевидцев</h1>

<div class="buttons">
    <?php foreach ($sections as $section): 
        $active = $section === $activeSection ? 'active' : '';
        echo "<a class='$active' href='?section=" . urlencode($section) . "'>$section</a>";
    endforeach; ?>
</div>

<?php if (!empty($message)) echo "<p><strong>$message</strong></p>"; ?>

<?php
$stmt = $pdo->prepare("SELECT * FROM people WHERE section = :section ORDER BY id ASC");
$stmt->execute(['section' => $activeSection]);
$results = $stmt->fetchAll();

if ($results):
    foreach ($results as $index => $row):
        $isNew = isset($section, $title) && $row['title'] === $title && $row['section'] === $section;
        $highlight = $isNew ? 'highlight' : '';
        $anchor = $isNew ? 'id="new-entry"' : '';
?>
        <div class="person <?= $highlight ?>" <?= $anchor ?>>
            <h2><?= htmlspecialchars($row['title']) ?></h2>
            <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
            <?php if (!empty($row['image']) && file_exists($row['image'])): ?>
                <img src="<?= htmlspecialchars($row['image']) ?>" alt="Изображение" style="max-width: 100%; margin-top: 10px; border-radius: 8px;">
            <?php endif; ?>
        </div>
<?php
    endforeach;
else:
    echo "<p>Нет записей в этом разделе.</p>";
endif;
?>

<hr>

<h2>Если вы хотите добавить информацию о новом очевидце или пополнить, заполните форму:</h2>

<form action="" method="post" enctype="multipart/form-data">
    <label for="title">Заголовок:</label>
    <input type="text" id="title" name="title" required>

    <label for="content">Основной текст:</label>
    <textarea id="content" name="content" required></textarea>

    <label for="section">Выберите очевидца:</label>
    <select id="section" name="section">
        <option value="">-- Не выбран --</option>
        <?php foreach ($sections as $section): ?>
            <option value="<?= htmlspecialchars($section) ?>"><?= htmlspecialchars($section) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="new_section">Или добавьте нового очевидца:</label>
    <input type="text" id="new_section" name="new_section" placeholder="Введите имя нового очевидца">

    <label for="image">Изображение:</label>
    <input type="file" id="image" name="image" accept="image/*" required>

    <input type="submit" value="Добавить">

</form>
<!-- Кнопка "Наверх" -->
    <button onclick="scrollToTop()" id="scrollTopBtn" title="Наверх">↑</button>


    <!-- JS логика кнопки "Наверх" -->
    <script>
        window.onscroll = function() {
            const btn = document.getElementById("scrollTopBtn");
            if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                btn.style.display = "block";
            } else {
                btn.style.display = "none";
            }
        };

        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const newEntry = document.getElementById("new-entry");
    if (newEntry) {
        newEntry.scrollIntoView({ behavior: "smooth", block: "start" });
    }
});
</script>
<script type="text/javascript">
  function googleTranslateElementInit() {
    console.log("Google Translate Init"); // Для отладки
    new google.translate.TranslateElement({
      pageLanguage: 'ru',
      includedLanguages: 'en,ru,be,uk,de,fr,pl',
      layout: google.translate.TranslateElement.InlineLayout.SIMPLE
    }, 'google_translate_element');
  }
</script>
<script type="text/javascript" 
  src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
</script>

<script>
function toggleAccessibility() {
    const body = document.body;
    const btn = document.getElementById('accessibilityBtn');
    const enabled = body.classList.toggle('accessibility');

    if (enabled) {
        btn.textContent = 'Обычная версия';
    } else {
        btn.textContent = 'Версия для слабовидящих';
    }
}
</script>
</body>
</html>
