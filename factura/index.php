<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <style media="print">
        *{ background: transparent !important; }
        #table_home{display: none;}
        #imprimedoc{display:block !important;}
        #titulo_doc{display:none !important;}
        #form_doc{display:none !important;}
        #table_modal{display: none !important;}
        body nav {display: none !important;}
        #app p h2 {display: none !important;}
    </style>
    <style type="text/css">
        resumen {
            background-color: #563d7c;
        }
        resumen a:hover {
            background-color: #fff;
        }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css"
          integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://unpkg.com/vue"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.2/vue-resource.min.js"></script>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
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
    <h2 id="titulo_doc">Documentos
        <small>15/09/2017</small>
    </h2>
    </p>
    <div class="row" id="form_doc">
        <div class="col">

            <form>
                <div class="form-row">
                    <div class="col">
                        <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="date" class="form-control" id="inlineFormInputGroupUsername2"
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
        <table class="table table-sm table-bordered" v-show="!loading" id="table_home">
            <thead>
            <tr>
                <th>#</th>
                <th>Serie <i class="fa fa-caret-up"></i></th>
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
            <tr v-for="document in documents">
                <th>{{document.id}}</th>
                <td>{{document.serie}}</td>
                <td>{{document.numero}}</td>
                <td>{{document.cliente}}</td>
                <td>{{document.impresion}}</td>
                <td>{{document.anulado}} {{document.franquicia}} {{document.anticipo}}</td>
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
    <div class="modal fade" id="table_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-file-text-o"></i>
                        {{document.tipo_doc}} {{document.serie}} - {{ document.numero }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i v-show="loadingFactura"  class="fa fa-spinner fa-3x fa-spin"></i>
                    </div>


                    <table class="table table-sm" v-show="!loadingFactura">
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
                        <tr class="thead-inverse" >
                            <th class="text-center">#</th>
                            <th class="text-left">Código</th>
                            <th class="text-left">Descripción</th>
                            <th class="text-center">Cant</th>
                            <th class="text-right">P.Unit</th>
                            <th class="text-right">Import</th>
                            <th class="text-right">Descto</th>
                            <th class="text-right">V.Venta</th>
                        </tr>
                        <tr v-for="item in documento.items" class="table-active">
                            <td class="text-center">{{item.id}}</td>
                            <td class="text-left">{{item.codigo}}</td>
                            <td class="text-left">{{item.descripcion}}</td>
                            <td class="text-center">{{item.cantidad}}</td>
                            <td class="text-right">{{item.unitario}}</td>
                            <td class="text-right">{{item.importe}}</td>
                            <td class="text-right">{{item.descuento}}</td>
                            <td class="text-right">{{item.venta}}</td>
                        </tr>
                        <tr v-show="documento.suma_active">
                            <td colspan="3"><strong>Sumatoria</strong></td>
                            <td></td>
                            <td></td>
                            <td class="text-right">{{documento.suma_import}}</td>
                            <td class="text-right">{{documento.suma_descuento}}</td>
                            <td class="text-right">{{documento.suma_venta}}</td>
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
                                Total <strong>{{documento.total_total}}</strong>
                            </td>
                        </tr>
                        <tr v-show="documento.mensaje_active">
                            <td colspan="2" >
                                <strong>Mensajes </strong>
                            </td>
                            <td class="text-right" colspan="6"  v-html="documento.mensajes">
                            </td>
                        </tr>

                    </table>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-primary" @click="printPDF('a')"><i class="fa fa-print"></i> Imprimir</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-windows-close"></i>Close</button>
                </div>
            </div>
        </div>
    </div>

    <!--Impresion-->
    <div id="imprimedoc" style="display: none;">
        <!--Cabezera de direcciones-->
        <table style="width: 100%;  margin-bottom: 20px;" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 15%; border-left: solid 1px #000; border-top: solid 1px #000; border-bottom: solid 1px #000;">
                    <img src="images/logo.jpg" style="height: 100px;">
                </td>
                <td style="width: 41%; border-top: solid 1px #000; border-right: solid 1px #000; border-bottom: solid 1px #000; font-size: 9px; line-height: 13px;">
                    TACNA: Av. Leguia 1870 Tacna. Telef.: (052) 426368 - 244015
                    cel.:952869639 (repuestos) cel.: 992566630 (servicios)
                    email: tacna@surmotriz.com y repuestos@surmotriz.com
                    MOQUEGUA: Sector Yaracachi Mz.D Lte.09 Mariscal Nieto/Moquegua
                    Telef:(53) 479365 Cel: #953922105 email: moquegua@surmotriz.com
                    Venta de vehiculos-repuestos y accesorios legitimos Toyota
                    Reparacion y mantenimiento de automoviles y camionetas.
                </td>
                <td style="width: 4%;"></td>
                <td style="width: 40%; border: solid 1px #000;">
                    <div style="text-align: center; color: red; font-size: 18px; line-height: 25px;">RUC: 20532710066</div>
                    <div style="text-align: center; font-weight: bold; font-size: 18px; line-height: 25px; ">
                        {{documento.documento_noombre}}
                    </div>
                    <div style="text-align: center; color: blue; font-size: 19px; line-height: 25px;">
                        {{documento.serie}} - {{documento.numero}}
                    </div>
                </td>
            </tr>
        </table>
        <!--Cabezera de informacion de documento-->
        <table style="width: 100%; font-size: 12px; border: solid 1px #000; margin-bottom: 20px; padding: 5px;" cellspacing="0" cellpadding="0">
            <tr>
                <td style="width: 60%;"><span style="padding-left: 5px;">{{documento.fecha}}</span></td>
                <td style="width: 40%;">{{documento.ord_tra}}</td>
            </tr>
            <tr>
                <td style="width: 60%;"><span style="padding-left: 5px;">{{documento.cliente}}</span></td>
                <td style="width: 40%;">{{documento.placa}}</td>
            </tr>
            <tr>
                <td style="width: 60%;"><span style="padding-left: 5px;">{{documento.doc_cliente}}</span></td>
                <td style="width: 40%;">{{documento.modelo}}</td>
            </tr>
            <tr>
                <td style="width: 60%;"><span style="padding-left: 5px;">{{documento.direccion}}</span></td>
                <td style="width: 40%;">{{documento.chasis}}</td>
            </tr>
            <tr>
                <td style="width: 60%;"><span style="padding-left: 5px;">{{documento.pago}}</span></td>
                <td style="width: 40%;">{{documento.color}}</td>
            </tr>
            <tr>
                <td style="width: 60%;"><span style="padding-left: 5px;">{{documento.ubigeo}}</span></td>
                <td style="width: 40%;">{{documento.km}}</td>
            </tr>
        </table>
        <!--items-->
        <table style="width: 100%; font-size: 12px;" cellspacing="0" cellpadding="0">
            <tr style="font-weight: bold;">
                <td style="border-bottom: solid 1px #000; border-left: solid 1px #000; border-top: solid 1px #000; border-right: solid 1px #000; text-align: center;">Nro</td>
                <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; border-right: solid 1px #000; padding-left: 3px;">Codigo</td>
                <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; border-right: solid 1px #000; padding-left: 3px;">Descripcion</td>
                <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; border-right: solid 1px #000;  text-align: center;">Cant</td>
                <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; border-right: solid 1px #000;  text-align: right; padding-right: 3px;">P. Unit</td>
                <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; border-right: solid 1px #000;  text-align: right; padding-right: 3px;">Importe</td>
                <td style="border-bottom: solid 1px #000; border-top: solid 1px #000; border-right: solid 1px #000;  text-align: right; padding-right: 3px;">Desct</td>
                <td style="border-bottom: solid 1px #000; border-top: solid 1px #000;  border-right: solid 1px #000; text-align: right; padding-right: 3px;">Valor Venta</td>
            </tr>
            <tr v-for="item in documento.items">
                <td style="width: 4%; border-left: solid 1px #000; border-right: solid 1px #000; border-bottom: solid 1px #000; text-align: center;">{{item.id}}</td>
                <td style="width: 12%; border-right: solid 1px #000; border-bottom: solid 1px #000; padding-left: 3px;">{{item.codigo}}</td>
                <td style="width: 41%; border-right: solid 1px #000; border-bottom: solid 1px #000; padding-left: 3px;">{{item.descripcion}}</td>
                <td style="width: 5%; text-align: center; border-right: solid 1px #000; border-bottom: solid 1px #000;">{{item.cantidad}}</td>
                <td style="width: 9%; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000; padding-right: 3px;">{{item.unitario}}</td>
                <td style="width: 9%; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000; padding-right: 3px;">{{item.importe}}</td>
                <td style="width: 9%; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000; padding-right: 3px;">{{item.descuento}}</td>
                <td style="width: 11%; border-right: solid 1px #000; text-align: right; border-bottom: solid 1px #000; padding-right: 3px;">{{item.venta}}</td>
            </tr>

            <tr>
                <td colspan="4" rowspan="8" style="width: 60%;border-right: solid 1px #000; line-height: 14px;" v-html="`${documento.mensajes} <br> Son : ${documento.leyenda} <br><img src='images/20532710066-07-FN03-2917.png' style='height: 55px; width: 300px; text-align: center;'>`"></td>
                <td colspan="3" style="text-align: right; border-right: solid 1px #000; padding-right: 3px;">Sub Total {{documento.moneda}}</td>
                <td style="border-right: solid 1px #000; text-align: right; padding-right: 3px;">{{documento.total_sub}}</td>
            </tr>
            <tr>
                <td colspan="3" style="border-top: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;" >Total Descuentos {{documento.moneda}}</td>
                <td style="border-top: solid 1px #000; border-right: solid 1px #000; text-align: right; padding-right: 3px;">{{documento.total_descuentos}}</td>
            </tr>
            <tr>
                <td colspan="3" style="border-top: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;">Operaciones Gravadas {{documento.moneda}}</td>
                <td style="border-top: solid 1px #000; border-right: solid 1px #000; text-align: right; padding-right: 3px;">{{documento.total_gravadas}}</td>
            </tr>
            <tr>
                <td colspan="3" style="border-top: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;">Operaciones Inafectas {{documento.moneda}}</td>
                <td style="border-top: solid 1px #000; border-right: solid 1px #000; text-align: right; padding-right: 3px;">0.00</td>
            </tr>
            <tr>
                <td colspan="3" style="border-top: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;">Operaciones Exoneradas {{documento.moneda}}</td>
                <td style="border-top: solid 1px #000; border-right: solid 1px #000; text-align: right; padding-right: 3px;">0.00</td>
            </tr>
            <tr>
                <td colspan="3" style="border-top: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;">Operaciones Gratuitas {{documento.moneda}}</td>
                <td style="border-top: solid 1px #000; border-right: solid 1px #000; text-align: right; padding-right: 3px;">0.00</td>
            </tr>
            <tr>
                <td colspan="3" style="border-top: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;">I.G.V. 18% {{documento.moneda}}</td>
                <td style="border-top: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;">{{documento.total_igv}}</td>
            </tr>
            <tr>
                <td colspan="3" style="border-top: solid 1px #000; border-bottom: solid 1px #000; text-align: right; border-right: solid 1px #000; padding-right: 3px;">
                    <strong>IMPORTE TOTAL {{documento.moneda}}</strong></td>
                <td style="border-top: solid 1px #000; border-bottom: solid 1px #000; border-right: solid 1px #000; text-align: right; padding-right: 3px;"><strong>{{documento.total_total}}</strong></td>
            </tr>
        </table>
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
                $(".modal").modal('show');
                this.$http.get('./apis/documento.php'+document.pdf_link).then(function (response) {
                    this.loadingFactura = false;
                    this.documento = response.data;
                });

            },
            printPDF: function (doc){
                window.print();
            }
        }
    })
</script>


</body>
</html>