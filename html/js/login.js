function showAlert(message, type) {
    const alertContainer = document.querySelector('.alert-container');
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.role = 'alert';
    alert.textContent = message;
    alertContainer.appendChild(alert);
    setTimeout(() => {
        alert.remove();
    }, 1000);
}

document.getElementById('salvarSenha').onclick = function () {
    const novaSenha = document.getElementById('novaSenha').value;
    const confirmaSenha = document.getElementById('confirmaSenha').value;

    if (novaSenha === confirmaSenha && novaSenha !== "") {
        $('#successAlert').removeClass('d-none');
        setTimeout(() => {
            $('#successAlert').addClass('d-none');
            $('#esqueceuSenhaModal').modal('hide'); // Fecha o modal após o alerta
        }, 1000);
    } else {
        $('#warningAlert').removeClass('d-none');
    }
};

// Mantém o modal aberto se as senhas não coincidirem
$('#esqueceuSenhaModal').on('hide.bs.modal', function (e) {
    const warningVisible = !$('#warningAlert').hasClass('d-none');
    if (warningVisible) {
        e.preventDefault(); // Cancela o fechamento do modal
    }
});

document.getElementById('confirmarCadastro').onclick = function () {
    const nome = document.getElementById('nome').value;
    const login = document.getElementById('login').value;
    const email = document.getElementById('email').value;
    const senha = document.getElementById('senha').value;

    // Prossegue com o cadastro
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
                showAlert('Cadastro realizado com sucesso!', 'success');
                $('#cadastroModal').modal('hide'); // Fecha o modal
            } else {
                showAlert('Erro: ' + data.message, 'warning');
            }
        })
        .catch(error => {
            showAlert('Erro: ' + error.message, 'danger');
        });
};

document.getElementById('loginForm').onsubmit = function (event) {
    event.preventDefault(); // Impede o envio padrão do formulário

    const login = document.getElementById('login').value;
    const senha = document.getElementById('senha').value;

    // Envia uma requisição ao servidor para verificar as credenciais
    fetch('login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            login: login,
            senha: senha
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Armazena a autenticação
                localStorage.setItem('authenticated', 'true');
                // Redireciona para uma página de sucesso
                window.location.href = '/home';
            } else {
                showAlert('Login ou senha inválidos.', 'danger');
            }
        })
        .catch(error => {
            showAlert('Erro: ' + error.message, 'danger');
        });
};

salvar.addEventListener('click', async () => {
    await Insert();
});