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
            var data = JSON.parse(res);
            var nLikes = data.newLikes;
            var status = data.status;
            if (status === 'liked') {
                $likes.html(nLikes + ' <img src="http://i.imgur.com/pSghtg6.png" class="heart_icon"/>');
            } else {
                $likes.html(nLikes + ' <img src="http://i.imgur.com/5098TmX.png" class="heart_icon"/>');
            }
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