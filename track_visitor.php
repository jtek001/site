<?php
// Inclui o arquivo de configuração do banco de dados
require_once 'db_config.php';

// --- Coleta de Dados do Visitante ---

// Endereço IP do visitante
$ip_address = $_SERVER['REMOTE_ADDR'];

// User Agent (informações sobre o navegador e sistema operacional)
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Não informado';

// Página que o visitante está a aceder
$page_visited = $_SERVER['REQUEST_URI'] ?? '/';

// Página de onde o visitante veio (se houver)
$referer_page = $_SERVER['HTTP_REFERER'] ?? 'Acesso direto';


// --- Insere os dados no banco de dados usando Prepared Statements para segurança ---

// Prepara a query SQL para evitar injeção de SQL
$sql = "INSERT INTO visitantes (ip_address, user_agent, page_visited, referer_page) VALUES (?, ?, ?, ?)";

if($stmt = $mysqli->prepare($sql)){
    // Associa os parâmetros com as variáveis
    // "ssss" significa que todos os 4 parâmetros são strings
    $stmt->bind_param("ssss", $ip_address, $user_agent, $page_visited, $referer_page);
    
    // Executa a query
    $stmt->execute();
    
    // Fecha o statement
    $stmt->close();
}

// Fecha a conexão com o banco de dados
$mysqli->close();
?>
