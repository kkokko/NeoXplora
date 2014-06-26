$(window).load(function() {

    var flag = false;
    $(".nav a.menu").click(function(e) {
        e.preventDefault();
        if (flag) {
            $(".nav ul").animate({'left': '-150px', 'width': '0px'}, 'slow', function() {
                $(this).hide();
            });
            flag = false;
        } else {
            $(".nav ul").show().animate({'width': '150px', 'left': '0'}, 'slow');
            flag = true;
        }
        $(".nav ul").addClass("done");
    });

    function window_resize() {
        var w_h = $(window).height() - $('#header').height() - $('div.message').height() - $('#footer').height();
        var b_h = $('#search_box').height() + 50;
        var l_h = $('#login_box').height();
        var m_h = (w_h - b_h) / 2;
        var m_h1 = (w_h - l_h) / 2;
        //$('#search_box').css({'marginTop': m_h + 'px'});  //-- ignored by osman
        if (m_h1 > 0)
            $('#login_box').css({'marginTop': m_h1 + 'px'});

        /*if ($(window).height() < $('#header').height() + $('#content').height() + $('#footer').height())
            $('#footer').css({'position': 'relative'});
        else
            $('#footer').css({'position': 'absolute'});*/
    }

    window_resize();

    $(window).resize(function() {
        window_resize();
    });

    var con_pos = $('#content .container').position();
    if (con_pos.left > 300) {
        $(".chat_box").width(parseInt(con_pos.left) + 'px');
    }

    $('#open_chat').click(function() {
        $(this).hide();
        $(".chat_box").show().animate({'left': '0px'}, 'slow', function() {
            $('#close_chat').show();
        });

    });
    $('#close_chat').click(function() {
        $(this).hide();
        $(".chat_box").animate({'left': '-300px'}, 'slow', function() {
            $(this).hide();
            $('#open_chat').show();
        });
    });

    $('#chat_text').focus(function() {
        $(this).css('backgroundColor', '#fff');
        $('#chat_button').css('backgroundPosition', '0 60px');
        $('#chat_button').removeAttr('disabled');
    });
    $('#chat_text').blur(function() {
        if ($('#chat_text').val() == '') {
            $('#chat_button').css('backgroundPosition', '0 0');
            $('#chat_text').css('backgroundColor', '#E0E0E0');
            $('#chat_button').attr('disabled', 'true');
        }
    });

    $('#chat_button').mouseover(function() {
        if ($('#chat_text').val() != '' || $("#chat_text").is(":focus"))
            $('#chat_button').css('backgroundPosition', '0 30px');
    });
    $('#chat_button').mousemove(function() {
        if ($('#chat_text').val() != '' || $("#chat_text").is(":focus"))
            $('#chat_button').css('backgroundPosition', '0 30px');
    });

    $('#chat_button').mouseout(function() {
        if ($('#chat_text').val() != '' || $("#chat_text").is(":focus"))
            $('#chat_button').css('backgroundPosition', '0 60px');
        else {
            $('#chat_button').css('backgroundPosition', '0 0');
            $('#chat_button').attr('disabled', 'true');
        }
    });

    $('#login_btn').mouseover(function() {
        if ($('#txtEmail').val() != '' && $("#password").val() != '' && $("#password2").val() == '')
            $(this).css('backgroundPosition', '0 -60px');
    });
    $('#login_btn').mousemove(function() {
        if ($('#txtEmail').val() != '' && $("#password").val() != '' && $("#password2").val() == '')
            $(this).css('backgroundPosition', '0 -60px');
    });

    $('#login_btn').mouseout(function() {
        if ($('#txtEmail').val() != '' && $("#password").val() != '' && $("#password2").val() == '')
            $('#login_btn').css('backgroundPosition', '0 -30px');
        else
            $('#login_btn').css('backgroundPosition', '0px 0px');
    });
    $('#register_btn').mouseover(function() {
        if ($('#txtEmail').val() != '' && $("#password").val() != '' && $("#password2").val() != '')
            $(this).css('backgroundPosition', '0 -60px');
    });
    $('#register_btn').mousemove(function() {
        if ($('#txtEmail').val() != '' && $("#password").val() != '' && $("#password2").val() != '')
            $(this).css('backgroundPosition', '0 -60px');
    });

    $('#register_btn').mouseout(function() {
        if ($('#txtEmail').val() != '' && $("#password").val() != '' && $("#password2").val() != '') {
            $('#register_btn').css('backgroundPosition', '0 -30px');
        } else
            $('#register_btn').css('backgroundPosition', '0px 0px');
    });

    if ($('#txtEmail').length > 0 && $('#password').length > 0) {
        if ($('#txtEmail').val().split(" ").join("") != '' && $('#password').val().split(" ").join("") != '') {
            $('#login_btn').css('backgroundPosition', '0 -30px');
            $('#login_btn').removeAttr('disabled');
        }
    }

    $('#txtEmail, #password, #password2').keyup(function() {
        if ($('#txtEmail').val().split(" ").join("") != '' && $('#password').val().split(" ").join("") != '' && $("#password2").val().split(" ").join("") == '') {
            $('#login_btn').css('backgroundPosition', '0 -30px');
            $('#login_btn').removeAttr('disabled');
        } else {
            $('#login_btn').css('backgroundPosition', '0 0px');
            $('#login_btn').attr('disabled', 'true');
        }
        if ($('#txtEmail').val().split(" ").join("") != '' && $('#password').val().split(" ").join("") != '' && $('#password2').val().split(" ").join("") != '') {
            $('#register_btn').css('backgroundPosition', '0 -30px');
            $('#register_btn').removeAttr('disabled');
        } else {
            $('#register_btn').css('backgroundPosition', '0 0px');
            $('#register_btn').attr('disabled', 'true');
        }
    });
    $('#txtEmail, #password, #password2').change(function() {
        if ($('#txtEmail').val().split(" ").join("") != '' && $('#password').val().split(" ").join("") != '' && $("#password2").val().split(" ").join("") == '') {
            $('#login_btn').css('backgroundPosition', '0 -30px');
            $('#login_btn').removeAttr('disabled');
        } else {
            $('#login_btn').css('backgroundPosition', '0 0px');
            $('#login_btn').attr('disabled', 'true');
        }
        if ($('#txtEmail').val().split(" ").join("") != '' && $('#password').val().split(" ").join("") != '' && $('#password2').val().split(" ").join("") != '') {
            $('#register_btn').css('backgroundPosition', '0 -30px');
            $('#register_btn').removeAttr('disabled');

            $('#login_btn').css('backgroundPosition', '0 0px');
            $('#login_btn').attr('disabled', 'true');
        } else {
            $('#register_btn').css('backgroundPosition', '0 0px');
            $('#register_btn').attr('disabled', 'true');
        }
    });

    $('form .login_box input').each(function() {
        if ($(this).val().split(' ').join('') != '')
            $(this).css({'backgroundColor': '#faffad'});
    });

    $('form .login_box input').focus(function() {
        $(this).css({'backgroundColor': '#faffad'});
    });
    $('form .login_box input').blur(function() {
        if ($(this).val().split(' ').join('') == '')
            $(this).css({'backgroundColor': '#ffffff'});
    });
});


// faffad

var show_notify_message = function(title, message, type, position) {
    if (position !== undefined)
        toastr.options.positionClass = position;
    if (type === 'success')
        toastr.success(message, title);
    else if (type === 'error')
        toastr.error(message, title);
}