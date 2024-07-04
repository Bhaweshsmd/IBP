<?php
    use App\Models\GlobalFunction;
?>

<style>
    .image-upload{
        width: fit-content;
        border: 1px solid #000;
        height: 45px;
        padding: 10px 12px;
    }
    
    .image-upload > input{
        display: none;
    }
    
    .image-upload img{    
        height: 50px; 
        width: auto;
        cursor: pointer;
        position: absolute;
        top: 60px;
        left: 0px;
        color: var(--white);
    }
    
    .chat-window .chat-cont-right .chat-footer .form-custom .send-blk .input-group-append .btn {
        height: 45px;
    }
    
    .chat-window .chat-cont-right .chat-footer .form-custom .send-blk .input-group-append {
        right: 0px;
        top: 0px;
    }
</style>

    <div class="chat-cont-right">
        <div class="chat-header">
            <div class="media">
                <div class="media-img-wrap">
                    <div class="avatar avatar-online">
                        <?php if(!empty($username->picture)): ?>
                            <?php $imgUrl = GlobalFunction::createMediaUrl($username->picture); ?>
                            <img src="<?php echo e($imgUrl); ?>" alt="User" id="userImage" class="avatar-img rounded-circle">
                        <?php else: ?>
                            <img src="<?php echo e(url('/assets/img/isidel.png')); ?>" alt="User" id="userImage" class="avatar-img rounded-circle">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="media-body">
                    <h5><?php echo e($username->first_name); ?> <?php echo e($username->last_name); ?></h5>
                </div>
            </div> 
        </div>
        <div class="chat-body">
            <div class="chat-scroll">
                <ul class="list-unstyled">
                    <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($message->sender == $my_id && $message->sender_type == 'user'): ?>
                            <li class="media sent">
                                <div class="media-body">
                                    <div class="msg-box">
                                        <div>
                                            <p><?php echo e($message->message); ?></p>
                                            <ul class="chat-msg-info">
                                                <li>
                                                    <div class="chat-time">
                                                        <span style="font-size: 11px;"><?php echo e(date('d/m/Y h:i:s a', strtotime($message->created_at))); ?></span>
                                                        <span class="msg-seen"><i class="fa-solid fas fa-check"></i></span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php else: ?>
                            <li class="media received">
                                <div class="media-body">
                                    <div class="msg-box"> 
                                        <div>
                                            <p><?php echo e($message->message); ?></p>
                                            <ul class="chat-msg-info">
                                                <li>
                                                    <div class="chat-time">
                                                        <span style="font-size: 11px;"><?php echo e(date('d/m/Y h:i:s a', strtotime($message->created_at))); ?></span>
                                                        <span class="msg-seen"><i class="fa-solid fas fa-check"></i></span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
        <div class="chat-footer">
            <form> 
                <div class="form-custom write-message">
                    <div class="send-blk message-area" style="display: flex;">
                        <div class="w-100">
                            <input type="text" class="send-message-text" id="messge" maxlength="250" placeholder="Type something..." style="width: 100%;"><br>
                            <small class="text-danger">Max Character Limit : 250</small>
                            <input type="hidden" name="receiver_id" id="hidden_id" value=""/>
                        </div>
                        <div class="input-group-append">
                            <button type="submit" class="btn msg-send-btn send-btn send-message-button"><i class="fas fa-location-arrow" style="font-size: 15px;"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/web/chat/message.blade.php ENDPATH**/ ?>