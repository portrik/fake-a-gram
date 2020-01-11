/** ID should be always 0 if only one reCaptcha is loaded. Global value is used just in case. */
var recaptchaID = '';

/**
 * Loads reCaptcha tickbox into element with recaptcha ID.
 * Sets reCaptchas theme according to client's system preference in sync with CSS.
 */
function recaptchaLoad () {
    document.getElementById('recaptcha').style.display = 'block';
    var theme = 'light';

    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        theme = 'dark';
    }

    recaptchaID = grecaptcha.render('recaptcha', {
        'sitekey' : '6LfnQ84UAAAAAPFeUpSP-OrYYh8XBHYrRcI8AvKa',
        'theme': theme,
    });
}

/**
 * Prevents Post form submition and adds listeners to title and imgur_address inputs.
 * Sets focus to the title input field.
 */
function initPost() {
    document.getElementById('submit').disabled = true;
    document.getElementById('title').addEventListener('blur', titleValidation);
    document.getElementById('imgur_address').addEventListener('blur', linkValidation);

    document.getElementById('title').focus();
}

/**
 * Validates submitted title
 * In case of invalid title, a warning message is displayed and submission is prevented.
 */
function titleValidation() {
    var title = document.getElementById('title').value.trim();
    var warning = document.getElementById('title-warning');

    if (title !== "" && title.length < 255) {
        if (warning !== null) {
            warning.parentNode.removeChild(warning);
        }
    }
    else {
        if (warning === null) {
            document.getElementById('postForm').appendChild(createWarning('Title can\'t be empty or longer than 255 characters.', 'title-warning'));
        }
    }

    warningCheck();
}

/**
 * Validates submitted link
 * Only accepts i.imgur.com links ending with formats specified in their variable.
 * In case of invalid link, a warning message is displayed and submission is prevented.
 */
function linkValidation() {
    var link = new URL(document.getElementById('imgur_address').value.trim());
    var warning = document.getElementById('link-warning');
    var formats = [
        'jpg',
        'png',
    ]

    if (link.hostname === 'i.imgur.com' && formats.includes(link.pathname.slice(link.pathname.length - 3))) {
        if (warning !== null) {
            warning.parentNode.removeChild(warning);
        }
    }
    else {
        if (warning === null) {
            document.getElementById('postForm').appendChild(createWarning('Link is not a valid imgur address.', 'link-warning'));
        }
    }

    warningCheck();
}

/**
 * Prevents Register from submitting form before submitted data are validated.
 * Adds listeners to all form fields.
 * Sets focus to username input field.
 */
function initRegister() {
    document.getElementById('username').addEventListener('blur', usernameValidation);
    document.getElementById('email').addEventListener('blur', emailValidation);
    document.getElementById('password').addEventListener('blur', passwordValidation);
    document.getElementById('passwordCheck').addEventListener('blur', passwordValidation);

    document.getElementById('submit').disabled = true;
    document.getElementById('username').focus();
}

/**
 * Checks whether warnings are present on the page.
 * If none are present, submission is enabled. Otherwise submit button is disabled.
 */
function warningCheck() {
    var warnings = document.getElementsByClassName('warning');

    if (warnings.length === 0) {
        document.getElementById('submit').disabled = false;
    }
    else {
        document.getElementById('submit').disabled = true;
    }
}

/**
 * Validates whether password is valid.
 * Also checks, if password and second password match.
 * Displays warning message if any of the conditions is not met.
 */
function passwordValidation() {
    var pass = document.getElementById('password').value;
    var check = document.getElementById('passwordCheck').value;
    var warning = document.getElementById('pass-warning');

    if (pass === "" || pass.length < 8) {
        if (warning !== null) {
            warning.innerText = 'Password is invalid. It should not be emtpy and have at least 8 characters.';
        }
        else {
            document.getElementById('registerForm').appendChild(createWarning('Password is invalid. It should not be emtpy and have at least 8 characters.', 'pass-warning'));
        }
    }
    else if (pass !== check) {
        if (warning !== null) {
            warning.innerText = 'Passwords do not match.';
        }
        else {
            document.getElementById('registerForm').appendChild(createWarning('Passwords do not match.', 'pass-warning'));
        }
    }
    else if (warning !== null) {
        warning.parentNode.removeChild(warning);
    }

    warningCheck();
}

/**
 * Uses regular expression to check for valid email format.
 * Aftewards checks with backend, if email is already registered.
 * In case of an error, a warning message is displayed.
 */
function emailValidation() {
    var email = document.getElementById('email').value.trim();
    var warning = document.getElementById('email-warning');
    var pattern = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;

    if (pattern.test(String(email).toLowerCase())) {
        registerRequest('email', email, 'Email is already registered', 'email-warning');
    }
    else {
        if (warning !== null) {
            warning.innerText = 'Email is not invalid.';
        }
        else {
            document.getElementById('registerForm').appendChild(createWarning('Email is not invalid.', 'email-warning'));
        }
    }

    warningCheck();
}

/**
 * Checks, if a valid username was submitted.
 * Afterwards checks with backend, if username is already registered.
 * In case of an error, a warning message is displayed.
 */
function usernameValidation() {
    var username = document.getElementById('username').value.trim().toLowerCase();
    var warning = document.getElementById('username-warning');

    if (username !== "" && username.length < 32) {
        registerRequest('username', username, 'Username is already registered.', 'username-warning');
    }
    else {
        if (warning !== null) {
            warning.innerText = 'Username is not valid. It should contain letters or numbers and be under 32 characters.';
        }
        else {
            document.getElementById('registerForm').appendChild(createWarning('Username is not valid. It should contain letters or numbers and be under 32 characters.', 'username-warning'));
        }
    }

    warningCheck();
}

/**
 * Sends an async AJAX request to backend.
 * In case of a negative result, a warning message is displayed.
 * Otherwise warning is not displayed or is deleted.
 * warningCheck is run at the end of the request.
 * @param  {string} type - type of request, used by backend
 * @param  {string} value - value to be evaluated by backend
 * @param  {string} warningMessage - message to be displayed in case of a negative result
 * @param  {string} warningID - ID of message element
 */
function registerRequest(type, value, warningMessage, warningID) {
    var request = new XMLHttpRequest();
    var params = 'type=' + encodeURI(type) + '&value=' + encodeURI(value) + '&recaptcha=' + encodeURI(grecaptcha.getResponse(recaptchaID));
    var warning = document.getElementById(warningID);

    request.open('POST', 'validation.php', true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.send(params);

    request.onreadystatechange = function () {
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            if (this.responseText === 'true') {
                if (warning !== null) {
                    warning.parentNode.removeChild(warning);
                }
            }
            else {
                if (warning !== null) {
                    warning.innerText = warningMessage;
                }
                else {
                    document.getElementById('registerForm').appendChild(createWarning(warningMessage, warningID));
                }
            }

            warningCheck();
        }
    }
}

/**
 * Adds event listener to loginForm and sets focus to username input.
 */
function initLogin() {
    document.getElementById('loginForm').addEventListener('submit', function (e) {
        e.preventDefault();

        var username = document.getElementById('username').value.trim();
        var pass = document.getElementById('password').value.trim();

        loginRequest(username, pass);
    });

    document.getElementById('username').focus();
}

/**
 * Sends username and password to backend via POST request to log in.
 * If logged in, user is redirected to the main page.
 * Otherwise a warning message is displayed.
 * @param  {string} username
 * @param  {string} pass
 */
function loginRequest(username, pass) {
    var request = new XMLHttpRequest();
    var params = 'type=login&value=' + encodeURI(username) + '&secondValue=' + encodeURI(pass) + '&recaptcha=' + encodeURI(grecaptcha.getResponse(recaptchaID));

    request.open('POST', 'validation.php', true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.send(params);

    request.onreadystatechange = function () {
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            console.log(this.responseText);
            if (request.responseText === 'true') {
                window.location.replace('/');
            }
            else if (request.responseText === 'false') {
                document.getElementById('loginForm').appendChild(createWarning('Username and password are not valid.'));
            }
        }
    }
}

/**
 * Creates a warning element with submitted text.
 * ID should be changed if more than one warning is present
 * @param  {string} text
 * @param  {string} id='warning'
 */
function createWarning(text, id = 'warning') {
    var warning = document.createElement('p');
    warning.classList.add('warning');
    warning.id = id;
    warning.innerHTML = text;

    return warning;
}