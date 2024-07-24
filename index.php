<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Gerenciamento de Oficina</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Sistema de Gerenciamento de Oficina</h1>
    </header>

    <main class="container">
        <!-- Conteúdo principal da página -->
        <h2>Lista de Clientes</h2>
        <div class="clientes">
            <?php
            // PHP para listar clientes
            $conn = new mysqli("localhost", "root", "", "oficina");
            if ($conn->connect_error) {
                die("Falha na conexão: " . $conn->connect_error);
            }
            $result = $conn->query("SELECT id, nome FROM clientes ORDER BY nome");
            if ($result->num_rows > 0) {
                echo "<ul>";
                while ($row = $result->fetch_assoc()) {
                    echo "<li><a href='servicos.php?cliente=" . $row['id'] . "'>" . $row['nome'] . "</a></li>";
                }
                echo "</ul>";
            } else {
                echo "<p>Nenhum cliente encontrado.</p>";
            }
            $conn->close();
            ?>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Sistema de Gerenciamento de Oficina</p>
    </footer>
</body>
</html>
