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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
    <link rel="stylesheet" href="layout/__docs.css">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://unpkg.com/vue"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.7.2/vue-resource.min.js"></script>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
</head>
<body>

        <header class="navbar navbar-expand navbar-dark flex-column flex-md-row bd-navbar">
            <a class="navbar-brand mr-0 mr-md-2" href="/" aria-label="Bootstrap">
                <svg  class="d-block" width="36" height="36" viewbox="0 0 612 612" xmlns="http://www.w3.org/2000/svg" focusable="false">
                    <title>Bootstrap</title>
                    <path fill="currentColor" d="M510 8a94.3 94.3 0 0 1 94 94v408a94.3 94.3 0 0 1-94 94H102a94.3 94.3 0 0 1-94-94V102a94.3 94.3 0 0 1 94-94h408m0-8H102C45.9 0 0 45.9 0 102v408c0 56.1 45.9 102 102 102h408c56.1 0 102-45.9 102-102V102C612 45.9 566.1 0 510 0z"/>
                    <path fill="currentColor" d="M196.77 471.5V154.43h124.15c54.27 0 91 31.64 91 79.1 0 33-24.17 63.72-54.71 69.21v1.76c43.07 5.49 70.75 35.82 70.75 78 0 55.81-40 89-107.45 89zm39.55-180.4h63.28c46.8 0 72.29-18.68 72.29-53 0-31.42-21.53-48.78-60-48.78h-75.57zm78.22 145.46c47.68 0 72.73-19.34 72.73-56s-25.93-55.37-76.46-55.37h-74.49v111.4z"/>
                </svg>
            </a>
            <div class="navbar-nav-scroll">
                <ul class="navbar-nav bd-navbar-nav flex-row">
                    <li class="nav-item">
                        <a class="nav-link " href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/docs/4.0/">Factura</a>
                    </li>                    
                </ul>
            </div>
            <ul class="navbar-nav flex-row ml-md-auto d-none d-md-flex">
                <li class="nav-item dropdown">
                    <a class="nav-item nav-link dropdown-toggle mr-md-2" href="#" id="bd-versions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    v4.0
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="bd-versions">
                        <a class="dropdown-item active" href="/docs/4.0/">Latest (4.x)</a>
                        <a class="dropdown-item" href="https://v4-alpha.getbootstrap.com">v4 Alpha 6</a>
                        <a class="dropdown-item" href="https://getbootstrap.com/docs/3.3/">v3.3.7</a>
                        <a class="dropdown-item" href="https://getbootstrap.com/2.3.2/">v2.3.2</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-2" href="https://github.com/twbs/bootstrap" target="_blank" rel="noopener" aria-label="GitHub">
                        <svg class="navbar-nav-svg" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 512 499.36" focusable="false">
                            <title>GitHub</title>
                            <path d="M256 0C114.64 0 0 114.61 0 256c0 113.09 73.34 209 175.08 242.9 12.8 2.35 17.47-5.56 17.47-12.34 0-6.08-.22-22.18-.35-43.54-71.2 15.49-86.2-34.34-86.2-34.34-11.64-29.57-28.42-37.45-28.42-37.45-23.27-15.84 1.73-15.55 1.73-15.55 25.69 1.81 39.21 26.38 39.21 26.38 22.84 39.12 59.92 27.82 74.5 21.27 2.33-16.54 8.94-27.82 16.25-34.22-56.84-6.43-116.6-28.43-116.6-126.49 0-27.95 10-50.8 26.35-68.69-2.63-6.48-11.42-32.5 2.51-67.75 0 0 21.49-6.88 70.4 26.24a242.65 242.65 0 0 1 128.18 0c48.87-33.13 70.33-26.24 70.33-26.24 14 35.25 5.18 61.27 2.55 67.75 16.41 17.9 26.31 40.75 26.31 68.69 0 98.35-59.85 120-116.88 126.32 9.19 7.9 17.38 23.53 17.38 47.41 0 34.22-.31 61.83-.31 70.23 0 6.85 4.61 14.81 17.6 12.31C438.72 464.97 512 369.08 512 256.02 512 114.62 397.37 0 256 0z" fill="currentColor" fill-rule="evenodd"/>
                        </svg>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-2" href="https://twitter.com/getbootstrap" target="_blank" rel="noopener" aria-label="Twitter">
                        <svg class="navbar-nav-svg" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 512 416.32" focusable="false">
                            <title>Twitter</title>
                            <path d="M160.83 416.32c193.2 0 298.92-160.22 298.92-298.92 0-4.51 0-9-.2-13.52A214 214 0 0 0 512 49.38a212.93 212.93 0 0 1-60.44 16.6 105.7 105.7 0 0 0 46.3-58.19 209 209 0 0 1-66.79 25.37 105.09 105.09 0 0 0-181.73 71.91 116.12 116.12 0 0 0 2.66 24c-87.28-4.3-164.73-46.3-216.56-109.82A105.48 105.48 0 0 0 68 159.6a106.27 106.27 0 0 1-47.53-13.11v1.43a105.28 105.28 0 0 0 84.21 103.06 105.67 105.67 0 0 1-47.33 1.84 105.06 105.06 0 0 0 98.14 72.94A210.72 210.72 0 0 1 25 370.84a202.17 202.17 0 0 1-25-1.43 298.85 298.85 0 0 0 160.83 46.92" fill="currentColor"/>
                        </svg>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link p-2" href="https://bootstrap-slack.herokuapp.com" target="_blank" rel="noopener" aria-label="Slack">
                        <svg class="navbar-nav-svg" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 512 512" focusable="false">
                            <title>Slack</title>
                            <path fill="currentColor" d="M210.787 234.832l68.31-22.883 22.1 65.977-68.309 22.882z"/>
                            <path d="M490.54 185.6C437.7 9.59 361.6-31.34 185.6 21.46S-31.3 150.4 21.46 326.4 150.4 543.3 326.4 490.54 543.34 361.6 490.54 185.6zM401.7 299.8l-33.15 11.05 11.46 34.38c4.5 13.92-2.87 29.06-16.78 33.56-2.87.82-6.14 1.64-9 1.23a27.32 27.32 0 0 1-24.56-18l-11.46-34.38-68.36 22.92 11.46 34.38c4.5 13.92-2.87 29.06-16.78 33.56-2.87.82-6.14 1.64-9 1.23a27.32 27.32 0 0 1-24.56-18l-11.46-34.43-33.15 11.05c-2.87.82-6.14 1.64-9 1.23a27.32 27.32 0 0 1-24.56-18c-4.5-13.92 2.87-29.06 16.78-33.56l33.12-11.03-22.1-65.9-33.15 11.05c-2.87.82-6.14 1.64-9 1.23a27.32 27.32 0 0 1-24.56-18c-4.48-13.93 2.89-29.07 16.81-33.58l33.15-11.05-11.46-34.38c-4.5-13.92 2.87-29.06 16.78-33.56s29.06 2.87 33.56 16.78l11.46 34.38 68.36-22.92-11.46-34.38c-4.5-13.92 2.87-29.06 16.78-33.56s29.06 2.87 33.56 16.78l11.47 34.42 33.15-11.05c13.92-4.5 29.06 2.87 33.56 16.78s-2.87 29.06-16.78 33.56L329.7 194.6l22.1 65.9 33.15-11.05c13.92-4.5 29.06 2.87 33.56 16.78s-2.88 29.07-16.81 33.57z" fill="currentColor"/>
                        </svg>
                    </a>
                </li>
            </ul>
        </header>

    <div class="container-fluid">
            <div class="row flex-xl-nowrap" id="app">
                <div class="col-xl-2 bd-sidebar">
                    <div class="bd-search d-flex align-items-center">
                        Menu
                    </div>
                    <nav class="collapse bd-links" id="bd-docs-nav">
                        <div class="bd-toc-item active">
                            <a class="bd-toc-link" href="/docs/4.0/getting-started/introduction/">
                                Documentos
                            </a>
                            <ul class="nav bd-sidenav">
                                <li class="">
                                    <a href="/docs/4.0/getting-started/introduction/">
                                        Resumen Diario
                                    </a>
                                </li>
                                <li class="">
                                    <a href="/docs/4.0/getting-started/download/">
                                        Resumen por Mes
                                    </a>
                                </li>                                                               
                            </ul>
                        </div>
                        <div class="bd-toc-item ">
                            <a class="bd-toc-link" href="/docs/4.0/layout/overview/">
                                Sunat link
                            </a>                            
                        </div>                        
                        <div class="bd-toc-item ">
                            <a class="bd-toc-link" href="/docs/4.0/extend/icons/">Manual</a>                            
                        </div>                        
                    </nav>
                </div>
                <main class="col-10 py-md-3 pl-md-5">

    <p>
        <h2 id="titulo_doc">Documentos
            <small>{{fecha}}</small>
        </h2>
    </p>
    <div class="row" id="form_doc">
        <div class="col-10">
            <form @submit.prevent="getDocuments">
                <div class="form-row">
                    <div class="form-group col-md-3 input-group">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="date" class="form-control" name="fecha" v-model="fecha">
                    </div>
                    <div class="form-group col-md-1">
                        <button type="submit" class="btn btn-dark " style="background-color: #563d7c;"><i class="fa fa-search"></i> Bus</button>
                    </div>
                    <div class="form-group col-md-1">
                        <input type="text" class="form-control" placeholder="Nro">
                    </div>
                    <div class="form-group col-md-1">
                        <input type="text" class="form-control" placeholder="Serie">
                    </div>
                    <div class="form-group col-md-2">
                        <input type="text" v-model="search" class="form-control" placeholder="Cliente">
                    </div>
                    <div class="form-group col-md-1">
                        <input type="text" class="form-control" placeholder="OT">
                    </div>
                </div>
            </form>
        </div>
        <div class="col-2 text-right">
            <a href="#" class="btn btn-dark" style="background-color: #563d7c;"><i class="fa fa-envelope-open-o"></i>
                Mes</a>
            <a href="#" class="btn btn-dark" style="background-color: #563d7c;"><i class="fa fa-envelope-open-o"></i>
                Dia</a>
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
            <tr v-for="document in filteredDocuments">
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
        <hr style="border: none; height: 1px; background-color: #414141; margin-top: 30px;">
        <span style="text-align: center; font-size: 11px; line-height: 15px;">Representación Impresa de la Factura Electrónica. SURMOTRIZ S.R.L. Autorizado para ser Emisor electrónico mediante Resolución de Intendencia N° 112-005-0000143/SUNAT Para consultar el comprobante ingresar a : http://www.surmotriz.com/sunat/consulta.php</span>
    </div>
    </main>
</div>
</div>




<script>
    // funcion para agregar cero al mes
    function pad (n, length) {
        var  n = n.toString();
        while(n.length < length)
            n = "0" + n;
        return n;
    }
    var f = new Date();
    var ff = f.getFullYear() + "-" + pad((f.getMonth() +1),2) + "-" + pad(f.getDate(),2);
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
            loadingFactura: false,
            fecha: this.ff,
            search: ''
        },
        methods: {
            getDocuments: function () {
                this.loading = true;
                this.$http.get('./apis/index.php?fecha='+this.fecha).then(function (response) {
                    this.loading = false;
                    this.documents = response.data;
                });
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
        },
        computed: {
            filteredDocuments: function () {
                return this.documents.filter((document)=>{
                    return document.cliente.match(this.search);
            });
            }
        }

    })
</script>


</body>
</html>