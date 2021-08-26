@php
    $totalDisbursement=0;
    //$totalApplicationFee=0;
    $totalRsnFee=0;
@endphp
<div>
 {{-- INSERTAR LOGO --}}
    Fecha: {{ $dateGenerated }}
    <br>
    <p>
        Emprendedor {{ $provider["first_name"] }} {{ $provider["last_name"] }}
        <br><br>
        Rendición del período {{ $dateFrom }} al {{ $dateTo }}.
        <br>
    </p>
    <table border="0.1">
        <tbody>
        <tr>
            <th>Fecha de compra</th>
            <th>Operación</th>
            <th>Nº pedido</th>
            <th>Total</th>
{{--            <th>Comisión</th>--}}
{{--            <th>%</th>--}}
            <th>RSN</th>
        </tr>
        @foreach($provider['disbursements'] as $disbursement)
            @php
                $totalDisbursement+=$disbursement['amount_total_disbursement'];
                //$totalApplicationFee+=$disbursement['amount_application_fee'];
                $totalRsnFee+=$disbursement['amount_rsn_fee'];
            @endphp
            <tr>
            <td> {{ date('d/m/Y', strtotime($disbursement['created_at'])) }} </td>
            <td>Venta</td>
            <td> {{ $disbursement['purchase_order'] }} </td>
            <td>$ {{ number_format($disbursement['amount_total_disbursement'], 2, ',', '.') }} </td>
{{--            <td>$ {{ $disbursement['amount_application_fee'] }} </td>--}}
{{--            <td>10</td>--}}
            <td>$ {{ $disbursement['amount_rsn_fee'] }} </td>
        </tr>
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>$ {{ number_format($totalDisbursement, 2, ',', '.') }} </td>
{{--            <td>$ {{ $totalApplicationFee }} </td>--}}
{{--            <td></td>--}}
            <td>$ {{ number_format($totalRsnFee, 2, ',', '.') }} </td>
        </tr>
        </tbody>
    </table>
    <p>
        <b>Comisión Total Período $ {{ number_format($totalRsnFee, 2, ',', '.') }}</b>
    </p>
</div>