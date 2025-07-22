/**
 * Asset Manager JavaScript Functions
 * Handles toggling and deletion of assets via AJAX
 */

function toggleAssetStatus(assetId, disable, e) {
    // Get the event object
    const evt = e || window.event;
    // Show loading state on the button
    const button = evt ? evt.target : document.querySelector(`button[onclick*="toggleAssetStatus(${assetId},"]`);
    button.textContent = '...';
    button.disabled = true;
    
    // Create form data
    const formData = new FormData();
    formData.append('action', 'amigoperf_asset_admin_toggle');
    formData.append('asset_id', assetId);
    formData.append('disable', disable ? '1' : '0');
    formData.append('nonce', amigoAssetManager.nonce);
    
    // Send AJAX request
    fetch(amigoAssetManager.ajaxUrl, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update button text and status badge
            const row = button.closest('tr');
            const statusBadge = row.querySelector('.amigo-status-badge');
            
            // Check the current state to determine if we need to increment or decrement
            const wasDisabled = statusBadge.classList.contains('disabled');
            
            if (disable) {
                // We are disabling the asset
                button.textContent = 'Enable';
                // Remove the old onclick handler and add a new one
                button.removeAttribute('onclick');
                button.onclick = function(e) {
                    toggleAssetStatus(assetId, false, e);
                    return false; // Prevent default
                };
                statusBadge.textContent = 'Disabled';
                statusBadge.className = 'amigo-status-badge disabled';
                
                // Update disabled count only if it wasn't already disabled
                if (!wasDisabled) {
                    updateDisabledCount(1);
                }
            } else {
                // We are enabling the asset
                button.textContent = 'Disable';
                // Remove the old onclick handler and add a new one
                button.removeAttribute('onclick');
                button.onclick = function(e) {
                    toggleAssetStatus(assetId, true, e);
                    return false; // Prevent default
                };
                statusBadge.textContent = 'Enabled';
                statusBadge.className = 'amigo-status-badge enabled';
                
                // Update disabled count only if it was previously disabled
                if (wasDisabled) {
                    updateDisabledCount(-1);
                }
            }
            
            // Show success message
            showNotice('success', data.message || 'Asset status updated successfully.');
        } else {
            // Reset button to its opposite state since the toggle failed
            const currentText = button.textContent;
            button.textContent = disable ? 'Disable' : 'Enable';
            showNotice('error', data.message || 'Failed to update asset status.');
        }
        button.disabled = false;
    })
    .catch(error => {
        console.error('Error:', error);
        button.textContent = disable ? 'Disable' : 'Enable'; 
        button.disabled = false;
        showNotice('error', 'An error occurred while processing your request.');
    });
}

function deleteAsset(assetId) {
    if (confirm(amigoAssetManager.confirmDelete)) {
        // Show loading state
        const button = event.target;
        button.textContent = '...';
        button.disabled = true;
        
        // Create form data
        const formData = new FormData();
        formData.append('action', 'amigoperf_asset_admin_delete');
        formData.append('asset_id', assetId);
        formData.append('nonce', amigoAssetManager.nonce);
        
        // Send AJAX request
        fetch(amigoAssetManager.ajaxUrl, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the row from the table
                const row = button.closest('tr');
                row.style.backgroundColor = '#ffdbdb';
                
                // Check if this is the last row in the table (excluding the header row)
                const tableBody = row.closest('tbody');
                const remainingRows = tableBody ? tableBody.querySelectorAll('tr').length : 0;
                
                setTimeout(() => {
                    row.style.opacity = 0;
                    setTimeout(() => {
                        row.remove();
                        
                        // Update stats count
                        updateStatsCount();
                        
                        // If that was the last row, check directly if we need to show empty state
                        if (remainingRows <= 1) {
                            showEmptyState();
                        }
                    }, 400);
                }, 300);
                
                showNotice('success', data.message || 'Asset deleted successfully.');
            } else {
                button.textContent = 'Delete';
                button.disabled = false;
                showNotice('error', data.message || 'Failed to delete asset.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            button.textContent = 'Delete';
            button.disabled = false;
            showNotice('error', 'An error occurred while processing your request.');
        });
    }
}

// Helper function to show admin notices
function showNotice(type, message) {
    const noticeDiv = document.createElement('div');
    noticeDiv.className = `notice notice-${type} is-dismissible`;
    
    const paragraph = document.createElement('p');
    paragraph.textContent = message;
    noticeDiv.appendChild(paragraph);
    
    // Add dismiss button
    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'notice-dismiss';
    button.innerHTML = '<span class="screen-reader-text">Dismiss this notice.</span>';
    button.addEventListener('click', () => {
        noticeDiv.parentNode.removeChild(noticeDiv);
    });
    noticeDiv.appendChild(button);
    
    // Insert notice at the top of the content area
    const contentArea = document.querySelector('.amigo-content');
    contentArea.insertBefore(noticeDiv, contentArea.firstChild);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        if (noticeDiv.parentNode) {
            noticeDiv.parentNode.removeChild(noticeDiv);
        }
    }, 5000);
}

// Helper function to update stats count when an asset is deleted
function updateStatsCount() {
    const totalAssetsElement = document.querySelector('.amigo-stat-card:nth-child(1) .amigo-stat-number');
    const disabledAssetsElement = document.querySelector('.amigo-stat-card:nth-child(2) .amigo-stat-number');
    const uniquePagesElement = document.querySelector('.amigo-stat-card:nth-child(3) .amigo-stat-number');
    const cssAssetsElement = document.querySelector('.amigo-stat-card:nth-child(4) .amigo-stat-number');
    const jsAssetsElement = document.querySelector('.amigo-stat-card:nth-child(5) .amigo-stat-number');
    
    // Update total assets count
    if (totalAssetsElement) {
        const currentTotal = parseInt(totalAssetsElement.textContent, 10);
        const newTotal = currentTotal - 1;
        totalAssetsElement.textContent = newTotal.toString();
        
        // Check if this was the last asset
        if (newTotal === 0) {
            // Show empty state after a small delay to allow animation to finish
            setTimeout(() => {
                showEmptyState();
            }, 500);
            
            // Reset all other counters to zero as well
            if (disabledAssetsElement) disabledAssetsElement.textContent = '0';
            if (uniquePagesElement) uniquePagesElement.textContent = '0';
            if (cssAssetsElement) cssAssetsElement.textContent = '0';
            if (jsAssetsElement) jsAssetsElement.textContent = '0';
            
            return;
        }
    }
    
    // Count current disabled assets
    const disabledBadges = document.querySelectorAll('.amigo-status-badge.disabled');
    if (disabledAssetsElement) {
        disabledAssetsElement.textContent = disabledBadges.length.toString();
    }
    
    // Calculate unique pages after deletion
    if (uniquePagesElement) {
        const uniqueUrls = new Set();
        document.querySelectorAll('.amigo-page-link').forEach(link => {
            uniqueUrls.add(link.getAttribute('href'));
        });
        uniquePagesElement.textContent = uniqueUrls.size.toString();
    }
    
    // Count assets by type
    if (cssAssetsElement) {
        const cssAssets = document.querySelectorAll('.amigo-asset-type-css');
        cssAssetsElement.textContent = cssAssets.length.toString();
    }
    
    if (jsAssetsElement) {
        const jsAssets = document.querySelectorAll('.amigo-asset-type-js');
        jsAssetsElement.textContent = jsAssets.length.toString();
    }
}

// Helper function to update the disabled assets count
function updateDisabledCount(change) {
    const disabledAssetsElement = document.querySelector('.amigo-stat-card:nth-child(2) .amigo-stat-number');
    
    if (disabledAssetsElement) {
        const currentDisabled = parseInt(disabledAssetsElement.textContent, 10);
        const newValue = currentDisabled + change;
        
        // Ensure the count never goes negative
        disabledAssetsElement.textContent = Math.max(0, newValue).toString();
    }
}

// Helper function to update CSS asset count
function updateCSSCount(change) {
    const cssAssetsElement = document.querySelector('.amigo-stat-card:nth-child(4) .amigo-stat-number');
    
    if (cssAssetsElement) {
        const currentCount = parseInt(cssAssetsElement.textContent, 10);
        cssAssetsElement.textContent = (currentCount + change).toString();
    }
}

// Helper function to update JS asset count
function updateJSCount(change) {
    const jsAssetsElement = document.querySelector('.amigo-stat-card:nth-child(5) .amigo-stat-number');
    
    if (jsAssetsElement) {
        const currentCount = parseInt(jsAssetsElement.textContent, 10);
        jsAssetsElement.textContent = (currentCount + change).toString();
    }
}

// Helper function to update total asset count
function updateTotalCount(change) {
    const totalAssetsElement = document.querySelector('.amigo-stat-card:nth-child(1) .amigo-stat-number');
    
    if (totalAssetsElement) {
        const currentCount = parseInt(totalAssetsElement.textContent, 10);
        const newCount = currentCount + change;
        totalAssetsElement.textContent = newCount.toString();
        
        return newCount; // Return the new count for checking if it's zero
    }
    
    return null;
}

// Helper function to show empty state
function showEmptyState() {
    // Get the asset table card
    const assetTableCard = document.querySelector('.amigo-card');
    if (!assetTableCard) return;
    
    // Check if empty state already exists
    const existingEmptyState = document.querySelector('.amigo-empty-state');
    if (existingEmptyState) return; // Don't add it twice
    
    // Create the empty state HTML
    const emptyStateHTML = `
    <div class="amigo-empty-state">
        <div class="amigo-empty-icon">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none">
                <path d="M12 2L2 7V10C2 16 6 20.5 12 22C18 20.5 22 16 22 10V7L12 2Z" stroke="currentColor" stroke-width="2" fill="none"/>
                <path d="M8 12L11 14L16 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h3>${amigoAssetManager.i18n.noAssetsYet}</h3>
        <p>${amigoAssetManager.i18n.visitPages}</p>
        <a href="${amigoAssetManager.homeUrl}" class="amigo-btn amigo-btn-primary" target="_blank">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                <path d="M18 13V19C18 19.5523 17.5523 20 17 20H5C4.44772 20 4 19.5523 4 19V7C4 6.44772 4.44772 6 5 6H11" stroke="currentColor" stroke-width="2"/>
                <path d="M15 3H21V9" stroke="currentColor" stroke-width="2"/>
                <path d="M10 14L21 3" stroke="currentColor" stroke-width="2"/>
            </svg>
            ${amigoAssetManager.i18n.visitHomepage}
        </a>
    </div>
    
    <!-- How to Use Section -->
    <div class="amigo-card">
        <div class="amigo-card-header">
            <h3 class="amigo-card-title">${amigoAssetManager.i18n.howToUse}</h3>
            <p class="amigo-card-desc">${amigoAssetManager.i18n.stepByStep}</p>
        </div>
        
        <div class="amigo-steps">
            <div class="amigo-step">
                <div class="amigo-step-number">1</div>
                <div class="amigo-step-content">
                    <h4>${amigoAssetManager.i18n.visitAnyPage}</h4>
                    <p>${amigoAssetManager.i18n.visitAnyPageDesc}</p>
                </div>
            </div>
            
            <div class="amigo-step">
                <div class="amigo-step-number">2</div>
                <div class="amigo-step-content">
                    <h4>${amigoAssetManager.i18n.openAdminBar}</h4>
                    <p>${amigoAssetManager.i18n.openAdminBarDesc}</p>
                </div>
            </div>
            
            <div class="amigo-step">
                <div class="amigo-step-number">3</div>
                <div class="amigo-step-content">
                    <h4>${amigoAssetManager.i18n.toggleAssets}</h4>
                    <p>${amigoAssetManager.i18n.toggleAssetsDesc}</p>
                </div>
            </div>
            
            <div class="amigo-step">
                <div class="amigo-step-number">4</div>
                <div class="amigo-step-content">
                    <h4>${amigoAssetManager.i18n.testPerformance}</h4>
                    <p>${amigoAssetManager.i18n.testPerformanceDesc}</p>
                </div>
            </div>
        </div>
    </div>
    `;
    
    // Replace the asset table with the empty state
    const amigoSection = document.querySelector('.amigo-section');
    if (amigoSection) {
        // Remove the asset table card
        assetTableCard.remove();
        
        // Append the empty state HTML
        amigoSection.insertAdjacentHTML('beforeend', emptyStateHTML);
    }
}