function initLogin() {
    document.getElementById('loginForm').addEventListener('submit', function (e) {
        console.log(e);
        removeWarnings();

        var username = document.getElementById('username').value.trim();
        var pass = document.getElementById('password').value.trim();

        if (!validationRequest('login', username, pass)) {
            var warning = createWarning('Submitted combination of username and password is not valid.');
            document.getElementById('loginForm').appendChild(warning);

            e.preventDefault();
        }
    });
}

function initRegister() {
    document.getElementById('registerForm').addEventListener('submit', function (e) {
        removeWarnings();

        warnings = [];

        if (!usernameValidation(document.getElementById('username'))) {
            warnings.append(createWarning('Username is not valid or is already registered.'));
        }

        if(!emailValidation(document.getElementById('email'))) {
            warnings.append(createWarning('Email is not valid or is already registered.'));
        }

        if(!passwordValidation(document.getElementById('password'))) {
            warnings.append(createWarning('Password is not strong enough.'));
        }

        if(document.getElementById('password') !== document.getElementById('passwordCheck')) {
            warnings.append(createWarning('Password and Password Confirmatin do not match.'));
        }

        if (warnings.length > 0) {
            var formElement = document.getElementById('registerForm');
            warnings.forEach(element => formElement.appendChild(element));
            e.preventDefault();
        }
    });
}

function passwordValidation (pass) {
    var valid = true;

    if (pass === "" || pass.length < 8) {
        valid = false;
    }

    return valid;
}

function emailValidation (email) {
    var valid = false;
    var emailFormat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

    if (email !== "" || email.match(emailFormat)) {
        if (validationRequest('email', email)) {
            valid = true;
        }
    }

    return valid;
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

function createWarning (text) {
    var warning = document.createElement('p');
    warning.classList.add('warning');
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

    console.log(request.responseText);

    request.onreadystatechange = function () {
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            
        }
    }
    if (request.responseText === "true") {
        return true;
    }
    else {
        return false;
    }
}