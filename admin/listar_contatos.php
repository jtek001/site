<?php
// Inicia a sessão e define o fuso horário
session_start();
date_default_timezone_set('America/Sao_Paulo');

// Inclui a configuração do banco de dados
require_once '../db_config.php';

// Verifica se o usuário está logado.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}

// --- LÓGICA PARA APAGAR CONTATO DO BANCO DE DADOS ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_para_apagar = intval($_GET['id']);
    
    $sql_delete = "DELETE FROM contatos WHERE id = ?";
    if ($stmt = $mysqli->prepare($sql_delete)) {
        $stmt->bind_param("i", $id_para_apagar);
        $stmt->execute();
        $stmt->close();
    }
    // Redireciona para a mesma página para limpar a URL e atualizar a lista
    header('Location: listar_contatos.php');
    exit;
}

// Lógica de Logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}

// Busca os contatos do banco de dados
$contatos = [];
$sql_select = "SELECT id, nome, email, assunto, mensagem, data_envio FROM contatos ORDER BY data_envio DESC";
if ($result = $mysqli->query($sql_select)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $contatos[] = $row;
        }
        $result->free();
    }
}

// O cabeçalho é incluído APÓS toda a lógica do PHP ter sido processada
include '../header.php';
?>

<main>
    <section class="page-header">
        <div class="container">
            <h1>Contatos Recebidos</h1>
            <p>Mensagens enviadas pelo formulário do site.</p>
            <div class="admin-buttons">
                 <a href="?action=logout" class="btn-outline logout-button">Sair</a>
            </div>
        </div>
    </section>

    <section class="contacts-list-section">
        <div class="container">
            <div class="contact-accordion">
                <?php if (!empty($contatos)): ?>
                    <?php foreach ($contatos as $contato): ?>
                        <div class="accordion-item">
                            <button class="accordion-header">
                                <span class="header-name"><?php echo htmlspecialchars($contato['nome']); ?></span>
                                <span class="header-subject"><?php echo htmlspecialchars($contato['assunto']); ?></span>
                                <span class="header-date"><?php echo date('d/m/Y H:i', strtotime($contato['data_envio'])); ?></span>
                            </button>
                            <div class="accordion-content">
                                <p><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($contato['email']); ?>"><?php echo htmlspecialchars($contato['email']); ?></a></p>
                                <p><strong>Mensagem:</strong></p>
                                <div class="message-body">
                                    <?php echo nl2br(htmlspecialchars($contato['mensagem'])); ?>
                                </div>
                                <a href="?action=delete&id=<?php echo $contato['id']; ?>" class="btn-delete" onclick="return confirm('Tem certeza que deseja apagar esta mensagem? Esta ação é irreversível.');">
                                    Apagar Mensagem
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center;">Nenhum contato recebido ainda.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php 
// O rodapé é incluído no final
include '../footer.php'; 

// A conexão com o banco de dados é fechada aqui, no final de tudo
$mysqli->close();
?>
