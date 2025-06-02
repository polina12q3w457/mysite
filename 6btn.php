<?php
function h($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

$pdo = new PDO('sqlite:bd_from_csv.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$surname = $_GET['surname'] ?? '';
$name = $_GET['name'] ?? '';
$patronymic = $_GET['patronymic'] ?? '';
$birth_date = $_GET['birth_date'] ?? '';
$death_date = $_GET['death_date'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 20;

$where = [];
$params = [];

// Удаляем строки-заголовки
$where[] = "surname != 'Фамилия'";
$where[] = "name != 'Имя'";
$where[] = "patronymic != 'Отчество'";
$where[] = "birth_date != 'Дата рождения'";
$where[] = "death_date != 'Дата смерти'";

// Поиск по полям
if ($surname !== '') {
    $where[] = "surname LIKE :surname";
    $params[':surname'] = "%$surname%";
}
if ($name !== '') {
    $where[] = "name LIKE :name";
    $params[':name'] = "%$name%";
}
if ($patronymic !== '') {
    $where[] = "patronymic LIKE :patronymic";
    $params[':patronymic'] = "%$patronymic%";
}
if ($birth_date !== '') {
    $where[] = "birth_date LIKE :birth_date";
    $params[':birth_date'] = "$birth_date%";
}
if ($death_date !== '') {
    $where[] = "death_date LIKE :death_date";
    $params[':death_date'] = "$death_date%";
}

$whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM people $whereSQL");
$countStmt->execute($params);
$total = $countStmt->fetchColumn();

$offset = ($page - 1) * $perPage;

$sql = "SELECT * FROM people $whereSQL ORDER BY surname, name, patronymic LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);

foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalPages = ceil($total / $perPage);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <title>Поиск людей</title>
    <style>
body {
    font-family: 'Helvetica Neue', 'Pathway Gothic One', sans-serif;
    background-color: #1c1c1c;
    color: #f0f0f0;
    margin: 0;
    padding: 0;
    background-image: url('fon.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    position: relative;
    z-index: 0;
}

/* Затемнение фона для лучшей читаемости текста */
body::before {
    content: "";
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(28, 28, 28, 0.85);
    z-index: -1;
}

h1 {
    color: #f0f0f0;
    font-weight: 600;
    font-size: 2.8em;
    text-align: center;
    margin-bottom: 30px;
}

/* Форма поиска */
form {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
    margin-bottom: 30px;
}

input[type=text] {
    padding: 12px 15px;
    width: 160px;
    background-color: #2a2a2a;
    border: 1px solid #444444;
    border-radius: 25px;
    color: #f0f0f0;
    font-size: 1em;
    transition: border-color 0.3s ease, background-color 0.3s ease;
}

input[type=text]::placeholder {
    color: #999999;
}

input[type=text]:focus {
    outline: none;
    border-color: #ff5722;
    background-color: #3a3a3a;
}

button[type=submit] {
    padding: 12px 30px;
    background-color: #ff5722;
    color: white;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    font-size: 1em;
    transition: background-color 0.3s ease, transform 0.3s ease;
    box-shadow: 0 4px 8px rgba(255, 87, 34, 0.4);
}

button[type=submit]:hover {
    background-color: #d84315;
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(216, 67, 21, 0.6);
}

/* Информация о количестве записей */
p {
    text-align: center;
    font-size: 1.1em;
    margin-bottom: 20px;
    color: #999999;
}

/* Таблица */
table {
    border-collapse: collapse;
    width: 100%;
    background-color: #2a2a2a;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.6);
}

th, td {
    padding: 12px 15px;
    border-bottom: 1px solid #444444;
    color: #f0f0f0;
    text-align: left;
    font-size: 1em;
}

th {
    background-color: #1c1c1c;
    font-weight: 600;
    color: #ff5722;
}

tbody tr:hover {
    background-color: #3a3a3a;
    cursor: default;
}

tbody tr:last-child td {
    border-bottom: none;
}

/* Сообщение при отсутствии данных */
tbody tr td[colspan="5"] {
    text-align: center;
    color: #999999;
    padding: 30px 0;
}

/* Пагинация */
.pagination {
    margin-top: 30px;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
}

.pagination a, .pagination span {
    padding: 10px 18px;
    border: 1px solid #444444;
    border-radius: 25px;
    text-decoration: none;
    color: #f0f0f0;
    background-color: #2a2a2a;
    font-weight: 500;
    transition: background-color 0.3s ease, border-color 0.3s ease;
}

.pagination a:hover {
    background-color: #ff5722;
    border-color: #ff5722;
    color: white;
    box-shadow: 0 0 8px rgba(255, 87, 34, 0.7);
}

.pagination span.current {
    background-color: #ff5722;
    color: white;
    border-color: #ff5722;
    font-weight: 700;
    box-shadow: 0 0 12px rgba(255, 87, 34, 0.9);
}

.top-controls a.back-home-btn,
.top-controls button {
    padding: 10px 20px;
    background-color: #2a2a2a;
    color: #f0f0f0;
    border: 1px solid #444444;
    border-radius: 20px;
    text-decoration: none;
    font-size: 1em;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
}

.top-controls a.back-home-btn:hover,
.top-controls button:hover {
    background-color: #ff5722;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 87, 34, 0.6);
}

.top-controls {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 10px;
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

/* Адаптив */
@media (max-width: 768px) {
    form {
        flex-direction: column;
        align-items: center;
    }

    input[type=text] {
        width: 100%;
        max-width: 300px;
    }

    button[type=submit] {
        width: 100%;
        max-width: 300px;
    }
}

    </style>
</head>
<body>
<div class="top-controls">
  <a href="index.html" class="back-home-btn">← На главную</a>
  <button onclick="toggleAccessibility()" id="accessibilityBtn">Версия для слабовидящих</button>
  <div id="google_translate_element"></div>
</div>
<h1>Список жертв</h1>

<form method="get">
    <input type="text" name="surname" placeholder="Фамилия" value="<?= h($surname) ?>" />
    <input type="text" name="name" placeholder="Имя" value="<?= h($name) ?>" />
    <input type="text" name="patronymic" placeholder="Отчество" value="<?= h($patronymic) ?>" />
    <input type="text" name="birth_date" placeholder="Год или дата рождения" value="<?= h($birth_date) ?>" />
    <input type="text" name="death_date" placeholder="Год или дата смерти" value="<?= h($death_date) ?>" />
    <button type="submit">Поиск</button>
</form>

<table>
    <thead>
        <tr>
            <th>Имя</th>
            <th>Фамилия</th>
            <th>Отчество</th>
            <th>Дата рождения</th>
            <th>Дата смерти</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($results): ?>
            <?php foreach ($results as $row): ?>
                <tr>
                    <td><?= h($row['surname']) ?></td>
                    <td><?= h($row['name']) ?></td>
                    <td><?= h($row['patronymic']) ?></td>
                    <td><?= h($row['birth_date']) ?></td>
                    <td><?= h($row['death_date']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">Нет данных</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">&laquo; Назад</a>
    <?php endif; ?>

    <?php
    $start = max(1, $page - 4);
    $end = min($totalPages, $page + 4);
    for ($p = $start; $p <= $end; $p++):
    ?>
        <?php if ($p == $page): ?>
            <span class="current"><?= $p ?></span>
        <?php else: ?>
            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $p])) ?>"><?= $p ?></a>
        <?php endif; ?>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">Вперед &raquo;</a>
    <?php endif; ?>
</div>
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
