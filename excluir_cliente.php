<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];

    // Excluindo cliente (e serviços relacionados em cascata)
    $sql = "DELETE FROM Clientes WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Cliente excluído com sucesso.";
        // Redirecionamento opcional para a página de listagem de clientes
        // header("Location: listar_clientes.php");
    } else {
        echo "Erro ao excluir cliente: " . $conn->error;
    }

    $conn->close();
}
?>
