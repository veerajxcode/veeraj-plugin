jQuery(document).ready(function ($) {
    // Trigger to fetch the data
    $('#refresh-data-button').on('click', function () {
        // Add loading state
        $('#data-container').html('<p>Loading...</p>');

        $.ajax({
            url: veerajPluginData.ajaxurl,
            method: 'GET',
            data: {
                action: 'get_veeraj_data',
            },
            success: function (response) {
                if (response.success) {
                    console.log('Fetched data:', response.data);
            
                    const tableData = response.data.data; // Extract the main data object
            
                    if (tableData) {
                        // Clear the existing content
                        $('#data-container').empty();
            
                        // Create a table element
                        const table = $('<table>').addClass('veeraj-table');
                        const thead = $('<thead>');
                        const tbody = $('<tbody>');
            
                        // Add the table title
                        if (response.data.title) {
                            $('#data-container').append(`<h3>${response.data.title}</h3>`);
                        }
            
                        // Render table headers
                        if (Array.isArray(tableData.headers)) {
                            const headerRow = $('<tr>');
                            tableData.headers.forEach(header => {
                                headerRow.append(`<th>${header}</th>`);
                            });
                            thead.append(headerRow);
                        }
            
                        // Render table rows
                        const rows = tableData.rows;
                        if (rows && typeof rows === 'object') {
                            Object.keys(rows).forEach(key => {
                                const row = rows[key];
                                const tableRow = $('<tr>');
                                tableRow.append(`<td>${row.id}</td>`);
                                tableRow.append(`<td>${row.fname}</td>`);
                                tableRow.append(`<td>${row.lname}</td>`);
                                tableRow.append(`<td>${row.email}</td>`);
                                tableRow.append(`<td>${new Date(row.date * 1000).toLocaleDateString()}</td>`); // Format the date
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
                    $('#data-container').html(`<p>Error fetching data: ${response.data.message}</p>`);
                }
            },            
            error: function () {
                $('#data-container').html('<p>Failed to fetch data. Please try again.</p>');
                console.error('AJAX request failed.');
            }
        });
    });
});
