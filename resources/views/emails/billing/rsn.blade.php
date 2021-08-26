<?php
//dd($shippingBilling);
?>
<div>
    <p>
        Estimado, le envíamos los datos para realizar la factura de envío a domicilio al cliente.
    </p>
    <p>
        <b>Datos de Cliente</b>
        <br>
        <b>Nombre y Apellido</b>: {{ $shippingBilling["customer"]["first_name"] }} {{ $shippingBilling["customer"]["last_name"] }}
        <br>
        <b>Email</b>: {{ $shippingBilling["customer"]["customer_email"] }}
        <br>
        <b>Identificación:</b> {{ $shippingBilling["customer"]["identification_type"] }} {{ $shippingBilling["customer"]["identification_number"] }}
        <br>
        <b>Domicilio:</b> {{ $shippingBilling["customer"]["customer_address"] }}
    </p>

    <b>ITEMS</b>
    <br>
    <table style="border-collapse: collapse; width: 100%" border="1">
        <tbody>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Costo de envío</th>
        </tr>
        @foreach($shippingBilling["items"] as $item)
            <tr>
                <td> {{ $item["item_name"] }} </td>
                <td style="text-align: right;"> {{ $item["item_quantity"] }} </td>
                <td style="text-align: right;"> {{ $item["delivery_cost"] }} </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <p>
        <b>TOTAL A FACTURAR:</b> $ {{ $shippingBilling["total_amount_delivery"] }}
        <br>
        Incluye IVA
    </p>
    <p>
        Un cordial saludo.
        <br>
        Equipo feriame.
    </p>
</div>