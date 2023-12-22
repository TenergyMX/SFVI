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
                        <div class="row">
                            <div class="col-12 col-lg-6 mb-3">
                                <div
                                    class="card h-100 rounded-4"
                                    style="
                                        box-shadow: 4px 4px #f39c12;
                                        border: 1px solid #f39c12;
                                    "
                                >
                                    <div class="card-header h3 text-center">
                                        Tipos de proyectos
                                    </div>

                                    <div class="card-body">
                                        <div id="chart"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 mb-3">
                                <div
                                    class="card h-100 rounded-4"
                                    style="
                                        box-shadow: 4px 4px #f39c12;
                                        border: 1px solid #f39c12;
                                    "
                                >
                                    <div class="card-header h3 text-center">
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
                            <div class="col-12 col-lg mb-3">
                                <div
                                    class="card h-100 rounded-4"
                                    style="
                                        box-shadow: 4px 4px #f39c12;
                                        border: 1px solid #f39c12;
                                    "
                                >
                                    <div class="card-header pb-0">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item">
                                                <button
                                                    class="nav-link active"
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
                                            <li class="nav-item">
                                                <button
                                                    class="nav-link"
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
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content m-0">
                                            <h2 class="h2 text-center">
                                                Energía Solar Generada Por Tenergy
                                            </h2>
                                            <!-- General -->
                                            <div
                                                class="tab-pane fade show active"
                                                id="tab-1"
                                                role="tabpanel"
                                                aria-labelledby="home-tab"
                                            >
                                                <div
                                                    class="text-center"
                                                    style="font-size: 5rem"
                                                >
                                                    <?php echo number_format($datos['calculo Tenergy']->data['general'],2,'.','');
                                                    ?>
                                                    <span>KW/h</span>
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
                                                                    KW/H
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
                            <div class="col-12 col-lg mb-3">
                                <div
                                    class="card h-100 rounded-4"
                                    style="
                                        box-shadow: 4px 4px #f39c12;
                                        border: 1px solid #f39c12;
                                    "
                                >
                                    <div class="card-header pb-0">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item">
                                                <button
                                                    class="nav-link active"
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
                                            <li class="nav-item">
                                                <button
                                                    class="nav-link"
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
                                        </ul>
                                    </div>
                                    <div class="card-body bg-ecologico">
                                        <div class="tab-content m-0">
                                            <h2 class="text-center">
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
                                                                class="h5 d-block text-center"
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
                                                                class="h5 d-block text-center"
                                                                >Cantidad De Árboles</span
                                                            >
                                                            <span class="fw-bold">
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
                                                        class="table table-bordered table-striped w-100"
                                                        style="
                                                            background-color: rgba(
                                                                255,
                                                                255,
                                                                255,
                                                                0.7
                                                            );
                                                        "
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
            }
            .bg-ecologico * {
                color: #f4f4f4;
            }
        </style>
        <script>
            var options = {
                series: [44, 55, 13, 43],
                chart: {
                    width: 380,
                    type: "pie",
                },
                labels: ["Doméstico", "Comercial", "Indutsrial", "General"],

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
                series: [50],
                chart: {
                    height: 200,
                    type: "radialBar",
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: "70%",
                        },
                    },
                },
                labels: ["Financiamiento"],
                colors: ["#28B463"],
                size: "80%",
            };
            var options6 = {
                series: [90],
                colors: ["#2874A6"],
                chart: {
                    height: 190,
                    type: "radialBar",
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: "70%",
                            colors: ["#283593"],
                        },
                    },
                },
                labels: ["Contado"],
                colors: ["#283593"],
            };

            var chart_5 = new ApexCharts(document.querySelector("#chart_5"), options5);
            var chart_6 = new ApexCharts(document.querySelector("#chart_6"), options6);

            /*chart_1.render();  y aqui se rendereiza */

            chart_5.render(); /* y aqui se rendereiza */
            chart_6.render();
        </script>
    </body>
</html>
