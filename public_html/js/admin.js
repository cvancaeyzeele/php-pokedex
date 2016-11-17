// shows the div containing list of users and hides posts div
function showUsers() {
    var userDiv = document.getElementById("user-list");
    var postDiv = document.getElementById("post-list");
    var banned = document.getElementById("banned-user-list");

    userDiv.style.display = "block";
    postDiv.style.display = "none";
    banned.style.display = "none";
}

// shows the div containing list of posts and hides users div
function showPosts() {
    var userDiv = document.getElementById("user-list");
    var postDiv = document.getElementById("post-list");
    var banned = document.getElementById("banned-user-list");

    userDiv.style.display = "none";
    postDiv.style.display = "block";
    banned.style.display = "none";
}

// shows the div containing list of banned and suspended users
function showBanned() {
    var userDiv = document.getElementById("user-list");
    var postDiv = document.getElementById("post-list");
    var banned = document.getElementById("banned-user-list");

    userDiv.style.display = "none";
    postDiv.style.display = "none";
    banned.style.display = "block";
}

// toggles select all of the checkboxes shown
function toggleUsers(source) {
    var checkboxes = document.getElementsByClassName('user-checkbox');
    for(var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = source.checked;
    }
}

function toggleBannedUsers(source) {
    var checkboxes = document.getElementsByClassName('banned-user-checkbox');
    for(var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = source.checked;
    }
}

function togglePosts(source) {
    var checkboxes = document.getElementsByClassName('post-checkbox');
    for(var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = source.checked;
    }
}

/*
 * Handles the load event of the document.
 */
function onLoad() {
    document.getElementById("user-button").addEventListener("click", showUsers);
    document.getElementById("post-button").addEventListener("click", showPosts);
    document.getElementById("unban-button").addEventListener("click", showBanned);
}

// Add document load event listener
document.addEventListener("DOMContentLoaded", onLoad, false);