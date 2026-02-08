/**
 * Excel Export Utility
 * Converts HTML tables to Excel format and downloads them
 */
class ExcelExporter {
    constructor() {
        this.init();
    }

    init() {
        // Add export buttons to tables with data-export attribute
        this.addExportButtons();
    }

    /**
     * Convert table to Excel format and download
     * @param {string} tableId - ID of the table to export
     * @param {string} filename - Name of the Excel file (without extension)
     */
    exportTableToExcel(tableId, filename = 'export') {
        const table = document.getElementById(tableId);
        if (!table) {
            console.error(`Table with ID "${tableId}" not found`);
            return;
        }

        // Get table data
        const rows = table.querySelectorAll('tr');
        let csvContent = '';
        
        // Process each row
        rows.forEach(row => {
            const cells = row.querySelectorAll('th, td');
            const rowData = [];
            
            cells.forEach(cell => {
                // Get cell text and clean it
                let cellText = cell.textContent || cell.innerText || '';
                cellText = cellText.trim();
                
                // Escape quotes and wrap in quotes if contains comma or quote
                if (cellText.includes(',') || cellText.includes('"') || cellText.includes('\n')) {
                    cellText = '"' + cellText.replace(/"/g, '""') + '"';
                }
                
                rowData.push(cellText);
            });
            
            csvContent += rowData.join(',') + '\n';
        });

        // Create blob and download
        this.downloadCSV(csvContent, filename);
    }

    /**
     * Export filtered data based on current filters
     * @param {string} tableId - ID of the table
     * @param {string} filename - Name of the file
     * @param {Object} filters - Current filter values
     */
    exportFilteredData(tableId, filename, filters = {}) {
        const table = document.getElementById(tableId);
        if (!table) return;

        let csvContent = '';
        
        // Add filter information as header
        if (Object.keys(filters).length > 0) {
            csvContent += 'Export Filters\n';
            Object.entries(filters).forEach(([key, value]) => {
                if (value) {
                    csvContent += `${key}: ${value}\n`;
                }
            });
            csvContent += '\n'; // Empty line after filters
        }

        // Get table headers
        const headers = table.querySelectorAll('thead th');
        const headerData = [];
        headers.forEach(header => {
            let headerText = header.textContent.trim();
            headerData.push(headerText);
        });
        csvContent += headerData.join(',') + '\n';

        // Get table body rows
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const rowData = [];
            
            cells.forEach(cell => {
                let cellText = cell.textContent.trim();
                
                // Handle special cases for status badges and other formatted content
                const statusBadge = cell.querySelector('.px-2.py-1');
                if (statusBadge) {
                    cellText = statusBadge.textContent.trim();
                }
                
                // Escape quotes and wrap in quotes if needed
                if (cellText.includes(',') || cellText.includes('"') || cellText.includes('\n')) {
                    cellText = '"' + cellText.replace(/"/g, '""') + '"';
                }
                
                rowData.push(cellText);
            });
            
            csvContent += rowData.join(',') + '\n';
        });

        // Add timestamp to filename
        const timestamp = new Date().toISOString().slice(0, 19).replace(/[:-]/g, '');
        const fullFilename = `${filename}_${timestamp}.csv`;
        
        this.downloadCSV(csvContent, fullFilename);
    }

    /**
     * Download CSV content as file
     * @param {string} content - CSV content
     * @param {string} filename - File name
     */
    downloadCSV(content, filename) {
        // Create blob with proper encoding
        const BOM = '\uFEFF'; // Byte Order Mark for UTF-8
        const blob = new Blob([BOM + content], { type: 'text/csv;charset=utf-8;' });
        
        // Create download link
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        
        link.setAttribute('href', url);
        link.setAttribute('download', filename + '.csv');
        link.style.visibility = 'hidden';
        
        // Trigger download
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Clean up
        URL.revokeObjectURL(url);
        
        // Show success message
        this.showSuccessMessage(`Exported "${filename}.csv" successfully`);
    }

    /**
     * Add export buttons to tables with data-export attribute
     */
    addExportButtons() {
        const tables = document.querySelectorAll('[data-export]');
        
        tables.forEach(table => {
            const exportOptions = table.dataset.export.split(',');
            const container = table.closest('.bg-white, .overflow-hidden');
            
            if (container) {
                const header = container.querySelector('.px-6.py-4, .flex.justify-between.items-center');
                if (header) {
                    const buttonGroup = document.createElement('div');
                    buttonGroup.className = 'flex gap-2';
                    
                    exportOptions.forEach(option => {
                        const button = this.createExportButton(table.id, option.trim());
                        buttonGroup.appendChild(button);
                    });
                    
                    header.appendChild(buttonGroup);
                }
            }
        });
    }

    /**
     * Create export button
     * @param {string} tableId - Table ID
     * @param {string} type - Export type
     */
    createExportButton(tableId, type) {
        const button = document.createElement('button');
        button.className = 'bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center gap-2';
        button.innerHTML = `
            <i class='bx bx-download text-lg'></i>
            Export to Excel
        `;
        
        button.addEventListener('click', () => {
            const filename = this.getFilenameFromTable(tableId);
            const filters = this.getCurrentFilters();
            
            if (filters && Object.keys(filters).length > 0) {
                this.exportFilteredData(tableId, filename, filters);
            } else {
                this.exportTableToExcel(tableId, filename);
            }
        });
        
        return button;
    }

    /**
     * Get filename based on table ID
     * @param {string} tableId - Table ID
     */
    getFilenameFromTable(tableId) {
        const filenames = {
            'inventoryTable': 'inventory_report',
            'inboundTable': 'inbound_logistics_report',
            'outboundTable': 'outbound_logistics_report',
            'vendorsTable': 'vendors_report',
            'assetsTable': 'assets_report',
            'purchaseOrdersTable': 'purchase_orders_report',
            'supplyRequestsTable': 'supply_requests_report'
        };
        
        return filenames[tableId] || 'data_export';
    }

    /**
     * Get current filter values from the page
     */
    getCurrentFilters() {
        const filters = {};
        
        // Get search input
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput && searchInput.value) {
            filters.search = searchInput.value;
        }
        
        // Get select filters
        const selects = document.querySelectorAll('select');
        selects.forEach(select => {
            if (select.value && select.name !== '') {
                const label = select.previousElementSibling?.textContent?.trim() || select.name;
                filters[label] = select.options[select.selectedIndex].text;
            }
        });
        
        return filters;
    }

    /**
     * Show success message
     * @param {string} message - Success message
     */
    showSuccessMessage(message) {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2';
        toast.innerHTML = `
            <i class='bx bx-check-circle text-xl'></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s ease';
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }
}

// Initialize the exporter when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.excelExporter = new ExcelExporter();
});

// Global function for manual export calls
window.exportToExcel = function(tableId, filename) {
    if (window.excelExporter) {
        window.excelExporter.exportTableToExcel(tableId, filename);
    }
};
