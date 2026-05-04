<?php
require_once 'config.php';
require_once 'functions.php';

// Создание тестовой таблицы
$pdo->exec("
    CREATE TABLE IF NOT EXISTS records (
        id SERIAL PRIMARY KEY,
        title VARCHAR(200) NOT NULL,
        content TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
");

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'all':
        // GET - получение всех записей
        $stmt = $pdo->query("SELECT * FROM records ORDER BY id");
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        sendResponse(['success' => true, 'data' => $records]);
        break;
        
    case 'get':
        // GET - получение записи по id
        if (!isset($_GET['id'])) {
            sendResponse(['error' => 'Parameter "id" is required'], 400);
        }
        
        $id = intval($_GET['id']);
        $stmt = $pdo->prepare("SELECT * FROM records WHERE id = ?");
        $stmt->execute([$id]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($record) {
            sendResponse(['success' => true, 'data' => $record]);
        } else {
            sendResponse(['error' => 'Record not found'], 404);
        }
        break;
        
    case 'del':
        // GET - удаление записи по id
        if (!isset($_GET['id'])) {
            sendResponse(['error' => 'Parameter "id" is required'], 400);
        }
        
        $id = intval($_GET['id']);
        $stmt = $pdo->prepare("DELETE FROM records WHERE id = ? RETURNING id");
        $stmt->execute([$id]);
        $deleted = $stmt->fetch();
        
        if ($deleted) {
            sendResponse(['success' => true, 'message' => 'Record deleted successfully']);
        } else {
            sendResponse(['error' => 'Record not found'], 404);
        }
        break;
        
    case 'edit':
        // POST - изменение записи
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            sendResponse(['error' => 'Method not allowed. Use POST'], 405);
        }
        
        if (!isset($_GET['id'])) {
            sendResponse(['error' => 'Parameter "id" is required'], 400);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['title']) || !isset($input['content'])) {
            sendResponse(['error' => 'Fields "title" and "content" are required'], 400);
        }
        
        $id = intval($_GET['id']);
        $title = $input['title'];
        $content = $input['content'];
        
        $stmt = $pdo->prepare("
            UPDATE records 
            SET title = ?, content = ?, updated_at = CURRENT_TIMESTAMP 
            WHERE id = ? 
            RETURNING id
        ");
        $stmt->execute([$title, $content, $id]);
        $updated = $stmt->fetch();
        
        if ($updated) {
            sendResponse(['success' => true, 'message' => 'Record updated successfully']);
        } else {
            sendResponse(['error' => 'Record not found'], 404);
        }
        break;
        
    default:
        sendResponse([
            'error' => 'Invalid action',
            'available_actions' => ['all', 'get', 'del', 'edit'],
            'examples' => [
                'GET /index.php?action=all',
                'GET /index.php?action=get&id=1',
                'GET /index.php?action=del&id=1',
                'POST /index.php?action=edit&id=1 (with JSON body: {"title":"New Title","content":"New Content"})'
            ]
        ], 400);
}
?>