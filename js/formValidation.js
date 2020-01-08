function initLogin() {
    document.getElementById('loginForm').addEventListener('submit', function (e) {
        e.preventDefault();

        removeWarnings();

        var username = document.getElementById('username').value.trim();
        var pass = document.getElementById('password').value.trim();

        validationRequest('login', username, pass);
    });
}

function initRegister() {
    document.getElementById('username').addEventListener('blur', usernameValidation);
    document.getElementById('email').addEventListener('blur', emailValidation);
    document.getElementById('password').addEventListener('blur', passwordValidation);
    document.getElementById('passwordCheck').addEventListener('blur', passwordValidation);

    document.getElementById('registerForm').addEventListener('submit', function (e) {
        var warnings = document.getElementsByClassName('warning');

        if (warnings !== null) {
            e.preventDefault();
        }
    });
}

function passwordValidation (pass) {
    var warning = document.getElementById('pass-warning');

    if (pass === "" || pass.length < 8) {
        if (warning === null) {
            document.getElementById('registerForm').appendChild(createWarning('Password is invalid.', 'pass-warning'));
        }
    }
    else {
        if (warning !== null) {
            warning.parentNode.removeChild(warning);
        }
    }
}

function emailValidation () {
    var email = document.getElementById('email').value;
    var warning = document.getElementById('email-warning');

    if (email !== "") {
        console.log(validationRequest('email', email));
        if (validationRequest('email', email) === 1) {
            
            if (warning !== null) {
                warning.parentNode.removeChild(warning);
            }
        }
        else {
            if (warning === null) {
                document.getElementById('registerForm').appendChild(createWarning('Email is already registered.', 'email-warning'));
            }
        }
    }
    else {
        if (warning === null) {
            document.getElementById('registerForm').appendChild(createWarning('Email is not valid.', 'email-warning'));
        }
    }
}

function usernameValidation (username) {
    var valid = true;

    if (username === '' || username.length < 6 || !validationRequest('username', username)) {
        valid = false;
    }

    return valid;
}

function removeWarnings () {
    var warnings = document.getElementsByClassName('warning');

    for (var i = 0; i < warnings.length; ++i) {
        warnings[i].parentNode.removeChild(warnings[i]);
    }
}

function createWarning (text, id = 'warning') {
    var warning = document.createElement('p');
    warning.classList.add('warning');
    warning.id = id;
    warning.innerHTML =  text;

    return warning;
}

function validationRequest (type, value, secondValue = null) {
    var request = new XMLHttpRequest();
    var params = 'type=' + encodeURI(type) + '&value=' + encodeURI(value);

    if (secondValue) {
        params += '&secondValue=' + encodeURI(secondValue);
    }

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