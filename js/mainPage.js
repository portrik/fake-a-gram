function initMainPage() {
    var likeForms = document.getElementsByClassName('likeForm');

    for (var i = 0; i < likeForms.length; ++i) {
        if (likeForms[i]) {
            likeForms[i].addEventListener('submit', function (e) {
                e.preventDefault();
                sendLike(e.srcElement.post_id.value);
            });
        }
    }

    var commentForms = document.getElementsByClassName('commentForm');

    for (var i = 0; i < commentForms.length; ++i) {
        if (commentForms[i]) {
            commentForms[i].addEventListener('submit', function (e) {
                e.preventDefault();
                sendComment(e.srcElement.post_id_comment.value, e.srcElement.comment.value.trim());
            })
        }
    }
}

function sendComment(post, comment) {
    if (comment === "" || comment.length > 255) {
        alert('Comment has to be shorter than 255 characters and contain at least one character.')
    }
    else if (username !== "" && post !== null) {
        var request = new XMLHttpRequest();
        var params = 'type=comment&post=' + encodeURI(post) + '&text=' + encodeURI(comment);

        request.open('POST', 'interaction.php', true);
        request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        request.send(params);

        request.onreadystatechange = function () {
            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                var newComment = document.createElement('p');
                newComment.innerText = 'From you just now: ' + comment;

                document.getElementById('commentsOf' + post).appendChild(newComment);
            }
        }
    }
}

function sendLike(post) {
    var request = new XMLHttpRequest();
    var params = 'type=like&post=' + encodeURI(post);

    request.open('POST', 'interaction.php', true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    request.send(params);

    request.onreadystatechange = function () {
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            document.getElementById('likesOf' + post).innerText = this.responseText;
        }
    }
}