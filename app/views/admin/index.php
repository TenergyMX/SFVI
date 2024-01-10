<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require_once(RUTA_APP.'/views/admin/templates/head.html'); ?>
        <?php require_once(RUTA_APP.'/views/templates/head-pwa.html'); ?>

        <title>DASHBOARD</title>
    </head>

    <body
        data-theme="default"
        data-layout="fluid"
        data-sidebar-position="left"
        data-sidebar-layout="default"
    >
        <div class="wrapper">
            <?php require_once(RUTA_APP.'/views/admin/templates/sidebar.html'); ?>
            <div class="main">
                <?php require_once(RUTA_APP.'/views/admin/templates/navbar.html'); ?>
                <main class="content">
                    <div class="container-fluid p-0">
                        <!-- 1 -->
                        <div class="row gx-5 gy-5">
                            <div class="col-12 col-lg-6">
                                <div
                                    class="card h-100 rounded-4"
                                    style="
                                        -webkit-box-shadow: 14px 14px 21px -2px rgba(184, 182, 184, 1);
                                        -moz-box-shadow: 14px 14px 21px -2px rgba(184, 182, 184, 1);
                                        box-shadow: 14px 14px 21px -2px rgba(184, 182, 184, 1);
                                    "
                                >
                                    <div
                                        class="card-header h3 text-center rounded-4 fw-bold"
                                        style="font-family: 'Product Sans Infanity'; color: black";
                                    >
                                        Tipos de proyectos
                                    </div>

                                    <div class="card-body rounded-4">
                                        <div id="chart"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div
                                    class="card h-100 rounded-4"
                                    style="
                                        -webkit-box-shadow: 14px 14px 21px -2px rgba(184, 182, 184, 1);
                                        -moz-box-shadow: 14px 14px 21px -2px rgba(184, 182, 184, 1);
                                        box-shadow: 14px 14px 21px -2px rgba(184, 182, 184, 1);
                                    "
                                >
                                    <div
                                        class="card-header h3 text-center rounded-4 fw-bold"
                                        style="font-family: 'Product Sans Infanity'; color: black";
                                    >
                                        Tipos de venta
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div
                                                id="chart_5"
                                                class="col-12 col-lg-6"
                                            ></div>
                                            <div
                                                id="chart_6"
                                                class="col-12 col-lg-6"
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg">
                                <div
                                    class="card h-100 rounded-4"
                                    style="
                                        -webkit-box-shadow: 14px 14px 21px -2px rgba(184, 182, 184, 1);
                                        -moz-box-shadow: 14px 14px 21px -2px rgba(184, 182, 184, 1);
                                        box-shadow: 14px 14px 21px -2px rgba(184, 182, 184, 1);
                                    "
                                >
                                    <div class="card-header pb-0 rounded-4">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item">
                                                <button
                                                    class="nav-link active fw-bold"
                                                    id="home-tab"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#tab-1"
                                                    type="button"
                                                    role="tab"
                                                    aria-controls="home"
                                                    aria-selected="true"
                                                >
                                                    General
                                                </button>
                                            </li>
                                            <?php if ($datos['user']['int_role'] <= 2) : ?>
                                            <li class="nav-item">
                                                <button
                                                    class="nav-link fw-bold"
                                                    id="profile-tab"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#profile"
                                                    type="button"
                                                    role="tab"
                                                    aria-controls="profile"
                                                    aria-selected="false"
                                                >
                                                    Individual
                                                </button>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content m-0">
                                            <h2
                                                class="h3 text-center fw-bold"
                                                style="font-family: 'Product Sans Infanity'" 
                                                ;
                                            >
                                                <p>Energía Generada</p>
                                            </h2>

                                            <!-- General -->
                                            <div
                                                class="tab-pane fade show active py-5"
                                                id="tab-1"
                                                role="tabpanel"
                                                aria-labelledby="home-tab"
                                            >
                                                <div
                                                    class="text-center"
                                                    style="
                                                        font-size: 52px;
                                                        transform: scaleY(2);
                                                        font-family: 'SF Pro Display';
                                                        color: black;
                                                        font-weight: 950;
                                                    "
                                                >
                                                    <?php echo number_format($datos['calculo Tenergy']->data['general'],2,'.','');
                                                    ?> kW/h
                                                </div>
                                            </div>
                                            <!-- individual -->
                                            <div
                                                class="tab-pane fade"
                                                id="profile"
                                                role="tabpanel"
                                                aria-labelledby="profile-tab"
                                            >
                                                <div class="table-responsive">
                                                    <table
                                                        class="table table-bordered table-striped w-100"
                                                    >
                                                        <thead>
                                                            <tr>
                                                                <th>Proyecto</th>
                                                                <th>Energía Solar</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach($datos['calculo Tenergy']->data['individual']
                                                            as $key => $data) : ?>
                                                            <tr>
                                                                <th>
                                                                    <?php echo $data['project']; ?>
                                                                </th>
                                                                <td>
                                                                    <?php echo number_format($data['tenergy'],2,'.',''); ?>
                                                                    kW/h
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg">
                                <div
                                    class="card h-100 rounded-4"
                                    style="
                                        -webkit-box-shadow: 14px 14px 21px -2px rgba(184, 182, 184, 1);
                                        -moz-box-shadow: 14px 14px 21px -2px rgba(184, 182, 184, 1);
                                        box-shadow: 14px 14px 21px -2px rgba(184, 182, 184, 1);
                                    "
                                >
                                    <div class="card-header pb-0 rounded-4">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item">
                                                <button
                                                    class="nav-link active fw-bold"
                                                    id="home-tab"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#tab-3"
                                                    type="button"
                                                    role="tab"
                                                    aria-controls="home"
                                                    aria-selected="true"
                                                >
                                                    General
                                                </button>
                                            </li>
                                            <?php if ($datos['user']['int_role'] <= 2) : ?>
                                            <li class="nav-item">
                                                <button
                                                    class="nav-link fw-bold"
                                                    id="profile-tab"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#profile3"
                                                    type="button"
                                                    role="tab"
                                                    aria-controls="profile"
                                                    aria-selected="false"
                                                >
                                                    Individual
                                                </button>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                    <div class="card-body bg-ecologico">
                                        <div class="tab-content m-0">
                                            <h2
                                                class="text-center fw-bold"
                                                style="font-family: 'Product Sans Infanity'"
                                                ;
                                            >
                                                Apartado Ecológico
                                            </h2>
                                            <!-- General -->
                                            <div
                                                class="tab-pane fade show active"
                                                id="tab-3"
                                                role="tabpanel"
                                                aria-labelledby="home-tab"
                                            >
                                                <div class="row">
                                                    <div
                                                        class="col-12 col-md-6 h-100 text-center"
                                                    >
                                                        <p
                                                            class="my-5"
                                                            style="font-size: 3rem"
                                                        >
                                                            <span
                                                                class="h4 d-block text-center"
                                                                >Dióxido De Carbono</span
                                                            >
                                                            <span class="fw-bold">
                                                                <?php echo number_format((($datos['calculo Tenergy']->data['general']
                                                                * 0.385)/1000),2, '.','');
                                                                ?>
                                                            </span>
                                                        </p>
                                                    </div>
                                                    <div
                                                        class="col-12 col-md-6 text-center"
                                                    >
                                                        <p
                                                            class="my-5"
                                                            style="font-size: 3rem"
                                                        >
                                                            <span
                                                                class="h4 mb-0 d-block text-center"
                                                                >Cantidad De Árboles</span
                                                            >
                                                            <span class="fw-bold my-0">
                                                                <?php echo number_format((($datos['calculo Tenergy']->data['general']
                                                                * 0.385)/60),0, '.','');
                                                                ?>
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- indivifual -->
                                            <div
                                                class="tab-pane fade"
                                                id="profile3"
                                                role="tabpanel"
                                                aria-labelledby="profile-tab"
                                            >
                                                <div class="table-responsive">
                                                    <table
                                                        class="table table-primary w-100"
                                                    >
                                                        <thead>
                                                            <tr>
                                                                <th>Proyecto</th>
                                                                <th>
                                                                    Dióxido De Carbono
                                                                </th>
                                                                <th>Árboles</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach($datos['calculo Tenergy']->data['individual']
                                                            as $key => $data) : ?>
                                                            <tr>
                                                                <th>
                                                                    <?php echo $data['project']; ?>
                                                                </th>
                                                                <td>
                                                                    <?php echo number_format(($data['tenergy']* 0.385)/1000,2, '.',''); ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo number_format(($data['tenergy']* 0.385)/60,0, '.','') ?>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end -->
                    </div>
                </main>
                <?php require_once(RUTA_APP.'/views/admin/templates/footer.html'); ?>
            </div>
        </div>
        <?php require_once(RUTA_APP.'/views/admin/templates/scripts.html'); ?>
        <style>
            .bg-ecologico {
                background-image: url("https://www.shutterstock.com/image-photo/reduce-co2-emissions-limit-climate-600nw-2277545979.jpg");
                background-color: rgba(60, 60, 60, 0.5);
                background-blend-mode: multiply;
                background-size: cover;
                background-position: center;
                border-bottom-left-radius: var(--bs-border-radius-xl);
                border-bottom-right-radius: var(--bs-border-radius-xl);
            }
            .bg-ecologico * {
                color: #f4f4f4;
            }
        </style>
        <script>
            var options = {
                series: [<?= $datos['tipos_de_proyecto']['domestico'] ?>, <?= $datos['tipos_de_proyecto']['comercial'] ?>, <?= $datos['tipos_de_proyecto']['industrial'] ?>],
                chart: {
                    width: 380,
                    type: "pie",
                },
                labels: ["Doméstico", "Comercial", "Indutsrial"],

                responsive: [
                    {
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 200,
                            },
                            legend: {
                                position: "bottom",
                            },
                        },
                    },
                ],
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();

            var options5 = {
                series: [<?= $datos['tipos_de_venta']['fide'] ?>],
                chart: {
                    height: 280,
                    type: "radialBar",
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: "60%",
                        },
                        dataLabels: {
                            name: {
                                show: true,
                                fontSize: "17px", // Tamaño de la fuente para el porcentaje
                                fontFamily: "SF Pro Display",
                                fontWeight: "100",
                            },
                            value: {
                                show: true,
                                fontSize: "17px", // Tamaño de la fuente para el porcentaje
                                fontFamily: "SF Pro Display",
                                fontWeight: "100",
                            },
                        },
                    },
                },
                labels: ["Financiamiento"],
                colors: ["#FFA200"],
                size: "80%",
            };
            var options6 = {
                series: [<?= $datos['tipos_de_venta']['contado'] ?>],
                colors: ["#2874A6"],
                chart: {
                    height: 280,
                    type: "radialBar",
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: "60%",
                            colors: ["#283593"],
                        },
                        dataLabels: {
                            name: {
                                show: true,
                                fontSize: "17px", // Tamaño de la fuente para el porcentaje
                                fontFamily: "SF Pro Display",
                                fontWeight: "100",
                            },
                            value: {
                                show: true,
                                fontSize: "17px", // Tamaño de la fuente para el porcentaje
                                fontFamily: "SF Pro Display",
                                fontWeight: "100",
                            },
                        },
                    },
                },
                labels: ["Contado"],
                colors: ["#FF6624"],
            };

            var chart_5 = new ApexCharts(document.querySelector("#chart_5"), options5);
            var chart_6 = new ApexCharts(document.querySelector("#chart_6"), options6);

            /*chart_1.render();  y aqui se rendereiza */

            chart_5.render(); /* y aqui se rendereiza */
            chart_6.render();
        </script>
    </body>
</html>
