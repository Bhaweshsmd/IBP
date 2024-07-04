<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Receipt</title>
</head>
<style>
    *{
        padding:0;
        margin:0;
        box-sizing: border-box;
    }
    p{
        font-size:13px;
    }
    main{
        display:flex;
        justify-content: center;
        align-items: center;
    }
    main .receipt{
    }
    .section{
        border-bottom:1px dotted #333;
        padding:6px 0;
    }
    .text-center{
        text-align: center;
    }
    .ml-3{
        margin-left:15px;
    }
    table{
        width: 100%;
        border-collapse: collapse;
    }

    .tabletitle{
    border-bottom: 1px dotted #333;
    }
    .tabletitle td{
        padding:4px 0;
    }
</style>
<body style="margin:0;padding:0;" onload="window.print();">
    <main>
        <div class="receipt">
            <div class="section">
                <p class="text-center">Isidel Beach Park</p>
                <p class="text-center">Bonaire</p>
                <p class="text-center">Phone: +599-98754322</p>
                <p class="text-center">Email: info@isidelbeachpark.com</p>
            </div>
            <div class="section">
                <div id="table">
                    <table>
                        <tr class="g-amt">
                            <td class="tableitem"><p>Booking ID:</p></td>
                            <td class="tableitem" align="center"></td>
                            <td class="tableitem" align="right"><p><?php echo e($booking->booking_id); ?></p></td>
                        </tr>
                        <tr class="g-amt">
                            <td class="tableitem"><p>Date:</p></td>
                            <td class="tableitem" align="center"></td>
                            <td class="tableitem" align="right"><p><?php echo e(!empty($booking->created_at) ? Carbon\Carbon::parse($booking->created_at)->format('d-M-Y') : 'N/A'); ?></p></td>
                        </tr>
                        <tr class="g-amt">
                            <td class="tableitem"><p>Name:</p></td>
                            <td class="tableitem" align="center"></td>
                            <td class="tableitem" align="right"><p><?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?></p></td>
                        </tr>
                        <tr class="g-amt">
                            <td class="tableitem"><p>Email:</p></td>
                            <td class="tableitem" align="center"></td>
                            <td class="tableitem" align="right"><p><?php echo e(strlen($user->email) > 20 ? substr($user->email,0,20)."..." : $user->email); ?></p></td>
                        </tr>
                        <tr class="g-amt">
                            <td class="tableitem"><p>Mobile number:</p></td>
                            <td class="tableitem" align="center"></td>
                            <td class="tableitem" align="right"><p><?php echo e($user->formated_number); ?></p></td>
                        </tr>
                    </table>
                </div>   
            </div>
            <div id="table">
                <table>
                    <tr>
                        <td style="padding:2px;"></td>
                        <td style="padding:2px;"></td>
                        <td style="padding:2px;"></td>
                    </tr>
                    <tr class="total-qty" >
                        <td class="tableitem"><p>Item Name:</p></td>
                        <td class="tableitem" align="center"></td>
                        <td class="tableitem" align="right"><p><?php echo e($service->title); ?></p></td>
                    </tr>
                    <tr class="total-qty" >
                        <td class="tableitem"><p>Price:</p></td>
                        <td class="tableitem" align="center"></td>
                        <td class="tableitem" align="right"><p>
                            <?php if($user->user_type == '1'): ?>
                                <?php echo e($settings->currency); ?><?php echo e($service->foreiner_price); ?>/hr
                            <?php else: ?>
                                <?php echo e($settings->currency); ?><?php echo e($service->price); ?>/hr
                            <?php endif; ?>
                        </p></td>
                    </tr>
                    <tr class="total-qty" >
                        <td class="tableitem"><p>Qty:</p></td>
                        <td class="tableitem" align="center"></td>
                        <td class="tableitem" align="right"><p><?php echo e($booking->quantity); ?></p></td>
                    </tr>
                    <tr class="total-qty" >
                        <td class="tableitem"><p>Hours:</p></td>
                        <td class="tableitem" align="center"></td>
                        <td class="tableitem" align="right"><p><?php echo e($booking->booking_hours); ?> hrs</p></td>
                    </tr>
                    <tr class="total-qty" >
                        <td class="tableitem"><p>Schedule date:</p></td>
                        <td class="tableitem" align="center"></td>
                        <td class="tableitem" align="right"><p><?php echo e(!empty($booking->date) ? Carbon\Carbon::parse($booking->date)->format('d-M-Y') : 'N/A'); ?></p></td>
                    </tr>
                    <tr class="total-qty" >
                        <td class="tableitem"><p>Schedule Time:</p></td>
                        <td class="tableitem" align="center"></td>
                        <td class="tableitem" align="right"><p><?php echo e(date('h:i A', strtotime($booking->time))); ?></p></td>
                    </tr>
                    <tr class="total-qty" >
                        <td class="tableitem"><p>Booking Date:</p></td>
                        <td class="tableitem" align="center"></td>
                        <td class="tableitem" align="right"><p><?php echo e(!empty($booking->created_at) ? Carbon\Carbon::parse($booking->created_at)->format('d-M-Y') : 'N/A'); ?></p></td>
                    </tr>
                    <tr>
                        <td style="padding:2px;"></td>
                        <td style="padding:2px;"></td>
                        <td style="padding:2px;"></td>
                    </tr>
                    <tr style="border-top: 1px dotted #333;">
                        <td style="padding:2px;"></td>
                        <td style="padding:2px;"></td>
                        <td style="padding:2px;"></td>
                    </tr>
                    <tr>
                        <td style="padding:2px;"></td>
                        <td style="padding:2px;"></td>
                        <td style="padding:2px;"></td>
                    </tr>
                    <tr class="tax">
                        <td class="tableitem"><p>Tax </p></td>
                        <td class="tableitem" align="center"></td>
                        <td class="tableitem" align="right"><p><?php echo e($tax->value); ?>% (Included)</p></td>
                    </tr>
                    <tr class="g-amt">
                        <td class="tableitem"><p>Total Amount:</p></td>
                        <td class="tableitem" align="center"></td>
                        <td class="tableitem" align="right"><p><?php echo e($settings->currency); ?><?php echo e(number_format($booking->payable_amount, 2, '.', ',')); ?></p></td>
                    </tr>
                    <tr class="round-off">
                        <td class="tableitem"><p>Payment method:</p></td>
                        <td class="tableitem" align="center"></td>
                        <td class="tableitem" align="right"><p><?php echo e(ucfirst($booking->payment_method)); ?></p></td>
                    </tr>
                    <tr>
                        <td style="padding:2px;"></td>
                        <td style="padding:2px;"></td>
                        <td style="padding:2px;"></td>
                    </tr>
                    <tr style="border-top: 1px dotted #333;">
                        <td style="padding:2px;"></td>
                        <td style="padding:2px;"></td>
                        <td style="padding:2px;"></td>
                    </tr>
                </table>
            </div>
            <p style="padding-bottom:8px;text-align: center;">Thank you, visit again!</p>
        </div>
    </main>
</body>
</html><?php /**PATH /home/wwwsmddeveloper/public_html/ibp/resources/views/invoice/booking.blade.php ENDPATH**/ ?>