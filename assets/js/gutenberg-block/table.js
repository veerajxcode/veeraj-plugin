import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, CheckboxControl } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';

registerBlockType('veeraj/table-block', {
    title: 'Veeraj Table Block',
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
    },
    edit: (props) => {
        const { attributes, setAttributes } = props;
        const { visibleColumns } = attributes;
        const blockProps = useBlockProps();
        const [data, setData] = useState(null);
        const [loading, setLoading] = useState(true);
        const [error, setError] = useState(null);

        useEffect(() => {
            // Fetch data from the AJAX endpoint
            setLoading(true);
            fetch(veerajPluginData.ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    action: 'get_veeraj_data',
                    nonce:   veerajPluginData.nonce,
                }),
            })
                .then((response) => response.json())
                .then((result) => {
                    if (result.success) {
                        setData(result.data);
                    } else {
                        setError(result.data?.error || 'Unknown error');
                    }
                })
                .catch((err) => {
                    setError(err.message);
                })
                .finally(() => {
                    setLoading(false);
                });
        }, []);

        if (loading) {
            return <p {...blockProps}>Loading...</p>;
        }

        if (error) {
            return <p {...blockProps}>Error: {error}</p>;
        }

        return (
            <div {...blockProps}>
                <InspectorControls>
                    <PanelBody title="Column Visibility">
                        {Object.keys(visibleColumns).map((column) => (
                            <CheckboxControl
                                key={column}
                                label={`Show ${column}`}
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
                            {visibleColumns.id && <th>ID</th>}
                            {visibleColumns.fname && <th>First Name</th>}
                            {visibleColumns.lname && <th>Last Name</th>}
                            {visibleColumns.email && <th>Email</th>}
                            {visibleColumns.date && <th>Date</th>}
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
    save: () => null, // Save function is unnecessary for dynamic blocks.
});
