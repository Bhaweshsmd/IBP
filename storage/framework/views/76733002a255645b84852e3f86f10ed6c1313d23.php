<?php $__env->startSection('header'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>">
<?php $__env->stopSection(); ?>

<?php
    use App\Models\GlobalFunction;
    
    $admin = DB::table('admin_user')->where('user_name', session()->get('user_name'))->first();
?>

<?php $__env->startSection('content'); ?>

    <style>
        #messages{
            padding-bottom: 30%;
        }
        
        #chat {
            margin: auto;
        }
        
        #message-form {
            text-align: center;
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: #b7b5b9;
        }
        
        input {
            width: 70%;
            height: 30px;
        }
        
        button {
            width: 25%;
            height: 38px;
        }
        .rounded-circle {
            border-radius: 50% !important;
            width: 40px;
            height: 40px;
        }
        .chatImage{
            width: 300px;
            height: 200px;
            min-width: 100%;
            min-height: auto;
            cursor: pointer;
        }
        .media {
            width: auto;
        }
            
        .media-body {
            display: block !important;
        }
        
        .chat-window .chat-cont-left .chat-users-list a.media .media-body > div:last-child{
            text-align: left !important;
        }
        
        .chat-window .chat-cont-left .chat-users-list a.media:hover {
            text-decoration: none !important;
        }
        
        .chat-window .chat-cont-right .chat-body .media.sent {
            text-align: right;
        }
        
        .chat-window .chat-cont-right .chat-body .media.received .media-body .msg-box > div {
            background-color: #fae3b2 !important;
        }
        
        .chat-window .chat-scroll {
            height: 450px;
        }
        
        .chat-window .chat-cont-right {
            max-width: 100%;
        }
        
        .all-msg-ul li{
            border-radius: 10px;
            padding: 10px;
        }
        
        .active{
            background-color: #3abaf445;
            border-radius: 10px;
            padding: 10px;
        }
    </style>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <h5>Customers</h5> 
                <hr>
                <div class="all-msg mbtabs users">
                    <ul class="all-msg-ul" style="list-style: none; padding: 0px;">
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php 
                                $user_id = $user->id;
                                $friend_id = $admin->user_id;
                                
                                $messages = DB::table('chat_messages')->where(function ($query) use ($user_id, $friend_id) {
                                    $query->where('sender', $user_id)->where('receiver', $friend_id);
                                })->oRwhere(function ($query) use ($user_id, $friend_id) {
                                    $query->where('sender', $friend_id)->where('receiver', $user_id);
                                })->orderBy('id', 'desc')->first(); 
                                
                                $new_messages = DB::table('chat_messages')->where('read', '0')->where('sender', $friend_id)->where('receiver', $user_id)->orderBy('id', 'desc')->count(); 
                            ?>
                            
                            <li class="user py-2" id="<?php echo e($user->id); ?>">
                                
                                <a class="media">
                                    <div class="media-img-wrap">
                                        <div class="avatar avatar-online">
                                            <?php if($user->profile_image): ?>
                                                <?php $imgUrl = GlobalFunction::createMediaUrl($user->profile_image); ?>
                                                <img src="<?php echo e($imgUrl); ?>" alt="User" class="avatar-img rounded-circle">
                                            <?php else: ?>
                                                <img src="<?php echo e(url('/assets/img/isidel.png')); ?>" alt="User" class="avatar-img rounded-circle">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="media-body ml-2">
                                        <div class="user-name"><strong><?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?></strong></div>
                                        <div class="user-last-chat"><span id="lastMsg"><?php echo e(!empty($messages->message) ? $messages->message : ''); ?></span></div>
                                        <div class="last-chat-time block" id="lastTime" style="font-size: 11px;"><?php echo e(!empty($messages->created_at) ? date('d/m/Y h:i:s a', strtotime($messages->created_at)) : ''); ?></div>
                                        <?php if($new_messages > 0): ?><span style="float: right; position: relative; bottom: 80px;"><div class="badge badge-success badge-pill"><span class="message-count"><?php echo e($new_messages); ?></span></div></span><?php endif; ?>
                                    </div>
                                </a>
                            </i>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
		    <div class="chat-window card" id="messages">
		        <div class="text-center">
		            <img src="<?php echo e(url('/assets/img/isidel.png')); ?>" alt="User" class="avatar-img rounded-circle" style="width: 200px; height: auto;">
		            <h2 class="mt-5">Chat Support</h2>
		        </div>
            </div>
        </div>
    </div>
    
    <script src="https://js.pusher.com/7.0.3/pusher.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    
    <script>
        function scrollToBottomFunc() {
            $('.chat-scroll').animate({
            scrollTop: $('.chat-scroll')[0].scrollHeight}, 0);
        }

        var receiver_id = '';
        var my_id = "<?php echo e($admin->user_id); ?>";
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
    
            Pusher.logToConsole = true;
    
            var pusher = new Pusher('<?php echo e($settings->pusher_key); ?>', {
                cluster: '<?php echo e($settings->pusher_cluster); ?>',
                forceTLS: true
            });
    
            var channel = pusher.subscribe('my-channel');
            channel.bind('my-event', function (data) {
                if (my_id == data.sender) {
                    $('#' + data.receiver).click();
                } else if (my_id == data.receiver) {
                    if (receiver_id == data.sender) {
                        $('#' + data.sender).click();
                    } else {
                        var pending = parseInt($('#' + data.sender).find('.message-count').html());
    
                        if (pending) {
                            $('#' + data.sender).find('.message-count').html(pending + 1);
                        } else {
                            $('#' + data.sender).append('');
                        }
                    }
                }
            });
    
            $('.user').click(function () {
                $('.user').removeClass('active');
                $(this).addClass('active');
                $(this).find('.message-count').remove();
                var csrf = "<?php echo e(csrf_token()); ?>";
                var receiver_id = $(this).attr('id');
                
                $.ajax({
                    type: "post",
                    url: "getmessage/" + receiver_id,
                    data: "",
                    cache: false,
                    success: function (data) {
                        $('#messages').html(data);
                        scrollToBottomFunc();
                    }
                });
            
                $(document).on('click', '.send-message-button', function (e) { 
                    e.preventDefault();
                    var message = $('#messge').val();
                    if (receiver_id != '') {
                        $(this).val('');
                        var datastr = "receiver_id=" + receiver_id + "&message=" + message;
                        $.ajax({
                            type: "post",
                            url: "send-message",
                            data: datastr,
                            cache: false,
                            success: function (data) {
        
                            },
                        })
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('include.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/chat/index.blade.php ENDPATH**/ ?>