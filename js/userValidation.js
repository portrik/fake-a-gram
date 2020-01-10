function initRegister() {
    document.getElementById('username').addEventListener('blur', usernameValidation);
    document.getElementById('email').addEventListener('blur', emailValidation);
    document.getElementById('password').addEventListener('blur', passwordValidation);
    document.getElementById('passwordCheck').addEventListener('blur', passwordValidation);

    document.getElementById('username').focus();
}

function registerCheck() {
    var warnings = document.getElementsByClassName('warning');

    if (warnings.length === 0) {
        document.getElementById('submit').disabled = false;
    }
}

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

    registerCheck();
}

function emailValidation() {
    var email = document.getElementById('email').value.trim();
    var warning = document.getElementById('email-warning');
    var pattern = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;

    if (email !== "" && email.length < 256 && pattern.test(String(email).toLowerCase())) {
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
}

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
}

function registerRequest(type, value, warningMessage, warningID) {
    var request = new XMLHttpRequest();
    var params = 'type=' + encodeURI(type) + '&value=' + encodeURI(value);
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

            registerCheck();
        }
    }
}



function initLogin() {
    document.getElementById('loginForm').addEventListener('submit', function (e) {
        e.preventDefault();

        removeWarnings();

        var username = document.getElementById('username').value.trim();
        var pass = document.getElementById('password').value.trim();

        loginRequest(username, pass);
    });

    document.getElementById('username').focus();
}

function loginRequest(value, secondValue) {
    var request = new XMLHttpRequest();
    var params = 'type=login&value=' + encodeURI(value) + '&secondValue=' + encodeURI(secondValue);

    request.open('POST', 'validation.php', true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.send(params);

    request.onreadystatechange = function () {
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            if (request.responseText === 'true') {
                window.location.replace('/');
            }
            else if (request.responseText === 'false') {
                document.getElementById('loginForm').appendChild(createWarning('Username and password are not valid.'));
            }
        }
    }
}


function removeWarnings() {
    var warnings = document.getElementsByClassName('warning');

    for (var i = 0; i < warnings.length; ++i) {
        warnings[i].parentNode.removeChild(warnings[i]);
    }
}

function createWarning(text, id = 'warning') {
    var warning = document.createElement('p');
    warning.classList.add('warning');
    warning.id = id;
    warning.innerHTML = text;

    return warning;
}