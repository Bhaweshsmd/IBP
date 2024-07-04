<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Reload</title>
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
                            <td class="tableitem"><p>Order ID:</p></td>
                            <td class="tableitem" align="center"></td>
                            <td class="tableitem" align="right"><p>{{$topup->order_id}}</p></td>
                        </tr>
                        <tr class="g-amt">
                            <td class="tableitem"><p>Date:</p></td>
                            <td class="tableitem" align="center"></td>
                            <td class="tableitem" align="right"><p>{{ !empty($topup->created_at) ? Carbon\Carbon::parse($topup->created_at)->format('d-M-Y') : 'N/A'}}</p></td>
                        </tr>
                        <tr class="g-amt">
                            <td class="tableitem"><p>Name:</p></td>
                            <td class="tableitem" align="center"></td>
                            <td class="tableitem" align="right"><p>{{ $user->first_name }} {{ $user->last_name }}</p></td>
                        </tr>
                        <tr class="g-amt">
                            <td class="tableitem"><p>Email:</p></td>
                            <td class="tableitem" align="center"></td>
                            <td class="tableitem" align="right"><p>{{ strlen($user->email) > 20 ? substr($user->email,0,20)."..." : $user->email; }}</p></td>
                        </tr>
                        <tr class="g-amt">
                            <td class="tableitem"><p>Mobile number:</p></td>
                            <td class="tableitem" align="center"></td>
                            <td class="tableitem" align="right"><p>{{ $user->formated_number }}</p></td>
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
                            <td class="tableitem" align="right"><p>IBP Card Reload</p></td>
                        </tr>
                        <tr class="total-qty" >
                            <td class="tableitem"><p>Card Number:</p></td>
                            <td class="tableitem" align="center"></td>
                            <td class="tableitem" align="right"><p>{{ chunk_split($card->card_number, 4, ' ') }}</p></td>
                        </tr>
                        <tr class="total-qty" >
                            <td class="tableitem"><p>Reload Amount:</p></td>
                            <td class="tableitem" align="center"></td>
                            <td class="tableitem" align="right"><p>{{$settings->currency}}{{ number_format($topup->amount, 2, '.', ',') }}</p></td>
                        </tr>
                        <tr class="total-qty" >
                            <td class="tableitem"><p>Order Date:</p></td>
                            <td class="tableitem" align="center"></td>
                            <td class="tableitem" align="right"><p>{{ !empty($topup->created_at) ? Carbon\Carbon::parse($topup->created_at)->format('d-M-Y') : 'N/A'}}</p></td>
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
                        <tr class="g-amt">
                            <td class="tableitem"><p>Total Amount:</p></td>
                            <td class="tableitem" align="center"></td>
                            <td class="tableitem" align="right"><p>{{$settings->currency}}{{ number_format($topup->amount, 2, '.', ',') }}</p></td>
                        </tr>
                        <tr class="round-off">
                            <td class="tableitem"><p>Payment Method:</p></td>
                            <td class="tableitem" align="center"></td>
                            <td class="tableitem" align="right"><p>{{ $topup->payment_type }}</p></td>
                        </tr>
                        <tr class="round-off">
                            <td class="tableitem"><p>Updated Card Balance:</p></td>
                            <td class="tableitem" align="center"></td>
                            <td class="tableitem" align="right"><p>{{$settings->currency}}{{ number_format($card->balance, 2, '.', ',') }}</p></td>
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
</html>