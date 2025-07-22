/* 
* Amigo Performance - Iframe Lazy Loader
* Author: Amigo Dheena
* Version: 3.0
*/
document.addEventListener("DOMContentLoaded", function() {
    // Get all lazy iframes
    let amframe = document.querySelectorAll(".amigolazy");
    console.log("AmigoLazy: Found " + amframe.length + " iframes to lazy load");
    
    // Process each iframe
    for(let i = 0; i < amframe.length; i++){
        let amsrc = amframe[i];
        let amdata = amsrc.getAttribute("data-src");
        let datanew = amsrc.getAttribute("lazy") || "1500";
        
        // Skip if no data-src is found
        if(!amdata) {
            console.warn("AmigoLazy: Missing data-src attribute on iframe", amsrc);
            continue;
        }
        
        // Convert delay to number
        datanew = parseInt(datanew);
        
        // Create loading indicator (optional)
        let loading = document.createElement("div");
        loading.className = "amigolazy-loading";
        loading.innerHTML = "Loading iframe...";
        loading.style.cssText = "background:#f5f5f5;text-align:center;padding:20px;border:1px solid #ddd;color:#666;";
        
        // Insert loading indicator before iframe
        if(amsrc.parentNode) {
            amsrc.parentNode.insertBefore(loading, amsrc);
        }
        
        console.log("AmigoLazy: Will load iframe in " + datanew + "ms: " + amdata);
        
        // Set timeout to load iframe after delay
        setTimeout(function(){
            // Set the src attribute to load the iframe
            amsrc.setAttribute("src", amdata);
            
            // Remove loading indicator when iframe loads
            amsrc.addEventListener("load", function() {
                if(loading && loading.parentNode) {
                    loading.parentNode.removeChild(loading);
                }
                console.log("AmigoLazy: Loaded iframe: " + amdata);
            });
        }, datanew);
    }
});
