/**
 * Created by Courtney1 on 11/2/16.
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
function formHasErrors()
{
    var hasErrors = false;

    var username = document.getElementById("username");
    var email = document.getElementById("emailaddress");
    var password = document.getElementById("password");
    var confirmpassword = document.getElementById("confirmpassword");

    // regex for username
    var pattern = new RegExp(/[~`!#$%\^&*+=\-\[\]\\';,/{}|\\":<>\?]/); //unacceptable characters

    // regex for email
    var emailpattern = new RegExp(/^([a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4})*$/);

    // if no username is entered
    if (trim(username.value) == '') {
        // add class to show error
        username.parentNode.className += " has-error";

        // create new element
        var span = document.createElement("span");
        var node = document.createTextNode("Please enter a username.");
        span.appendChild(node);
        username.parentNode.appendChild(span);
        span.id = "helpBlock";
        span.className = "help-block";

        hasErrors = true;
    } else if (pattern.test(username.value)) { // if username contains special characters
        // add class to show error
        username.parentNode.className += " has-error";

        // create new element
        var span = document.createElement("span");
        var node = document.createTextNode("Username cannot contain special characters.");
        span.appendChild(node);
        username.parentNode.appendChild(span);
        span.id = "helpBlock";
        span.className = "help-block";

        hasErrors = true;
    } else if (trim(email.value) == '') { // if no email address is entered
        // add class to show error
        email.parentNode.className += " has-error";

        // create new element
        var span = document.createElement("span");
        var node = document.createTextNode("Please enter an email address.");
        span.appendChild(node);
        email.parentNode.appendChild(span);
        span.id = "helpBlock";
        span.className = "help-block";

        hasErrors = true;
    } else if (emailpattern.test(email.value)) { // if email address is invalid
        // add class to show error
        email.parentNode.className += " has-error";

        // create new element
        var span = document.createElement("span");
        var node = document.createTextNode("Please enter a valid email address.");
        span.appendChild(node);
        email.parentNode.appendChild(span);
        span.id = "helpBlock";
        span.className = "help-block";

        hasErrors = true;
    } else if (trim(password.value) == '' && trim(confirmpassword.value) == '') { // if no password is entered
        // add class to show error
        password.parentNode.className += " has-error";

        // create new element
        var span = document.createElement("span");
        var node = document.createTextNode("Please enter a password.");
        span.appendChild(node);
        password.parentNode.appendChild(span);
        span.id = "helpBlock";
        span.className = "help-block";

        hasErrors = true;
    } else if (password.value != confirmpassword.value) { // if passwords don't match
        // add class to show error
        password.parentNode.className += " has-error";
        confirmpassword.parentNode.className += " has-error";

        // create new element
        var span = document.createElement("span");
        var node = document.createTextNode("Passwords do not match.");
        span.appendChild(node);
        confirmpassword.parentNode.appendChild(span);
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
    document.getElementById("registrationform").addEventListener("submit", validate, false);


}

// Add document load event listener
document.addEventListener("DOMContentLoaded", onLoad, false);
