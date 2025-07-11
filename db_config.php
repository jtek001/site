<?php
// --- Configurações do Banco de Dados ---
define('DB_SERVER', 'localhost'); // Geralmente é 'localhost'
define('DB_USERNAME', 'jtek_site2');    // Substitua pelo seu usuário do banco de dados
define('DB_PASSWORD', 'G021567e');      // Substitua pela sua senha
define('DB_NAME', 'jtek_site2');          // Substitua pelo nome do seu banco de dados

// --- Tenta estabelecer a conexão ---
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// --- Verifica a conexão ---
if($mysqli === false){
    // Se não conseguir conectar, não mostra o erro detalhado para o usuário final por segurança.
    // Em vez disso, podemos gravar o erro num log no servidor.
    // Para depuração, você pode descomentar a linha abaixo:
    // die("ERRO: Não foi possível conectar. " . $mysqli->connect_error);
    
    // Apenas encerra o script silenciosamente em produção.
    exit();
}

// Define o charset para UTF-8 para evitar problemas com acentuação
$mysqli->set_charset("utf8mb4");
?>
