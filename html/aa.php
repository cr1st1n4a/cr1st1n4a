<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 400px;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Login</h2>
    <form id="loginForm">
        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" id="nome" placeholder="Seu Nome" required>
        </div>
        <div class="form-group">
            <label for="loginLogin">Login/Email</label>
            <input type="text" class="form-control" id="login" placeholder="Seu Login/Email" required>
        </div>
        <div class="form-group">
            <label for="senha">Senha</label>
            <input type="password" class="form-control" id="senha" placeholder="Sua Senha" required>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="confirmCadastro" required>
            <label class="form-check-label" for="confirmCadastro">Confirmar Cadastro</label>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <button type="button" class="btn btn-link" data-toggle="modal" data-target="#esqueceuSenhaModal">Esqueceu sua senha?</button>
        <button type="button" class="btn btn-link" data-toggle="modal" data-target="#cadastroModal">Cadastro</button>
        <div class="modal fade" id="esqueceuSenhaModal" tabindex="-1" role="dialog" aria-labelledby="esqueceuSenhaModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="esqueceuSenhaModalLabel">Alterar Senha</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="resetPasswordForm">
                            <div class="form-group">
                                <label for="novaSenha">Digite sua nova senha</label>
                                <input type="password" class="form-control" id="novaSenha" required>
                            </div>
                            <div class="form-group">
                                <label for="confirmeSenha">Confirme sua nova senha</label>
                                <input type="password" class="form-control" id="confirmeSenha" required>
                            </div>
                            <div id="error-message" class="text-danger" style="display: none;">As senhas não coincidem.</div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" id="confirmResetPassword">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="cadastroModal" tabindex="-1" role="dialog" aria-labelledby="cadastroModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cadastroModalLabel">Cadastro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="cadastroForm">
                    <div class="form-group">
                        <label for="nomeCadastro">Nome</label>
                        <input type="text" class="form-control" id="nomeCadastro" required>
                    </div>
                    <div class="form-group">
                        <label for="loginCadastro">Login</label>
                        <input type="text" class="form-control" id="loginCadastro" required>
                    </div>
                    <div class="form-group">
                        <label for="emailCadastro">E-mail</label>
                        <input type="text" class="form-control" id="emailCadastro" required>
                    </div>
                    <div class="form-group">
                        <label for="senhaCadastro">Senha</label>
                        <input type="password" class="form-control" id="senhaCadastro" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary"   data-toggle="modal" id="confirmarCadastro">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Função para verificar as senhas no modal de recuperação de senha
    document.getElementById('confirmResetPassword').onclick = function() {
        const novaSenha = document.getElementById('novaSenha').value;
        const confirmeSenha = document.getElementById('confirmeSenha').value;
        const errorMessage = document.getElementById('error-message');

        if (novaSenha !== confirmeSenha) {
            errorMessage.style.display = 'block';
        } else {
            errorMessage.style.display = 'none';
            // Aqui você pode adicionar lógica para processar a nova senha
            alert('Senha alterada com sucesso!');
            
        }
    };

    document.getElementById('loginForm').onsubmit = function(event) {
    const confirmCadastro = document.getElementById('confirmCadastro');
    if (!confirmCadastro.checked) {
        alert('Você deve confirmar o cadastro.');
        event.preventDefault(); // Impede o envio do formulário
    }
};

document.getElementById('confirmarCadastro').onclick = function() {
        const nome = document.getElementById('nomeCadastro').value;
        const login = document.getElementById('loginCadastro').value;
        const email = document.getElementById('emailCadastro').value;
        const senha = document.getElementById('senhaCadastro').value;
        
        fetch('cadastrar.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            nome: nome,
            login: login,
            email: email,
            senha: senha
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Cadastro realizado com sucesso!');
            // Fechar o modal após o cadastro
            $('#cadastroModal').modal('hide');
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => console.error('Erro:', error));
};
        // Obter valores dos campos do formulário principal
        const nomePrincipal = document.getElementById('nome').value;
        const loginPrincipal = document.getElementById('login').value;
        const senhaPrincipal = document.getElementById('senha').value;

    
    
   

</script>

</body>
</html>