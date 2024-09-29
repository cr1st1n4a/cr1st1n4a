document.getElementById('form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    const options = {
        method: "POST",
        headers: {
            'Accept': 'application/json',
        },
        body: formData
    };

    try {
        const response = await fetch('/login/authenticate', options);
        if (response.ok) {
            window.location.href = '/'; // Redireciona após login bem-sucedido
        } else {
            const errorMessage = await response.text();
            alert(errorMessage); // Exibe mensagem de erro
        }
    } catch (error) {
        console.error('Erro ao realizar login:', error);
        alert('Ocorreu um erro. Tente novamente mais tarde.');
    }
});

document.getElementById('confirmarCadastro').addEventListener('click', async () => {
    const nome = document.getElementById('nomeCadastro').value;
    const login = document.getElementById('loginCadastro').value;
    const email = document.getElementById('emailCadastro').value;
    const senha = document.getElementById('senhaCadastro').value;

    const response = await fetch('/usuario/insert', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ nome, login, email, senha })

    });
    if (response.ok) {
        alert('Cadastro realizado com sucesso!');
        $('#cadastroModal').modal('hide'); // Fecha o modal após cadastro bem-sucedido
    } else {
        const errorMessage = await response.text();
        alert(errorMessage); // Exibe mensagem de erro
    }

});

// Adicionando funcionalidade para a redefinição de senha (opcional)
document.getElementById('salvarSenha').addEventListener('click', async () => {
    const novaSenha = document.getElementById('novaSenha').value;
    const confirmaSenha = document.getElementById('confirmaSenha').value;

    if (novaSenha !== confirmaSenha) {
        document.getElementById('warningAlert').classList.remove('d-none'); // Mostra alerta se as senhas não coincidirem
        return;
    }

    // Aqui você pode implementar a lógica para redefinir a senha
    // const response = await fetch('/usuario/reset-password', {...});

    alert('Senha redefinida com sucesso!'); // Simulação de sucesso
    $('#esqueceuSenhaModal').modal('hide'); // Fecha o modal
});
