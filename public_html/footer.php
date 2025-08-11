    <!-- Rodapé -->
    <footer class="main-footer">
        <div class="container footer-grid">
            <div class="footer-col">
                <!-- Caminho da imagem absoluto a partir da raiz -->
                <img src="/img/logo-branco.png" width="220" alt="Jtek Info" style="margin-bottom: 15px;">
                <p>Assistência Técnica em Informática<br> Redes e Infraestrutura em Geral</p>
                <p class="cnpj">CNPJ: 60.167.603/0001-04</p>
              <br>
              <p>Nova Parceria:</p>
              <p><a href="/gdoor"><img src="/img/parceiros/gdoor.png" width="220" ></a></p>
              <p><a href="/dell"><img src="/img/parceiros/dellen.png" width="220" ></a></p>
            </div>
            <div class="footer-col">
                <h4>Navegação</h4>
                <ul>
                    <li><a href="/">Incio</a></li>
                    <li><a href="/servicos">Serviços</a></li>
                    <li><a href="/sobre">Sobre</a></li>
                    <li><a href="/parceiros">Parceiros</a></li>
                    <li><a href="/clientes">Clientes</a></li>
                    <li><a href="/app" target="_blank">O.S</a></li>
                    <li><a href="/webmail" target="_blank">Webmail</a></li>
                </ul>
            </div>
             <div class="footer-col">
                <h4>Serviços</h4>
                <ul>
                    <li><a href="/servicos/redes">Redes e Infraestrutura</a></li>
                    <li><a href="/servicos/seguranca">Segurança Digital</a></li>
                    <li><a href="/servicos/suporte">Suporte Técnico</a></li>
                    <li><a href="/servicos/manutencao">Manutenção</a></li>
                    <li><a href="/servicos/backup">Backups</a></li>
                    <li><a href="/servicos/treinamento">Treinamento</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contato</h4>
                <ul>
                    <li><a href="mailto:contato@jtekinfo.com.br">contato@jtekinfo.com.br</a></li>
                    <li><a href="https://wa.me/5543999302023" target="_blank">(43) 99930-2023</a></li>
              </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <!-- O link para a área de admin j  um caminho absoluto -->
            <p><a href="/admin" title="Acesso Restrito" style="text-decoration: none; color: #ccc;">&copy;</a> <?php echo date("Y"); ?> Jtek Info. Todos os direitos reservados.</p>
        </div>
    </footer>
	<!-- JavaScript para o Acordeão -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const accordionItems = document.querySelectorAll('.accordion-header');
            
            accordionItems.forEach(item => {
                item.addEventListener('click', function () {
                    // Fecha todos os outros itens abertos
                    accordionItems.forEach(otherItem => {
                        if (otherItem !== this) {
                            otherItem.classList.remove('active');
                            otherItem.nextElementSibling.style.display = 'none';
                        }
                    });

                    // Abre ou fecha o item clicado
                    this.classList.toggle('active');
                    const content = this.nextElementSibling;
                    if (content.style.display === 'block') {
                        content.style.display = 'none';
                    } else {
                        content.style.display = 'block';
                    }
                });
            });
        });
    </script>
    

<!-- ===== CHAT WIDGET ===== -->
    <div id="chat-widget-container">
        <div id="chat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
        </div>
        <div id="chat-window" class="hidden">
            <div id="chat-header">
                <span>Fale Conosco</span>
                <button id="close-chat">-</button>
            </div>
            <div id="chat-messages">
                <!-- As mensagens serão inseridas aqui -->
            </div>
            <div id="chat-input-area">
                <input type="text" id="chat-input" placeholder="Digite sua mensagem...">
                <button id="send-chat-message">Enviar</button>
            </div>
            <!-- Nova área para quando o chat for finalizado -->
            <div id="chat-finished-area" class="hidden">
                <button id="start-new-chat">Iniciar Novo Atendimento</button>
            </div>
        </div>
    </div>
    <!-- ===== FIM DO CHAT WIDGET ===== -->

	<!-- JavaScript para o Acordeão e Chat -->
    <script>
        // ... (o seu script do acordeão existente) ...

        // ===== LÓGICA DO CHAT =====
        document.addEventListener('DOMContentLoaded', function () {
            const chatIcon = document.getElementById('chat-icon');
            const chatWindow = document.getElementById('chat-window');
            const closeChat = document.getElementById('close-chat');
            const messagesContainer = document.getElementById('chat-messages');
            const chatInputArea = document.getElementById('chat-input-area');
            const chatInput = document.getElementById('chat-input');
            const sendMessageBtn = document.getElementById('send-chat-message');
            const chatFinishedArea = document.getElementById('chat-finished-area');
            const startNewChatBtn = document.getElementById('start-new-chat');
            
            let chatSessionId = localStorage.getItem('chat_session_id');
            let messagePolling;
            let chatStatus = 'Aguardando';

            // Função para formatar a data e hora
            function formatTimestamp(timestamp) {
                const date = new Date(timestamp);
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                return `${day}/${month} ${hours}:${minutes}`;
            }

            chatIcon.addEventListener('click', () => {
                chatWindow.classList.remove('hidden');
                chatIcon.classList.add('hidden');
                startChat();
            });

            closeChat.addEventListener('click', () => {
                chatWindow.classList.add('hidden');
                chatIcon.classList.remove('hidden');
                stopPolling();
            });

            sendMessageBtn.addEventListener('click', sendMessage);
            chatInput.addEventListener('keypress', (e) => e.key === 'Enter' && sendMessage());

            startNewChatBtn.addEventListener('click', () => {
                localStorage.removeItem('chat_session_id');
                chatSessionId = null;
                messagesContainer.innerHTML = '';
                startChat();
            });

            async function startChat() {
                if (!chatSessionId) {
                    try {
                        const response = await fetch('/chat_api.php?action=start_session');
                        const data = await response.json();
                        if (data.status === 'success') {
                            chatSessionId = data.session_id;
                            localStorage.setItem('chat_session_id', chatSessionId);
                        }
                    } catch (error) {
                        console.error('Erro ao iniciar sessão:', error);
                        return;
                    }
                }
                loadMessages();
                startPolling();
            }

            async function loadMessages() {
                if (!chatSessionId) return;
                try {
                    const response = await fetch(`/chat_api.php?action=get_messages&session_id=${chatSessionId}`);
                    const data = await response.json();

                    if(data.status === 'error') return;

                    chatStatus = data.status;
                    const messages = data.messages;

                    const shouldScroll = messagesContainer.scrollTop + messagesContainer.clientHeight >= messagesContainer.scrollHeight - 20;

                    messagesContainer.innerHTML = '';
                    messages.forEach(msg => appendMessage(msg.sender, msg.message, msg.timestamp));

                    if (chatStatus === 'Finalizado') {
                        const finalizadoDiv = document.createElement('div');
                        finalizadoDiv.className = 'chat-message system-message';
                        finalizadoDiv.textContent = 'Atendimento finalizado pelo operador.';
                        messagesContainer.appendChild(finalizadoDiv);
                        
                        chatInputArea.classList.add('hidden');
                        chatFinishedArea.classList.remove('hidden');
                        stopPolling();
                    } else {
                        chatInputArea.classList.remove('hidden');
                        chatFinishedArea.classList.add('hidden');
                    }

                    if (shouldScroll) {
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    }
                } catch (error) {
                    console.error('Erro ao carregar mensagens:', error);
                }
            }

            async function sendMessage() {
                const message = chatInput.value.trim();
                if (message === '' || !chatSessionId) return;
                
                chatInput.value = '';

                try {
                    await fetch('/chat_api.php?action=send_message', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ session_id: chatSessionId, message: message, sender: 'user' })
                    });
                    loadMessages(); // Carrega as mensagens imediatamente para ver a sua e a hora
                } catch (error) {
                    console.error('Erro ao enviar mensagem:', error);
                }
            }

            function appendMessage(sender, message, timestamp) {
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('chat-message', `${sender}-message`);
                
                const messageText = document.createElement('span');
                messageText.className = 'message-text';
                messageText.textContent = message;
                
                const timeText = document.createElement('span');
                timeText.className = 'chat-timestamp';
                timeText.textContent = formatTimestamp(timestamp);

                messageDiv.appendChild(messageText);
                messageDiv.appendChild(timeText);
                messagesContainer.appendChild(messageDiv);
            }

            function startPolling() {
                stopPolling();
                messagePolling = setInterval(loadMessages, 3000);
            }

            function stopPolling() {
                clearInterval(messagePolling);
            }
        });
    </script>

    
</body>
</html>
