/* global $*/

$(".like_btn").click(function() {
    var $likes = $(this);
    $.ajax({
        type: "POST",
        url: window.location.origin + '/posts/'+$(this).attr('data-id')+'/like',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(res) {
            var nLikes = JSON.parse(res).newLikes;
            $likes.html(nLikes + ' Likes');
        },
        error: function(res) {
            if (res.status === 401) {
                window.location = "/login";
            } else {
                console.log(res);
            }
        }
    });
})