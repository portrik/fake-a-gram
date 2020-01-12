/**
 * Adds listeners to Submit and Reset forms
 */
function initSettings () {
    document.getElementById('settingsForm').addEventListener('submit', function (e) {
        e.preventDefault();

        saveSettings();
    });

    document.getElementById('resetForm').addEventListener('submit', function (e) {
        e.preventDefault();

        resetSettings();
    });

    document.getElementById('accentColor').addEventListener('change', handleColor);
    document.getElementById('textColor').addEventListener('change', handleText);
}

/**
 * Saves settings through AJAX request to backend and redirects to index on completion.
 */
function saveSettings () {
    var color = document.getElementById('accentColor').value;
    var compact = document.getElementById('compact').checked;
    var textColor = document.getElementById('textColor').checked;

    if (compact) {
        compact = 'on';
    }
    else {
        compact = null;
    }

    if (textColor) {
        textColor = 'on';
    }
    else {
        textColor = null;
    }

    var request = new XMLHttpRequest();
    var params = 'type=settings&accentColor=' + encodeURI(color) + '&compact=' + encodeURI(compact) + '&textColor=' + encodeURI(textColor);

    request.open('POST', 'interaction.php', true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.send(params);

    request.onreadystatechange = function () {
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            window.location.replace('/~dvorap74/fake-a-gram/');
        }
    }
}

/**
 * Resets settings through AJAX request to backend and redirects to index on completion.
 */
function resetSettings() {
    var request = new XMLHttpRequest();
    var params = 'type=reset';

    request.open('POST', 'interaction.php', true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.send(params);

    request.onreadystatechange = function () {
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            window.location.replace('/~dvorap74/fake-a-gram/');
        }
    }
}

/**
 * Temporarily sets background color of the nav element to the selected color.
 */
function handleColor () {
    var color = document.getElementById('accentColor').value;
    var nav = document.getElementsByTagName('nav')[0];

    nav.style.backgroundColor = color;
}

/**
 * Temporarily sets color of a elements to the selected value.
 */
function handleText () {
    var value = document.getElementById('textColor').checked;
    var elements = document.getElementsByTagName('a');
    var color = 'black';

    if (value) {
        color = 'white';
    }

    for (var i = 0; i < elements.length; ++i) {
        if (elements[i]) {
            elements[i].style.color = color;
        }
    }
}