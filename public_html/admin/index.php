<?php
// Inicia a sessão para controlar o login
session_start();

// --- DEFINA SUA SENHA AQUI ---
$senha_mestra = '021567'; // IMPORTANTE: Troque por uma senha forte e segura

$erro = '';

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['senha']) && $_POST['senha'] == $senha_mestra) {
        // Senha correta, define a sessão e redireciona para a lista de contatos
        $_SESSION['loggedin'] = true;
        header('Location: listar_contatos.php');
        exit;
    } else {
        // Senha incorreta
        $erro = 'Senha incorreta. Tente novamente.';
    }
}

// Se o usuário já estiver logado, redireciona para a lista
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: listar_contatos.php');
    exit;
}

// Inclui o cabeçalho, ajustando o caminho para voltar um nível ('../')
include '../header.php';
?>
<main>
    <section class="login-section">
        <div class="container">
            <div class="login-box">
                <h2>Acesso Restrito</h2>
                <p>Esta área é protegida.</p>
                
                <?php if (!empty($erro)): ?>
                    <div class="alert alert-error"><?php echo $erro; ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php">
                    <div class="form-group">
                        <label for="senha">Senha:</label>
                        <input type="password" id="senha" name="senha" required>
                    </div>
                    <button type="submit" class="btn btn-primary-dark">Entrar</button>
                </form>
            </div>
        </div>
    </section>
</main>

<?php 
// Inclui o rodapé, ajustando o caminho para voltar um nível ('../')
include '../footer.php'; 
?>
