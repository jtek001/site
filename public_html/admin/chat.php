<?php
session_start();
require_once '../db_config.php';
date_default_timezone_set('America/Sao_Paulo');

// --- LÓGICA PARA FINALIZAR SESSÕES INATIVAS ---
$sql_cleanup = "UPDATE chat_sessions SET status = 'Finalizado' WHERE updated_at < NOW() - INTERVAL 10 MINUTE AND status != 'Finalizado'";
$mysqli->query($sql_cleanup);
// --- FIM DA LÓGICA DE LIMPEZA ---

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}

// Lógica de Logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}

include '../header.php';
?>

<style>
/* Estilos específicos para a página de admin do chat */
.chat-admin-container { display: flex; height: 70vh; max-width: 1200px; margin: 0 auto; border: 1px solid var(--secondary-gray); border-radius: 8px; overflow: hidden; }
#session-list { width: 30%; border-right: 1px solid var(--secondary-gray); overflow-y: auto; background-color: #fff; }
#chat-area { width: 70%; display: flex; flex-direction: column; }
.session-item { padding: 15px; border-bottom: 1px solid var(--secondary-gray); cursor: pointer; transition: background-color 0.3s ease; }
.session-item:hover, .session-item.active { background-color: var(--primary-light); }
.session-item span { display: block; }
.session-id { font-weight: bold; }
.session-status { font-size: 0.8em; color: #666; text-transform: uppercase; }
#chat-messages-admin { flex-grow: 1; padding: 15px; overflow-y: auto; background-color: var(--primary-light); }
#chat-admin-input-area { display: flex; border-top: 1px solid var(--secondary-gray); }
#chat-admin-input { flex-grow: 1; border: none; padding: 15px; outline: none; font-size: 1em; }
#send-admin-message { background-color: var(--primary-dark); color: var(--text-light); border: none; padding: 0 20px; cursor: pointer; font-weight: bold; }
#chat-actions { padding: 10px; background: #fff; border-bottom: 1px solid var(--secondary-gray); text-align: right; }
#chat-actions button { margin-left: 10px; padding: 8px 15px; border-radius: 5px; border: 1px solid; cursor: pointer; font-weight: bold; }
.btn-finalizar { background-color: var(--error-color); color: #fff; border-color: var(--error-color); }
</style>

<main>
    <section class="page-header">
        <div class="container">
            <h1>Atendimento via Chat</h1>
            <p>Visualize e responda às conversas em tempo real.</p>
            <div class="admin-buttons">
                <a href="listar_contatos.php" class="btn-outline btn-lixeira">Ver Contatos</a>
                <a href="?action=logout" class="btn-outline logout-button">Sair</a>
            </div>
        </div>
    </section>

    <section class="contacts-list-section">
        <div class="chat-admin-container">
            <div id="session-list">
                <!-- A lista de sessões de chat será carregada aqui -->
            </div>
            <div id="chat-area">
                <div id="chat-actions" style="display: none;">
                    <button id="btn-finalizar" class="btn-finalizar">Finalizar Atendimento</button>
                </div>
                <div id="chat-messages-admin">
                    <p style="padding: 15px; color: #666;">Selecione uma conversa na lista para começar.</p>
                </div>
                <div id="chat-admin-input-area" style="display: none;">
                    <input type="text" id="chat-admin-input" placeholder="Digite sua resposta...">
                    <button id="send-admin-message">Enviar</button>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sessionList = document.getElementById('session-list');
    const messagesContainer = document.getElementById('chat-messages-admin');
    const chatInput = document.getElementById('chat-admin-input');
    const sendBtn = document.getElementById('send-admin-message');
    const chatInputArea = document.getElementById('chat-admin-input-area');
    const chatActions = document.getElementById('chat-actions');
    const btnFinalizar = document.getElementById('btn-finalizar');

    let currentSessionId = null;
    let pollingInterval;

    async function loadSessions() {
        try {
            const response = await fetch('/chat_api.php?action=get_sessions');
            const sessions = await response.json();
            sessionList.innerHTML = '';
            sessions.forEach(session => {
                const div = document.createElement('div');
                div.className = 'session-item';
                if (session.session_id === currentSessionId) {
                    div.classList.add('active');
                }
                div.dataset.sessionId = session.session_id;
                div.innerHTML = `
                    <span class="session-id">ID: ${session.session_id.substring(0, 8)}...</span>
                    <span class="session-status">Status: ${session.status}</span>
                `;
                div.addEventListener('click', () => openChat(session.session_id));
                sessionList.appendChild(div);
            });
        } catch (error) {
            console.error("Erro ao carregar sessões:", error);
        }
    }

    async function openChat(sessionId) {
        currentSessionId = sessionId;
        loadSessions(); // Recarrega para marcar como ativo
        messagesContainer.innerHTML = 'Carregando mensagens...';
        chatInputArea.style.display = 'flex';
        chatActions.style.display = 'block';
        loadMessages();
        startPolling();
    }

    async function loadMessages() {
        if (!currentSessionId) return;
        try {
            const response = await fetch(`/chat_api.php?action=get_messages&session_id=${currentSessionId}`);
            const data = await response.json();

            if (data && Array.isArray(data.messages)) {
                const messages = data.messages;
                messagesContainer.innerHTML = '';
                messages.forEach(msg => {
                    const messageDiv = document.createElement('div');
                    messageDiv.className = `chat-message ${msg.sender}-message`;
                    messageDiv.textContent = msg.message;
                    messagesContainer.appendChild(messageDiv);
                });
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            } else if (messagesContainer.innerHTML === 'Carregando mensagens...') {
                 messagesContainer.innerHTML = ''; // Limpa se não houver mensagens
            }
        } catch (error) {
            console.error("Erro ao carregar mensagens:", error);
        }
    }

    async function sendMessage() {
        const message = chatInput.value.trim();
        if (message === '' || !currentSessionId) return;
        
        chatInput.value = '';

        try {
            await fetch('/chat_api.php?action=send_message', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    session_id: currentSessionId,
                    message: message,
                    sender: 'admin'
                })
            });
            loadMessages(); // Carrega as mensagens imediatamente após o envio
        } catch (error) {
            console.error("Erro ao enviar mensagem:", error);
        }
    }
    
    async function updateStatus(status) {
        if (!currentSessionId) return;
        try {
            await fetch('/chat_api.php?action=update_status', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    session_id: currentSessionId,
                    status: status
                })
            });
            if (status === 'Finalizado') {
                currentSessionId = null;
                messagesContainer.innerHTML = '<p style="padding: 15px; color: #666;">Atendimento finalizado. Selecione outra conversa.</p>';
                chatInputArea.style.display = 'none';
                chatActions.style.display = 'none';
                stopPolling();
            }
            loadSessions();
        } catch (error) {
            console.error("Erro ao atualizar status:", error);
        }
    }

    sendBtn.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', e => e.key === 'Enter' && sendMessage());
    btnFinalizar.addEventListener('click', () => updateStatus('Finalizado'));

    function startPolling() {
        clearInterval(pollingInterval);
        pollingInterval = setInterval(loadMessages, 3000); // Verifica a cada 3 segundos
    }

    function stopPolling() {
        clearInterval(pollingInterval);
    }

    // Carrega as sessões ativas a cada 5 segundos para manter a lista atualizada
    setInterval(loadSessions, 5000);
    loadSessions(); // Carga inicial
});
</script>

<?php include '../footer.php'; ?>
