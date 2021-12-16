<?php $ruta = base_url(); ?>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title">Dashboard</h4></div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

        <ol class="breadcrumb">
            <li><a href="#">SID</a></li>
            <li class="active"><?= $this->session->userdata('EMPRESA_NOMBRE') ?></li>
        </ol>
    </div>

</div>

<div class="row white-box">
    <div class="col-md-8">
        <h3 class="box-title"> Bienvenido <?= $this->session->userdata('nombre') ?>
        </h3>
    </div>

    <div class="col-md-4">


        <i class="fa fa-calendar text-info fa-2x"></i> <?= date('d/m/Y h:i A') ?>

    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">

        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="white-box">
                    <h3 class="box-title">NUEVOS CLIENTES (7 días)</h3>
                    <ul class="list-inline two-part">
                        <li><i class="icon-people text-info"></i></li>
                        <li class="text-right"><span class="counter"><?= $ultimosclientes ?></span></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="white-box">
                    <h3 class="box-title">VENTAS DE HOY</h3>
                    <ul class="list-inline two-part">
                        <li><i class="icon-folder text-purple"></i></li>
                        <li class="text-right"><span class="counter"><?= $nro_ventashoy ?></span></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <div class="white-box">
                    <h3 class="box-title">Ventas Abiertas</h3>
                    <ul class="list-inline two-part">
                        <li><i class="icon-folder-alt text-danger"></i></li>
                        <li class="text-right"><span class="counter"><?= $nro_ventasabiertas?></span></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 col-md-3 col-sm-6 col-xs-12">
                <div class="white-box">
                    <h3 class="box-title">Ventas diarias</h3>
                    <div class="text-right"><span class="text-muted">Mis ventas de hoy</span>
                        <h1><sup><i class="ti-arrow-up text-success"></i></sup> $00,00</h1></div>

                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-sm-12 col-xs-12">


        <div class="news-slide m-b-15">
            <div class="vcarousel slide">
                <!-- Carousel items -->
                <div class="carousel-inner">
                    <div class="active item">
                        <div class="overlaybg"><img
                                    src="<?php echo $imgbanner; ?>"/></div>
                        <div class="news-content"><span class="label label-danger label-rounded">SUGERENCIA</span>
                            <h2>Toma el control de tus bodegas. Adquiere nuestra App Android y controla el inventario
                                desde tu celular.
                            </h2> <a target="_blank" href="http://www.prosode.com">Leer mas</a></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<!--<div class="col-md-3 col-sm-6">
    <div class="white-box">
        <div class="r-icon-stats"><i class="ti-user bg-megna"></i>
            <div class="bodystate">
                <h4>370</h4> <span class="text-muted">Clientes nuevos</span></div>
        </div>
    </div>
</div>
<div class="col-md-3 col-sm-6">
    <div class="white-box">
        <div class="r-icon-stats"><i class="ti-shopping-cart bg-info"></i>
            <div class="bodystate">
                <h4>342</h4> <span class="text-muted">Ventas</span></div>
        </div>
    </div>
</div>
<div class="col-md-3 col-sm-6">
    <div class="white-box">
        <div class="r-icon-stats"><i class="ti-wallet bg-success"></i>
            <div class="bodystate">
                <h4>13</h4> <span class="text-muted">Creditos</span></div>
        </div>
    </div>
</div>
<div class="col-md-3 col-sm-6">
    <div class="white-box">
        <div class="r-icon-stats"><i class="ti-wallet bg-inverse"></i>
            <div class="bodystate">
                <h4>$34650</h4> <span class="text-muted">Ganancias</span></div>
        </div>
    </div>
</div>-->

<!--/row -->
<!-- .row -->

<!-- /.row -->
<!--row -->

<!-- row -->
<!-- /row -->
<!--<div class="row">
    <div class="col-sm-6">
        <div class="white-box">
            <h3 class="box-title m-b-0">Pacientes crónicos</h3>
            <p class="text-muted">this is the sample data here for crm</p>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Username</th>
                        <th>Diseases</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr>
                        <td>2</td>
                        <td>Deshmukh</td>
                        <td>Gaylord</td>
                        <td>@Ritesh</td>
                        <td><span class="label label-info">Cancer</span></td>
                    </tr>

                    <tr>
                        <td>4</td>
                        <td>Roshan</td>
                        <td>Rogahn</td>
                        <td>@Hritik</td>
                        <td><span class="label label-success">Dental</span></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Joshi</td>
                        <td>Hickle</td>
                        <td>@Maruti</td>
                        <td><span class="label label-info">Cancer</span></td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>Nigam</td>
                        <td>Eichmann</td>
                        <td>@Sonu</td>
                        <td><span class="label label-success">Dental</span></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="white-box">
            <h3 class="box-title m-b-0">Metas vendedores</h3>
            <p class="text-muted">this is the sample data here for crm</p>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>ECG</th>
                        <th>Result</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>1</td>
                        <td>Genelia Deshmukh</td>
                        <td><span class="peity-line" data-width="120"
                                  data-peity='{ "fill": ["#01c0c8"], "stroke":["#01c0c8"]}' data-height="40">0,-3,-2,-4,-5,-4,-3,-2,-5,-1</span>
                        </td>
                        <td><span class="text-danger text-semibold"><i class="fa fa-level-down" aria-hidden="true"></i> 28.76%</span>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Ajay Devgan</td>
                        <td><span class="peity-line" data-width="120"
                                  data-peity='{ "fill": ["#01c0c8"], "stroke":["#01c0c8"]}' data-height="40">0,-1,-1,-2,-3,-1,-2,-3,-1,-2</span>
                        </td>
                        <td><span class="text-warning text-semibold"><i class="fa fa-level-down" aria-hidden="true"></i> 8.55%</span>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Hrithik Roshan</td>
                        <td><span class="peity-line" data-width="120"
                                  data-peity='{ "fill": ["#01c0c8"], "stroke":["#01c0c8"]}' data-height="40">0,3,6,1,2,4,6,3,2,1</span>
                        </td>
                        <td><span class="text-success text-semibold"><i class="fa fa-level-up" aria-hidden="true"></i> 58.56%</span>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Steve Gection</td>
                        <td><span class="peity-line" data-width="120"
                                  data-peity='{ "fill": ["#01c0c8"], "stroke":["#01c0c8"]}' data-height="40">0,3,6,4,5,4,7,3,4,2</span>
                        </td>
                        <td><span class="text-info text-semibold"><i class="fa fa-level-up" aria-hidden="true"></i> 35.76%</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>-->
