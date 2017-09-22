<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css"
          integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css" media="screen">
        resumen {
            background-color: #563d7c;
        }

        resumen a:hover {
            background-color: #fff;
        }
    </style>
    <script src="https://unpkg.com/vue"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.2/vue-resource.min.js"></script>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
            integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"
            integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1"
            crossorigin="anonymous"></script>
</head>
<body>
<!-- Menu Navbar-->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #563d7c;  font-size: 120%;">
    <div class="container">
        <a class="navbar-brand" href="#"><i class="fa fa-car"></i> Surmotriz S.R.L</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Inicio <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">Disabled</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container" id="app">
    <p>
    <h2>Documentos
        <small>15/09/2017</small>
    </h2>
    </p>
    <div class="row">
        <div class="col">

            <form>
                <div class="form-row">
                    <div class="col">
                        <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control" id="inlineFormInputGroupUsername2"
                                   placeholder="15-09-2017" value="15-09-2017">
                        </div>
                    </div>
                    <div class="col">
                        <a href="#" class="btn btn-dark" style="background-color: #563d7c;"><i class="fa fa-search"></i>
                            Buscar</a>
                    </div>
                </div>
            </form>

        </div>
        <div class="col-8 text-right">
            <a href="#" class="btn btn-dark" style="background-color: #563d7c;"><i class="fa fa-envelope-open-o"></i>
                Resumen Mes</a>
            <a href="#" class="btn btn-dark" style="background-color: #563d7c;"><i class="fa fa-envelope-open-o"></i>
                Resumen Dia</a>
        </div>
    </div>


    <p>
        <div class="text-center" v-show="loading">
            <i v-show="loading" style="margin-top: 100px;" class="fa fa-spinner fa-3x fa-spin"></i>
        </div>
        <table class="table table-md table-bordered" v-show="!loading">
            <thead>
            <tr>
                <th>#</th>
                <th>Serie <i class="fa fa-caret-up"></i></th>
                <th>Numero</th>
                <th>Cliente</th>
                <th>Imp</th>
                <th>Anu</th>
                <th class="text-center">OT</th>
                <th>Sunat</th>
                <th class="text-right">Total</th>
                <th class="text-center">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="document in documents">
                <th>{{document.id}}</th>
                <td>{{document.serie}}</td>
                <td>{{document.numero}}</td>
                <td>{{document.cliente}}</td>
                <td>{{document.impresion}}</td>
                <td>{{document.anulado}}</td>
                <td class="text-center">{{document.ot}}</td>
                <td>{{document.sunat_codigo}}</td>
                <td class="text-right">{{document.total}}</td>
                <td class="text-center">
                    <a href="#" class="btn btn-sm btn-primary" @click="itemClicked(document)">PDF</a>
                    <a href="#" class="btn btn-sm btn-info">XML</a>
                    <a href="#" class="btn btn-sm btn-secondary">COM</a>
                </td>
            </tr>
            </tbody>
        </table>
    </p>
    <div class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-file-text-o"></i>
                        {{document.tipo_doc}} F001 - {{ document.numero }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i v-show="loadingFactura" style="margin-top: 80px;" class="fa fa-spinner fa-3x fa-spin"></i>
                    </div>
                    <p>

                    <table class="table table-striped table-sm" v-show="!loadingFactura">
                        <tr>
                            <td colspan="3">
                                <div>{{documento.fecha}}</div>
                                <div>{{documento.cliente}}</div>
                                <div>{{documento.doc_cliente}}</div>
                                <div>{{documento.direccion}}</div>
                                <div>{{documento.pago}}</div>
                                <div>{{documento.ubigeo}}</div>
                            </td>
                            <td colspan="5">
                                <div>{{documento.ord_tra}}</div>
                                <div>{{documento.placa}}</div>
                                <div>{{documento.modelo}}</div>
                                <div>{{documento.chasis}}</div>
                                <div>{{documento.color}}</div>
                                <div>{{documento.km}}</div>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-left">Código</th>
                            <th class="text-left">Descripción</th>
                            <th class="text-center">Cant</th>
                            <th class="text-right">P.Unit</th>
                            <th class="text-right">Import</th>
                            <th class="text-right">Descto</th>
                            <th class="text-right">V.Venta</th>
                        </tr>
                        <tr v-for="item in documento.items">
                            <td class="text-center">{{item.id}}</td>
                            <td class="text-left">{{item.codigo}}</td>
                            <td class="text-left">{{item.descripcion}}</td>
                            <td class="text-center">{{item.cantidad}}</td>
                            <td class="text-right">{{item.unitario}}</td>
                            <td class="text-right">{{item.importe}}</td>
                            <td class="text-right">{{item.descuento}}</td>
                            <td class="text-right">{{item.venta}}</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <strong>Totales {{documento.moneda}}</strong>
                            </td>
                            <td class="text-right" colspan="6">
                                Subtotal {{documento.total_sub}} |
                                Descuentos {{documento.total_descuentos}} |
                                Gravadas {{documento.total_gravadas}} |
                                I.G.V {{documento.total_igv}} |
                                Total {{documento.total_total}}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <strong>Mensajes :</strong>
                            </td>
                            <td class="text-right" colspan="6">
                                {{documento.mensajes}}
                            </td>
                        </tr>
                    </table>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

    var app = new Vue({
        el: '#app',
        created: function () {
            this.getDocuments();
        },
        data: {
            documento: [],
            document: [],
            documents: [],
            loading: false,
            loadingFactura: false
        },

        methods: {
            getDocuments: function () {
                this.loading = true;
                this.$http.get('./apis/index.php').then(function (response) {
                    this.loading = false;
                    this.documents = response.data;
                })
            },
            itemClicked: function (document) {
                this.documento = '',
                this.document = document;
                this.loadingFactura = true;
                this.$http.get('./apis/documento.php'+document.pdf_link).then(function (response) {
                    this.loadingFactura = false;
                    this.documento = response.data;
                });
                $(".modal").modal('show');
            }
        }
    })
</script>


</body>
</html>