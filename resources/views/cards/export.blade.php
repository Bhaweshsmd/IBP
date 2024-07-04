<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <title>Cards</title>

    <style>
        table,
        td,
        div,
        h1,
        p,
        h3 {
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body style="margin:0;padding:0;" onload="window.print();">
    <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
        <tr>
            <td align="center" style="padding:0;">
                <table role="presentation" style="width:800px;border-collapse:collapse;border:2px solid #90D6D6;border-spacing:0;text-align:left;">
                    <tr>
                        <td style="padding:36px 30px 42px 30px;">
                            <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                <tr>
                                    <td style="padding:0;">
                                        <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                            <tr style="border-bottom:2px solid #90D6D6">
                                                <td style="width:150px;padding:0;vertical-align:top;">
                                                    <p style="font-size:16px;line-height:24px; margin-top: 0px;"><img src="{{ asset('assets/img/isidel.png') }}" alt="" width="150" style="height:auto;display:block;" /></p>

                                                </td>
                                                <td style="width:10px;padding:0;font-size:0;line-height:0;">&nbsp;</td>
                                                <td style="width:350px;padding:0;vertical-align:top;">
                                                    <h3 style="margin:0 0 6px 0;font-size:20px;line-height:24px;">Isidel Beach Park</h3>
                                                    <p style="margin:0;font-size:16px;line-height:24px;">Bonaire Overheidsgebouwen N. V., Kaya Grandi 2, Bonaire, CN</p>
                                                    <p style="margin:0;font-size:16px;line-height:24px;">Phone: +599-98754322</p>
                                                    <p style="margin:0 0 8px 0;font-size:16px;line-height:24px;">Email: info@isidelbeachpark.com</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                <tr>
                                    <td style="padding:0;">
                                        <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                            <tr>
                                                <td style="padding:0;">
                                                    <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                                        <tr style="border: 1px solid #000;">
                                                            <td style="text-align: center; padding: 15px; font-weight: bold;">S.No.</td>
                                                            <td style="text-align: center; padding: 15px; font-weight: bold;">Card Number</td>
                                                            <td style="text-align: center; padding: 15px; font-weight: bold;">Bar Code</td>
                                                        </tr>
                                                        @foreach($cards as $k=>$card)
                                                            <tr style="border: 1px solid #000;">
                                                                <td style="text-align: center; padding: 10px;">{{++$k}}</td>
                                                                <td style="text-align: center; padding: 10px;"><a href="data:image/png;base64,{{DNS1D::getBarcodePNG($card->card_number, 'I25')}}" download="{{chunk_split($card->card_number, 4, ' ')}}.png">{{chunk_split($card->card_number, 4, ' ')}}</a></td>
                                                                <td style="text-align: center; padding: 10px;">
                                                                    <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($card->card_number, 'I25')}}" alt="barcode" />
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 0 18px;">
                            <p style="text-align:center;font-size:18px;line-height:24px;">
                                * This is currently available cards list.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>