<?php $ruta = base_url(); ?>
<input type="hidden" id="TIPO_IMPRESION" value="<?= $this->session->userdata('TIPO_IMPRESION'); ?>">
<input type="hidden" id="IMPRESORA" value="<?= $this->session->userdata('IMPRESORA'); ?>">
<input type="hidden" id="MENSAJE_FACTURA" value="<?= $this->session->userdata('MENSAJE_FACTURA'); ?>">
<input type="hidden" id="MOSTRAR_PROSODE" value="<?= $this->session->userdata('MOSTRAR_PROSODE'); ?>">
<input type="hidden" id="TICKERA_URL" value="<?= $this->session->userdata('USUARIO_IMPRESORA'); ?>">



<div class="table-responsive">
    <table class="table table-striped dataTable table-bordered" id="tablaresultado">
        <thead>
        <tr>
            <th>Fecha</th>
            <?php
            if (count($tipos_venta) > 0) {
                foreach ($tipos_venta as $tipo_venta) { ?>
                    <th># Fac. <?= $tipo_venta['tipo_venta_nombre'] ?></th>
                    <th>Valor</th>
                <?php }
            }?>
            <th># Cotizaciones</th>
            <th>Total Venta</th>
            <th>Total # Fac.</th>
            <th>Total Fac - Cotizaciones</th>
            <th>Mas Cotizaciones</th>
            <th>Menos Cotizaciones</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $promedio_mas_cot=0;
        $promedio_menos_cot=0;
        if (count($data) > 0) {
            $cont = 0;

            foreach ($data as $key=>$row) {
                $nro_total_tipo=0;
                $suma_total_tipo=0;
                $cont++;
                ?>
                <tr data-child-value="hidden <?= $cont ?>">
                    <td><span style="display:none"><?= date('Ymd', strtotime($key)) ?></span><?= $key ?></td>
                    <?php
                    if (count($row) > 0) {
                        foreach ($row as $valor) {
                            $nro_total_tipo+=$valor['contador'];
                            $suma_total_tipo+=$valor['total']; ?>
                            <td data-sumar="true" data-sumcuantosdecimales="0"><?= $valor['contador'] ?></td>
                            <td data-sumar="true" data-sumcuantosdecimales="0">
                                <?= number_format($valor['total'], 2, ',', '.')  ?></td>
                        <?php }
                    }?>

                    <td>-</td>
                    <td data-sumar="true"><?= number_format($suma_total_tipo, 2, ',', '.') ?> </td>
                    <td data-sumar="true" data-sumcuantosdecimales="0"><?= $nro_total_tipo ?></td>
                    <td data-sumar="true" data-sumcuantosdecimales="0"><?= $nro_total_tipo //por los momentos, como no tenemos cotizaciones, es la misma cantidad, toca modifcarlo uego ?></td>
                    <td><?= $nro_total_tipo>0?number_format($suma_total_tipo/$nro_total_tipo, 2, ',', '.'):0 ?></td>
                    <td ><?= $nro_total_tipo>0?number_format($suma_total_tipo/$nro_total_tipo, 2, ',', '.'):0 ?></td>
                </tr>
            <?php

                $promedio_mas_cot+=$nro_total_tipo>0?($suma_total_tipo/$nro_total_tipo):0.00;
                $promedio_menos_cot+=$nro_total_tipo>0?($suma_total_tipo/$nro_total_tipo):0.00;
            }
        } ?>
        </tbody>
        <tfoot>
        <th>Total</th>
        <?php
        if (count($tipos_venta) > 0) {
            foreach ($tipos_venta as $tipo_venta) { ?>
                <th></th>
                <th></th>
            <?php }
        }?>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th><?= $promedio_mas_cot>0 &&  count($data)>0? number_format($promedio_mas_cot/count($data), 2, ',', '.'): number_format(0.00, 2, ',', '.') ?></th>
        <th><?= $promedio_mas_cot>0 &&  count($data)>0? number_format($promedio_mas_cot/count($data), 2, ',', '.'):number_format(0.00, 2, ',', '.') ?></th>
        </tfoot>

    </table>
</div>


<script type="text/javascript">
    $(function () {
        TablesDatatables.init(0,false,'asc','Promedio de Compras por Cliente',[{width: '8%', targets: 0}]);
        $('[data-toggle="tooltip"], .enable-tooltip').tooltip({animation: false});
    });


</script>