<?php

    $hace = $_GET['h'];
    $gen = $_GET['gen'];
    $emp = $_GET['emp'];

    if ($hace == 0){
        //$dia = date('d-m-Y');
        $dia = '2017-04-13';
    }elseif ($hace == 1){
        $fecha = date('Y-m-d');
        //$dia = date("d-m-Y", strtotime("$fecha -1 day"));
        $dia = '2017-04-10';
    }elseif ($hace == 2){
        $fecha = date('Y-m-d');
        //$dia = date("d-m-Y", strtotime("$fecha -2 day"));
        $dia = '2017-04-10';
    }elseif ($hace == 3){
        $fecha = date('Y-m-d');
        //$dia = date("d-m-Y", strtotime("$fecha -3 day"));
        $dia = '2017-04-10';
    }elseif ($hace == 4){
        $fecha = date('Y-m-d');
        //$dia = date("d-m-Y", strtotime("$fecha -4 day"));
        $dia = '2017-04-10';
    }elseif ($hace == 5){
        $fecha = date('Y-m-d');
        //$dia = date("d-m-Y", strtotime("$fecha -5 day"));
        $dia = '2017-04-10';
    }elseif ($hace == 6){
        $fecha = date('Y-m-d');
        //$dia = date("d-m-Y", strtotime("$fecha -6 day"));
        $dia = '2017-04-10';
    }elseif ($hace == 7){
        $fecha = date('Y-m-d');
        //$dia = date("d-m-Y", strtotime("$fecha -7 day"));
        $dia = '2017-04-10';
    }


require("app/coneccion.php");

$sql_boletas = oci_parse($conn, "select * from cab_doc_gen where cdg_tip_doc ='B' and to_char(cdg_fec_gen,'yyyy-mm-dd') = '".$dia."' and cdg_anu_sn !='S' and cdg_doc_anu !='S' and cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."'  order by cdg_tip_doc, cdg_fec_gen Asc"); oci_execute($sql_boletas);
while($res_boletas = oci_fetch_array($sql_boletas)){ $boletas[] = $res_boletas; }

$sql_notas = oci_parse($conn, "select * from cab_doc_gen where cdg_tip_doc ='A' and cdg_tip_ref in ('BR','BS') and to_char(cdg_fec_gen,'yyyy-mm-dd') = '".$dia."' and cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."' order by cdg_tip_doc, cdg_fec_gen ASC"); oci_execute($sql_notas);
while($res_notas = oci_fetch_array($sql_notas)){ $notas[] = $res_notas; }

if ($emp == '01')
{
    $serie_boleta = 'B001';
    $serie_nota = 'BN03';
} else
{
    $serie_boleta = 'B004';
    $serie_nota = 'BN04';
}

$ant = 0;
$i = 0;
$h = 0;

if (isset($boletas)){
    foreach ( $boletas as $boleta ){
        if ($i==0){
            $bols[$h][0] = $boleta['CDG_NUM_DOC'];
            $ant = $boleta['CDG_NUM_DOC'];
            $i++;
        }else {
            if (($ant+1) == $boleta['CDG_NUM_DOC']){
                if ($boleta['CDG_NUM_DOC'] == $boletas[count($boletas)-1]['CDG_NUM_DOC']){
                    if (($ant+2) == $boleta['CDG_NUM_DOC']){
                        $bols[$h][1] = $ant;
                        $bols[$h][2] = $serie_boleta;
                        $h++;
                        $bols[$h][0] = $boleta['CDG_NUM_DOC'];
                        $bols[$h][1] = $boleta['CDG_NUM_DOC'];
                        $bols[$h][2] = $serie_boleta;
                    }else {
                        $bols[$h][1] = $boleta['CDG_NUM_DOC'];
                        $bols[$h][2] = $serie_boleta;
                    }
                    $h++;
                    $i=0;
                }else {
                    $ant = $boleta['CDG_NUM_DOC'];
                }
            }else {
                $bols[$h][1] = $ant;
                $bols[$h][2] = $serie_boleta;
                $h++;
                $bols[$h][0] = $boleta['CDG_NUM_DOC'];
                $ant = $boleta['CDG_NUM_DOC'];
                if ($boleta['CDG_NUM_DOC'] == $boletas[count($boletas)-1]['CDG_NUM_DOC']){
                    $bols[$h][1] = $boleta['CDG_NUM_DOC'];
                    $bols[$h][2] = $serie_boleta;
                }

            }
        }
    }
}

$ant = 0;
$i = 0;
$h = 0;
if (isset($notas)){
    foreach ( $notas as $nota ){
        if ($i==0){
            $nots[$h][0] = $nota['CDG_NUM_DOC'];
            $ant = $nota['CDG_NUM_DOC'];
            $i++;
        }else {
            if (($ant+1) == $nota['CDG_NUM_DOC']){
                if ($nota['CDG_NUM_DOC'] == $notas[count($notas)-1]['CDG_NUM_DOC']){
                    if (($ant+2) == $nota['CDG_NUM_DOC']){
                        $nots[$h][1] = $ant;
                        $nots[$h][2] = $serie_nota;
                        $h++;
                        $nots[$h][0] = $nota['CDG_NUM_DOC'];
                        $nots[$h][1] = $nota['CDG_NUM_DOC'];
                        $nots[$h][2] = $serie_nota;
                    }else {
                        $nots[$h][1] = $nota['CDG_NUM_DOC'];
                        $nots[$h][2] = $serie_nota;
                    }
                    $h++;
                    $i=0;
                }else {
                    $ant = $nota['CDG_NUM_DOC'];
                }
            }else {
                $nots[$h][1] = $ant;
                $nots[$h][2] = $serie_nota;
                $h++;
                $nots[$h][0] = $nota['CDG_NUM_DOC'];
                $ant = $nota['CDG_NUM_DOC'];
                if ($nota['CDG_NUM_DOC'] == $notas[count($notas)-1]['CDG_NUM_DOC']){
                    $nots[$h][1] = $nota['CDG_NUM_DOC'];
                    $nots[$h][2] = $serie_nota;
                }

            }
        }
    }
}


$ruta = './app/resumenes/'.date('Y').'/'.date('m').'/'.date('d').'/';

if (!file_exists($ruta)) { mkdir($ruta, 0777, true); }
$i=1;
while(file_exists($ruta.'20532710066-RC-'.date('Ymd').'-'.$i.'.xml')){
    $i++;
    // el valor de i es el actual que se va crear
}






// creacion del xml
$xml = new DomDocument('1.0', 'ISO-8859-1');
$xml->standalone         = false;
$xml->preserveWhiteSpace = false;
$Invoice = $xml->createElement('SummaryDocuments'); $Invoice = $xml->appendChild($Invoice);
$Invoice->setAttribute('xmlns',"urn:sunat:names:specification:ubl:peru:schema:xsd:SummaryDocuments-1");
$Invoice->setAttribute('xmlns:cac',"urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2");
$Invoice->setAttribute('xmlns:cbc',"urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2");
$Invoice->setAttribute('xmlns:ds',"http://www.w3.org/2000/09/xmldsig#");
$Invoice->setAttribute('xmlns:ext',"urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2");
$Invoice->setAttribute('xmlns:sac',"urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1");
$Invoice->setAttribute('xmlns:xsi',"http://www.w3.org/2001/XMLSchema-instance");
//$Invoice->setAttribute('xsi:schemaLocation','urn:sunat:names:specification:ubl:peru:schema:xsd:InvoiceSummary-1 D:\UBL_SUNAT\SUNAT_xml_20110112\20110112\xsd\maindoc\UBLPE-InvoiceSummary-1.0.xsd');
    $UBLExtension = $xml->createElement('ext:UBLExtensions'); $UBLExtension = $Invoice->appendChild($UBLExtension);
        $ext = $xml->createElement('ext:UBLExtension'); $ext = $UBLExtension->appendChild($ext);
            $contents = $xml->createElement('ext:ExtensionContent'); $contents = $ext->appendChild($contents);

$cbc = $xml->createElement('cbc:UBLVersionID', '2.0'); $cbc = $Invoice->appendChild($cbc);
$cbc = $xml->createElement('cbc:CustomizationID', '1.0'); $cbc = $Invoice->appendChild($cbc);
$cbc = $xml->createElement('cbc:ID', 'RC-'.date('Ymd').'-'.$i); $cbc = $Invoice->appendChild($cbc);
$cbc = $xml->createElement('cbc:ReferenceDate', '2017-06-18'); $cbc = $Invoice->appendChild($cbc);
$cbc = $xml->createElement('cbc:IssueDate', date('Y-m-d')); $cbc = $Invoice->appendChild($cbc);

// signature
$cac_signature = $xml->createElement('cac:Signature'); $cac_signature = $Invoice->appendChild($cac_signature);
    $cbc = $xml->createElement('cbc:ID', 'IDSignKG'); $cbc = $cac_signature->appendChild($cbc);
    $cac_signatory = $xml->createElement('cac:SignatoryParty'); $cac_signatory = $cac_signature->appendChild($cac_signatory);
    $cac = $xml->createElement('cac:PartyIdentification'); $cac = $cac_signatory->appendChild($cac);
        $cbc = $xml->createElement('cbc:ID', '20532710066'); $cbc = $cac->appendChild($cbc);
    $cac = $xml->createElement('cac:PartyName'); $cac = $cac_signatory->appendChild($cac);
        $cbc = $xml->createElement('cbc:Name', 'DESARROLLO DE SISTEMAS INTEGRADOS DE GESTION'); $cbc = $cac->appendChild($cbc);
    $cac_digital = $xml->createElement('cac:DigitalSignatureAttachment'); $cac_digital = $cac_signature->appendChild($cac_digital);
        $cac = $xml->createElement('cac:ExternalReference'); $cac = $cac_digital->appendChild($cac);
            $cbc = $xml->createElement('cbc:URI', '#signatureKG'); $cbc = $cac->appendChild($cbc);


// Datos Surmotriz
$cac_accounting = $xml->createElement('cac:AccountingSupplierParty'); $cac_accounting = $Invoice->appendChild($cac_accounting);
    $cbc = $xml->createElement('cbc:CustomerAssignedAccountID', '20532710066'); $cbc = $cac_accounting->appendChild($cbc);
    $cbc = $xml->createElement('cbc:AdditionalAccountID', '6'); $cbc = $cac_accounting->appendChild($cbc);
    $cac_party = $xml->createElement('cac:Party'); $cac_party = $cac_accounting->appendChild($cac_party);
        $cac = $xml->createElement('cac:PartyName'); $cac = $cac_party->appendChild($cac);
            $cbc = $xml->createElement('cbc:Name', 'TOYOTA SURMOTRIZ'); $cbc = $cac->appendChild($cbc);
        $legal = $xml->createElement('cac:PartyLegalEntity'); $legal = $cac_party->appendChild($legal);
            $cbc = $xml->createElement('cbc:RegistrationName', 'SURMOTRIZ S.R.L.'); $cbc = $legal->appendChild($cbc);


$line = 1;
// Boletas
if (isset($bols))
{
    foreach ($bols as $bol)
    {
        $grabadas = 0;
        $igv = 0;
        $total = 0;
        $sql_rboletas = oci_parse($conn, "select * from cab_doc_gen where cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."' and cdg_num_doc between ".$bol[0]." and ".$bol[1]." and cdg_tip_doc='B' order by cdg_fec_gen ASC"); oci_execute($sql_rboletas);
        while($res_rboletas = oci_fetch_array($sql_rboletas))
        {
            $grabadas = $grabadas + ($res_rboletas['CDG_VVP_TOT'] - $res_rboletas['CDG_DES_TOT']);
            $igv = $igv + $res_rboletas['CDG_IGV_TOT'];
            $total = $total + $res_rboletas['CDG_IMP_NETO'];
        }
        $SummaryDocumentsLine = $xml->createElement('sac:SummaryDocumentsLine'); $SummaryDocumentsLine = $Invoice->appendChild($SummaryDocumentsLine);
            $cbc = $xml->createElement('cbc:LineID',$line); $cbc = $SummaryDocumentsLine->appendChild($cbc);
            $cbc = $xml->createElement('cbc:DocumentTypeCode','03'); $cbc = $SummaryDocumentsLine->appendChild($cbc);
            $sac = $xml->createElement('sac:DocumentSerialID',$serie_boleta); $sac = $SummaryDocumentsLine->appendChild($sac);
            $sac = $xml->createElement('sac:StartDocumentNumberID',$bol[0]); $sac = $SummaryDocumentsLine->appendChild($sac);
            $sac = $xml->createElement('sac:EndDocumentNumberID',$bol[1]); $sac = $SummaryDocumentsLine->appendChild($sac);
            $sac = $xml->createElement('sac:TotalAmount',$total); $sac = $SummaryDocumentsLine->appendChild($sac); $sac->setAttribute('currencyID',"PEN");

            // Gravado
            $sac = $xml->createElement('sac:BillingPayment'); $sac = $SummaryDocumentsLine->appendChild($sac);
                $cbc = $xml->createElement('cbc:PaidAmount',$grabadas); $cbc = $sac->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
                $cbc = $xml->createElement('cbc:InstructionID','01'); $cbc = $sac->appendChild($cbc);
            // Exonerado
            $sac = $xml->createElement('sac:BillingPayment'); $sac = $SummaryDocumentsLine->appendChild($sac);
                $cbc = $xml->createElement('cbc:PaidAmount','0.00'); $cbc = $sac->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
                $cbc = $xml->createElement('cbc:InstructionID','02'); $cbc = $sac->appendChild($cbc);
            // Inafecto
            $sac = $xml->createElement('sac:BillingPayment'); $sac = $SummaryDocumentsLine->appendChild($sac);
                $cbc = $xml->createElement('cbc:PaidAmount','0.00'); $cbc = $sac->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
                $cbc = $xml->createElement('cbc:InstructionID','03'); $cbc = $sac->appendChild($cbc);
            // otros cargos
            $cac = $xml->createElement('cac:AllowanceCharge'); $cac = $SummaryDocumentsLine->appendChild($cac);
                $cbc = $xml->createElement('cbc:ChargeIndicator','true'); $cbc = $cac->appendChild($cbc);
                $cbc = $xml->createElement('cbc:Amount','0.00'); $cbc = $cac->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
            // Total ISC
            $TaxTotal = $xml->createElement('cac:TaxTotal'); $TaxTotal =  $SummaryDocumentsLine->appendChild($TaxTotal);
                $cbc = $xml->createElement('cbc:TaxAmount','0.00'); $cbc = $TaxTotal->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
                $cac = $xml->createElement('cac:TaxSubtotal'); $cac = $TaxTotal->appendChild($cac);
                    $cbc = $xml->createElement('cbc:TaxAmount','0.00'); $cbc = $cac->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
                    $TaxCategory = $xml->createElement('cac:TaxCategory'); $TaxCategory = $cac->appendChild($TaxCategory);
                        $TaxScheme = $xml->createElement('cac:TaxScheme'); $TaxScheme = $TaxCategory->appendChild($TaxScheme);
                            $cbc = $xml->createElement('cbc:ID','2000'); $cbc = $TaxScheme->appendChild($cbc);
                            $cbc = $xml->createElement('cbc:Name','ISC'); $cbc = $TaxScheme->appendChild($cbc);
                            $cbc = $xml->createElement('cbc:TaxTypeCode','EXC'); $cbc = $TaxScheme->appendChild($cbc);
            // Total IGV
            $TaxTotal = $xml->createElement('cac:TaxTotal'); $TaxTotal =  $SummaryDocumentsLine->appendChild($TaxTotal);
                $cbc = $xml->createElement('cbc:TaxAmount',$igv); $cbc = $TaxTotal->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
                $cac = $xml->createElement('cac:TaxSubtotal'); $cac = $TaxTotal->appendChild($cac);
                    $cbc = $xml->createElement('cbc:TaxAmount',$igv); $cbc = $cac->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
                    $TaxCategory = $xml->createElement('cac:TaxCategory'); $TaxCategory = $cac->appendChild($TaxCategory);
                        $TaxScheme = $xml->createElement('cac:TaxScheme'); $TaxScheme = $TaxCategory->appendChild($TaxScheme);
                            $cbc = $xml->createElement('cbc:ID','1000'); $cbc = $TaxScheme->appendChild($cbc);
                            $cbc = $xml->createElement('cbc:Name','IGV'); $cbc = $TaxScheme->appendChild($cbc);
                            $cbc = $xml->createElement('cbc:TaxTypeCode','VAT'); $cbc = $TaxScheme->appendChild($cbc);
            // Total Otros tributos
            $TaxTotal = $xml->createElement('cac:TaxTotal'); $TaxTotal =  $SummaryDocumentsLine->appendChild($TaxTotal);
                $cbc = $xml->createElement('cbc:TaxAmount','0.00'); $cbc = $TaxTotal->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
                $cac = $xml->createElement('cac:TaxSubtotal'); $cac = $TaxTotal->appendChild($cac);
                    $cbc = $xml->createElement('cbc:TaxAmount','0.00'); $cbc = $cac->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
                    $TaxCategory = $xml->createElement('cac:TaxCategory'); $TaxCategory = $cac->appendChild($TaxCategory);
                        $TaxScheme = $xml->createElement('cac:TaxScheme'); $TaxScheme = $TaxCategory->appendChild($TaxScheme);
                            $cbc = $xml->createElement('cbc:ID','9999'); $cbc = $TaxScheme->appendChild($cbc);
                            $cbc = $xml->createElement('cbc:Name','OTROS'); $cbc = $TaxScheme->appendChild($cbc);
                            $cbc = $xml->createElement('cbc:TaxTypeCode','OTH'); $cbc = $TaxScheme->appendChild($cbc);
        $line++;
    }
}

// Notas
if (isset($nots))
{
    foreach ($nots as $not)
    {
        $grabadas = 0;
        $igv = 0;
        $total = 0;
        $sql_rboletas = oci_parse($conn, "select * from cab_doc_gen where cdg_cod_gen='".$gen."' and cdg_cod_emp='".$emp."' and cdg_num_doc between ".$not[0]." and ".$not[1]." and cdg_tip_doc='A' order by cdg_fec_gen ASC"); oci_execute($sql_rboletas);
        while($res_rboletas = oci_fetch_array($sql_rboletas))
        {
            $grabadas = $grabadas + ($res_rboletas['CDG_VVP_TOT'] - $res_rboletas['CDG_DES_TOT']);
            $igv = $igv + $res_rboletas['CDG_IGV_TOT'];
            $total = $total + $res_rboletas['CDG_IMP_NETO'];
        }
        $SummaryDocumentsLine = $xml->createElement('sac:SummaryDocumentsLine'); $SummaryDocumentsLine = $Invoice->appendChild($SummaryDocumentsLine);
            $cbc = $xml->createElement('cbc:LineID',$line); $cbc = $SummaryDocumentsLine->appendChild($cbc);
            $cbc = $xml->createElement('cbc:DocumentTypeCode','07'); $cbc = $SummaryDocumentsLine->appendChild($cbc);
            $sac = $xml->createElement('sac:DocumentSerialID',$serie_nota); $sac = $SummaryDocumentsLine->appendChild($sac);
            $sac = $xml->createElement('sac:StartDocumentNumberID',$not[0]); $sac = $SummaryDocumentsLine->appendChild($sac);
            $sac = $xml->createElement('sac:EndDocumentNumberID',$not[1]); $sac = $SummaryDocumentsLine->appendChild($sac);
            $sac = $xml->createElement('sac:TotalAmount',$total); $sac = $SummaryDocumentsLine->appendChild($sac); $sac->setAttribute('currencyID',"PEN");

            // Gravado
            $sac = $xml->createElement('sac:BillingPayment'); $sac = $SummaryDocumentsLine->appendChild($sac);
                $cbc = $xml->createElement('cbc:PaidAmount',$grabadas); $cbc = $sac->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
                $cbc = $xml->createElement('cbc:InstructionID','01'); $cbc = $sac->appendChild($cbc);
            // Exonerado
            $sac = $xml->createElement('sac:BillingPayment'); $sac = $SummaryDocumentsLine->appendChild($sac);
                $cbc = $xml->createElement('cbc:PaidAmount','0.00'); $cbc = $sac->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
                $cbc = $xml->createElement('cbc:InstructionID','02'); $cbc = $sac->appendChild($cbc);
            // Inafecto
            $sac = $xml->createElement('sac:BillingPayment'); $sac = $SummaryDocumentsLine->appendChild($sac);
                $cbc = $xml->createElement('cbc:PaidAmount','0.00'); $cbc = $sac->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
                $cbc = $xml->createElement('cbc:InstructionID','03'); $cbc = $sac->appendChild($cbc);
            // otros cargos
            $cac = $xml->createElement('cac:AllowanceCharge'); $cac = $SummaryDocumentsLine->appendChild($cac);
                $cbc = $xml->createElement('cbc:ChargeIndicator','true'); $cbc = $cac->appendChild($cbc);
                $cbc = $xml->createElement('cbc:Amount','0.00'); $cbc = $cac->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
            // Total ISC
            $TaxTotal = $xml->createElement('cac:TaxTotal'); $TaxTotal =  $SummaryDocumentsLine->appendChild($TaxTotal);
                $cbc = $xml->createElement('cbc:TaxAmount','0.00'); $cbc = $TaxTotal->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
                $cac = $xml->createElement('cac:TaxSubtotal'); $cac = $TaxTotal->appendChild($cac);
                    $cbc = $xml->createElement('cbc:TaxAmount','0.00'); $cbc = $cac->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
                    $TaxCategory = $xml->createElement('cac:TaxCategory'); $TaxCategory = $cac->appendChild($TaxCategory);
                        $TaxScheme = $xml->createElement('cac:TaxScheme'); $TaxScheme = $TaxCategory->appendChild($TaxScheme);
                            $cbc = $xml->createElement('cbc:ID','2000'); $cbc = $TaxScheme->appendChild($cbc);
                            $cbc = $xml->createElement('cbc:Name','ISC'); $cbc = $TaxScheme->appendChild($cbc);
                            $cbc = $xml->createElement('cbc:TaxTypeCode','EXC'); $cbc = $TaxScheme->appendChild($cbc);
            // Total IGV
            $TaxTotal = $xml->createElement('cac:TaxTotal'); $TaxTotal =  $SummaryDocumentsLine->appendChild($TaxTotal);
                $cbc = $xml->createElement('cbc:TaxAmount',$igv); $cbc = $TaxTotal->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
                $cac = $xml->createElement('cac:TaxSubtotal'); $cac = $TaxTotal->appendChild($cac);
                    $cbc = $xml->createElement('cbc:TaxAmount',$igv); $cbc = $cac->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
                    $TaxCategory = $xml->createElement('cac:TaxCategory'); $TaxCategory = $cac->appendChild($TaxCategory);
                        $TaxScheme = $xml->createElement('cac:TaxScheme'); $TaxScheme = $TaxCategory->appendChild($TaxScheme);
                            $cbc = $xml->createElement('cbc:ID','1000'); $cbc = $TaxScheme->appendChild($cbc);
                            $cbc = $xml->createElement('cbc:Name','IGV'); $cbc = $TaxScheme->appendChild($cbc);
                            $cbc = $xml->createElement('cbc:TaxTypeCode','VAT'); $cbc = $TaxScheme->appendChild($cbc);
            // Total Otros tributos
            $TaxTotal = $xml->createElement('cac:TaxTotal'); $TaxTotal =  $SummaryDocumentsLine->appendChild($TaxTotal);
                $cbc = $xml->createElement('cbc:TaxAmount','0.00'); $cbc = $TaxTotal->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
                $cac = $xml->createElement('cac:TaxSubtotal'); $cac = $TaxTotal->appendChild($cac);
                    $cbc = $xml->createElement('cbc:TaxAmount','0.00'); $cbc = $cac->appendChild($cbc); $cbc->setAttribute('currencyID',"PEN");
                    $TaxCategory = $xml->createElement('cac:TaxCategory'); $TaxCategory = $cac->appendChild($TaxCategory);
                        $TaxScheme = $xml->createElement('cac:TaxScheme'); $TaxScheme = $TaxCategory->appendChild($TaxScheme);
                            $cbc = $xml->createElement('cbc:ID','9999'); $cbc = $TaxScheme->appendChild($cbc);
                            $cbc = $xml->createElement('cbc:Name','OTROS'); $cbc = $TaxScheme->appendChild($cbc);
                            $cbc = $xml->createElement('cbc:TaxTypeCode','OTH'); $cbc = $TaxScheme->appendChild($cbc);
        $line++;
    }
}

$xml->formatOutput = true;
$strings_xml = $xml->saveXML();
$xml->save($ruta.'20532710066-RC-'.date('Ymd').'-'.($i).'.xml');
// end xml



// 2.- Firmar documento xml
// ========================
require './robrichards/src/xmlseclibs.php';
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;
// Cargar el XML a firmar
$doc = new DOMDocument();
$doc->load($ruta.'20532710066-RC-'.date('Ymd').'-'.($i).'.xml');
// Crear un nuevo objeto de seguridad
$objDSig = new XMLSecurityDSig();
// Utilizar la canonización exclusiva de c14n
$objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);
// Firmar con SHA-256
$objDSig->addReference(
    $doc,
    XMLSecurityDSig::SHA1,
    array('http://www.w3.org/2000/09/xmldsig#enveloped-signature'),
    array('force_uri' => true)
);
//Crear una nueva clave de seguridad (privada)
$objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type' => 'private'));
//Cargamos la clave privada
$objKey->loadKey('./archivos_pem/private_key.pem', true);
$objDSig->sign($objKey);
// Agregue la clave pública asociada a la firma
$objDSig->add509Cert(file_get_contents('./archivos_pem/public_key.pem'), true, false, array('subjectName' => true)); // array('issuerSerial' => true, 'subjectName' => true));
// Anexar la firma al XML
$objDSig->appendSignature($doc->getElementsByTagName('ExtensionContent')->item(0));
// Guardar el XML firmado
$doc->save($ruta.'20532710066-RC-'.date('Ymd').'-'.($i).'.xml');
chmod($ruta.'20532710066-RC-'.date('Ymd').'-'.($i).'.xml', 0777);
// 3.- Enviar documento xml y obtener respuesta
// ============================================
require('./lib/pclzip.lib.php'); // Librería que comprime archivos en .ZIP
## Creación del archivo .ZIP
$zip = new PclZip($ruta.'20532710066-RC-'.date('Ymd').'-'.($i).'.zip');
$zip->create($ruta.'20532710066-RC-'.date('Ymd').'-'.($i).'.xml',PCLZIP_OPT_REMOVE_ALL_PATH);
chmod($ruta.'20532710066-RC-'.date('Ymd').'-'.($i).'.zip', 0777);



// Envio cliente

# Procedimiento para enviar comprobante a la SUNAT
class feedSoap extends SoapClient{
    public $XMLStr = "";
    public function setXMLStr($value){
        $this->XMLStr = $value;
    }
    public function getXMLStr(){
        return $this->XMLStr;
    }
    public function __doRequest($request, $location, $action, $version, $one_way = 0){
        $request = $this->XMLStr;
        $dom = new DOMDocument('1.0');
        try{
            $dom->loadXML($request);
        } catch (DOMException $e) {
            die($e->code);
        }
        $request = $dom->saveXML();
        //Solicitud
        return parent::__doRequest($request, $location, $action, $version, $one_way = 0);
    }
    public function SoapClientCall($SOAPXML){
        return $this->setXMLStr($SOAPXML);
    }
}
function soapCall($wsdlURL, $callFunction = "", $XMLString){
    $client = new feedSoap($wsdlURL, array('trace' => true));
    $reply  = $client->SoapClientCall($XMLString);
    //echo "REQUEST:\n" . $client->__getFunctions() . "\n";
    $client->__call("$callFunction", array(), array());
    //$request = prettyXml($client->__getLastRequest());
    //echo highlight_string($request, true) . "<br/>\n";
    return $client->__getLastResponse();
    //print_r($client);
}
//URL para enviar las solicitudes a SUNAT
$wsdlURL = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';
//Estructura del XML para la conexión

$XMLString = '<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope 
xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
xmlns:ser="http://service.sunat.gob.pe" 
xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
<soapenv:Header>
<wsse:Security>
<wsse:UsernameToken>
<wsse:Username>20532710066MODDATOS</wsse:Username>
<wsse:Password>MODDATOS</wsse:Password>
</wsse:UsernameToken>
</wsse:Security>
</soapenv:Header>
<soapenv:Body>
<ser:sendSummary>
<fileName>'.'20532710066-RC-'.date('Ymd').'-'.($i).'.zip</fileName>
<contentFile>' . base64_encode(file_get_contents($ruta.'20532710066-RC-'.date('Ymd').'-'.($i).'.zip')) . '</contentFile>
</ser:sendSummary>
</soapenv:Body>
</soapenv:Envelope>';

//echo $XMLString;
//Realizamos la llamada a nuestra función
$result = soapCall($wsdlURL, $callFunction = "sendBill", $XMLString);
echo $result;


?>