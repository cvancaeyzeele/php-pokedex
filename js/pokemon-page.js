/**
 * Created by Courtney1 on 11/7/16.
 */

function getGenValue(dropdown) {
    var value = dropdown.value;
    dropdown.setAttribute("id", value);
}

/*
 * Handles the load event of the document.
 */
function onLoad() {

}

// Add document load event listener
document.addEventListener("DOMContentLoaded", onLoad, false);