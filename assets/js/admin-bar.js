/**
 * Asset Manager Admin Bar JavaScript
 */

function amigoPerfToggleAsset(handle, type, isDequeued, ajaxUrl, nonce) {
    console.log("Asset Manager: Toggle called - Handle:", handle, "Type:", type, "Dequeued:", isDequeued);

    // Use provided values or fall back to localized script values
    var adminAjaxUrl = ajaxUrl || (typeof amigoPerf !== 'undefined' ? amigoPerf.ajaxUrl : '');
    var securityNonce = nonce || (typeof amigoPerf !== 'undefined' ? amigoPerf.nonce : '');
    var debug = typeof amigoPerf !== 'undefined' ? amigoPerf.debug : false;

    if (debug) {
        console.log("Asset Manager Debug - AJAX URL:", adminAjaxUrl);
        console.log("Asset Manager Debug - Nonce:", securityNonce ? "Provided" : "Missing");
    }

    // Update the visual status immediately
    var itemId = "wp-admin-bar-amigoperf-asset-" + type + "-" + handle;
    var listItem = document.getElementById(itemId);

    console.log("Asset Manager: Found list item:", listItem);

    if (listItem) {
        if (isDequeued) {
            listItem.classList.remove("asset-enabled");
            listItem.classList.add("asset-disabled");
        } else {
            listItem.classList.remove("asset-disabled");
            listItem.classList.add("asset-enabled");
        }
    }

    // Make AJAX request to update backend
    // Get URL without query string for consistency
    var cleanUrl = window.location.protocol + '//' + window.location.host + window.location.pathname;
    // Remove trailing slash for consistent matching with server-side
    if (cleanUrl.endsWith('/') && cleanUrl !== window.location.protocol + '//' + window.location.host + '/') {
        cleanUrl = cleanUrl.slice(0, -1);
    }

    if (debug) {
        console.log("Asset Manager: Using normalized URL:", cleanUrl);
    }

    var ajaxData = {
        action: "amigoperf_toggle_asset",
        asset_handle: handle,
        asset_type: type,
        action_type: isDequeued ? "disable" : "enable",
        isDequeued: isDequeued,
        page_url: cleanUrl,
        nonce: securityNonce
    };

    console.log("Asset Manager: Sending AJAX request with data:", ajaxData);

    // Add timestamp to prevent caching
    ajaxData.timestamp = new Date().getTime();

    jQuery.ajax({
        url: adminAjaxUrl,
        type: "POST",
        data: ajaxData,
        cache: false,
        dataType: "json",
        success: function(response) {
            console.log("Asset Manager: AJAX response received:", response);

            try {
                // Parse response if it's a string
                if (typeof response === "string") {
                    response = JSON.parse(response);
                }

                if (response && response.success) {
                    console.log("Amigo Performance: Asset status updated - " + handle);

                    // Add visual feedback
                    if (listItem) {
                        var feedbackSpan = document.createElement("span");
                        feedbackSpan.textContent = " ✓ Saved";
                        feedbackSpan.className = "save-feedback";

                        // Remove existing feedback
                        var existing = listItem.querySelector(".save-feedback");
                        if (existing) existing.remove();

                        var abItem = listItem.querySelector(".ab-item");
                        if (abItem) {
                            abItem.appendChild(feedbackSpan);

                            // Remove after 3 seconds
                            setTimeout(function() {
                                feedbackSpan.style.opacity = "0";
                                setTimeout(function() {
                                    if (feedbackSpan && feedbackSpan.parentNode) {
                                        feedbackSpan.parentNode.removeChild(feedbackSpan);
                                    }
                                }, 1000);
                            }, 2000);
                        }
                    }
                } else {
                    console.error("Failed to update asset:", response);
                    alert("Failed to update asset: " + (response && response.data ? response.data.message : "Unknown error"));

                    // Revert checkbox state on error
                    var checkbox = listItem ? listItem.querySelector("input[type=checkbox]") : null;
                    if (checkbox) {
                        checkbox.checked = !isDequeued;
                    }
                }
            } catch (e) {
                console.error("Error processing response:", e);
                alert("Error processing response: " + e.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("Network error:", error);
            console.error("Asset Manager: XHR details:", xhr.responseText);
            console.error("Asset Manager: Status:", status);
            alert("Network error occurred. Please try again.");

            // Revert visual state on error
            if (listItem) {
                if (isDequeued) {
                    listItem.classList.remove("asset-disabled");
                    listItem.classList.add("asset-enabled");
                } else {
                    listItem.classList.remove("asset-enabled");
                    listItem.classList.add("asset-disabled");
                }

                // Also revert checkbox
                var checkbox = listItem.querySelector("input[type=checkbox]");
                if (checkbox) {
                    checkbox.checked = !isDequeued;
                }
            }
        }
    });
}

// Keep the dropdown inside the viewport, including when other plugins add toolbar items.
(function () {
    function initAmigoToolbarLayout() {
        var root = document.getElementById('wp-admin-bar-amigo-performance-main');
        if (!root) return;
        var panel = root.querySelector(':scope > .ab-sub-wrapper');
        if (!panel) return;
        function positionPanel() {
            var rect = root.getBoundingClientRect();
            var viewportWidth = document.documentElement.clientWidth;
            var viewportHeight = window.innerHeight;
            var width = Math.min(360, viewportWidth - 16);
            var top = Math.max(0, rect.bottom);
            var preferredLeft = document.documentElement.dir === 'rtl' ? rect.right - width : rect.left;
            panel.style.position = 'fixed';
            panel.style.left = Math.max(8, Math.min(preferredLeft, viewportWidth - width - 8)) + 'px';
            panel.style.right = 'auto';
            panel.style.top = top + 'px';
            panel.style.maxHeight = Math.max(0, Math.min(560, viewportHeight - top - 12)) + 'px';
        }
        root.addEventListener('pointerenter', positionPanel);
        root.addEventListener('focusin', positionPanel);
        root.addEventListener('touchstart', positionPanel, { passive: true });
        window.addEventListener('resize', positionPanel);
        window.addEventListener('scroll', function () {
            if (root.matches(':hover, :focus-within') || root.classList.contains('hover')) positionPanel();
        }, { passive: true });
        positionPanel();
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAmigoToolbarLayout);
    } else {
        initAmigoToolbarLayout();
    }
})();
