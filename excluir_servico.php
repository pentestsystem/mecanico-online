<script>
    function excluirServico(id) {
        if (confirm('Tem certeza que deseja excluir este serviço?')) {
            // Requisição AJAX para deletar o serviço no backend
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'excluir_servico.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                // Verifica se a exclusão foi bem-sucedida
                if (xhr.status === 200) {
                    // Atualiza a página após a exclusão ou toma outra ação necessária
                    window.location.reload();
                } else {
                    alert('Erro ao excluir serviço.');
                }
            };
            xhr.send('id=' + id);
        }
    }
</script>
