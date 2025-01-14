import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, CheckboxControl } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';

registerBlockType('veeraj/table-block', {
    title: veerajPluginData.translations.tableBlockTitle,
    category: 'widgets',
    icon: 'table-col-after',
    attributes: {
        visibleColumns: {
            type: 'object',
            default: {
                id: true,
                fname: true,
                lname: true,
                email: true,
                date: true,
            },
        },
        data: {
            type: 'object',
            default: null,
        },
    },

    // edit function for the block editor
    edit: (props) => {
        const { attributes, setAttributes } = props;
        const { visibleColumns, data } = attributes;
        const blockProps = useBlockProps();
        const [loading, setLoading] = useState(false);
        const [error, setError] = useState(null);
    
        // Function to capitalize the first letter of each word
        const capitalizeFirstLetter = (string) => {
            return string.charAt(0).toUpperCase() + string.slice(1);
        };
    
        // Column names map for full names
        const columnNames = {
            id: 'ID',
            fname: 'First Name',
            lname: 'Last Name',
            email: 'Email',
            date: 'Date'
        };
    
        useEffect(() => {
            // If data is already loaded in the block, don't fetch it again
            if (data) {
                return; // Skip fetching if data is already present
            }
    
            // Fetch data from the AJAX endpoint if no data is already available
            setLoading(true);
            fetch(veerajPluginData.ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    action: 'get_veeraj_data',
                    nonce: veerajPluginData.nonce,
                }),
            })
                .then((response) => response.json())
                .then((result) => {
                    if (result.success) {
                        setAttributes({ data: result.data });
                    } else {
                        setError(result.data?.error || veerajPluginData.translations.unknownError);
                    }
                })
                .catch((err) => {
                    setError(err.message);
                })
                .finally(() => {
                    setLoading(false);
                });
        }, [data, setAttributes]); // Run effect only if `data` is not available
    
        if (loading) {
            return <p {...blockProps}>{veerajPluginData.translations.loadingMessage}</p>;
        }
    
        if (error) {
            return <p {...blockProps}>{veerajPluginData.translations.errorMessage}: {escapeHTML(error)}</p>;
        }
    
        return (
            <div {...blockProps}>
                <InspectorControls>
                    <PanelBody title="Column Visibility">
                        {Object.keys(visibleColumns).map((column) => (
                            <CheckboxControl
                                key={column}
                                label={`Show ${columnNames[column] || capitalizeFirstLetter(column)}`}  // Use full names or capitalize the first letter
                                checked={visibleColumns[column]}
                                onChange={(value) =>
                                    setAttributes({
                                        visibleColumns: {
                                            ...visibleColumns,
                                            [column]: value,
                                        },
                                    })
                                }
                            />
                        ))}
                    </PanelBody>
                </InspectorControls>
                <table>
                    <thead>
                        <tr>
                            {visibleColumns.id && <th>{veerajPluginData.translations.id}</th>}
                            {visibleColumns.fname && <th>{veerajPluginData.translations.firstName}</th>}
                            {visibleColumns.lname && <th>{veerajPluginData.translations.lastName}</th>}
                            {visibleColumns.email && <th>{veerajPluginData.translations.email}</th>}
                            {visibleColumns.date && <th>{veerajPluginData.translations.date}</th>}
                        </tr>
                    </thead>
                    <tbody>
                        {Object.values(data?.data?.rows || {}).map((row) => (
                            <tr key={row.id}>
                                {visibleColumns.id && <td>{row.id}</td>}
                                {visibleColumns.fname && <td>{row.fname}</td>}
                                {visibleColumns.lname && <td>{row.lname}</td>}
                                {visibleColumns.email && <td>{row.email}</td>}
                                {visibleColumns.date && (
                                    <td>{new Date(row.date * 1000).toLocaleString()}</td>
                                )}
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        );
    },
    // save function for saving data and rendering it on the frontend
    save: (props) => {
        const { attributes } = props;
        const { visibleColumns, data } = attributes;

        // No data to render if no visible columns or data
        if (!data || !Object.values(visibleColumns).includes(true)) {
            return <p>{veerajPluginData.translations.noDataMessage}</p>;
        }

        return (
            <div {...useBlockProps.save()}>
                <div className="veeraj-table-wrapper">
                    <table className="veeraj-table">
                        <thead>
                            <tr>
                                {visibleColumns.id && <th>{veerajPluginData.translations.id}</th>}
                                {visibleColumns.fname && <th>{veerajPluginData.translations.firstName}</th>}
                                {visibleColumns.lname && <th>{veerajPluginData.translations.lastName}</th>}
                                {visibleColumns.email && <th>{veerajPluginData.translations.email}</th>}
                                {visibleColumns.date && <th>{veerajPluginData.translations.date}</th>}
                            </tr>
                        </thead>
                        <tbody>
                            {Object.values(data?.data?.rows || {}).map((row) => (
                                <tr key={row.id}>
                                    {visibleColumns.id && <td>{row.id}</td>}
                                    {visibleColumns.fname && <td>{row.fname}</td>}
                                    {visibleColumns.lname && <td>{row.lname}</td>}
                                    {visibleColumns.email && <td>{row.email}</td>}
                                    {visibleColumns.date && (
                                        <td>{new Date(row.date * 1000).toLocaleString()}</td>
                                    )}
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        );
    },
});
