import '../css/admin-style.css'; // Import the CSS file for the admin page
jQuery(document).ready(function ($) {
    const fetchData = (forceRefresh = false) => {
        $('#data-container').html('<p>Loading...</p>');

        $.ajax({
            url: veerajPluginData.ajaxurl,
            method: 'POST',
            data: {
                action: 'get_veeraj_data',
                force_refresh: forceRefresh ? 'true' : 'false',
            },
            success: function (response) {
                if (response.success) {
                    const tableData = response.data.data;

                    if (tableData) {
                        $('#data-container').empty();
                        const table = $('<table>').addClass('veeraj-table');
                        const thead = $('<thead>');
                        const tbody = $('<tbody>');

                        if (response.data.title) {
                            $('#data-container').append(`<h3>${response.data.title}</h3>`);
                        }

                        if (Array.isArray(tableData.headers)) {
                            const headerRow = $('<tr>');
                            tableData.headers.forEach(header => {
                                headerRow.append(`<th>${header}</th>`);
                            });
                            thead.append(headerRow);
                        }

                        const rows = tableData.rows;
                        if (rows && typeof rows === 'object') {
                            Object.keys(rows).forEach(key => {
                                const row = rows[key];
                                const tableRow = $('<tr>');
                                tableRow.append(`<td>${row.id}</td>`);
                                tableRow.append(`<td>${row.fname}</td>`);
                                tableRow.append(`<td>${row.lname}</td>`);
                                tableRow.append(`<td>${row.email}</td>`);
                                tableRow.append(`<td>${new Date(row.date * 1000).toLocaleDateString()}</td>`);
                                tbody.append(tableRow);
                            });
                        }

                        table.append(thead).append(tbody);
                        $('#data-container').append(table);
                    } else {
                        $('#data-container').html('<p>No data available to display.</p>');
                    }
                } else {
                    $('#data-container').html(`<p>Error fetching data: ${response.data.message || 'Unknown error'}</p>`);
                }
            },
            error: function () {
                $('#data-container').html('<p>Failed to fetch data. Please try again.</p>');
                console.error('AJAX request failed.');
            }
        });
    };

    // Fetch data on page load
    fetchData();

    // Trigger force refresh on button click
    $('#refresh-data-button').on('click', function () {
        fetchData(true);
    });
});
