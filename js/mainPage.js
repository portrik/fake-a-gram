var username = '';

function initMainPage () {
    username = getUsername();
    var likeForms = document.getElementsByClassName('likeForm');
    var commentForms = document.getElementsByClassName('commentForm');
    
    for (var i = 0; i < likeForms.length; ++i) {
        likeForms[i].addEventListener('submit', function (e) {
            e.preventDefault();
            sendLike(username, e.srcElement.post_id.value);
        });
    }

    for (var i = 0; i < commentForms.length; ++i) {
         commentForms[i].addEventListener('submit', function (e) {
            e.preventDefault();
            sendComment(username, e.srcElement.post_id.value, e.srcElement.comment.value.trim());
         });
    }
}

function commentValidation(username, post, comment) {
    if (comment !== "" || comment.length > 254) {
        
    }
    else if (username !== "" && post !== null) {
        var request = new XMLHttpRequest();
        var params = 'type=comment&user=' + encodeURI(username) + '&post=' + encodeURI(post) + '&text=' + encodeURI(comment);

        request.open('POST', 'interaction.php', true);
        request.setRequestHeader('Content-type', 'application/x-www-urlencoded');
        request.send(params);

        request.onreadystatechange = function () {
            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                var newComment = document.createElement('p');
                newComment.innerText = 'From ' + username + ': ' + comment;

                document.getElementById('commentsOf' + post).appendChild(newComment);
            }
        }
    }
}

function sendLike(username, post) {
    var request = new XMLHttpRequest();
    var params = 'type=like&user=' + encodeURI(username) + '&post=' + encodeURI(post);

    request.open('POST', 'interaction.php', true);
    request.setRequestHeader('Content-type', 'application/x-www-urlencoded');
    request.send(params);

    request.onreadystatechange = function () {
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            document.getElementById('likesOf' + post).innerText = this.responseText;
        }
    }
}

function getUsername() {
    var request = new XMLHttpRequest();
    var params = 'type=user';

    request.open('POST', 'interaction.php', true);
    request.setRequestHeader('Content-type', 'application/x-www-urlencoded');
    request.send(params);

    request.onreadystatechange = function() {
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            username = this.responseText;
        }
    }
}