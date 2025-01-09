/******/ (() => { // webpackBootstrap
/*!***********************************!*\
  !*** ./assets/js/admin-script.js ***!
  \***********************************/
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
jQuery(document).ready(function ($) {
  // Trigger to fetch the data
  $('#refresh-data-button').on('click', function () {
    // Add loading state
    $('#data-container').html('<p>Loading...</p>');
    $.ajax({
      url: veerajPluginData.ajaxurl,
      method: 'GET',
      data: {
        action: 'get_veeraj_data'
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
            if (rows && _typeof(rows) === 'object') {
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
/******/ })()
;
//# sourceMappingURL=admin.bundle.js.map