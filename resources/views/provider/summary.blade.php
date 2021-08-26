@php
    $totalDisbursement=0;
    $totalApplicationFee=0;
    $totalRsnFee=0;
    $totalAlitawareFee=0;
@endphp
<div>
    {{-- INSERTAR LOGO --}}
    Fecha: {{ $dateGenerated }}
    <br>
    <p>
        Sr/a {{$provider["first_name"] }} {{ $provider["last_name"] }}
        <br><br>
        Es grato comunicarnos nuevamente contigo, en esta ocasión para enviarte la rendición del período {{ $dateFrom }}
        al {{ $dateTo }}.
        <br>
        Como es habitual quedamos a tu entera dispocisión para cualquier consulta o comentario que quieras hacernos.
        <br>
        Somos feriame, contigo seguimos creciendo.
    </p>
    <table border="0.1">
        <tbody>
        <tr>
            <th>Fecha de compra</th>
            <th>Operación</th>
            <th>Nº pedido</th>
            <th>Total</th>
            <th>Comisión</th>
            <th>%</th>
            <th>RSN</th>
            <th>Alitáware</th>
        </tr>
        @foreach($provider['disbursements'] as $disbursement)
            @php
                $totalDisbursement+=$disbursement['amount_total_disbursement'];
                $totalApplicationFee+=$disbursement['amount_application_fee'];
                $totalRsnFee+=$disbursement['amount_rsn_fee'];
                $totalAlitawareFee+=$disbursement['amount_alitaware_fee'];
            @endphp
            <tr>
                <td> {{ date('d/m/Y', strtotime($disbursement['created_at'])) }} </td>
                <td>Venta</td>
                <td> {{ $disbursement['purchase_order'] }} </td>
                <td>$ {{ number_format($disbursement['amount_total_disbursement'], 2, ',', '.') }} </td>
                <td>$ {{ number_format($disbursement['amount_application_fee'], 2, ',', '.') }} </td>
                <td>10</td>
                <td>$ {{ number_format($disbursement['amount_rsn_fee'], 2, ',', '.') }} </td>
                <td>$ {{ number_format($disbursement['amount_alitaware_fee'], 2, ',', '.') }} </td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>$ {{number_format($totalDisbursement, 2, ',', '.') }} </td>
            <td>$ {{number_format($totalApplicationFee, 2, ',', '.') }} </td>
            <td></td>
            <td>$ {{number_format($totalRsnFee, 2, ',', '.') }} </td>
            <td>$ {{number_format($totalAlitawareFee, 2, ',', '.') }} </td>
        </tr>
        </tbody>
    </table>
    <p>
        <b>Comisión Total Período $ {{ number_format($totalApplicationFee, 2, ',', '.') }}</b>
    </p>
</div>