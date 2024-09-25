const salvar = document.getElementById('fazerlogin');

async function Insert() {
    const form = document.getElementById('form');
    const formData = new FormData(form);
    const opt = {
        method: 'POST',
        body: formData
    };
    try {
        const response = await fetch('/login/entrar', opt);
        alert('oi');
        die;
        
        // Captura o texto da resposta
        const text = await response.text();
        console.log(text);
        
        // Verifica se a resposta não é OK
        if (!response.ok) {
            const errorJson = JSON.parse(text); // Tenta converter o texto em JSON
            throw new Error(errorJson.message || 'Restrição ao fazer login');
        }
        
        // Converte o texto em JSON após a verificação
        const json = JSON.parse(text);
        
        if (json.status) {
            alert('Bem-vindo!');
        } else {
            alert('Verifique os dados digitados e tente novamente!');
        }
    } catch (error) {
        alert(`Erro: ${error.message}`);
    }
}

salvar.addEventListener('click', async () => {
    await Insert();
});