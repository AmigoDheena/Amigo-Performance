/**
 * Amigo Performance Plugin - Admin JavaScript
 * Version: 3.2
 * Author: Amigo Dheena
 */

function openTab(evt, tabName) {
    var i, tabcontent, tablinks;

    // Hide all tab content
    tabcontent = document.getElementsByClassName("amigo-tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].classList.remove("active");
    }

    // Remove active class from all tab buttons
    tablinks = document.getElementsByClassName("amigo-nav-item");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active");
    }

    // Show the selected tab and mark button as active
    document.getElementById(tabName).classList.add("active");
    evt.currentTarget.classList.add("active");
}

function getSelectedValues(tableId, outputId) {
    var table = document.getElementById(tableId);
    var checkboxes = table.querySelectorAll('input[type="checkbox"]:not(.amigo-select-all)');
    var selectedValues = [];
    
    checkboxes.forEach(function(checkbox) {
        if (checkbox.checked) {
            selectedValues.push(checkbox.value);
        }
    });
    
    var output = document.getElementById(outputId);
    if (output) {
        output.value = selectedValues.join(',');
    }
}

function clearSelection(textareaId) {
    var textarea = document.getElementById(textareaId);
    if (textarea) {
        textarea.value = '';
    }
    
    // Also uncheck all checkboxes in the corresponding table
    var tableId = textareaId === 'js_handle' ? 'jsContainer' : 'cssContainer';
    var table = document.getElementById(tableId);
    if (table) {
        var checkboxes = table.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = false;
        });
    }
}

// Select All functionality
document.addEventListener('DOMContentLoaded', function() {
    // Handle select all for JavaScript table
    var selectAllJs = document.getElementById('selectAllJs');
    if (selectAllJs) {
        selectAllJs.addEventListener('change', function() {
            var jsTable = document.getElementById('jsContainer');
            if (jsTable) {
                var checkboxes = jsTable.querySelectorAll('.amigo-item-checkbox');
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = selectAllJs.checked;
                });
                getSelectedValues('jsContainer', 'js_handle');
            }
        });
    }
    
    // Handle select all for CSS table
    var selectAllCss = document.getElementById('selectAllCss');
    if (selectAllCss) {
        selectAllCss.addEventListener('change', function() {
            var cssTable = document.getElementById('cssContainer');
            if (cssTable) {
                var checkboxes = cssTable.querySelectorAll('.amigo-item-checkbox');
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = selectAllCss.checked;
                });
                getSelectedValues('cssContainer', 'css_handle');
            }
        });
    }
    
    // Update select all checkbox state when individual checkboxes change
    function updateSelectAllState(tableId, selectAllId) {
        var table = document.getElementById(tableId);
        var selectAll = document.getElementById(selectAllId);
        
        if (table && selectAll) {
            var checkboxes = table.querySelectorAll('.amigo-item-checkbox');
            var checkedCount = table.querySelectorAll('.amigo-item-checkbox:checked').length;
            
            if (checkedCount === 0) {
                selectAll.indeterminate = false;
                selectAll.checked = false;
            } else if (checkedCount === checkboxes.length) {
                selectAll.indeterminate = false;
                selectAll.checked = true;
            } else {
                selectAll.indeterminate = true;
                selectAll.checked = false;
            }
        }
    }
    
    // Add event listeners to individual checkboxes
    var jsCheckboxes = document.querySelectorAll('#jsContainer .amigo-item-checkbox');
    jsCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateSelectAllState('jsContainer', 'selectAllJs');
        });
    });
    
    var cssCheckboxes = document.querySelectorAll('#cssContainer .amigo-item-checkbox');
    cssCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateSelectAllState('cssContainer', 'selectAllCss');
        });
    });
    
    // Initialize select all states
    updateSelectAllState('jsContainer', 'selectAllJs');
    updateSelectAllState('cssContainer', 'selectAllCss');
    
    // Function to set/remove JS and CSS items
    function setRemoveJsCss(ele, handle) {
        let jc = document.querySelector(ele);
        let jh = document.querySelector(handle);
        
        // Check if both elements exist before proceeding
        if (jc && jh) {
            var chks = jc.getElementsByTagName("INPUT");
            for (var i = 0; i < chks.length; i++) {
                if (jh.value.includes(chks[i].value)) {
                    chks[i].checked = true;
                }
            }
        } else {
            console.log('Asset Manager: Element not found', ele, handle);
        }
    }
    
    // Only run these functions if we're on the asset manager page
    if (document.querySelector('#jsContainer') && document.querySelector('#js_handle')) {
        setRemoveJsCss('#jsContainer', '#js_handle');
    }
    
    if (document.querySelector('#cssContainer') && document.querySelector('#css_handle')) {
        setRemoveJsCss('#cssContainer', '#css_handle');
    }
    
    // Add click handlers for Enable and Delete buttons
    const enableButtons = document.querySelectorAll('.enable-asset-btn');
    const deleteButtons = document.querySelectorAll('.delete-asset-btn');
    
    if (enableButtons.length > 0) {
        enableButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const assetId = this.dataset.assetId;
                const assetType = this.dataset.assetType;
                if (assetId) {
                    // Submit the form or make AJAX request
                    console.log('Enable asset:', assetId, assetType);
                    this.closest('form').submit();
                }
            });
        });
    }
    
    if (deleteButtons.length > 0) {
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const assetId = this.dataset.assetId;
                if (assetId && confirm('Are you sure you want to delete this asset?')) {
                    // Submit the form or make AJAX request
                    console.log('Delete asset:', assetId);
                    this.closest('form').submit();
                }
            });
        });
    }
});