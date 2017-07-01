<?php
//header('Content-Type: text/xml; charset=UTF-8');
//error_reporting(0);

$xml = new DomDocument('1.0', 'UTF-8');
$xml->preserveWhiteSpace = false;

$CreditNote = $xml->createElement('CreditNote'); $CreditNote = $xml->appendChild($CreditNote);
$CreditNote->setAttribute('xmlns',"urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2");
$CreditNote->setAttribute('xmlns:cac',"urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2");
$CreditNote->setAttribute('xmlns:cbc',"urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2");
$CreditNote->setAttribute('xmlns:ccts',"urn:un:unece:uncefact:documentation:2");
$CreditNote->setAttribute('xmlns:ds',"http://www.w3.org/2000/09/xmldsig#");
$CreditNote->setAttribute('xmlns:ext',"urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2");
$CreditNote->setAttribute('xmlns:qdt',"urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2");
$CreditNote->setAttribute('xmlns:sac',"urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1");
$CreditNote->setAttribute('xmlns:udt',"urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2");
$CreditNote->setAttribute('xmlns:xsi',"http://www.w3.org/2001/XMLSchema-instance");

    $UBLExtension = $xml->createElement('ext:UBLExtensions'); $UBLExtension = $CreditNote->appendChild($UBLExtension);
        $ext = $xml->createElement('ext:UBLExtension'); $ext = $UBLExtension->appendChild($ext);
            $contents = $xml->createElement('ext:ExtensionContent'); $contents = $ext->appendChild($contents);
                $sac = $xml->createElement('sac:AdditionalInformation'); $sac = $contents->appendChild($sac);                    
                    // el 2005 es Total descuentos
                    $monetary = $xml->createElement('sac:AdditionalMonetaryTotal'); $monetary = $sac->appendChild($monetary);
                    $cbc = $xml->createElement('cbc:ID', '2005'); $cbc = $monetary->appendChild($cbc);
                    $cbc = $xml->createElement('cbc:PayableAmount', $c34); $cbc = $monetary->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                    // El 1001 total velor venta - operaciones gravadas1
                    $monetary = $xml->createElement('sac:AdditionalMonetaryTotal'); $monetary = $sac->appendChild($monetary);
                    $cbc = $xml->createElement('cbc:ID', '1001'); $cbc = $monetary->appendChild($cbc);
                    $cbc = $xml->createElement('cbc:PayableAmount', $c31); $cbc = $monetary->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                    // el 1002 total valor venta - operaciones inafectas
                    $monetary = $xml->createElement('sac:AdditionalMonetaryTotal'); $monetary = $sac->appendChild($monetary);
                    $cbc = $xml->createElement('cbc:ID', '1002'); $cbc = $monetary->appendChild($cbc);
                    $cbc = $xml->createElement('cbc:PayableAmount', $c28); $cbc = $monetary->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                    // el 1003 total valor venta - operaciones exoneradas
                    $monetary = $xml->createElement('sac:AdditionalMonetaryTotal'); $monetary = $sac->appendChild($monetary);
                    $cbc = $xml->createElement('cbc:ID', '1003'); $cbc = $monetary->appendChild($cbc);
                    $cbc = $xml->createElement('cbc:PayableAmount', $c25); $cbc = $monetary->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                    
        // 2.- Firma electronica
        $ext = $xml->createElement('ext:UBLExtension'); $ext = $UBLExtension->appendChild($ext);
            $contents = $xml->createElement('ext:ExtensionContent', ' '); $contents = $ext->appendChild($contents);

    // 36. Version del UBL
    $cbc = $xml->createElement('cbc:UBLVersionID', '2.0'); $cbc = $CreditNote->appendChild($cbc);

    // 37.- Version de la estructura del documento
    $cbc = $xml->createElement('cbc:CustomizationID', '1.0'); $cbc = $CreditNote->appendChild($cbc);

    // 8.- Numeracion , conformada por serie y numero correlativo B001-00012926
    $cbc = $xml->createElement('cbc:ID', $f4); $cbc = $CreditNote->appendChild($cbc);

    // 1.- Fecha de emision 2017-04-13
    $cbc = $xml->createElement('cbc:IssueDate', $c3); $cbc = $CreditNote->appendChild($cbc);

    // 28.- Tipo de moneda en la cual se emite la factura electronica
    $cbc = $xml->createElement('cbc:DocumentCurrencyCode', $c19); $cbc = $CreditNote->appendChild($cbc);

    //
    $cac = $xml->createElement('cac:DiscrepancyResponse'); $cac = $CreditNote->appendChild($cac);
        $cbc = $xml->createElement('cbc:ReferenceID',$c13); $cbc = $cac->appendChild($cbc);
        $cbc = $xml->createElement('cbc:ResponseCode','07'); $cbc = $cac->appendChild($cbc);
        $cbc = $xml->createElement('cbc:Description',$c35); $cbc = $cac->appendChild($cbc);

    $BillingReference = $xml->createElement('cac:BillingReference'); $BillingReference = $CreditNote->appendChild($BillingReference);
        $cac = $xml->createElement('cac:InvoiceDocumentReference'); $cac = $BillingReference->appendChild($cac);
            $cbc = $xml->createElement('cbc:ID',$c13); $cbc = $cac->appendChild($cbc);
            $cbc = $xml->createElement('cbc:DocumentTypeCode',$cab['TIPOREF']); $cbc = $cac->appendChild($cbc);

    // signature
    $cac_signature = $xml->createElement('cac:Signature'); $cac_signature = $CreditNote->appendChild($cac_signature);
        $cbc = $xml->createElement('cbc:ID', '20532710066_2'); $cbc = $cac_signature->appendChild($cbc);
        $cac_signatory = $xml->createElement('cac:SignatoryParty'); $cac_signatory = $cac_signature->appendChild($cac_signatory);
            $cac = $xml->createElement('cac:PartyIdentification'); $cac = $cac_signatory->appendChild($cac);
                $cbc = $xml->createElement('cbc:ID', '20532710066'); $cbc = $cac->appendChild($cbc);
        $cac = $xml->createElement('cac:PartyName'); $cac = $cac_signatory->appendChild($cac);
            $cbc = $xml->createElement('cbc:Name', 'CDATA[DESARROLLO DE SISTEMAS INTEGRADOS DE GESTION'); $cbc = $cac->appendChild($cbc);        
        $cac_digital = $xml->createElement('cac:DigitalSignatureAttachment'); $cac_digital = $cac_signature->appendChild($cac_digital);
        $cac = $xml->createElement('cac:ExternalReference'); $cac = $cac_digital->appendChild($cac);
            $cbc = $xml->createElement('cbc:URI', 'SIGN'); $cbc = $cac->appendChild($cbc);



    // Datos del emisor de la factura (surmotriz)
    $cac_accounting = $xml->createElement('cac:AccountingSupplierParty'); $cac_accounting = $CreditNote->appendChild($cac_accounting);
        $cbc = $xml->createElement('cbc:CustomerAssignedAccountID', '20532710066'); $cbc = $cac_accounting->appendChild($cbc);
        $cbc = $xml->createElement('cbc:AdditionalAccountID', '6'); $cbc = $cac_accounting->appendChild($cbc);
        $cac_party = $xml->createElement('cac:Party'); $cac_party = $cac_accounting->appendChild($cac_party);
        // nombre comercial
            $cac = $xml->createElement('cac:PartyName'); $cac = $cac_party->appendChild($cac);
                $cbc = $xml->createElement('cbc:Name', 'TOYOTA SURMOTRIZ'); $cbc = $cac->appendChild($cbc);
            $address = $xml->createElement('cac:PostalAddress'); $address = $cac_party->appendChild($address);
                //ubigeo
                $cbc = $xml->createElement('cbc:ID', '220101'); $cbc = $address->appendChild($cbc);
                // Direccion
                $cbc = $xml->createElement('cbc:StreetName', 'AV. LEGUIA NRO. 1870'); $cbc = $address->appendChild($cbc);
                // urbanizacion
                $cbc = $xml->createElement('cbc:CitySubdivisionName', 'FRENTE A I.E. JOSE ROSA ARA'); $cbc = $address->appendChild($cbc);
                //departamento
                $cbc = $xml->createElement('cbc:CityName', 'TACNA'); $cbc = $address->appendChild($cbc);
                $cbc = $xml->createElement('cbc:CountrySubentity', 'TACNA'); $cbc = $address->appendChild($cbc);
                $cbc = $xml->createElement('cbc:District', 'TACNA'); $cbc = $address->appendChild($cbc);
                // pais
                $country = $xml->createElement('cac:Country'); $country = $address->appendChild($country);
                    $cbc = $xml->createElement('cbc:IdentificationCode', 'PE'); $cbc = $country->appendChild($cbc);
                // razon social
            $legal = $xml->createElement('cac:PartyLegalEntity'); $legal = $cac_party->appendChild($legal);
                $cbc = $xml->createElement('cbc:RegistrationName', 'SURMOTRIZ S.R.L.'); $cbc = $legal->appendChild($cbc);



    //DATOS CLIENTE
    $cac_accounting = $xml->createElement('cac:AccountingCustomerParty'); $cac_accounting = $CreditNote->appendChild($cac_accounting);
        $cbc = $xml->createElement('cbc:CustomerAssignedAccountID', "".$c11.""); $cbc = $cac_accounting->appendChild($cbc);
        $cbc = $xml->createElement('cbc:AdditionalAccountID', "".$f9.""); $cbc = $cac_accounting->appendChild($cbc);
        $cac_party = $xml->createElement('cac:Party'); $cac_party = $cac_accounting->appendChild($cac_party);
            $address = $xml->createElement('cac:PostalAddress'); $address = $cac_party->appendChild($address);
                // direccion
                $cbc = $xml->createElement('cbc:StreetName', $c15); $cbc = $address->appendChild($cbc);
            // nombre o razon zocial
            $legal = $xml->createElement('cac:PartyLegalEntity'); $legal = $cac_party->appendChild($legal);
                $cbc = $xml->createElement('cbc:RegistrationName', $c7); $cbc = $legal->appendChild($cbc);



    // 22.- Sumatoria IGV
    $taxtotal = $xml->createElement('cac:TaxTotal'); $taxtotal = $CreditNote->appendChild($taxtotal);
        $cbc = $xml->createElement('cbc:TaxAmount', $c40); $cbc = $taxtotal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
        $taxtsubtotal = $xml->createElement('cac:TaxSubtotal'); $taxtsubtotal = $taxtotal->appendChild($taxtsubtotal);
            $cbc = $xml->createElement('cbc:TaxAmount', $c40); $cbc = $taxtsubtotal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
            $taxtcategory = $xml->createElement('cac:TaxCategory'); $taxtcategory = $taxtsubtotal->appendChild($taxtcategory);
                $taxscheme = $xml->createElement('cac:TaxScheme'); $taxscheme = $taxtcategory->appendChild($taxscheme);
                    $cbc = $xml->createElement('cbc:ID', '1000'); $cbc = $taxscheme->appendChild($cbc);
                    $cbc = $xml->createElement('cbc:Name', 'IGV'); $cbc = $taxscheme->appendChild($cbc);
                    $cbc = $xml->createElement('cbc:TaxTypeCode', 'VAT'); $cbc = $taxscheme->appendChild($cbc);


    // 27.- Importe total de venta
    $legal = $xml->createElement('cac:LegalMonetaryTotal'); $legal = $CreditNote->appendChild($legal);
        // Descuento a nivel global creo que se usara en franquisia
        $cbc = $xml->createElement('cbc:PayableAmount', $c43); $cbc = $legal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");



if ($c18 == 'COCRD' || $c18 == 'ARAND') {
    $i=1;
    foreach ($dets as $det ) {
        // detalle de la factura
        $CreditNoteLine = $xml->createElement('cac:CreditNoteLine'); $CreditNoteLine = $CreditNote->appendChild($CreditNoteLine);
            // id del item
            $cbc = $xml->createElement('cbc:ID', $i); $cbc = $CreditNoteLine->appendChild($cbc);
            // cantidad x item:  1
            $cbc = $xml->createElement('cbc:CreditedQuantity', $det['CTDUNIDADITEM1']); $cbc = $CreditNoteLine->appendChild($cbc); $cbc->setAttribute('unitCode', "NIU");
            // total valor linea x item: 1x1043.48 = 1043.48
            $cbc = $xml->createElement('cbc:LineExtensionAmount', $det['MTOVALORVENTAITEM12']); $cbc = $CreditNoteLine->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
            // precio unitario del producto con igv
            $pricing = $xml->createElement('cac:PricingReference'); $pricing = $CreditNoteLine->appendChild($pricing);
                $cac = $xml->createElement('cac:AlternativeConditionPrice'); $cac = $pricing->appendChild($cac);
                    // precio unitario con igv
                    $cbc = $xml->createElement('cbc:PriceAmount', round($det['MTOVALORUNITARIO5']*0.18+$det['MTOVALORUNITARIO5'],2)); $cbc = $cac->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                    // 01 con igv, 02 operaciones no onerosas
                    $cbc = $xml->createElement('cbc:PriceTypeCode', '01'); $cbc = $cac->appendChild($cbc);            
            // igv del total del producto aplicado ya el descuento *0.18
            $taxtotal = $xml->createElement('cac:TaxTotal'); $taxtotal = $CreditNoteLine->appendChild($taxtotal);
                $cbc = $xml->createElement('cbc:TaxAmount', round($det['MTOVALORVENTAITEM12']*0.18,2)); $cbc = $taxtotal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                $taxtsubtotal = $xml->createElement('cac:TaxSubtotal'); $taxtsubtotal = $taxtotal->appendChild($taxtsubtotal);
                    $cbc = $xml->createElement('cbc:TaxAmount', round($det['MTOVALORVENTAITEM12']*0.18,2)); $cbc = $taxtsubtotal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                    $taxtcategory = $xml->createElement('cac:TaxCategory'); $taxtcategory = $taxtsubtotal->appendChild($taxtcategory);
                        $cbc = $xml->createElement('cbc:TaxExemptionReasonCode', '10'); $cbc = $taxtcategory->appendChild($cbc);
                        $taxscheme = $xml->createElement('cac:TaxScheme'); $taxscheme = $taxtcategory->appendChild($taxscheme);
                            $cbc = $xml->createElement('cbc:ID', '1000'); $cbc = $taxscheme->appendChild($cbc);
                            $cbc = $xml->createElement('cbc:Name', 'IGV'); $cbc = $taxscheme->appendChild($cbc);
                            $cbc = $xml->createElement('cbc:TaxTypeCode', 'VAT'); $cbc = $taxscheme->appendChild($cbc);
            $item = $xml->createElement('cac:Item'); $item = $CreditNoteLine->appendChild($item);
                $cbc = $xml->createElement('cbc:Description', $det['DESITEM4']); $cbc = $item->appendChild($cbc);
                $sellers = $xml->createElement('cac:SellersItemIdentification'); $sellers = $item->appendChild($sellers);
                    $cbc = $xml->createElement('cbc:ID', $det['CODPRODUCTO2']); $cbc = $sellers->appendChild($cbc);
            // precio sin igv ejm 83.05
            $price = $xml->createElement('cac:Price'); $price = $CreditNoteLine->appendChild($price);
                $cbc = $xml->createElement('cbc:PriceAmount', $det['MTOVALORUNITARIO5']); $cbc = $price->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
        $i++;
    }

}elseif ($c18 == 'COCRR' || $c18 == 'AND' || $c18 == 'NANDR'){
    $InvoiceLine = $xml->createElement('cac:CreditNoteLine'); $InvoiceLine = $CreditNote->appendChild($InvoiceLine);
        $cbc = $xml->createElement('cbc:ID', '1'); $cbc = $InvoiceLine->appendChild($cbc);
        // cantidad x item:  1
        $cbc = $xml->createElement('cbc:CreditedQuantity', '1'); $cbc = $InvoiceLine->appendChild($cbc); $cbc->setAttribute('unitCode', "NIU");
        // total valor linea x item: 1x1043.48 = 1043.48
        $cbc = $xml->createElement('cbc:LineExtensionAmount', $c31); $cbc = $InvoiceLine->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
        $pricing = $xml->createElement('cac:PricingReference'); $pricing = $InvoiceLine->appendChild($pricing);
            $cac = $xml->createElement('cac:AlternativeConditionPrice'); $cac = $pricing->appendChild($cac);
                // precio unitario x item + igv:  1043.48 + 0.18x 1043.48 = 1231.31
                $cbc = $xml->createElement('cbc:PriceAmount', round($c31+0.18*$c31,2)); $cbc = $cac->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                // 01 con igv, 02 operaciones no onerosas
                $cbc = $xml->createElement('cbc:PriceTypeCode', '01'); $cbc = $cac->appendChild($cbc);
        // IGV:    1043.48*0.18 = 187.83
        $taxtotal = $xml->createElement('cac:TaxTotal'); $taxtotal = $InvoiceLine->appendChild($taxtotal);
            $cbc = $xml->createElement('cbc:TaxAmount', round($c31*0.18,2)); $cbc = $taxtotal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
            $taxtsubtotal = $xml->createElement('cac:TaxSubtotal'); $taxtsubtotal = $taxtotal->appendChild($taxtsubtotal);
                $cbc = $xml->createElement('cbc:TaxAmount', round($c31*0.18,2)); $cbc = $taxtsubtotal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                $taxtcategory = $xml->createElement('cac:TaxCategory'); $taxtcategory = $taxtsubtotal->appendChild($taxtcategory);
                    $cbc = $xml->createElement('cbc:TaxExemptionReasonCode', '10'); $cbc = $taxtcategory->appendChild($cbc);
                    $taxscheme = $xml->createElement('cac:TaxScheme'); $taxscheme = $taxtcategory->appendChild($taxscheme);
                        $cbc = $xml->createElement('cbc:ID', '1000'); $cbc = $taxscheme->appendChild($cbc);
                        $cbc = $xml->createElement('cbc:Name', 'IGV'); $cbc = $taxscheme->appendChild($cbc);
                        $cbc = $xml->createElement('cbc:TaxTypeCode', 'VAT'); $cbc = $taxscheme->appendChild($cbc);
        $item = $xml->createElement('cac:Item'); $item = $InvoiceLine->appendChild($item);
            $cbc = $xml->createElement('cbc:Description', $det); $cbc = $item->appendChild($cbc);
            $sellers = $xml->createElement('cac:SellersItemIdentification'); $sellers = $item->appendChild($sellers);
                $cbc = $xml->createElement('cbc:ID', ''); $cbc = $sellers->appendChild($cbc);
        // precio unitario x item sin igv:  1043.48
        $price = $xml->createElement('cac:Price'); $price = $InvoiceLine->appendChild($price);
            $cbc = $xml->createElement('cbc:PriceAmount', $c31); $cbc = $price->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
}

$xml->formatOutput = true;
$strings_xml = $xml->saveXML();
$ruta = explode('-',$c3);
$ruta = './app/repo/'.$ruta[0].'/'.$ruta[1].'/'.$ruta[2].'/';

// Directorio
if (!file_exists($ruta)) {
    mkdir($ruta, 0777, true);
}
$xml->save($ruta.$f8.'.xml');





// 2.- Firmar documento xml
// ========================

require './robrichards/src/xmlseclibs.php';
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;

// Cargar el XML a firmar
$doc = new DOMDocument();
$doc->load($ruta.$f8.'.xml');

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
$doc->save($ruta.$f8.'.xml');
chmod($ruta.$f8.'.xml', 0777);
//echo $doc->saveXML();



// 3.- Enviar documento xml y obtener respuesta
// ============================================

require('./lib/pclzip.lib.php'); // Librería que comprime archivos en .ZIP

## Creación del archivo .ZIP
$zip = new PclZip($ruta.$f8.'.zip');
$zip->create($ruta.$f8.'.xml',PCLZIP_OPT_REMOVE_ALL_PATH);
chmod($ruta.$f8.'.zip', 0777);


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
        <contentFile>' . base64_encode(file_get_contents($ruta.$f8.'.zip')) . '</contentFile>
     </ser:sendBill>
 </soapenv:Body>
</soapenv:Envelope>';

if ($cab['TIP_REF'] != 'BR')
{
    if ($cab['TIP_REF'] != 'BS')
    {
        //Realizamos la llamada a nuestra función
        $result = soapCall($wsdlURL, $callFunction = "sendBill", $XMLString);

        //Descargamos el Archivo Response
        $archivo = fopen($ruta.'C'.$f8.'.xml','w+');
        fputs($archivo,$result);
        fclose($archivo);

        /*LEEMOS EL ARCHIVO XML*/
        $xml = simplexml_load_file($ruta.'C'.$f8.'.xml');
        foreach ($xml->xpath('//applicationResponse') as $response){ }

        /*AQUI DESCARGAMOS EL ARCHIVO CDR(CONSTANCIA DE RECEPCIÓN)*/
        $cdr=base64_decode($response);

        $archivo = fopen($ruta.'R-'.$f8.'.zip','w+');
        fputs($archivo,$cdr);
        fclose($archivo);

        chmod($ruta.'R-'.$f8.'.zip', 0777);
        $archive = new PclZip($ruta.'R-'.$f8.'.zip');
        if ($archive->extract($ruta)==0) {
            die("Error : ".$archive->errorInfo(true));
        }else{
            chmod($ruta.$f8.'.xml', 0777);
        }

        /*Eliminamos el Archivo Response*/
        unlink($ruta.'C'.$f8.'.xml');
    }
}
//echo $cab['CDG_TIP_DOC'].$cab['TIP_REF'];
?>