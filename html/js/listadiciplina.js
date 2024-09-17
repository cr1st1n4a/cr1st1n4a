const table = new DataTable('#tabela', {
    paging: true,
    lengthChange: true,
    searching: true,
    ordering: true,
    info: true,
    autoWidth: false,
    responsive: true,
    stateSave: true,
    select: true,
    language: {
        url: '/js/pt-BR.json'
    },
});