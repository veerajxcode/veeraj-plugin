/******/ (() => { // webpackBootstrap
/*!*****************************************************!*\
  !*** ./assets/js/gutenberg-block/table-frontend.js ***!
  \*****************************************************/
document.addEventListener('DOMContentLoaded', function () {
  var toggleButtons = document.querySelectorAll('.veeraj-toggle-column');
  toggleButtons.forEach(function (button) {
    button.addEventListener('click', function (event) {
      var columnClass = event.target.dataset.column;
      var cells = document.querySelectorAll(".".concat(columnClass));
      cells.forEach(function (cell) {
        cell.style.display = cell.style.display === 'none' ? '' : 'none';
      });
    });
  });
});
/******/ })()
;
//# sourceMappingURL=frontend.bundle.js.map