window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables initialization
    const datatablesSimple = document.getElementById('datatablesSimple');
    if (datatablesSimple) {
        new simpleDatatables.DataTable(datatablesSimple);
    }
});
