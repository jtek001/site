<?php
// Inclui o arquivo de configuração do banco de dados
require_once '../db_config.php';

// Define o fuso horário para garantir que a data seja salva corretamente
date_default_timezone_set('America/Sao_Paulo');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // --- Colete e limpe os dados do formulário ---
    $nome = strip_tags(trim($_POST["nome"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $assunto = strip_tags(trim($_POST["assunto"]));
    $mensagem = trim($_POST["mensagem"]);

    // --- Validação ---
    if (empty($nome) || empty($assunto) || empty($mensagem) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: /contato/?status=erro");
        exit;
    }

    // --- Insere os dados no banco de dados usando Prepared Statements ---
    $sql = "INSERT INTO contatos (nome, email, assunto, mensagem) VALUES (?, ?, ?, ?)";

    if ($stmt = $mysqli->prepare($sql)) {
        // Associa os parâmetros com as variáveis
        $stmt->bind_param("ssss", $nome, $email, $assunto, $mensagem);
        
        // Executa a query e verifica o resultado
        if ($stmt->execute()) {
            // Sucesso: redireciona com mensagem de sucesso
            header("Location: /contato/?status=sucesso");
        } else {
            // Falha na execução: redireciona com mensagem de erro
            header("Location: /contato/?status=erro_db");
        }
        
        // Fecha o statement
        $stmt->close();
    } else {
        // Falha na preparação da query
        header("Location: /contato/?status=erro_db");
    }

    // Fecha a conexão
    $mysqli->close();

} else {
    // Se alguém tentar acessar o arquivo diretamente, nega o acesso.
    echo "Acesso negado.";
}
?>
