<?php

// 1.- crear documento XML
    $xml = new DomDocument('1.0', 'ISO-8859-1');
    $xml->standalone         = false;
    $xml->preserveWhiteSpace = false;
    $Invoice = $xml->createElement('Invoice');
    $Invoice = $xml->appendChild($Invoice);
    // Set the attributes.
    $Invoice->setAttribute('xmlns', 'urn:oasis:names:specification:ubl:schema:xsd:Invoice-2');
    $Invoice->setAttribute('xmlns:cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
    $Invoice->setAttribute('xmlns:cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
    $Invoice->setAttribute('xmlns:ccts', "urn:un:unece:uncefact:documentation:2");
    $Invoice->setAttribute('xmlns:ds', "http://www.w3.org/2000/09/xmldsig#");
    $Invoice->setAttribute('xmlns:ext', "urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2");
    $Invoice->setAttribute('xmlns:qdt', "urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2");
    $Invoice->setAttribute('xmlns:sac', "urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1");
    //$Invoice->setAttribute('xmlns:schemaLocation', "urn:oasis:names:specification:ubl:schema:xsd:Invoice-2 ../xsd/maindoc/UBLPE-Invoice-1.0.xsd");
    $Invoice->setAttribute('xmlns:udt', "urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2");

    $UBLExtension = $xml->createElement('ext:UBLExtensions'); $UBLExtension = $Invoice->appendChild($UBLExtension);

        // 18.- Total valor de venta - operaciones gravadas
        // 19.- Total valor de venta - operaciones inafectas
        // 20.- Total valor de venta - operaciones exoneradas
        // 49.- Total valor de venta - operaciones gratuitass
        $ext = $xml->createElement('ext:UBLExtension'); $ext = $UBLExtension->appendChild($ext);
            $contents = $xml->createElement('ext:ExtensionContent'); $contents = $ext->appendChild($contents);
                $sac = $xml->createElement('sac:AdditionalInformation'); $sac = $contents->appendChild($sac);
                    // el 2005 es Total descuentos
                    $monetary = $xml->createElement('sac:AdditionalMonetaryTotal'); $monetary = $sac->appendChild($monetary);
                        $cbc = $xml->createElement('cbc:ID', '2005'); $cbc = $monetary->appendChild($cbc);
                        $cbc = $xml->createElement('cbc:PayableAmount', $c34); $cbc = $monetary->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                    // el 1001 total velor venta - operaciones gravadas
                    $monetary = $xml->createElement('sac:AdditionalMonetaryTotal'); $monetary = $sac->appendChild($monetary);
                        $cbc = $xml->createElement('cbc:ID', '1001'); $cbc = $monetary->appendChild($cbc);
                        $cbc = $xml->createElement('cbc:PayableAmount', $c31); $cbc = $monetary->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                    // el 1002 total valor venta - operaciones inafectas
                    $monetary = $xml->createElement('sac:AdditionalMonetaryTotal'); $monetary = $sac->appendChild($monetary);
                        $cbc = $xml->createElement('cbc:ID', '1002'); $cbc = $monetary->appendChild($cbc);
                        $cbc = $xml->createElement('cbc:PayableAmount', '0.00'); $cbc = $monetary->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                    // el 1003 total valor venta - operaciones exoneradas
                    $monetary = $xml->createElement('sac:AdditionalMonetaryTotal'); $monetary = $sac->appendChild($monetary);
                        $cbc = $xml->createElement('cbc:ID', '1003'); $cbc = $monetary->appendChild($cbc);
                        $cbc = $xml->createElement('cbc:PayableAmount', '0.00'); $cbc = $monetary->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                    //31.- leyendas - esta en el catalogo 15 sunat
                    $aditional = $xml->createElement('sac:AdditionalProperty'); $aditional = $sac->appendChild($aditional);
                        $cbc = $xml->createElement('cbc:ID', '1000'); $cbc = $aditional->appendChild($cbc);
                        $cbc = $xml->createElement('cbc:Value', 'SON CIENTO TREINTACINCO PUNTO CINCUENTA Y CUATRO SOLES'); $cbc = $aditional->appendChild($cbc);
                    // falta encontrar y especificar
                    $sunat = $xml->createElement('sac:SUNATTransaction'); $sunat = $sac->appendChild($sunat);
                        $cbc = $xml->createElement('cbc:ID', '1'); $cbc = $sunat->appendChild($cbc);

        // 2.- Firma electronica
        $ext = $xml->createElement('ext:UBLExtension'); $ext = $UBLExtension->appendChild($ext);
            $contents = $xml->createElement('ext:ExtensionContent', ' '); $contents = $ext->appendChild($contents);

    // 36. Version del UBL
    $cbc = $xml->createElement('cbc:UBLVersionID', '2.0'); $cbc = $Invoice->appendChild($cbc);

    // 37.- Version de la estructura del documento
    $cbc = $xml->createElement('cbc:CustomizationID', '1.0'); $cbc = $Invoice->appendChild($cbc);

    // 8.- Numeracion , conformada por serie y numero correlativo B001-00012926
    $cbc = $xml->createElement('cbc:ID', $f4); $cbc = $Invoice->appendChild($cbc);

    // 1.- Fecha de emision 2017-04-13
    $cbc = $xml->createElement('cbc:IssueDate', $c3); $cbc = $Invoice->appendChild($cbc);

    // 7.- Tipo de Documento 01 Factura 03 Boleta 07 Nota credito - catalogo numero 06
    $cbc = $xml->createElement('cbc:InvoiceTypeCode', $f3); $cbc = $Invoice->appendChild($cbc);

    // 28.- Tipo de moneda en la cual se emite la factura electronica
    $cbc = $xml->createElement('cbc:DocumentCurrencyCode', 'PEN'); $cbc = $Invoice->appendChild($cbc);

    // 2.- Parte de la firma electronica. esto es de quien creo la firma electronica
    $cac_signature = $xml->createElement('cac:Signature'); $cac_signature = $Invoice->appendChild($cac_signature);
        $cbc = $xml->createElement('cbc:ID', '20532710066'); $cbc = $cac_signature->appendChild($cbc);
        $cbc = $xml->createElement('cbc:Note', 'Elaborado por Sistema de Emision Electronica Facturador SUNAT (SEE-SFS) 1.0.0'); $cbc = $cac_signature->appendChild($cbc);
        $cbc = $xml->createElement('cbc:ValidatorID', '780086'); $cbc = $cac_signature->appendChild($cbc);
        $cac_signatory = $xml->createElement('cac:SignatoryParty'); $cac_signatory = $cac_signature->appendChild($cac_signatory);
            $cac = $xml->createElement('cac:PartyIdentification'); $cac = $cac_signatory->appendChild($cac);
                $cbc = $xml->createElement('cbc:ID', '20532710066'); $cbc = $cac->appendChild($cbc);
            $cac = $xml->createElement('cac:PartyName'); $cac = $cac_signatory->appendChild($cac);
                $cbc = $xml->createElement('cbc:Name', 'DESARROLLO DE SISTEMAS INTEGRADOS DE GESTIÓN'); $cbc = $cac->appendChild($cbc);
            $agent = $xml->createElement('cac:AgentParty'); $agent = $cac_signatory->appendChild($agent);
                $cac = $xml->createElement('cac:PartyIdentification'); $cac = $agent->appendChild($cac);
                    $cbc = $xml->createElement('cbc:ID', '20532710066'); $cbc = $cac->appendChild($cbc);
                $cac = $xml->createElement('cac:PartyName'); $cac = $agent->appendChild($cac);
                    $cbc = $xml->createElement('cbc:Name', $c7); $cbc = $cac->appendChild($cbc);
                $cac = $xml->createElement('cac:PartyLegalEntity'); $cac = $agent->appendChild($cac);
                    $cbc = $xml->createElement('cbc:RegistrationName', $c7); $cbc = $cac->appendChild($cbc);
        $cac_digital = $xml->createElement('cac:DigitalSignatureAttachment'); $cac_digital = $cac_signature->appendChild($cac_digital);
            $cac = $xml->createElement('cac:ExternalReference'); $cac = $cac_digital->appendChild($cac);
                $cbc = $xml->createElement('cbc:URI', 'SIGN'); $cbc = $cac->appendChild($cbc);

    // 3.- Apellidos y nombres, denominacion o razon social (DATOS DEL PROVEEDOR)
    // 4.- Nombre Comercial
    // 5.- Domicilio fiscal
    // 6.- Numero RUC
    $cac_accounting = $xml->createElement('cac:AccountingSupplierParty'); $cac_accounting = $Invoice->appendChild($cac_accounting);
        $cbc = $xml->createElement('cbc:CustomerAssignedAccountID', '20532710066'); $cbc = $cac_accounting->appendChild($cbc);
        $cbc = $xml->createElement('cbc:AdditionalAccountID', '6'); $cbc = $cac_accounting->appendChild($cbc);
        $cac_party = $xml->createElement('cac:Party'); $cac_party = $cac_accounting->appendChild($cac_party);
            $cac = $xml->createElement('cac:PartyName'); $cac = $cac_party->appendChild($cac);
                $cbc = $xml->createElement('cbc:Name', 'TOYOTA SURMOTRIZ'); $cbc = $cac->appendChild($cbc);
            $address = $xml->createElement('cac:PostalAddress'); $address = $cac_party->appendChild($address);
                // este numerito no se de donde es me parece que es direccion postal
                $cbc = $xml->createElement('cbc:ID', '040101'); $cbc = $address->appendChild($cbc);
                $cbc = $xml->createElement('cbc:StreetName', 'AV. LEGUIA NRO. 1870 (FRENTE A I.E. JOSE ROSA ARA)'); $cbc = $address->appendChild($cbc);
                $cbc = $xml->createElement('cbc:CityName', 'TACNA'); $cbc = $address->appendChild($cbc);
                $cbc = $xml->createElement('cbc:District', 'TACNA'); $cbc = $address->appendChild($cbc);
                $country = $xml->createElement('cac:Country'); $country = $address->appendChild($country);
                    $cbc = $xml->createElement('cbc:IdentificationCode', 'PER'); $cbc = $country->appendChild($cbc);
            $legal = $xml->createElement('cac:PartyLegalEntity'); $legal = $cac_party->appendChild($legal);
                $cbc = $xml->createElement('cbc:RegistrationName', 'TOYOTA SURMOTRIZ'); $cbc = $legal->appendChild($cbc);

    // 9.- Tipo y numero de documento de identidad del adquiriente o usuario
    // 10.- Apellidos y nombres, denominacion o razon social del adquiriente o usuario
    $cac_accounting = $xml->createElement('cac:AccountingCustomerParty'); $cac_accounting = $Invoice->appendChild($cac_accounting);
        $cbc = $xml->createElement('cbc:CustomerAssignedAccountID', "".$c11.""); $cbc = $cac_accounting->appendChild($cbc);
        $cbc = $xml->createElement('cbc:AdditionalAccountID', "".$f9.""); $cbc = $cac_accounting->appendChild($cbc);
        $cac_party = $xml->createElement('cac:Party'); $cac_party = $cac_accounting->appendChild($cac_party);
            $legal = $xml->createElement('cac:PartyLegalEntity'); $legal = $cac_party->appendChild($legal);
                $cbc = $xml->createElement('cbc:RegistrationName', $c7); $cbc = $legal->appendChild($cbc);

    // no tiene numero o no esta registrado
    $seller = $xml->createElement('cac:SellerSupplierParty'); $seller = $Invoice->appendChild($seller);
        $cac_party = $xml->createElement('cac:Party'); $cac_party = $seller->appendChild($cac_party);
            $address = $xml->createElement('cac:PostalAddress'); $address = $cac_party->appendChild($address);
                $cbc = $xml->createElement('cbc:AddressTypeCode', '0'); $cbc = $address->appendChild($cbc);

    // 22.- Sumatoria IGV
    // 23.- Sumatoria ISC
    // 24.- Sumatoria otros tributos
    $taxtotal = $xml->createElement('cac:TaxTotal'); $taxtotal = $Invoice->appendChild($taxtotal);
        $cbc = $xml->createElement('cbc:TaxAmount', $c40); $cbc = $taxtotal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
        $taxtsubtotal = $xml->createElement('cac:TaxSubtotal'); $taxtsubtotal = $taxtotal->appendChild($taxtsubtotal);
            $cbc = $xml->createElement('cbc:TaxAmount', $c40); $cbc = $taxtsubtotal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
            $taxtcategory = $xml->createElement('cac:TaxCategory'); $taxtcategory = $taxtsubtotal->appendChild($taxtcategory);
                $taxscheme = $xml->createElement('cac:TaxScheme'); $taxscheme = $taxtcategory->appendChild($taxscheme);
                    $cbc = $xml->createElement('cbc:ID', '1000'); $cbc = $taxscheme->appendChild($cbc);
                    $cbc = $xml->createElement('cbc:Name', 'IGV'); $cbc = $taxscheme->appendChild($cbc);
                    $cbc = $xml->createElement('cbc:TaxTypeCode', 'VAT'); $cbc = $taxscheme->appendChild($cbc);

    // 25.- Sumatoria otros cargos
    $legal = $xml->createElement('cac:LegalMonetaryTotal'); $legal = $Invoice->appendChild($legal);
        $cbc = $xml->createElement('cbc:AllowanceTotalAmount', '0.00'); $cbc = $legal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
        $cbc = $xml->createElement('cbc:ChargeTotalAmount', '0.00'); $cbc = $legal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
        $cbc = $xml->createElement('cbc:PayableAmount', $c43); $cbc = $legal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");

    // detalle de la factura
    $InvoiceLine = $xml->createElement('cac:InvoiceLine'); $InvoiceLine = $Invoice->appendChild($InvoiceLine);
        $cbc = $xml->createElement('cbc:ID', '1'); $cbc = $InvoiceLine->appendChild($cbc);
        $cbc = $xml->createElement('cbc:InvoicedQuantity', '100.00'); $cbc = $InvoiceLine->appendChild($cbc); $cbc->setAttribute('unitCode', "ZZ");
        $cbc = $xml->createElement('cbc:LineExtensionAmount', '100.00'); $cbc = $InvoiceLine->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
        $pricing = $xml->createElement('cac:PricingReference'); $pricing = $InvoiceLine->appendChild($pricing);
            $cac = $xml->createElement('cac:AlternativeConditionPrice'); $cac = $pricing->appendChild($cac);
                $cbc = $xml->createElement('cbc:PriceAmount', '118.00'); $cbc = $cac->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                $cbc = $xml->createElement('cbc:PriceTypeCode', '01'); $cbc = $cac->appendChild($cbc);
        $allowance = $xml->createElement('cac:AllowanceCharge'); $allowance = $InvoiceLine->appendChild($allowance);
            $cbc = $xml->createElement('cbc:ChargeIndicator', 'false'); $cbc = $allowance->appendChild($cbc);
            $cbc = $xml->createElement('cbc:Amount', '0.00'); $cbc = $allowance->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
        $taxtotal = $xml->createElement('cac:TaxTotal'); $taxtotal = $InvoiceLine->appendChild($taxtotal);
            $cbc = $xml->createElement('cbc:TaxAmount', '18.00'); $cbc = $taxtotal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
            $taxtsubtotal = $xml->createElement('cac:TaxSubtotal'); $taxtsubtotal = $taxtotal->appendChild($taxtsubtotal);
                $cbc = $xml->createElement('cbc:TaxableAmount', '18.00'); $cbc = $taxtsubtotal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                $cbc = $xml->createElement('cbc:TaxAmount', '18.00'); $cbc = $taxtsubtotal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                $taxtcategory = $xml->createElement('cac:TaxCategory'); $taxtcategory = $taxtsubtotal->appendChild($taxtcategory);
                    $cbc = $xml->createElement('cbc:TaxExemptionReasonCode', '10'); $cbc = $taxtcategory->appendChild($cbc);
                    $taxscheme = $xml->createElement('cac:TaxScheme'); $taxscheme = $taxtcategory->appendChild($taxscheme);
                        $cbc = $xml->createElement('cbc:ID', '1000'); $cbc = $taxscheme->appendChild($cbc);
                        $cbc = $xml->createElement('cbc:Name', 'IGV'); $cbc = $taxscheme->appendChild($cbc);
                        $cbc = $xml->createElement('cbc:TaxTypeCode', 'VAT'); $cbc = $taxscheme->appendChild($cbc);
        $item = $xml->createElement('cac:Item'); $item = $InvoiceLine->appendChild($item);
            $cbc = $xml->createElement('cbc:Description', 'CLAVO PARA CONCRETO DE  2"'); $cbc = $item->appendChild($cbc);
            $sellers = $xml->createElement('cac:SellersItemIdentification'); $sellers = $item->appendChild($sellers);
                $cbc = $xml->createElement('cbc:ID', 'ALM'); $cbc = $sellers->appendChild($cbc);
            $additional = $xml->createElement('cac:AdditionalItemIdentification'); $additional = $item->appendChild($additional);
                $cbc = $xml->createElement('cbc:ID', 'A'); $cbc = $additional->appendChild($cbc);
        $price = $xml->createElement('cac:Price'); $price = $InvoiceLine->appendChild($price);
            $cbc = $xml->createElement('cbc:PriceAmount', '1.00'); $cbc = $price->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");


$xml->formatOutput = true;
$strings_xml       = $xml->saveXML();

// Directorio
if (!file_exists($f7)) {
    mkdir($f7, 0777, true);
}
$xml->save($f7.$f8.'.xml');



    // 2.- Firmar documento xml
    // ========================

    require './robrichards/src/xmlseclibs.php';
    use RobRichards\XMLSecLibs\XMLSecurityDSig;
    use RobRichards\XMLSecLibs\XMLSecurityKey;

    // Cargar el XML a firmar
    $doc = new DOMDocument();
    $doc->load($f7.$f8.'.xml');

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
    $objDSig->appendSignature($doc->getElementsByTagName('ExtensionContent')->item(1));

// Guardar el XML firmado
$doc->save($f7.$f8.'.xml');
chmod($f7.$f8.'.xml', 0777);





// 3.- Enviar documento xml y obtener respuesta
// ============================================

require('./lib/pclzip.lib.php'); // Librería que comprime archivos en .ZIP

## Creación del archivo .ZIP
$zip = new PclZip($f7.$f8.'.zip');
$zip->create($f7.$f8.'.xml',PCLZIP_OPT_REMOVE_ALL_PATH);
chmod($f7.$f8.'.zip', 0777);

# Procedimiento para enviar comprobante a la SUNAT
class feedSoap extends SoapClient{

    public $XMLStr = "";

    public function setXMLStr($value)
    {
        $this->XMLStr = $value;
    }

    public function getXMLStr()
    {
        return $this->XMLStr;
    }

    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $request = $this->XMLStr;

        $dom = new DOMDocument('1.0');

        try
        {
            $dom->loadXML($request);
        } catch (DOMException $e) {
            die($e->code);
        }

        $request = $dom->saveXML();

        //Solicitud
        return parent::__doRequest($request, $location, $action, $version, $one_way = 0);
    }

    public function SoapClientCall($SOAPXML)
    {
        return $this->setXMLStr($SOAPXML);
    }
}


function soapCall($wsdlURL, $callFunction = "", $XMLString)
{
    $client = new feedSoap($wsdlURL, array('trace' => true));
    $reply  = $client->SoapClientCall($XMLString);

    //echo "REQUEST:\n" . $client->__getFunctions() . "\n";
    $client->__call("$callFunction", array(), array());
    //$request = prettyXml($client->__getLastRequest());
    //echo highlight_string($request, true) . "<br/>\n";
    return $client->__getLastResponse();
}

//URL para enviar las solicitudes a SUNAT
$wsdlURL = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';

//Estructura del XML para la conexión
$XMLString = '<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
 <soapenv:Header>
     <wsse:Security>
         <wsse:UsernameToken Id="ABC-123">
             <wsse:Username>20532710066MODDATOS</wsse:Username>
             <wsse:Password>MODDATOS</wsse:Password>
         </wsse:UsernameToken>
     </wsse:Security>
 </soapenv:Header>
 <soapenv:Body>
     <ser:sendBill>
        <fileName>'.$f8.'.zip</fileName>
        <contentFile>' . base64_encode(file_get_contents($f7.$f8.'.zip')) . '</contentFile>
     </ser:sendBill>
 </soapenv:Body>
</soapenv:Envelope>';

//Realizamos la llamada a nuestra función
 $result = soapCall($wsdlURL, $callFunction = "sendBill", $XMLString);


//Descargamos el Archivo Response
$archivo = fopen($f7.'C'.$f8.'.xml','w+');
fputs($archivo,$result);
fclose($archivo);

/*LEEMOS EL ARCHIVO XML*/
$xml = simplexml_load_file($f7.'C'.$f8.'.xml');
foreach ($xml->xpath('//applicationResponse') as $response){ }

/*AQUI DESCARGAMOS EL ARCHIVO CDR(CONSTANCIA DE RECEPCIÓN)*/
$cdr=base64_decode($response);

$archivo = fopen($f7.'R-'.$f8.'.zip','w+');
fputs($archivo,$cdr);
fclose($archivo);
chmod($f7.'R-'.$f8.'.zip', 0777);

$archive = new PclZip($f7.'R-'.$f8.'.zip');
if ($archive->extract()==0) {
    die("Error : ".$archive->errorInfo(true));
}else{
    chmod($f7.$f8.'.xml', 0777);
}

/*Eliminamos el Archivo Response*/
unlink($f7.'C'.$f8.'.xml');




// 5.- Generar PDF y codigo de barras
// ==================================


require("./fpdf/fpdf.php");
require_once ("./PDF417/vendor/autoload.php");
use BigFish\PDF417\PDF417;
use BigFish\PDF417\Renderers\ImageRenderer;

// Variables correspondientes a la factura.
$RUC = "";       // RUC.
$NomRazSoc = ""; // Nombre o Razón social.
$FecEmi = "";    // Fecha de emisión.
$Domicilio = ""; // Domicilio.
$CodHash = "";   // Código Hash.
$TipoDoc = "";   // Tipo de documento.
$TotGrav = 0;    // Total gravado.
$TotIGV = 0;     // Total IGV.
$TotMonto = 0;   // Total importe.

// Variables correspondientes a los productos o servicios de la factura.

$CodProdServ = ""; // Código.
$ProdServ = ""; // Descripción.
$Cant = 0; // Cantidad.
$UniMed = ""; // Unidad de medida.
$Precio = 0; // Precio unitario.
$Importe = 0;  // Importe.

// Obteniendo datos del archivo .XML (factura electrónica)======================

$xml = file_get_contents($f7.$f8.'.xml');


#== Obteniendo datos del archivo .XML
$DOM = new DOMDocument('1.0', 'ISO-8859-1');
$DOM->preserveWhiteSpace = FALSE;
$DOM->loadXML($xml);

### DATOS DE LA FACTURA ####################################################
// Obteniendo RUC.
$DocXML = $DOM->getElementsByTagName('CustomerAssignedAccountID');
foreach($DocXML as $Nodo){
    $RUC = $Nodo->nodeValue;
}

// Obteniendo Fecha de emisión.
$DocXML = $DOM->getElementsByTagName('IssueDate');
foreach($DocXML as $Nodo){
    $FecEmi = $Nodo->nodeValue;
}

// Obteniendo Nombre o Razón social.
$DocXML = $DOM->getElementsByTagName('RegistrationName');
$i=0;
foreach($DocXML as $Nodo){
    if ($i==0){
        $NomRazSoc = $Nodo->nodeValue;
    }
    $i++;
}

// Obteniendo domicilio.
$DocXML = $DOM->getElementsByTagName('StreetName');
$i=0;
foreach($DocXML as $Nodo){
    if ($i==0){
        $Domicilio = $Nodo->nodeValue;
    }
    $i++;
}

// Obteniendo Codigo Hash.
$DocXML = $DOM->getElementsByTagName('DigestValue');
$i=0;
foreach($DocXML as $Nodo){
    if ($i==0){
        $CodHash = $Nodo->nodeValue;
    }
    $i++;
}

// Clave del tipo de documento.
$DocXML = $DOM->getElementsByTagName('InvoiceTypeCode');
$i=0;
foreach($DocXML as $Nodo){
    if ($i==0){
        $TipoDoc = $Nodo->nodeValue;
    }
    $i++;
}


### DATOS DEL PRODUCTO O SERVICIO. #########################################

// Código del producto o servicio.
$DocXML = $DOM->getElementsByTagName('PriceTypeCode');
$i=0;
foreach($DocXML as $Nodo){
    if ($i==0){
        $CodProdServ = $Nodo->nodeValue;
    }
    $i++;
}

// Descripción del producto o servicio.
$DocXML = $DOM->getElementsByTagName('Description');
$i=0;
foreach($DocXML as $Nodo){
    if ($i==0){
        $ProdServ = $Nodo->nodeValue;
    }
    $i++;
}

// Cantidad de producto o servicio.
$DocXML = $DOM->getElementsByTagName('InvoicedQuantity');
$i=0;
foreach($DocXML as $Nodo){
    if ($i==0){
        $Cant = $Nodo->nodeValue;
    }
    $i++;
}

// Unidad de medida del producto o servicio.
$DocXML = $DOM->getElementsByTagName('InvoicedQuantity');
$i=0;
foreach($DocXML as $Nodo){
    if ($i==0){
        //$UniMed = $Nodo->nodeValue;
        $UniMed = $Nodo->getAttribute('unitCode');
    }
    $i++;
}

// Precio unitario.
$DocXML = $DOM->getElementsByTagName('PriceAmount');
$i=0;
foreach($DocXML as $Nodo){
    if ($i==1){
        $Precio = $Nodo->nodeValue;
    }
    $i++;
}

// Importe.
$DocXML = $DOM->getElementsByTagName('LineExtensionAmount');
$i=0;
foreach($DocXML as $Nodo){
    if ($i==0){
        $Importe = $Nodo->nodeValue;
    }
    $i++;
}

### TOTALES DE LA FACTURA ##################################################

// Total gravado.
$DocXML = $DOM->getElementsByTagName('PayableAmount');
$i=0;
foreach($DocXML as $Nodo){
    if ($i==1){
        $TotGrav = $Nodo->nodeValue;
    }
    $i++;
}

// Total IGV.
$DocXML = $DOM->getElementsByTagName('TaxAmount');
$i=0;
foreach($DocXML as $Nodo){
    $TotIGV = $Nodo->nodeValue;
}

// Monto total.
$DocXML = $DOM->getElementsByTagName('PayableAmount');
$i=0;
foreach($DocXML as $Nodo){
    $TotMonto = $Nodo->nodeValue;
}

// Crear el gráfico con el código de barras. ===================================
$textoCodBar =
    "| $TipoDoc 
    | A
    | 123
    | $TotIGV
    | $TotMonto
    | $FecEmi
    | $TipoDoc
    | F002-026
    | VALOR RESUMEN
    | $CodHash
    |";

$pdf417 = new PDF417();
$codigo_barra = $pdf417->encode($textoCodBar);

// Create a PNG image
$renderer = new ImageRenderer([
    'format' => 'png'
]);

$image = $renderer->render($codigo_barra);
$image->save($f7.$f8.'.png');

//= Creación del documetno .PDF ================================================

class PDF extends FPDF{

    function Header(){

    }

    function Footer(){

        $this->SetTextColor(0,0,0);
        $this->SetFont('arial','',12);
        $this->SetXY(18,26.2);
        $this->Cell(0.8, 0.25, utf8_decode("Pág. ").$this->PageNo().' de {nb}', 0, 1,'L', 0);
    }
}

$NomArchPDF = $f7.$f8.".pdf";

$pdf=new PDF('P','cm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->AddFont('IDAutomationHC39M','','IDAutomationHC39M.php');
$pdf->AddFont('verdana','','verdana.php');
$pdf->SetAutoPageBreak(true);
$pdf->SetMargins(0, 0, 0);
$pdf->SetLineWidth(0.02);
$pdf->SetFillColor(0,0,0);
$pdf->image("./archs_graf/Membrete_Fact.jpg",1, 1, 10, 2.5);
$pdf->image($f7.$f8.".png",0.7, 10, 9, 3);

// Direccion surmotriz
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',7);
$pdf->SetXY(3.3,1.1);
$pdf->Cell(1, 0.35, utf8_decode("PRINCIPAL: Av. Leguia 1870 Tacna. Telef.: (052) 426368 - 244015"), 0, 1,'L', 0);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',7);
$pdf->SetXY(3.3,1.5);
$pdf->Cell(1, 0.35, utf8_decode("Cel.:952869639 (Repuestos) Cel.: 992566630 (Servicio)"), 0, 1,'L', 0);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',7);
$pdf->SetXY(3.3,1.9);
$pdf->Cell(1, 0.35, utf8_decode("RPM #945625993 email: tacna@surmotriz.com"), 0, 1,'L', 0);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',7);
$pdf->SetXY(3.3,2.3);
$pdf->Cell(1, 0.35, utf8_decode("SUCURSAL: Urb Vera Vera Mz C Lote 06 Moque. Mariscal Nieto"), 0, 1,'L', 0);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',7);
$pdf->SetXY(3.3,2.7);
$pdf->Cell(1, 0.35, utf8_decode("Telf (053) 792646 Cel 953922105 email: moquegua@surmotriz.com"), 0, 1,'L', 0);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',7);
$pdf->SetXY(3.3,3.1);
$pdf->Cell(1, 0.35, utf8_decode("Reparacion y Mantenimiento con accesorios legitimos de Toyota."), 0, 1,'L', 0);

// termina datos surmotriz

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',10);
$pdf->SetXY(1.2,13);
$pdf->Cell(8, 0.25, utf8_decode("Representación impresa de la factura electrónica."), 0, 1,'C', 0);

$pdf->RoundedRect(12, 1, 8, 2.5, 0.2, '');

$pdf->SetTextColor(170,0,0);
$pdf->SetFont('arial','',14);
$pdf->SetXY(12,1.5);
$pdf->Cell(8, 0.25, "RUC: 20532710066", 0, 1,'C', 0);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','B',14);
$pdf->SetXY(12,2.2);
$pdf->Cell(8, 0.25, utf8_decode("FACTURA ELECTRÓNICA"), 0, 1,'C', 0);


$pdf->SetTextColor(0,0,150);
$pdf->SetFont('arial','',14);
$pdf->SetXY(12,2.9);
$pdf->Cell(8, 0.25, substr($f8,15,13), 0, 1,'C', 0);


$pdf->RoundedRect(1, 4, 19, 3.2, 0.2, '');

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','B',10);

$pdf->SetXY(1.1,4.2);
$pdf->Cell(1, 0.35, utf8_decode("Señores: "), 0, 1,'L', 0);



$pdf->SetXY(1.1,4.2+0.6);
$pdf->Cell(1, 0.35, utf8_decode("Dirección: "), 0, 1,'L', 0);

$pdf->SetXY(1.1,4.2+(0.6*2));
$pdf->Cell(1, 0.35, utf8_decode("RUC: "), 0, 1,'L', 0);

$pdf->SetXY(1.1,4.2+(0.6*3));
$pdf->Cell(1, 0.35, utf8_decode("Fecha de emisión: "), 0, 1,'L', 0);

$pdf->SetXY(1.1,4.2+(0.6*4));
$pdf->Cell(1, 0.35, utf8_decode("Moneda: "), 0, 1,'L', 0);


$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',10);

$pdf->SetXY(4.7,4.2);
$pdf->Cell(1, 0.35, utf8_decode($NomRazSoc), 0, 1,'L', 0);

$pdf->SetXY(4.7,4.2+0.6);
$pdf->Cell(1, 0.35, utf8_decode($Domicilio), 0, 1,'L', 0);

$pdf->SetXY(4.7,4.2+(0.6*2));
$pdf->Cell(1, 0.35, utf8_decode($RUC), 0, 1,'L', 0);

$pdf->SetXY(4.7,4.2+(0.6*3));
$pdf->Cell(1, 0.35, utf8_decode($FecEmi), 0, 1,'L', 0);

$pdf->SetXY(4.7,4.2+(0.6*4));
$pdf->Cell(1, 0.35, utf8_decode("SOL"), 0, 1,'L', 0);


$X = 0;
$Y = 0;

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',10);

$pdf->SetXY($X+1,$Y+8);
$pdf->Cell(2.5, 0.5, utf8_decode("Código"), 1, 1,'L', 0);

$pdf->SetXY($X+3.5,$Y+8);
$pdf->Cell(6.65, 0.5, utf8_decode("Descripción"), 1, 1,'L', 0);

$pdf->SetXY($X+10.15,$Y+8);
$pdf->Cell(2, 0.5, utf8_decode("Cantidad"), 1, 1,'C', 0);

$pdf->SetXY($X+12.15,$Y+8);
$pdf->Cell(2.65, 0.5, utf8_decode("Unidad"), 1, 1,'L', 0);

$pdf->SetXY($X+14.8,$Y+8);
$pdf->Cell(2.7, 0.5, utf8_decode("Precio unitario"), 1, 1,'R', 0);

$pdf->SetXY($X+17.5,$Y+8);
$pdf->Cell(2.5, 0.5, utf8_decode("Precio venta"), 1, 1,'R', 0);


$Y = $Y + 0.5;

$pdf->SetXY($X+1,$Y+8);
$pdf->Cell(2.5, 0.8, utf8_decode($CodProdServ), 1, 1,'L', 0);

$pdf->SetXY($X+3.5,$Y+8);
$pdf->Cell(6.65, 0.8, utf8_decode($ProdServ), 1, 1,'L', 0);

$pdf->SetXY($X+10.15,$Y+8);
$pdf->Cell(2, 0.8, utf8_decode($Cant), 1, 1,'C', 0);

$pdf->SetXY($X+12.15,$Y+8);
$pdf->Cell(2.65, 0.8, utf8_decode($UniMed), 1, 1,'L', 0);

$pdf->SetXY($X+14.8,$Y+8);
$pdf->Cell(2.7, 0.8, number_format($Precio,2), 1, 1,'R', 0);

$pdf->SetXY($X+17.5,$Y+8);
$pdf->Cell(2.5, 0.8, number_format($Importe,2), 1, 1,'R', 0);

$Y = $Y + 0.8;

$pdf->SetXY($X+1,$Y+8);
$pdf->Cell(2.5, 0.8, utf8_decode($CodProdServ), 1, 1,'L', 0);

$pdf->SetXY($X+3.5,$Y+8);
$pdf->Cell(6.65, 0.8, utf8_decode($ProdServ), 1, 1,'L', 0);

$pdf->SetXY($X+10.15,$Y+8);
$pdf->Cell(2, 0.8, utf8_decode($Cant), 1, 1,'C', 0);

$pdf->SetXY($X+12.15,$Y+8);
$pdf->Cell(2.65, 0.8, utf8_decode($UniMed), 1, 1,'L', 0);

$pdf->SetXY($X+14.8,$Y+8);
$pdf->Cell(2.7, 0.8, number_format($Precio,2), 1, 1,'R', 0);

$pdf->SetXY($X+17.5,$Y+8);
$pdf->Cell(2.5, 0.8, number_format($Importe,2), 1, 1,'R', 0);





$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',10);

$pdf->SetXY(9.9,10);
$pdf->Cell(7.6, 0.5, utf8_decode("Total Valor de Venta - Operaciones Gravadas:"), 1, 1,'R', 0);

$pdf->SetXY(17.5,10);
$pdf->Cell(2.5, 0.5, number_format($TotGrav,2), 1, 1,'R', 0);

$pdf->SetXY(9.9,10+0.5);
$pdf->Cell(7.6, 0.5, utf8_decode("IGV:"), 1, 1,'R', 0);

$pdf->SetXY(17.5,10+0.5);
$pdf->Cell(2.5, 0.5, number_format($TotIGV,2), 1, 1,'R', 0);

$pdf->SetXY(9.9,10+(0.5*2));
$pdf->Cell(7.6, 0.5, utf8_decode("Importe Total:"), 1, 1,'R', 0);

$pdf->SetXY(17.5,10+(0.5*2));
$pdf->Cell(2.5, 0.5, number_format($TotMonto,2), 1, 1,'R', 0);

$pdf->SetXY(9.9,10+(0.5*3));
$pdf->Cell(7.6, 0.5, utf8_decode("Importe Total:"), 1, 1,'R', 0);

$pdf->SetXY(17.5,10+(0.5*3));
$pdf->Cell(2.5, 0.5, number_format($TotMonto,2), 1, 1,'R', 0);

$pdf->SetXY(9.9,10+(0.5*4));
$pdf->Cell(7.6, 0.5, utf8_decode("Importe Total:"), 1, 1,'R', 0);

$pdf->SetXY(17.5,10+(0.5*4));
$pdf->Cell(2.5, 0.5, number_format($TotMonto,2), 1, 1,'R', 0);



$pdf->line(1, 24.8, 20.5, 24.8);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial','',9);
$pdf->SetXY(1,25);
$pdf->MultiCell(19.5, 0.35, utf8_decode("Representación Impresa de la Factura ElectrónicaCódigo Hash: $CodHash
Autorizado para ser Emisor electrónico mediante la Resolución de Intendencia N° 0180050002185/SUNAT
Para consultar el comprobante ingresar a : https://portal.efacturacion.pe/appefacturacion"), 0, 'C');

//==============================================================================
$pdf->Output($NomArchPDF, 'F'); // Se graba el documento .PDF en el disco duro o unidad de estado sólido.
chmod ($NomArchPDF,0777);  // Se dan permisos de lectura y escritura.

$pdf->Output($NomArchPDF, 'I'); // Se muestra el documento .PDF en el navegador.
