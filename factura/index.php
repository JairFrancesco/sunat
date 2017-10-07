<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="layout/print.css" media="print">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
    <link rel="stylesheet" href="layout/__docs.css">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://unpkg.com/vue"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.2/vue-resource.min.js"></script>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
</head>
<body>
    <div id="app">
        <?php include "layout/__index_nav.html"; ?>
        <div class="container-fluid d-print-none" >
            <div class="row flex-xl-nowrap">
                <?php include "layout/__index_sidebar.html"; ?>
                <main class="col-10 py-md-3 pl-md-5 d-print-none">
                    <!-- Navegacion Facturas-->
                    <div class="row">
                        <div class="col-9">
                            <form @submit.prevent="getDocuments">
                                <div class="form-row">
                                    <div class="form-group col-md-3 ">
                                        <input type="text" class="form-control" name="fecha" v-model="fecha">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <button type="submit" class="form-control btn btn-dark " style="background-color: #563d7c;">
                                            <i class="fa fa-search"></i> Buscar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-3 text-right">
                            <a href="#" class="btn btn-dark" style="background-color: #563d7c;"><i class="fa fa-envelope-open-o"></i> Fact</a>
                            <a href="#" class="btn btn-dark" style="background-color: #563d7c;"><i class="fa fa-envelope-open-o"></i> Bols</a>
                        </div>
                        <div class="col-12 text-center" v-show="loading">
                            <i v-show="loading" style="margin-top: 100px;" class="fa fa-spinner fa-3x fa-spin"></i>
                        </div>
                        <div class="col" v-show="!loading">
                            <!-- Facturas y Boletas -->
                            <h1 class="bd-title">Facturas</h1>
                            <table class="table table-sm table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Serie</th>
                                    <th>Numero</th>
                                    <th>Cliente</th>
                                    <th>Imp</th>
                                    <th>AFN</th>
                                    <th class="text-center">OT</th>
                                    <th>Sunat</th>
                                    <th class="text-right">Total</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="document in documents" v-if="document.orden_index==1">
                                    <th>{{document.id}}</th>
                                    <td>{{document.tipo_doc}}</td>
                                    <td>{{document.numero}}</td>
                                    <td>{{document.cliente}}</td>
                                    <td>{{document.impresion}}</td>
                                    <td>{{document.anulado}} {{document.franquicia}} {{document.anticipo}}</td>
                                    <td class="text-center">{{document.ot}}</td>
                                    <td>{{document.sunat_codigo}}</td>
                                    <td class="text-right">{{document.total}}</td>
                                    <td class="text-center">
                                        <a href="#" @click="itemClicked(document)">PDF</a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <h1 class="bd-title">Boletas</h1>
                            <table class="table table-sm table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Serie</th>
                                    <th>Numero</th>
                                    <th>Cliente</th>
                                    <th>Imp</th>
                                    <th>AFN</th>
                                    <th class="text-center">OT</th>
                                    <th>Sunat</th>
                                    <th class="text-right">Total</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="document in documents" v-if="document.orden_index==2">
                                    <th>{{document.id}}</th>
                                    <td>{{document.tipo_doc}}</td>
                                    <td>{{document.numero}}</td>
                                    <td>{{document.cliente}}</td>
                                    <td>{{document.impresion}}</td>
                                    <td>{{document.anulado}} {{document.franquicia}} {{document.anticipo}}</td>
                                    <td class="text-center">{{document.ot}}</td>
                                    <td>{{document.sunat_codigo}}</td>
                                    <td class="text-right">{{document.total}}</td>
                                    <td class="text-center">
                                        <a href="#" @click="itemClicked(document)">PDF</a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </main>
            </div>
            <?php include "layout/__index_modal.html"; ?>
        </div>
        <?php include "layout/__index_imprimir.html"; ?>
    </div>
    <script src="layout/__index_vue.js"></script>
</body>
</html>