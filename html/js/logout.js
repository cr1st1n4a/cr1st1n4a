document.getElementById('logoutButton').addEventListener('click', async () => {
    const options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    };

    try {
        const response = await fetch('/logout', options);
        if (response.ok) {
            window.location.href = '/login'; // Redireciona após logout bem-sucedido
        } else {
            const errorMessage = await response.text();
            alert('Erro ao desfazer login: ' + errorMessage);
        }
    } catch (error) {
        console.error('Erro ao realizar logout:', error);
        alert('Ocorreu um erro. Tente novamente mais tarde.');
    }
});