<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Тестирование</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
        .api-section { background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #333; margin-top: 0; }
        button { background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; margin: 5px; }
        button:hover { background: #0056b3; }
        input, select { padding: 8px; margin: 5px; border: 1px solid #ddd; border-radius: 4px; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 4px; overflow-x: auto; }
        .result { margin-top: 10px; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Тестирование API</h1>
        
        <div class="api-section">
            <h2>1. Текущая дата и время</h2>
            <button onclick="testAPI('/day.php')">Текущий день</button>
            <button onclick="testAPI('/month.php')">Текущий месяц</button>
            <button onclick="testAPI('/year.php')">Текущий год</button>
            <div id="result1" class="result"></div>
        </div>
        
        <div class="api-section">
            <h2>2. День недели по дате</h2>
            <input type="date" id="weekdayDate" value="2024-12-25">
            <button onclick="getWeekday()">Получить день недели</button>
            <div id="result2" class="result"></div>
        </div>
        
        <div class="api-section">
            <h2>3. Разница между датами</h2>
            <input type="date" id="date1" value="2024-01-01">
            <input type="date" id="date2" value="2024-12-31">
            <button onclick="getDateDiff()">Рассчитать разницу</button>
            <div id="result3" class="result"></div>
        </div>
        
        <div class="api-section">
            <h2>4. Города по стране</h2>
            <input type="text" id="country" placeholder="Введите страну" value="Russia">
            <button onclick="getCities()">Получить города</button>
            <div id="result4" class="result"></div>
        </div>
        
        <div class="api-section">
            <h2>5-8. CRUD операции с записями</h2>
            
            <h3>Создать запись (через форму)</h3>
            <input type="text" id="newTitle" placeholder="Заголовок">
            <textarea id="newContent" placeholder="Содержание" rows="3"></textarea>
            <button onclick="createRecord()">Создать</button>
            
            <h3>Получить все записи</h3>
            <button onclick="getAllRecords()">Показать все</button>
            
            <h3>Получить запись по ID</h3>
            <input type="number" id="getRecordId" placeholder="ID">
            <button onclick="getRecord()">Получить</button>
            
            <h3>Обновить запись</h3>
            <input type="number" id="updateId" placeholder="ID">
            <input type="text" id="updateTitle" placeholder="Новый заголовок">
            <textarea id="updateContent" placeholder="Новое содержание" rows="3"></textarea>
            <button onclick="updateRecord()">Обновить</button>
            
            <h3>Удалить запись</h3>
            <input type="number" id="deleteId" placeholder="ID">
            <button onclick="deleteRecord()">Удалить</button>
            
            <div id="result5" class="result"></div>
        </div>
    </div>

    <script>
        const API_BASE = 'http://api.ponka.ru';
        
        async function testAPI(endpoint) {
            try {
                const response = await fetch(API_BASE + endpoint);
                const data = await response.json();
                document.getElementById('result1').innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
            } catch (error) {
                document.getElementById('result1').innerHTML = `<div class="error">Ошибка: ${error.message}</div>`;
            }
        }
        
        async function getWeekday() {
            const date = document.getElementById('weekdayDate').value;
            try {
                const response = await fetch(`${API_BASE}/weekday.php?date=${date}`);
                const data = await response.json();
                document.getElementById('result2').innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
            } catch (error) {
                document.getElementById('result2').innerHTML = `<div class="error">Ошибка: ${error.message}</div>`;
            }
        }
        
        async function getDateDiff() {
            const date1 = document.getElementById('date1').value;
            const date2 = document.getElementById('date2').value;
            try {
                const response = await fetch(`${API_BASE}/diff.php?date1=${date1}&date2=${date2}`);
                const data = await response.json();
                document.getElementById('result3').innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
            } catch (error) {
                document.getElementById('result3').innerHTML = `<div class="error">Ошибка: ${error.message}</div>`;
            }
        }
        
        async function getCities() {
            const country = document.getElementById('country').value;
            try {
                const response = await fetch(`${API_BASE}/cities.php?country=${encodeURIComponent(country)}`);
                const data = await response.json();
                document.getElementById('result4').innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
            } catch (error) {
                document.getElementById('result4').innerHTML = `<div class="error">Ошибка: ${error.message}</div>`;
            }
        }
        
        async function getAllRecords() {
            try {
                const response = await fetch(`${API_BASE}/index.php?action=all`);
                const data = await response.json();
                document.getElementById('result5').innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
            } catch (error) {
                document.getElementById('result5').innerHTML = `<div class="error">Ошибка: ${error.message}</div>`;
            }
        }
        
        async function getRecord() {
            const id = document.getElementById('getRecordId').value;
            if (!id) {
                alert('Введите ID');
                return;
            }
            try {
                const response = await fetch(`${API_BASE}/index.php?action=get&id=${id}`);
                const data = await response.json();
                document.getElementById('result5').innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
            } catch (error) {
                document.getElementById('result5').innerHTML = `<div class="error">Ошибка: ${error.message}</div>`;
            }
        }
        
        async function createRecord() {
            const title = document.getElementById('newTitle').value;
            const content = document.getElementById('newContent').value;
            
            if (!title || !content) {
                alert('Заполните заголовок и содержание');
                return;
            }
            
            alert('Функция создания записей. Добавьте action=add в index.php если необходимо');
        }
        
        async function updateRecord() {
            const id = document.getElementById('updateId').value;
            const title = document.getElementById('updateTitle').value;
            const content = document.getElementById('updateContent').value;
            
            if (!id || !title || !content) {
                alert('Заполните ID, заголовок и содержание');
                return;
            }
            
            try {
                const response = await fetch(`${API_BASE}/index.php?action=edit&id=${id}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ title, content })
                });
                const data = await response.json();
                document.getElementById('result5').innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
            } catch (error) {
                document.getElementById('result5').innerHTML = `<div class="error">Ошибка: ${error.message}</div>`;
            }
        }
        
        async function deleteRecord() {
            const id = document.getElementById('deleteId').value;
            if (!id) {
                alert('Введите ID');
                return;
            }
            
            if (confirm('Вы уверены, что хотите удалить запись?')) {
                try {
                    const response = await fetch(`${API_BASE}/index.php?action=del&id=${id}`);
                    const data = await response.json();
                    document.getElementById('result5').innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
                } catch (error) {
                    document.getElementById('result5').innerHTML = `<div class="error">Ошибка: ${error.message}</div>`;
                }
            }
        }
    </script>
</body>
</html>