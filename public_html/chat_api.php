<?php
session_start();
require_once 'db_config.php';
date_default_timezone_set('America/Sao_Paulo');

header('Content-Type: application/json');

// --- LÓGICA PARA FINALIZAR SESSÕES INATIVAS ---
// Finaliza sessões que não tiveram atualização nos últimos 10 minutos
$sql_cleanup = "UPDATE chat_sessions SET status = 'Finalizado' WHERE updated_at < NOW() - INTERVAL 10 MINUTE AND status != 'Finalizado'";
$mysqli->query($sql_cleanup);
// --- FIM DA LÓGICA DE LIMPEZA ---

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$data = json_decode(file_get_contents('php://input'), true);

switch ($action) {
    case 'start_session':
        $sessionId = bin2hex(random_bytes(16));
        $userIp = $_SERVER['REMOTE_ADDR'];
        
        $sql = "INSERT INTO chat_sessions (session_id, user_ip) VALUES (?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $sessionId, $userIp);
        $stmt->execute();
        $newId = $stmt->insert_id;
        $stmt->close();

        // Mensagem de boas-vindas automática
        $welcomeMessage = "Olá! Bem-vindo ao nosso chat. Em que podemos ajudar? Um dos nossos consultores irá atendê-lo em breve.";
        $sql = "INSERT INTO chat_messages (session_id_fk, sender, message) VALUES (?, 'admin', ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("is", $newId, $welcomeMessage);
        $stmt->execute();
        $stmt->close();

        echo json_encode(['status' => 'success', 'session_id' => $sessionId]);
        break;

    case 'send_message':
        $sessionId = $data['session_id'] ?? '';
        $message = $data['message'] ?? '';
        $sender = $data['sender'] ?? 'user'; // 'user' ou 'admin'

        if (empty($sessionId) || empty($message)) {
            echo json_encode(['status' => 'error', 'message' => 'Dados inválidos.']);
            exit;
        }

        // Encontrar o ID da sessão
        $sql = "SELECT id FROM chat_sessions WHERE session_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $sessionId);
        $stmt->execute();
        $result = $stmt->get_result();
        $session = $result->fetch_assoc();
        $stmt->close();

        if ($session) {
            $sessionIdFk = $session['id'];
            $sql = "INSERT INTO chat_messages (session_id_fk, sender, message) VALUES (?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("iss", $sessionIdFk, $sender, $message);
            $stmt->execute();
            $stmt->close();
            
            // Atualiza o status se um admin responder
            if ($sender === 'admin') {
                $sqlUpdate = "UPDATE chat_sessions SET status = 'Atendido' WHERE id = ? AND status = 'Aguardando'";
                $stmtUpdate = $mysqli->prepare($sqlUpdate);
                $stmtUpdate->bind_param("i", $sessionIdFk);
                $stmtUpdate->execute();
                $stmtUpdate->close();
            }

            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Sessão não encontrada.']);
        }
        break;

    case 'get_messages':
        $sessionId = $_GET['session_id'] ?? '';
        if (empty($sessionId)) {
            echo json_encode(['status' => 'error', 'message' => 'ID de sessão não fornecido.']);
            exit;
        }

        // Primeiro, obter o status da sessão
        $sql_status = "SELECT status FROM chat_sessions WHERE session_id = ?";
        $stmt_status = $mysqli->prepare($sql_status);
        $stmt_status->bind_param("s", $sessionId);
        $stmt_status->execute();
        $result_status = $stmt_status->get_result();
        $session_data = $result_status->fetch_assoc();
        $stmt_status->close();

        if (!$session_data) {
            echo json_encode(['status' => 'error', 'message' => 'Sessão não encontrada.']);
            exit;
        }
        
        $status = $session_data['status'];

        $sql = "SELECT m.sender, m.message, m.timestamp 
                FROM chat_messages m
                JOIN chat_sessions s ON m.session_id_fk = s.id
                WHERE s.session_id = ? ORDER BY m.timestamp ASC";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $sessionId);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        echo json_encode(['status' => $status, 'messages' => $messages]);
        break;

    // Ações para o painel de administração
    case 'get_sessions':
        if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
            echo json_encode(['status' => 'error', 'message' => 'Acesso negado.']);
            exit;
        }
        $sql = "SELECT session_id, status, updated_at FROM chat_sessions WHERE status != 'Finalizado' ORDER BY updated_at DESC";
        $result = $mysqli->query($sql);
        $sessions = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($sessions);
        break;
        
    case 'update_status':
        if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
            echo json_encode(['status' => 'error', 'message' => 'Acesso negado.']);
            exit;
        }
        $sessionId = $data['session_id'] ?? '';
        $status = $data['status'] ?? '';
        
        $sql = "UPDATE chat_sessions SET status = ? WHERE session_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $status, $sessionId);
        $stmt->execute();
        $stmt->close();
        echo json_encode(['status' => 'success']);
        break;

}

$mysqli->close();
?>
