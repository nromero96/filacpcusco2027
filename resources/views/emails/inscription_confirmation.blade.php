<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <title>Confirmación de inscripción</title>
    <style>
        body, table, td, p, a { font-family: Arial, Helvetica, sans-serif; }
        table { border-collapse: collapse; }
        img { border: 0; display: block; height: auto; outline: none; text-decoration: none; }
        @media only screen and (max-width:620px) {
            .email-shell { width:100% !important; }
            .email-content { padding:22px 16px !important; }
            .detail-cell { display:block !important; width:auto !important; }
            .detail-label { border-bottom:0 !important; padding-bottom:4px !important; }
            .detail-value { padding-top:4px !important; }
            .email-title { font-size:22px !important; line-height:28px !important; }
        }
    </style>
</head>
<body style="margin:0; padding:0; background:#f1f3f5; color:#20242a; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;">
    <div style="display:none; max-height:0; overflow:hidden; opacity:0; color:transparent;">Su inscripción #{{ $datainscription->id }} ha sido confirmada.</div>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%; background:#f1f3f5;">
        <tr><td align="center" style="padding:24px 12px;">
            <!--[if mso]><table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0"><tr><td><![endif]-->
            <table role="presentation" class="email-shell" width="600" cellpadding="0" cellspacing="0" border="0" style="width:100%; max-width:600px; background:#fff; border:1px solid #e2e5e9;">
                <tr><td class="email-content" style="padding:30px 34px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr><td align="center" style="padding:0 0 18px;"><img src="{{ $message->embed(public_path('assets/img/logo.png')) }}" width="150" alt="XIV Congreso Bolivariano FILACP 2027" style="width:150px; max-width:100%; height:auto; margin:0 auto;"></td></tr>
                        <tr><td align="center" style="padding:0 0 16px;"><h1 class="email-title" style="margin:0; color:#16753a; font-size:25px; line-height:32px; text-align:center;">CONFIRMACIÓN DE INSCRIPCIÓN</h1></td></tr>
                        <tr><td style="padding:0 0 22px;"><table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td height="1" style="height:1px; line-height:1px; font-size:1px; background-color:#dfe3e8;">&nbsp;</td></tr></table></td></tr>
                    </table>

                    <p style="margin:0 0 14px; font-size:15px; line-height:23px;">Estimado(a) <strong>{{ trim($userinfo->name.' '.$userinfo->lastname.' '.$userinfo->second_lastname) }}</strong>,</p>
                    <p style="margin:0 0 20px; font-size:15px; line-height:23px;">Tenemos el agrado de confirmar su inscripción al <strong>XIV CONGRESO BOLIVARIANO DE CIRUGÍA PLÁSTICA FILACP 2027</strong>. La jornada se celebrará del 4 al 7 de mayo de 2027 en Valle Sagrado, Centro de Convenciones Vidawasi, Cusco - Perú.</p>
                    <h2 style="margin:0 0 12px; font-size:19px; line-height:26px;">Detalle de su inscripción <span style="white-space:nowrap;">#{{ $datainscription->id }}</span></h2>

                    @php
                        $cell = 'padding:10px 12px; border:1px solid #dfe3e8; font-size:14px; line-height:20px;';
                        $name = trim($userinfo->name.' '.$userinfo->lastname.' '.$userinfo->second_lastname);
                    @endphp
                    <table role="table" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%; border:1px solid #dfe3e8;">
                        <tr>
                            <th align="left" width="42%" style="{{ $cell }} background:#e9ecef;">Descripción</th>
                            <th align="left" width="58%" style="{{ $cell }} background:#e9ecef;">Información</th>
                        </tr>
                        <tr><td class="detail-cell detail-label" width="42%" style="{{ $cell }} color:#343a40;">Nombre completo</td><td class="detail-cell detail-value" width="58%" style="{{ $cell }}">{{ $name }}</td></tr>
                        <tr><td class="detail-cell detail-label" width="42%" style="{{ $cell }} color:#343a40;">Categoría</td><td class="detail-cell detail-value" width="58%" style="{{ $cell }}">{{ $datainscription->category_inscription_name }}@if($datainscription->special_code)<br><span style="color:#1156cf; font-size:12px;">{{ $datainscription->special_code }}</span>@endif</td></tr>
                        <tr><td class="detail-cell detail-label" width="42%" style="{{ $cell }} color:#343a40;">Precio de categoría</td><td class="detail-cell detail-value" width="58%" style="{{ $cell }}">US$ {{ number_format($datainscription->price_category, 2) }}</td></tr>
                        @if($datainscription->accompanist_id)
                            <tr><td class="detail-cell detail-label" width="42%" style="{{ $cell }} color:#343a40;">Acompañante</td><td class="detail-cell detail-value" width="58%" style="{{ $cell }}">US$ {{ number_format($datainscription->price_accompanist, 2) }}</td></tr>
                        @endif
                        @foreach($datainscription->courses as $course)
                            <tr><td class="detail-cell detail-label" width="42%" style="{{ $cell }} color:#343a40;">Curso: {{ $course->name }}</td><td class="detail-cell detail-value" width="58%" style="{{ $cell }}">US$ {{ number_format($course->pivot->unit_price, 2) }}</td></tr>
                        @endforeach
                        @foreach($datainscription->tours as $tour)
                            <tr><td class="detail-cell detail-label" width="42%" style="{{ $cell }} color:#343a40;">Tour: {{ $tour->name }}@if($tour->pivot->has_accompanist)<br><span style="font-size:12px; color:#5f6873;">Acompañante: {{ $tour->pivot->accompanist_name }}</span>@endif</td><td class="detail-cell detail-value" width="58%" style="{{ $cell }}">US$ {{ number_format($tour->pivot->unit_price + $tour->pivot->accompanist_price, 2) }}</td></tr>
                        @endforeach
                        <tr><td class="detail-cell detail-label" width="42%" style="{{ $cell }} color:#343a40;">Método de pago</td><td class="detail-cell detail-value" width="58%" style="{{ $cell }}">{{ $datainscription->payment_method }}
                            @if($datainscription->payment_method === 'Tarjeta' && $datainscription->payment)
                                @php($cardDigits = preg_replace('/\D/', '', $datainscription->payment->card_number ?? ''))
                                @if($cardDigits !== '')<br><span style="font-size:12px; color:#5f6873;">{{ $datainscription->payment->card_brand }} terminada en {{ substr($cardDigits, -4) }}</span>@endif
                            @endif
                        </td></tr>
                        <tr><td class="detail-cell detail-label" width="42%" style="{{ $cell }} background:#f6f8fa;"><strong>Monto total</strong></td><td class="detail-cell detail-value" width="58%" style="{{ $cell }} background:#f6f8fa;"><strong>US$ {{ number_format($datainscription->total, 2) }}</strong></td></tr>
                    </table>

                    <p style="margin:20px 0 12px; font-size:15px; line-height:23px;">Muchas gracias por participar en el XIV Congreso Bolivariano FILACP 2027.</p>
                    <p style="margin:0 0 16px; font-size:15px; line-height:23px;">Puede consultar el detalle de su inscripción en el siguiente enlace:</p>
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 22px;"><tr><td bgcolor="#16753a"><a href="{{ url('/inscriptions/'.$datainscription->id) }}" style="display:inline-block; padding:11px 18px; color:#fff; font-size:14px; font-weight:bold; text-decoration:none;">Ver inscripción</a></td></tr></table>
                    <p style="margin:0; padding-top:18px; border-top:1px solid #e2e5e9; font-size:13px; line-height:20px; color:#68717c;">Atentamente,<br><br>Millie Estrada<br>Inscripciones<br><strong>XIV CONGRESO BOLIVARIANO FILACP 2027</strong><br>WhatsApp: +51 983 481 269<br><a href="mailto:inscripciones@bolivarianofilacpcusco2027.org" style="color:#1156cf; text-decoration:none;">inscripciones@bolivarianofilacpcusco2027.org</a><br>www.bolivarianofilacpcusco2027.org</p>
                </td></tr>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
        </td></tr>
    </table>
</body>
</html>
