<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <title>Trabajo registrado correctamente</title>
    <style>
        body, table, td, p, h1 { font-family: Arial, Helvetica, sans-serif; }
        table { border-collapse: collapse; }
        img { border: 0; display: block; height: auto; outline: none; text-decoration: none; }
        @media only screen and (max-width: 620px) {
            .email-shell { width: 100% !important; }
            .email-content { padding: 24px 18px !important; }
            .email-title { font-size: 22px !important; line-height: 28px !important; }
        }
    </style>
</head>
<body style="margin:0; padding:0; background-color:#f2f4f7; color:#2d3035; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;">
    <div style="display:none; max-height:0; overflow:hidden; opacity:0; color:transparent;">Tu trabajo fue registrado correctamente y será revisado.</div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%; background-color:#f2f4f7;">
        <tr>
            <td align="center" style="padding:28px 12px;">
                <!--[if mso]><table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0"><tr><td><![endif]-->
                <table role="presentation" class="email-shell" width="600" cellpadding="0" cellspacing="0" border="0" style="width:100%; max-width:600px; background-color:#ffffff; border:1px solid #e3e6ea; border-radius:12px;">
                    <tr>
                        <td style="height:5px; line-height:5px; font-size:1px; background-color:#882c31; border-radius:12px 12px 0 0;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="email-content" style="padding:32px 38px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center" style="padding:0 0 20px;">
                                        <img src="{{ $message->embed(public_path('assets/img/logo2.png')) }}" width="150" alt="XIV Congreso Bolivariano FILACP 2027" style="width:150px; max-width:100%; height:auto; margin:0 auto;">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding:0 0 14px;">
                                        <div style="width:48px; height:48px; line-height:48px; border-radius:50%; background-color:#eef8f1; color:#247a42; font-size:26px; text-align:center;">✓</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding:0 0 18px;">
                                        <h1 class="email-title" style="margin:0; color:#882c31; font-size:25px; line-height:32px; font-weight:700; text-align:center;">Trabajo registrado correctamente</h1>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0 0 22px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td height="1" style="height:1px; line-height:1px; font-size:1px; background-color:#e5e7eb;">&nbsp;</td></tr></table>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 14px; font-size:15px; line-height:23px;">Hola <strong>{{ $work->first_name }} {{ $work->last_name }}</strong>,</p>
                            <p style="margin:0 0 14px; font-size:15px; line-height:23px;">Hemos registrado correctamente tu trabajo <strong>“{{ $work->title }}”</strong>.</p>
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 16px;">
                                <tr>
                                    <td style="padding:8px 12px; border-radius:6px; background-color:#f4e8e9; color:#882c31; font-size:14px; line-height:20px; font-weight:bold;">TRABAJO #{{ $work->id }} Registrado</td>
                                </tr>
                            </table>
                            <p style="margin:0; font-size:15px; line-height:23px;">El trabajo será revisado y te confirmaremos por correo electrónico si fue <strong>aprobado o rechazado</strong>.</p>

                            <p style="margin:24px 0 0; padding-top:18px; border-top:1px solid #e5e7eb; color:#707782; font-size:13px; line-height:20px;">Atentamente,<br><strong>Comité Organizador</strong><br>XIV Congreso Bolivariano FILACP 2027</p>
                        </td>
                    </tr>
                </table>
                <!--[if mso]></td></tr></table><![endif]-->
            </td>
        </tr>
    </table>
</body>
</html>
