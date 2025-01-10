document.addEventListener('DOMContentLoaded', () => {
    const toggleButtons = document.querySelectorAll('.veeraj-toggle-column');
    toggleButtons.forEach((button) => {
        button.addEventListener('click', (event) => {
            const columnClass = event.target.dataset.column;
            const cells = document.querySelectorAll(`.${columnClass}`);
            cells.forEach((cell) => {
                cell.style.display = cell.style.display === 'none' ? '' : 'none';
            });
        });
    });
});
