<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= (isset($pageTitle)) ? $pageTitle : ""; ?></title>
    <!-- HTML5 Shim and Respond.js IE10 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 10]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
    <!-- Meta -->
    <!-- <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Datta Able Bootstrap admin template made using Bootstrap 4 and it has huge amount of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
    <meta name="keywords" content="admin templates, bootstrap admin templates, bootstrap 4, dashboard, dashboard templets, sass admin templets, html admin templates, responsive, bootstrap admin templates free download,premium bootstrap admin templates, datta able, datta able bootstrap admin template" />
    <meta name="author" content="Codedthemes" /> -->

    <!-- Favicon icon -->
    <link rel="icon" href="<?= base_url(); ?>app_assets/images/favicon.ico" type="image/x-icon" />
    <!-- fontawesome icon -->
    <link rel="stylesheet" href="<?= base_url(); ?>app_assets/fonts/fontawesome/css/fontawesome-all.min.css" />
    <!-- animation css -->
    <link rel="stylesheet" href="<?= base_url(); ?>app_assets/plugins/animation/css/animate.min.css" />
    <!-- vendor css -->
    <link rel="stylesheet" href="<?= base_url(); ?>app_assets/css/style.css" />
    <!-- Flag icon -->
    <link rel="stylesheet" href="<?= base_url(); ?>app_assets/fonts/flag/css/flag-icon.min.css" />

    <!-- <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app_assets/css/pages/jquery.emojipicker.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app_assets/css/pages/jquery.emojipicker.tw.css"> -->

    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app_assets/css/pages/jquery.emojipicker.css">

    <!-- Emoji Data -->
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app_assets/css/pages/jquery.emojipicker.tw.css">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ navigation menu ] start -->
    <?php $this->load->view('crm/common/left_menu'); ?>

    <!-- [ navigation menu ] end -->

    <!-- [ Header ] start -->
    <?php $this->load->view('crm/common/top_header'); ?>
    <style>
        .pcoded-navbar.menupos-static {
            height: 125%;
        }
    </style>
    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <?php  /* ?>
                    <!-- [ breadcrumb ] start -->
                    <div class="page-header">
                        <div class="page-block">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <div class="page-header-title">
                                        <h5 class="m-b-10">Message</h5>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="./index.html"><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:">Social</a></li>
                                        <li class="breadcrumb-item"><a href="javascript:">Message</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [ breadcrumb ] end -->
                    <?php */ ?>
                    <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ Main Content ] start -->
                            <div class="row">
                                <!-- [ message ] start -->
                                <div class="col-sm-12">
                                    <div class="card msg-card mb-0">
                                        <a name="" id="" onclick="prev_msg();" class="btn btn-primary float-right col-2" href="#" role="button">Refresh Chat</a>
                                        <h4 class="mx-auto"><?=$username?></h4>
                                        <div class="card-body msg-block">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-12" style="display:none">
                                                    <div class="message-mobile">
                                                        <div class="task-right-header-status">
                                                            <!-- <span class="f-w-400" data-toggle="collapse">Friend List</span>
                                                            <i class="fas fa-times float-right m-t-10"></i> -->
                                                        </div>
                                                        <div class="taskboard-right-progress">
                                                            <!-- <div class="h-list-header">
                                                                <div class="input-group">
                                                                    <input type="text" id="msg-friends" class="form-control" placeholder="Search Friend . . ." />
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class='feather icon-search'></i></span>
                                                                    </div>
                                                                </div>
                                                            </div> -->
                                                            <div class="h-list-body">
                                                                <div class="msg-user-list scroll-div">
                                                                    <?php /*  ?>
                                                                    <div class="main-friend-list">
                                                                        <div class="media userlist-box " data-id="1" data-status="online" data-username="Josephin Doe">
                                                                            <a class="media-left" href="javascript:"><img class="media-object img-radius" src="<?= base_url(); ?>app_assets/images/user/avatar-1.jpg" alt="Generic placeholder image " />
                                                                                <div class="live-status">3</div>
                                                                            </a>
                                                                            <div class="media-body">
                                                                                <h6 class="chat-header">Josephin Doe<small class="d-block text-c-green">Typing . . </small></h6>
                                                                            </div>
                                                                        </div>
                                                                        <div class="media userlist-box active " data-id="2" data-status="online" data-username="Lary Doe">
                                                                            <a class="media-left" href="javascript:"><img class="media-object img-radius" src="<?= base_url(); ?>app_assets/images/user/avatar-2.jpg" alt="Generic placeholder image" />
                                                                                <div class="live-status">1</div>
                                                                            </a>
                                                                            <div class="media-body">
                                                                                <h6 class="chat-header">Lary Doe<small class="d-block text-c-green">online</small></h6>
                                                                            </div>
                                                                        </div>
                                                                        <div class="media userlist-box " data-id="3" data-status="online" data-username="Alice">
                                                                            <a class="media-left" href="javascript:"><img class="media-object img-radius" src="<?= base_url(); ?>app_assets/images/user/avatar-3.jpg" alt="Generic placeholder image" /></a>
                                                                            <div class="media-body">
                                                                                <h6 class="chat-header">Alice<small class="d-block text-c-green">online</small></h6>
                                                                            </div>
                                                                        </div>
                                                                        <div class="media userlist-box " data-id="4" data-status="offline" data-username="Alia">
                                                                            <a class="media-left" href="javascript:"><img class="media-object img-radius" src="<?= base_url(); ?>app_assets/images/user/avatar-1.jpg" alt="Generic placeholder image" />
                                                                                <div class="live-status">1</div>
                                                                            </a>
                                                                            <div class="media-body">
                                                                                <h6 class="chat-header">Alia<small class="d-block text-muted">10 min ago</small></h6>
                                                                            </div>
                                                                        </div>
                                                                        <div class="media userlist-box " data-id="5" data-status="offline" data-username="Suzen">
                                                                            <a class="media-left" href="javascript:"><img class="media-object img-radius" src="<?= base_url(); ?>app_assets/images/user/avatar-4.jpg" alt="Generic placeholder image" /></a>
                                                                            <div class="media-body">
                                                                                <h6 class="chat-header">Suzen<small class="d-block text-muted">15 min ago</small></h6>
                                                                            </div>
                                                                        </div>
                                                                        <div class="media userlist-box " data-id="1" data-status="online" data-username="Josephin Doe">
                                                                            <a class="media-left" href="javascript:"><img class="media-object img-radius" src="<?= base_url(); ?>app_assets/images/user/avatar-1.jpg" alt="Generic placeholder image " /></a>
                                                                            <div class="media-body">
                                                                                <h6 class="chat-header">Josephin Doe<small class="d-block text-muted">10 min ago</small></h6>
                                                                            </div>
                                                                        </div>
                                                                        <div class="media userlist-box " data-id="2" data-status="online" data-username="Lary Doe">
                                                                            <a class="media-left" href="javascript:"><img class="media-object img-radius" src="<?= base_url(); ?>app_assets/images/user/avatar-2.jpg" alt="Generic placeholder image" /></a>
                                                                            <div class="media-body">
                                                                                <h6 class="chat-header">Lary Doe<small class="d-block text-c-green">online</small></h6>
                                                                            </div>
                                                                        </div>
                                                                        <div class="media userlist-box " data-id="3" data-status="online" data-username="Alice">
                                                                            <a class="media-left" href="javascript:"><img class="media-object img-radius" src="<?= base_url(); ?>app_assets/images/user/avatar-3.jpg" alt="Generic placeholder image" /></a>
                                                                            <div class="media-body">
                                                                                <h6 class="chat-header">Alice<small class="d-block text-c-green">online</small></h6>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php */ ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12">
                                                    <div class="ch-block">
                                                        <div class="h-list-body">
                                                            <div class="msg-user-chat scroll-div">
                                                                <div class="main-friend-chat">
                                                                    <div id="prev_msg"></div>

                                                                    <?php  /* ?>
                                                                    <div class="media chat-messages">
                                                                        <a class="media-left photo-table" href="javascript:"><img class="media-object img-radius img-radius m-t-5" src="<?= base_url(); ?>app_assets/images/user/avatar-2.jpg" alt="Generic placeholder image" /></a>
                                                                        <div class="media-body chat-menu-content">
                                                                            <div class="">
                                                                                <p class="chat-cont">hello Datta! Will you tell me something</p>
                                                                                <p class="chat-cont">about yourself?</p>
                                                                            </div>
                                                                            <p class="chat-time">8:20 a.m.</p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="media chat-messages">
                                                                        <div class="media-body chat-menu-reply">
                                                                            <div class="">
                                                                                <p class="chat-cont">Ohh! very nice</p>
                                                                            </div>
                                                                            <p class="chat-time">8:22 a.m.</p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="media chat-messages">
                                                                        <a class="media-left photo-table" href="javascript:"><img class="media-object img-radius img-radius m-t-5" src="<?= base_url(); ?>app_assets/images/user/avatar-2.jpg" alt="Generic placeholder image" /></a>
                                                                        <div class="media-body chat-menu-content">
                                                                            <div class="">
                                                                                <p class="chat-cont">can you help me?</p>
                                                                            </div>
                                                                            <p class="chat-time">8:20 a.m.</p>
                                                                        </div>
                                                                    </div>
                                                                    <?php  */ ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr />
                                                        <div class="msg-form">
                                                            <div class="input-group mb-0">
                                                                <input type="hidden" class="" value="<?= (isset($user_id) && $user_id != "") ? $user_id : ""; ?>" name="fk_to_id" />
                                                                <input type="text" class="form-control msg-send-chat" name="message" id="myInput" placeholder="Text . . ." />
                                                                <div class="input-group-append">
                                                                    <input type="file" name="imgupload" id="imgupload" style="display:none" />
                                                                    <button id="OpenImgUpload" class="btn btn-secondary btn-icon" type="button" data-toggle="tooltip" title="file attachment"><i class="feather icon-paperclip"></i></button>
                                                                </div>
                                                                <div class="input-group-append">
                                                                    <button class="btn btn-theme btn-icon btn-msg-send" type="button" onclick="send_msg()"><i class="feather icon-play"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- [ message ] end -->
                            </div>
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->

    <!-- Warning Section start -->
    <!-- Older IE warning message -->
    <!--[if lt IE 11]>
        <div class="ie-warning">
            <h1>Warning!!</h1>
            <p>You are using an outdated version of Internet Explorer, please upgrade
               <br />to any of the following web browsers to access this website.
            </p>
            <div class="iew-container">
                <ul class="iew-download">
                    <li>
                        <a href="http://www.google.com/chrome/">
                            <img src="<?= base_url(); ?>app_assets/images/browser/chrome.png" alt="Chrome" />
                            <div>Chrome</div>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.mozilla.org/en-US/firefox/new/">
                            <img src="<?= base_url(); ?>app_assets/images/browser/firefox.png" alt="Firefox" />
                            <div>Firefox</div>
                        </a>
                    </li>
                    <li>
                        <a href="http://www.opera.com">
                            <img src="<?= base_url(); ?>app_assets/images/browser/opera.png" alt="Opera" />
                            <div>Opera</div>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.apple.com/safari/">
                            <img src="<?= base_url(); ?>app_assets/images/browser/safari.png" alt="Safari" />
                            <div>Safari</div>
                        </a>
                    </li>
                    <li>
                        <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                            <img src="<?= base_url(); ?>app_assets/images/browser/ie.png" alt="" />
                            <div>IE (11 & above)</div>
                        </a>
                    </li>
                </ul>
            </div>
            <p>Sorry for the inconvenience!</p>
        </div>
    <![endif]-->
    <!-- Warning Section Ends -->

    <!-- Required Js -->
    <script src="<?= base_url(); ?>app_assets/js/vendor-all.min.js"></script>
    <script src="<?= base_url(); ?>app_assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?= base_url(); ?>app_assets/js/pcoded.min.js"></script>
    <!-- <script src="<?= base_url(); ?>app_assets/js/menu-setting.min.js"></script> -->

    <script type="text/javascript" src="<?= base_url(); ?>app_assets/js/pages/jquery.emojipicker.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>app_assets/js/pages/jquery.emojis.js"></script>

    <script type="text/javascript">
        $(function() {
            msg_fsc();
            let page = 1;
            $(".scroll-div").scroll(function() {
                var div = $(this);
                if (div.scrollTop() == 0) {
                    // alert("Reached the top!");
                    page += 1;
                    prev_msg(page, scroll = "yes");
                }
            });
        });

        $("#msg-friends").on("keyup", function() {
            var g = $(this).val().toLowerCase();
            $(".msg-user-list .userlist-box .media-body .chat-header").each(function() {
                var s = $(this).text().toLowerCase();
                $(this).closest('.userlist-box')[s.indexOf(g) !== -1 ? 'show' : 'hide']();
            });
        });

        $('#OpenImgUpload').click(function() {
            $('#imgupload').trigger('click');
        });

        $('input[type=file]').change(function () {
            send_msg();
        });

        $('.msg-send-chat').on('keyup', function(e) {
            msg_cfc(e);
        });

        $('.btn-msg-send').on('click', function(e) {
            msg_fc(e);
        });

        function msg_cfc(e) {
            if (e.which == 13) {
                msg_fc(e);
            }
        };

        function msg_fc(e) {
            /* $('.msg-block .main-friend-chat').append('' +
                '<div class="media chat-messages">' +
                '<div class="media-body chat-menu-reply">' +
                '<div class="">' +
                '<p class="chat-cont">' + $('.msg-send-chat').val() + '</p>' +
                '</div>' +
                '<p class="chat-time">now</p>' +
                '</div>' +
                '</div>' +
                ''); */
            msg_frc($('.msg-send-chat').val());
            msg_fsc();
            $('.msg-send-chat').val(null);
        };

        function msg_frc(wrmsg) {
            /* setTimeout(function() {
                $('.msg-block .main-friend-chat').append('' +
                    '<div class="media chat-messages typing">' +
                    '<a class="media-left photo-table" href="javascript:"><img class="media-object img-radius img-radius m-t-5" src="<?= base_url(); ?>app_assets/images/user/avatar-2.jpg" alt="Generic placeholder image"></a>' +
                    '<div class="media-body chat-menu-content">' +
                    '<div class="rem-msg">' +
                    '<p class="chat-cont">Typing . . .</p>' +
                    '</div>' +
                    '<p class="chat-time">now</p>' +
                    '</div>' +
                    '</div>' +
                    '');
                msg_fsc();
            }, 1500);
            setTimeout(function() {
                document.getElementsByClassName("rem-msg")[0].innerHTML = "<p class='chat-cont'>hello superior personality you write '" + wrmsg + " '</p>";
                $('.rem-msg').removeClass("rem-msg");
                $('.typing').removeClass("typing");
                msg_fsc();
            }, 3000); */
        };

        function msg_fsc() {
            // var tmph = $('.header-chat .main-friend-chat');
            /* $('.msg-user-chat.scroll-div').scrollTop(tmph.outerHeight()); */

            /* $('.msg-user-chat.scroll-div').animate({
                scrollTop: $('.header-chat .main-friend-chat').prop("scrollHeight")
            }, 500); */
            $(".msg-user-chat.scroll-div").scrollTop($(".msg-user-chat.scroll-div")[0].scrollHeight);
        }
        msg_fsc();

        var ps = new PerfectScrollbar('.msg-user-list.scroll-div', {
            wheelSpeed: .5,
            swipeEasing: 0,
            suppressScrollX: !0,
            wheelPropagation: 1,
            minScrollbarLength: 40,
        });

        var ps = new PerfectScrollbar('.msg-user-chat.scroll-div', {
            wheelSpeed: .5,
            swipeEasing: 0,
            suppressScrollX: !0,
            wheelPropagation: 1,
            minScrollbarLength: 40,
        });

        $(".task-right-header-status").on('click', function() {
            $(".taskboard-right-progress").slideToggle();
        });

        $(".message-mobile .media").on('click', function() {
            var vw = $(window)[0].innerWidth;
            if (vw < 992) {
                $(".taskboard-right-progress").slideUp();
                $(".msg-block").addClass('dis-chat');
            }
        });

        function prev_msg(page = 1, scroll = "no") {
            var response;
            $.ajax({
                type: "GET",
                url: "<?= base_url('rudra_chat/get_chats?user=' . $user_id . '&page='); ?>" + page,
                async: false,
                success: function(text) {
                    response = text;
                }
            });
            if (scroll == "yes")
                $('#prev_msg').prepend('<div>' + response + '</div>');
            else
                $('#prev_msg').html('<div>' + response + '</div>');
        }
        prev_msg();

        function send_msg() {
            var data = {
                fk_to_id: $('input[name="fk_to_id"]').val(),
                message: $('input[name="message"]').val(),
            };
            var formData = new FormData();
            formData.append("post", JSON.stringify(data));

            var totalFiles = document.getElementById('imgupload').files.length;
            for (var i = 0; i < totalFiles; i++) {
                var file = document.getElementById('imgupload').files[i];
                formData.append("files[]", file);
            }

            if ($('input[name="message"]').val() != "" || totalFiles>0) {

                $.ajax({
                    type: "POST",
                    url: "<?= base_url('rudra_chat/post_actions/insert_data'); ?>",
                    data: formData,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status == true) {
                            $('#prev_msg').html('');
                            prev_msg();
                        }
                    }
                });
            } else {
                alert('Message looks empty.');
            }
        }

        var input = document.getElementById("myInput");
        input.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                send_msg();
            }
        });

        // setInterval(function() {
        //     // new_msg();
        // }, 1000);

        // function new_msg(){




        // }
    </script>
    <script>
        $(document).ready(function() {
            $('input[name="message"]').emojiPicker();
        });
    </script>
</body>

</html>