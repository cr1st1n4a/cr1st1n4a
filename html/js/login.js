document.addEventListener("DOMContentLoaded", function() {
    // Login
    document.getElementById('fazerlogin').addEventListener('click', function(event) {
        event.preventDefault(); // Evita o comportamento padrão do botão
        const form = document.getElementById('formLogin');
        const formData = new FormData(form);

        fetch('/authenticate', { // Rota correta do login
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text); });
            }
            return response.text();
        })
        .then(data => {
            console.log(data);
            // Redirecione ou mostre uma mensagem de sucesso
            window.location.href = '/home'; // Redirecione para uma página de sucesso
        })
        .catch(error => {
            console.error('Erro:', error);
            alert(error.message); // Mostre o erro ao usuário
        });
    });

    // Cadastro
    document.getElementById('salvarCadastro').addEventListener('click', function(event) {
        event.preventDefault(); // Evita o comportamento padrão do botão
        const form = document.getElementById('formCadastro');
        const formData = new FormData(form);

        fetch('/login/register', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text); });
            }
            return response.text();
        })
        .then(data => {
            console.log(data);
            $('#cadastroModal').modal('hide'); // Fecha o modal de cadastro
            alert('Usuário cadastrado com sucesso!'); // Mensagem de sucesso
        })
        .catch(error => {
            console.error('Erro:', error);
            alert(error.message); // Mostre o erro ao usuário
        });
    });

    // Mudar Senha
    document.getElementById('confirmResetPassword').addEventListener('click', function(event) {
        event.preventDefault(); // Evita o comportamento padrão do botão
        const senhaAtual = document.getElementById('senhaAtual').value;
        const novaSenha = document.getElementById('novaSenha').value;
        const confirmeSenha = document.getElementById('confirmeSenha').value;

        if (novaSenha !== confirmeSenha) {
            document.getElementById('error-message').style.display = 'block'; // Exibe mensagem de erro
            return;
        }

        const formData = new FormData();
        formData.append('senhaAtual', senhaAtual);
        formData.append('novaSenha', novaSenha);

        fetch('/login/change-password', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text); });
            }
            return response.text();
        })
        .then(data => {
            console.log(data);
            $('#mudancaSenhaModal').modal('hide'); // Fecha o modal de mudança de senha
            alert('Senha alterada com sucesso!'); // Mensagem de sucesso
        })
        .catch(error => {
            console.error('Erro:', error);
            alert(error.message); // Mostre o erro ao usuário
        });
    });
});
