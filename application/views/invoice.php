<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Invoice</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap');

        img {
            max-width: 600px;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }

        a {
            text-decoration: none;
            border: 0;
            outline: none;
            color: #fff;
        }

        a img {
            border: none;
        }

        /* General styling */
        td,
        h1,
        h2,
        h3 {
            font-family: 'Roboto', sans-serif;
            font-weight: 400;
        }

        body {
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: none;
            width: 100%;
            height: 100%;
            color: #ffffff;
            background: #ffffff;
            font-size: 12px;
        }

        table {
            border-collapse: collapse !important;
        }

        table td,
        table th {
            padding-bottom: 7px;
            padding-top: 7px;
            text-align: left;
        }

        .force-full-width {
            width: 100% !important;
        }

        .force-width-90 {
            width: 90% !important;
        }
    </style>


    <style type="text/css" media="only screen and (max-width: 480px)">
        /* Mobile styles */
        @media only screen and (max-width: 480px) {
            table[class="w320"] {
                width: 320px !important;
            }

            td[class="mobile-block"] {
                width: 100% !important;
                display: block !important;
            }
        }
    </style>
</head>

<body class="body" style="padding:0; margin:0; display:block; background:#ffffff; -webkit-text-size-adjust:none" bgcolor="#ffffff">
    <table align="center" cellpadding="0" cellspacing="0" class="force-full-width" height="100%">
        <tr>
            <td align="center" valign="top" bgcolor="#ffffff" width="100%">
                <center>
                    <table cellpadding="0" cellspacing="0" width="328" class="w320" style="margin: 0 auto;background:#193241;box-shadow:0px 0px 10px 5px black;">
                        <tr>
                            <td>
                                <center>
                                    <table width="300">
                                        <tr>
                                            <td align="center" valign="top">
                                                <center>
                                                    <table cellspacing="0" cellpadding="0" width="100%" style="border-bottom: 2px solid #212529;">
                                                        <tr>
                                                            <td>
                                                                <table width="100%">
                                                                    <tr>
                                                                        <td>
                                                                            <h4 style="font-weight: normal;font-size: 12px;margin: 0px;color: #46FFAA;text-align: center;">Booking ID : <?php echo (isset($booking_id_definition) && $booking_id_definition != "") ? $booking_id_definition : ""; ?></h4>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td>
                                                                <table width="100%">
                                                                    <tr>
                                                                        <td>
                                                                            <h4 style="font-weight: normal;font-size: 12px;margin: 0px;color: #46FFAA;text-align: left;">Invoice date : <?php echo date('d,M Y'); ?></h4>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </center>

                                                <center>
                                                    <table cellspacing="0" cellpadding="0" width="100%" style="border-bottom: 2px solid #212529;">
                                                        <tr>
                                                            <td>
                                                                <table width="100%">
                                                                    <tr>
                                                                        <td style="text-align: left;color: #46FFAA;">
                                                                            <p>Wash date</p>
                                                                            <h4 style="font-weight: bold;font-size: 16px;margin: 0px;"><?php echo (isset($date) && $date != "") ? $date : ""; ?></h4>
                                                                        </td>

                                                                        <td style="text-align:right;color: #46FFAA;">
                                                                        <p>Wash time</p>
                                                                        <span style="font-weight: bold;font-size:16px;"><?php echo (isset($time) && $time != "") ? $time : ""; ?></span></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <table width="100%">
                                                                    <tr>
                                                                        <td style="font-size:12px;color:#BEFFFF;text-align:left;"><?php echo (isset($address) && $address != "") ? $address : "-"; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="font-size:12px;color:#BEFFFF;text-align:left;"><?php echo (isset($city) && $city != "") ? $city : "-"; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="font-size:12px;color:#BEFFFF;text-align:left;"><?php echo (isset($zipcode) && $zipcode != "") ? $zipcode : "-"; ?></td>
                                                                    </tr>
                                                                    <?php /* ?>
                                                                    <tr>
                                                                        <td style="font-size:12px;color:#BEFFFF;text-align:left;">Note: P-øst 14 - 3. dæk</td>
                                                                    </tr>
                                                                    <?php */ ?>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </center>

                                                <center>
                                                    <table cellspacing="0" cellpadding="0" width="100%" style="border-bottom: 2px solid #212529;">
                                                        <tr>
                                                            <td>
                                                                <table width="100%">
                                                                    <tr>
                                                                        <td>
                                                                            <h4 style="font-weight: bold;font-size: 16px;margin: 0px;color: #46FFAA;text-align: left;">1 Car</h4>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="font-size:12px;color:#BEFFFF;">Car Number</td>
                                                                        <td style="font-size:12px;color:#BEFFFF;text-align:left;"><?= (isset($reg_id_definition) && $reg_id_definition != "") ? $reg_id_definition : "-"; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="font-size:12px;color:#BEFFFF;">Car Name</td>
                                                                        <td style="font-size:12px;color:#BEFFFF;text-align:left;"><?= (isset($car_name) && $car_name != "") ? $car_name : "-"; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="font-size:12px;color:#BEFFFF;">Car Brand</td>
                                                                        <td style="font-size:12px;color:#BEFFFF;text-align:left;"><?= (isset($car_brand) && $car_brand != "") ? $car_brand : "-"; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="font-size:12px;color:#BEFFFF;">Car Model</td>
                                                                        <td style="font-size:12px;color:#BEFFFF;text-align:left;"><?= (isset($car_model) && $car_model != "") ? $car_model : "-"; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="font-size:12px;color:#BEFFFF;">Car Color</td>
                                                                        <td style="font-size:12px;color:#BEFFFF;text-align:left;"><?= (isset($car_color) && $car_color != "") ? $car_color : "-"; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="font-size:12px;color:#BEFFFF;">Car Description</td>
                                                                        <td style="font-size:12px;color:#BEFFFF;text-align:left;"><?= (isset($car_description) && $car_description != "") ? $car_description : "-"; ?></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </center>

                                                <center>
                                                    <table cellspacing="0" cellpadding="0" width="100%" style="border-bottom: 2px solid #212529;">
                                                        <tr>
                                                            <td style="padding: 10px 0px;">
                                                                <table width="100%">
                                                                    <tr>
                                                                        <td>
                                                                            <h4 style="font-weight: bold;font-size: 16px;margin: 0px;color: #46FFAA;text-align: left;">Total</h4>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="font-size:12px;color:#BEFFFF;">1 x <?= isset($wash_amount) ? $wash_amount : "0.00"; ?> DK</td>
                                                                        <td style="font-size:12px;color:#BEFFFF;text-align:right;"><?= isset($wash_amount) ? $wash_amount : "0.00"; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="font-size:12px;color:#BEFFFF;">Extra washes</td>
                                                                        <td style="font-size:12px;color:#BEFFFF;text-align:right;"><?= isset($extra_wash_amount) ? $extra_wash_amount : "0.00"; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="font-size:12px;color:#BEFFFF;">VAT constitutes</td>
                                                                        <td style="font-size:12px;color:#BEFFFF;text-align:right;"><?= isset($vat_amount) ? $vat_amount : "0.00"; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="font-size:12px;color:#BEFFFF;">Total</td>
                                                                        <td style="font-size:16px;color:#46FFAA;text-align:right;font-weight: bold;"><?= isset($total_pay) ? $total_pay : "0.00"; ?></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </center>
                                            </td>
                                        </tr>
                                    </table>
                                </center>
                            </td>
                        </tr>
                    </table>
                </center>
            </td>
        </tr>
        </td>
        </tr>
    </table>
</body>

</html>