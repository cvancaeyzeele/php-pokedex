// shows the div containing list of posts by the user and hides comments div
function showPosts() {
    var postDiv = document.getElementById("user-posts");
    var commentDiv = document.getElementById("user-comments");

    postDiv.style.display = "block";
    commentDiv.style.display = "none";
}

// shows the div containing list of comments by the user and hides post div
function showComments() {
    var postDiv = document.getElementById("user-posts");
    var commentDiv = document.getElementById("user-comments");

    postDiv.style.display = "none";
    commentDiv.style.display = "block";
}

/*
 * Handles the load event of the document.
 */
function onLoad() {
    document.getElementById("post-button").addEventListener("click", showPosts);
    document.getElementById("comment-button").addEventListener("click", showComments);
}

// Add document load event listener
document.addEventListener("DOMContentLoaded", onLoad, false);