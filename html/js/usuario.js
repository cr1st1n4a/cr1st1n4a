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

document.getElementById('salvar').onclick = function() {
    const nome = document.getElementById('nome').value;
    const login = document.getElementById('login').value;
    const email = document.getElementById('email').value;
    const senha = document.getElementById('senha').value;
    
    fetch('usuario.php', {
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
    const emailPrincipal = document.getElementById('email').value;