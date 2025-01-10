/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/@babel/runtime/helpers/esm/typeof.js":
/*!***********************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/typeof.js ***!
  \***********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ _typeof)
/* harmony export */ });
function _typeof(o) {
  "@babel/helpers - typeof";

  return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) {
    return typeof o;
  } : function (o) {
    return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o;
  }, _typeof(o);
}


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!***********************************!*\
  !*** ./assets/js/admin-script.js ***!
  \***********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");

jQuery(document).ready(function ($) {
  // Trigger to fetch the data
  $('#refresh-data-button').on('click', function () {
    // Add loading state
    $('#data-container').html('<p>Loading...</p>');
    $.ajax({
      url: veerajPluginData.ajaxurl,
      method: 'POST',
      // POST method should be used for AJAX actions in WordPress
      data: {
        action: 'get_veeraj_data' // AJAX action
      },
      success: function success(response) {
        if (response.success) {
          console.log('Fetched data:', response.data);
          var tableData = response.data.data; // Extract the main data object

          if (tableData) {
            // Clear the existing content
            $('#data-container').empty();

            // Create a table element
            var table = $('<table>').addClass('veeraj-table');
            var thead = $('<thead>');
            var tbody = $('<tbody>');

            // Add the table title
            if (response.data.title) {
              $('#data-container').append("<h3>".concat(response.data.title, "</h3>"));
            }

            // Render table headers
            if (Array.isArray(tableData.headers)) {
              var headerRow = $('<tr>');
              tableData.headers.forEach(function (header) {
                headerRow.append("<th>".concat(header, "</th>"));
              });
              thead.append(headerRow);
            }

            // Render table rows
            var rows = tableData.rows;
            if (rows && (0,_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_0__["default"])(rows) === 'object') {
              Object.keys(rows).forEach(function (key) {
                var row = rows[key];
                var tableRow = $('<tr>');
                tableRow.append("<td>".concat(row.id, "</td>"));
                tableRow.append("<td>".concat(row.fname, "</td>"));
                tableRow.append("<td>".concat(row.lname, "</td>"));
                tableRow.append("<td>".concat(row.email, "</td>"));
                tableRow.append("<td>".concat(new Date(row.date * 1000).toLocaleDateString(), "</td>")); // Format the date
                tbody.append(tableRow);
              });
            }

            // Append the table sections and add to the container
            table.append(thead).append(tbody);
            $('#data-container').append(table);
          } else {
            $('#data-container').html('<p>No data available to display.</p>');
          }
        } else {
          $('#data-container').html("<p>Error fetching data: ".concat(response.data.message, "</p>"));
        }
      },
      error: function error() {
        $('#data-container').html('<p>Failed to fetch data. Please try again.</p>');
        console.error('AJAX request failed.');
      }
    });
  });
});
})();

/******/ })()
;
//# sourceMappingURL=admin.bundle.js.map