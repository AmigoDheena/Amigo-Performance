/**
 * Asset Manager JavaScript Functions
 * Handles toggling and deletion of assets via AJAX
 */

// Accordion functionality
function toggleAccordion(index) {
    const content = document.getElementById('accordion-' + index);
    const header = content.previousElementSibling;
    const arrow = header.querySelector('.amigo-accordion-arrow svg');
    
    if (content.style.maxHeight) {
        content.style.maxHeight = null;
        content.classList.remove('active');
        header.classList.remove('active');
        arrow.style.transform = 'rotate(0deg)';
    } else {
        // Close all other accordions first
        document.querySelectorAll('.amigo-accordion-content').forEach(item => {
            item.style.maxHeight = null;
            item.classList.remove('active');
            item.previousElementSibling.classList.remove('active');
            item.previousElementSibling.querySelector('.amigo-accordion-arrow svg').style.transform = 'rotate(0deg)';
        });
        
        // Open clicked accordion
        content.style.maxHeight = content.scrollHeight + "px";
        content.classList.add('active');
        header.classList.add('active');
        arrow.style.transform = 'rotate(180deg)';
    }
}

// Bulk toggle all assets in a page
function bulkToggleAssets(accordionIndex, disable) {
    const accordionContent = document.getElementById('accordion-' + accordionIndex);
    const assetCards = accordionContent.querySelectorAll('.amigo-asset-card');
    
    if (assetCards.length === 0) {
        showNotice('warning', 'No assets found in this page.');
        return;
    }
    
    // Get the button that was clicked for loading state
    const clickedButton = event.target.closest('button');
    const originalText = clickedButton.textContent.trim();
    clickedButton.disabled = true;
    clickedButton.innerHTML = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" stroke-dasharray="20" stroke-dashoffset="20"><animate attributeName="stroke-dashoffset" dur="1s" values="20;0" repeatCount="indefinite"/></circle></svg> Processing...`;
    
    // Collect all asset IDs that need to be toggled
    const assetsToToggle = [];
    assetCards.forEach(card => {
        const isCurrentlyDisabled = card.classList.contains('disabled');
        // Only toggle if the current state is different from what we want
        if (disable && !isCurrentlyDisabled) {
            // We want to disable, and it's currently enabled
            const toggleButton = card.querySelector('.amigo-btn-secondary');
            if (toggleButton) {
                const onclickAttr = toggleButton.getAttribute('onclick');
                const assetIdMatch = onclickAttr.match(/toggleAssetStatus\((\d+),/);
                if (assetIdMatch) {
                    assetsToToggle.push({
                        id: parseInt(assetIdMatch[1]),
                        card: card,
                        disable: true
                    });
                }
            }
        } else if (!disable && isCurrentlyDisabled) {
            // We want to enable, and it's currently disabled
            const toggleButton = card.querySelector('.amigo-btn-secondary');
            if (toggleButton) {
                const onclickAttr = toggleButton.getAttribute('onclick');
                const assetIdMatch = onclickAttr.match(/toggleAssetStatus\((\d+),/);
                if (assetIdMatch) {
                    assetsToToggle.push({
                        id: parseInt(assetIdMatch[1]),
                        card: card,
                        disable: false
                    });
                }
            }
        }
    });
    
    if (assetsToToggle.length === 0) {
        clickedButton.disabled = false;
        clickedButton.innerHTML = originalText;
        const action = disable ? 'disabled' : 'enabled';
        showNotice('info', `All assets are already ${action} on this page.`);
        return;
    }
    
    // Process assets in batches to avoid overwhelming the server
    let completed = 0;
    let errors = 0;
    
    const processAsset = (assetData) => {
        return new Promise((resolve) => {
            const formData = new FormData();
            formData.append('action', 'amigoperf_asset_admin_toggle');
            formData.append('asset_id', assetData.id);
            formData.append('disable', assetData.disable ? '1' : '0');
            formData.append('nonce', amigoAssetManager.nonce);
            
            fetch(amigoAssetManager.ajaxUrl, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the asset card UI
                    const card = assetData.card;
                    const statusIndicator = card.querySelector('.amigo-status-indicator');
                    const statusText = card.querySelector('.amigo-asset-status');
                    const toggleButton = card.querySelector('.amigo-btn-secondary');
                    
                    if (assetData.disable) {
                        // Disabling the asset
                        card.classList.remove('enabled');
                        card.classList.add('disabled');
                        statusIndicator.classList.remove('enabled');
                        statusIndicator.classList.add('disabled');
                        statusText.textContent = 'Disabled';
                        toggleButton.textContent = 'Enable';
                        toggleButton.setAttribute('onclick', `toggleAssetStatus(${assetData.id}, false, event)`);
                        
                        statusIndicator.innerHTML = `
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                <path d="M15 9L9 15" stroke="currentColor" stroke-width="2"/>
                                <path d="M9 9L15 15" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        `;
                    } else {
                        // Enabling the asset
                        card.classList.remove('disabled');
                        card.classList.add('enabled');
                        statusIndicator.classList.remove('disabled');
                        statusIndicator.classList.add('enabled');
                        statusText.textContent = 'Enabled';
                        toggleButton.textContent = 'Disable';
                        toggleButton.setAttribute('onclick', `toggleAssetStatus(${assetData.id}, true, event)`);
                        
                        statusIndicator.innerHTML = `
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2"/>
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        `;
                    }
                    completed++;
                } else {
                    errors++;
                }
                resolve();
            })
            .catch(() => {
                errors++;
                resolve();
            });
        });
    };
    
    // Process all assets
    Promise.all(assetsToToggle.map(processAsset)).then(() => {
        // Restore button state
        clickedButton.disabled = false;
        clickedButton.innerHTML = originalText;
        
        // Update accordion summary
        updateAccordionSummary(assetCards[0]);
        
        // Update global stats
        updateStatsCount();
        
        // Show result message
        if (errors === 0) {
            const action = disable ? 'disabled' : 'enabled';
            showNotice('success', `Successfully ${action} ${completed} assets on this page.`);
        } else {
            showNotice('warning', `Completed with ${completed} successful and ${errors} failed operations.`);
        }
    });
}

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
            // Update button text and status badge for accordion structure
            const assetCard = button.closest('.amigo-asset-card');
            const statusIndicator = assetCard.querySelector('.amigo-status-indicator');
            const statusText = assetCard.querySelector('.amigo-asset-status');
            
            // Check the current state to determine if we need to increment or decrement
            const wasDisabled = assetCard.classList.contains('disabled');
            
            if (disable) {
                // We are disabling the asset
                button.textContent = 'Enable';
                button.setAttribute('onclick', `toggleAssetStatus(${assetId}, false, event)`);
                statusText.textContent = 'Disabled';
                statusIndicator.classList.remove('enabled');
                statusIndicator.classList.add('disabled');
                assetCard.classList.remove('enabled');
                assetCard.classList.add('disabled');
                
                // Update status indicator icon
                statusIndicator.innerHTML = `
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                        <path d="M15 9L9 15" stroke="currentColor" stroke-width="2"/>
                        <path d="M9 9L15 15" stroke="currentColor" stroke-width="2"/>
                    </svg>
                `;
                
                // Update accordion header summary
                updateAccordionSummary(assetCard);
                
                // Update global stats
                if (!wasDisabled) {
                    updateDisabledCount(1);
                }
            } else {
                // We are enabling the asset
                button.textContent = 'Disable';
                button.setAttribute('onclick', `toggleAssetStatus(${assetId}, true, event)`);
                statusText.textContent = 'Enabled';
                statusIndicator.classList.remove('disabled');
                statusIndicator.classList.add('enabled');
                assetCard.classList.remove('disabled');
                assetCard.classList.add('enabled');
                
                // Update status indicator icon
                statusIndicator.innerHTML = `
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2"/>
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                    </svg>
                `;
                
                // Update accordion header summary
                updateAccordionSummary(assetCard);
                
                // Update global stats
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
                // Remove the card from the accordion
                const assetCard = button.closest('.amigo-asset-card');
                const accordionContent = assetCard.closest('.amigo-accordion-content');
                const accordionItem = accordionContent.closest('.amigo-accordion-item');
                
                assetCard.style.backgroundColor = '#ffdbdb';
                
                // Check if this is the last asset in the accordion
                const remainingCards = accordionContent.querySelectorAll('.amigo-asset-card').length;
                
                setTimeout(() => {
                    assetCard.style.opacity = 0;
                    setTimeout(() => {
                        assetCard.remove();
                        
                        // Update accordion height
                        if (accordionContent.classList.contains('active')) {
                            accordionContent.style.maxHeight = accordionContent.scrollHeight + "px";
                        }
                        
                        // If that was the last asset in this accordion, remove the whole accordion item
                        if (remainingCards <= 1) {
                            accordionItem.remove();
                        } else {
                            // Update accordion summary
                            updateAccordionHeaderAfterDelete(accordionItem);
                        }
                        
                        // Update global stats
                        updateStatsCount();
                        
                        // Check if this was the last asset overall
                        const totalAccordionItems = document.querySelectorAll('.amigo-accordion-item').length;
                        if (totalAccordionItems === 0) {
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

// Helper function to update accordion summary when asset status changes
function updateAccordionSummary(assetCard, disabledCountChange, wasDisabled) {
    const accordionItem = assetCard.closest('.amigo-accordion-item');
    const header = accordionItem.querySelector('.amigo-accordion-header');
    const disabledCountElement = header.querySelector('.amigo-disabled-count');
    
    if (disabledCountElement) {
        // Recount all disabled assets in this accordion instead of relying on incremental changes
        const accordionContent = accordionItem.querySelector('.amigo-accordion-content');
        const disabledAssets = accordionContent.querySelectorAll('.amigo-asset-card.disabled').length;
        disabledCountElement.textContent = `${disabledAssets} disabled`;
    }
}

// Helper function to update accordion header after asset deletion
function updateAccordionHeaderAfterDelete(accordionItem) {
    const accordionContent = accordionItem.querySelector('.amigo-accordion-content');
    const header = accordionItem.querySelector('.amigo-accordion-header');
    const summaryElements = header.querySelectorAll('.amigo-asset-count, .amigo-disabled-count');
    
    // Recount assets in this accordion
    const cssAssets = accordionContent.querySelectorAll('.amigo-asset-type-css').length;
    const jsAssets = accordionContent.querySelectorAll('.amigo-asset-type-js').length;
    const disabledAssets = accordionContent.querySelectorAll('.amigo-asset-card.disabled').length;
    
    // Update the summary
    summaryElements[0].textContent = `${cssAssets} CSS`;
    summaryElements[1].textContent = `${jsAssets} JS`;
    summaryElements[2].textContent = `${disabledAssets} disabled`;
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
    
    // Count current assets in accordion structure
    const allAssetCards = document.querySelectorAll('.amigo-asset-card');
    const totalAssets = allAssetCards.length;
    
    // Update total assets count
    if (totalAssetsElement) {
        totalAssetsElement.textContent = totalAssets.toString();
        
        // Check if this was the last asset
        if (totalAssets === 0) {
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
    const disabledCards = document.querySelectorAll('.amigo-asset-card.disabled');
    if (disabledAssetsElement) {
        disabledAssetsElement.textContent = disabledCards.length.toString();
    }
    
    // Calculate unique pages after deletion
    if (uniquePagesElement) {
        const uniqueAccordions = document.querySelectorAll('.amigo-accordion-item');
        uniquePagesElement.textContent = uniqueAccordions.length.toString();
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
    // Get the accordion container
    const accordionContainer = document.querySelector('.amigo-accordion-container');
    if (!accordionContainer) return;
    
    // Check if empty state already exists
    const existingEmptyState = document.querySelector('.amigo-empty-state');
    if (existingEmptyState) return; // Don't add it twice
    
    // Get the parent card
    const assetCard = accordionContainer.closest('.amigo-card');
    if (!assetCard) return;
    
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
    `;
    
    // Replace the accordion container with empty state
    accordionContainer.outerHTML = emptyStateHTML;
    
    // Add the How to Use section after the empty state
    const howToUseHTML = `
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
    
    // Insert the How to Use section after the current card
    assetCard.insertAdjacentHTML('afterend', howToUseHTML);
}