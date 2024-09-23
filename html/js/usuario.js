const salvar = document.getElementById('salvar');
async function Insert() {
    const form = document.getElementById('formCadastro');
    formData = new FormData(form);
    const opt = {
        method: 'POST',
        body: formData
    };
    const response = await fetch('/usuario/insert', opt);
    if (!response.ok) {
        console.error('Erro na requisição:', response.statusText);
        return;
    }
    const json = await response.json();
    if (json.status != true) {
        alert('Verique os dados digitados e tente novamente!');
        return;
    }
    alert('Disciplina cadastrada com sucesso!');
    return;
}
salvar.addEventListener('click', async () => {
    await Insert();
});