<?php
// Inicia a sessão e define o fuso horário
session_start();
date_default_timezone_set('America/Sao_Paulo');

// Verifica se o usuário está logado.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}

$lixeira_dir = '../lixeira/';
$contatos_dir = '../contatos/';

// --- LÓGICA PARA RESTAURAR ARQUIVO ---
if (isset($_GET['action']) && $_GET['action'] == 'restore' && isset($_GET['file'])) {
    $arquivo_para_restaurar = basename($_GET['file']);
    $caminho_origem = $lixeira_dir . $arquivo_para_restaurar;
    $caminho_destino = $contatos_dir . $arquivo_para_restaurar;

    if (file_exists($caminho_origem)) {
        rename($caminho_origem, $caminho_destino); // Move o arquivo de volta
    }
    header('Location: lixeira.php');
    exit;
}

// --- LGICA PARA APAGAR PERMANENTEMENTE ---
if (isset($_GET['action']) && $_GET['action'] == 'perm_delete' && isset($_GET['file'])) {
    $arquivo_para_apagar = basename($_GET['file']);
    $caminho_arquivo = $lixeira_dir . $arquivo_para_apagar;

    if (file_exists($caminho_arquivo)) {
        unlink($caminho_arquivo); // Apaga permanentemente
    }
    header('Location: lixeira.php');
    exit;
}

$arquivos = [];
if (is_dir($lixeira_dir)) {
    $arquivos_scandir = scandir($lixeira_dir);
    foreach ($arquivos_scandir as $arquivo) {
        if ($arquivo !== '.' && $arquivo !== '..') {
            $arquivos[] = $arquivo;
        }
    }
    rsort($arquivos);
}

include '../header.php';
?>

<main>
    <section class="page-header">
        <div class="container">
            <h1>Lixeira de Contatos</h1>
            <p>Arquivos movidos da lista principal. Restaure ou apague permanentemente.</p><br>
            <a href="listar_contatos.php" class="btn-outline btn-lixeira">Voltar para Contatos</a>
        </div>
    </section>

    <section class="contacts-list-section">
        <div class="container">
            <table>
                <thead>
                    <tr>
                        <th>Arquivo na Lixeira</th>
                        <th colspan="2" style="text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($arquivos)): ?>
                        <?php foreach ($arquivos as $arquivo): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($arquivo); ?></td>
                                <td style="text-align: center;">
                                    <a href="?action=restore&file=<?php echo urlencode($arquivo); ?>" class="btn-restore">
                                        Restaurar
                                    </a>
                                </td>
                                <td style="text-align: center;">
                                    <a href="?action=perm_delete&file=<?php echo urlencode($arquivo); ?>" class="btn-delete" onclick="return confirm('APAGAR PERMANENTEMENTE?\n\nEsta ação é irreversível e o contato não poderá ser recuperado.');">
                                        Apagar Perm.
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center;">A lixeira está vazia.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php include '../footer.php'; ?>
