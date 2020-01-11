function initSettings () {
    document.getElementById('settingsForm').addEventListener('submit', function (e) {
        

        settingsValidation();
    });
}

function settingsValidation () {
    var color = document.getElementById('accentColor').value;
    var compact = document.getElementById('compact').checked;

    console.log(color);
    console.log(compact);
}