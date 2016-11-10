/**
 * Created by Courtney1 on 11/4/16.
 */

/*
 * Removes white space from a string value.
 *
 * return  A string with leading and trailing white-space removed.
 */
function trim(str)
{
    // Uses a regex to remove spaces from a string.
    return str.replace(/^\s+|\s+$/g,"");
}

/*
 * Hides all of the error elements.
 */
function hideErrors()
{
    // get all error messages by class name and put in an array
    var errorFields = document.getElementsByClassName("help-block");

    // loop through all error messages and set display style of each to "none"
    for (var i = 0; i < errorFields.length; i++) {
        errorFields[i].parentNode.removeChild(errorFields[i]);
    }
}

/*
 * Handles the submit event of the survey form
 *
 * param e  A reference to the event object
 * return   True if no validation errors; False if the form has
 *          validation errors
 */
function validate(e) {
    // Hide all errors on page
    hideErrors();

    // Check if form has any errors
    if (formHasErrors()) {
        // Prevent form submission if errors are found
        e.preventDefault();

        // Prevents form from submitting by returning false
        return false;
    }

    // Allow form submission if no errors are found
    return true;
}

/*
 * Checks if a text field has input
 *
 * param textFieldElement		A text field input element object
 * return 	true if the field has input, false otherwise
 */
function formFieldHasInput(textFieldElement) {
    // Check if a text field is empty
    if (textFieldElement.value == null || trim(textFieldElement.value) == "") {
        return false;
    }

    return true;
}

/*
 * Does all the error checking for the form.
 *
 * returns   True if an error was found; False if no errors were found
 */
function formHasErrors() {
    var hasErrors = false;

    var title = document.getElementById("username");
    var content = document.getElementById("emailaddress");

    // if no username is entered
    if (!formFieldHasInput(title) && formFieldHasInput(content)) {
        // add class to show error
        title.parentNode.className += " has-error";

        // create new element
        var span = document.createElement("span");
        var node = document.createTextNode("Please enter a title.");
        span.appendChild(node);
        title.parentNode.appendChild(span);
        span.id = "helpBlock";
        span.className = "help-block";

        hasErrors = true;
    } else if (!formFieldHasInput(content)) { // if username contains special characters
        // add class to show error
        content.parentNode.className += " has-error";

        // create new element
        var span = document.createElement("span");
        var node = document.createTextNode("Post must have content.");
        span.appendChild(node);
        content.parentNode.appendChild(span);
        span.id = "helpBlock";
        span.className = "help-block";

        hasErrors = true;
    }


    return hasErrors;
}

/*
 * Handles the load event of the document.
 */
function onLoad() {
    document.getElementById("newpost").addEventListener("submit", validate, false);
}

// Add document load event listener
document.addEventListener("DOMContentLoaded", onLoad, false);