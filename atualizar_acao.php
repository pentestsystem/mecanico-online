<?php
// Incluir arquivo de conexão com o banco de dados
include 'config.php';

// Verificar se o ID do serviço e a ação foram recebidos via POST
if (isset($_POST['id']) && isset($_POST['acao'])) {
    $id = $_POST['id'];
    $acao = $_POST['acao'];

    // Atualizar ação no banco de dados
    $sql = "UPDATE Servicos SET acao = '$acao' WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Resposta HTTP 200 para sucesso
        http_response_code(200);
        echo 'Ação atualizada com sucesso.';
    } else {
        // Resposta HTTP 500 para erro
        http_response_code(500);
        echo 'Erro ao atualizar ação: ' . $conn->error;
    }
} else {
    // Resposta HTTP 400 para requisição inválida
    http_response_code(400);
    echo 'Dados incompletos para atualização.';
}

// Fechar conexão com o banco de dados
$conn->close();
?>
