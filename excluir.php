<?php
// Incluir arquivo de conexão com o banco de dados
include 'config.php';

// Verificar se o parâmetro cliente_id foi enviado via GET
if (isset($_GET['cliente_id'])) {
    $cliente_id = $_GET['cliente_id'];

    // Sanitize the input to prevent SQL injection
    $cliente_id = intval($cliente_id); // Assuming client_id is integer in your database
    
    // Query SQL para excluir serviços relacionados ao veículo
    $sql_delete_servicos = "DELETE FROM servicos WHERE veiculo_id = $cliente_id";
    
    if ($conn->query($sql_delete_servicos) === TRUE) {
        echo "Serviços relacionados ao veículo com ID $cliente_id foram excluídos com sucesso.<br>";
        
        // Agora podemos excluir o veículo
        $sql_delete_veiculo = "DELETE FROM veiculos WHERE id = $cliente_id";
        
        if ($conn->query($sql_delete_veiculo) === TRUE) {
            echo "Veículo com ID $cliente_id excluído com sucesso.<br>";
        } else {
            echo "Erro ao excluir veículo com ID $cliente_id: " . $conn->error . "<br>";
        }
    } else {
        echo "Erro ao excluir serviços relacionados ao veículo com ID $cliente_id: " . $conn->error . "<br>";
    }
} else {
    echo "Nenhum ID de veículo especificado.";
}

// Fechar conexão com o banco de dados
$conn->close();
?>
