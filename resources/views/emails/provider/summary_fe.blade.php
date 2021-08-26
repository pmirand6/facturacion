<?php
//
?>
<div>
    <p>
        Estimado/a {{ $provider["first_name"] }} {{ $provider["last_name"] }}:
        <br><br>
        Le pedimos disculpas por las molestias ocasionadas, le enviamos la corrección de la factura y resumen de cuenta de feriame,
        correspondiente al siguiente periodo {{ $dateFrom }} – {{ $dateTo }}.
        <br><br>
{{--        Como información adicional,  te contamos que en dicho periodo has logrado muchos avances, como:--}}
{{--        <br><br>--}}
{{--        Vendiste {n} artículos por la suma total {$00}.--}}
{{--        <br><br>--}}
        Como siempre quedamos en contacto para cualquier consulta.
        <br><br>
        Cordialmente.
        <br>
        Equipo feriame.
    </p>
</div>
