<?php
// variables para usar
// ===================
/*  c1  :   titulo de la factura o boleta B001-00012926
 *  c2 y c3     :   fecha   2017-04-13
 *  c4 y c5     :   forma de pago - Contado o Credito
 *  c6 y c7     :   Cliente - CHINO PAJUELO MANUEL - Customer
 *  c8 y c9     :   Nombre o razon - Surmotriz S.R.L  - Supplier
 *  c10 y c11   :   Documento o Ruc - 00491348  - Customer
 *  c12 y c13   :   Ruc - 20532710066 - Supplier
 *  c14 y c15   :   Direccion - AV. PINTO 1120 - Customer
 *  c16 y c17   :   Direccion -  Av. Leguia Nº 1870 - Supplier
 *  c18         :   S si tiene detalle $dets y N si no tiene detalle det
 *  c19         :   Moneda PEN
 *  c21 y c22   :   OP. GRATUITAS - 0.00
 *  c24 y c25   :   OP. EXONERADA - 0.00
 *  c27 y c28   :   OP. INAFECTA - 0.00
 *  c30 y c31   :   OP. GRAVADA - 135.54
 *  c33 y c34   :   TOTAL. DSCTO - 54.93
 *  c36 y c37   :   I.S.C. -  0.00
 *  c39 y c40   :   I.G.V. - 14.51
 *  c42 y c43   :   Total - 95.12
 *  $f4         :   B001-00012926
 *  $f3         :   01 Factura 03 Boleta 07 Nota Credito
 *  $f10        :   Nombre comercial surmotriz 'TOYOTA SURMOTRIZ'
 */

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
                        $cbc = $xml->createElement('cbc:PayableAmount', $c28); $cbc = $monetary->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");

                    // el 1003 total valor venta - operaciones exoneradas
                    $monetary = $xml->createElement('sac:AdditionalMonetaryTotal'); $monetary = $sac->appendChild($monetary);
                        $cbc = $xml->createElement('cbc:ID', '1003'); $cbc = $monetary->appendChild($cbc);
                        $cbc = $xml->createElement('cbc:PayableAmount', $c25); $cbc = $monetary->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");

                    // el 1004 es operaciones gratuitas
                    $monetary = $xml->createElement('sac:AdditionalMonetaryTotal'); $monetary = $sac->appendChild($monetary);
                        $cbc = $xml->createElement('cbc:ID', '1004'); $cbc = $monetary->appendChild($cbc);
                        $cbc = $xml->createElement('cbc:PayableAmount', $c22); $cbc = $monetary->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");

                    // el 1005 es sub total es como el importe sin descuento el precio
                    $monetary = $xml->createElement('sac:AdditionalMonetaryTotal'); $monetary = $sac->appendChild($monetary);
                        $cbc = $xml->createElement('cbc:ID', '1005'); $cbc = $monetary->appendChild($cbc);
                        $cbc = $xml->createElement('cbc:PayableAmount', $c37); $cbc = $monetary->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");

                    // 1000 leyenda
                    $monetary = $xml->createElement('sac:AdditionalProperty'); $monetary = $sac->appendChild($monetary);
                        $cbc = $xml->createElement('cbc:ID', '1000'); $cbc = $monetary->appendChild($cbc);
                        $cbc = $xml->createElement('cbc:Value', $leyenda_100); $cbc = $monetary->appendChild($cbc);

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
$cbc = $xml->createElement('cbc:DocumentCurrencyCode', $c19); $cbc = $Invoice->appendChild($cbc);

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

// Datos del emisor de la factura (surmotriz)
$cac_accounting = $xml->createElement('cac:AccountingSupplierParty'); $cac_accounting = $Invoice->appendChild($cac_accounting);
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
    $cac_accounting = $xml->createElement('cac:AccountingCustomerParty'); $cac_accounting = $Invoice->appendChild($cac_accounting);
        $cbc = $xml->createElement('cbc:CustomerAssignedAccountID', "".$c11.""); $cbc = $cac_accounting->appendChild($cbc);
        $cbc = $xml->createElement('cbc:AdditionalAccountID', "".$f9.""); $cbc = $cac_accounting->appendChild($cbc);
        $cac_party = $xml->createElement('cac:Party'); $cac_party = $cac_accounting->appendChild($cac_party);
            $address = $xml->createElement('cac:PostalAddress'); $address = $cac_party->appendChild($address);
            // direccion
                $cbc = $xml->createElement('cbc:StreetName', $c15); $cbc = $address->appendChild($cbc);
            // nombre o razon zocial
            $legal = $xml->createElement('cac:PartyLegalEntity'); $legal = $cac_party->appendChild($legal);
                $cbc = $xml->createElement('cbc:RegistrationName', $c7); $cbc = $legal->appendChild($cbc);



// anticipos
if ($anticipo_current == 1){
$PrepaidPayment = $xml->createElement('cac:PrepaidPayment'); $PrepaidPayment = $Invoice->appendChild($PrepaidPayment);
    $cbc = $xml->createElement('cbc:ID',$anticipo_doc); $cbc = $PrepaidPayment->appendChild($cbc); $cbc->setAttribute('schemeID', $anticipo_SchemaID);
    $cbc = $xml->createElement('cbc:PaidAmount',$anticipo_tot); $cbc = $PrepaidPayment->appendChild($cbc); $cbc->setAttribute('currencyID', $anticipo_moneda);
    $cbc = $xml->createElement('cbc:InstructionID',$anticipo_documento); $cbc = $PrepaidPayment->appendChild($cbc); $cbc->setAttribute('schemeID', $anticipo_document_type);
}


// 22.- Sumatoria IGV
$taxtotal = $xml->createElement('cac:TaxTotal'); $taxtotal = $Invoice->appendChild($taxtotal);
    $cbc = $xml->createElement('cbc:TaxAmount', $c40); $cbc = $taxtotal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
    $taxtsubtotal = $xml->createElement('cac:TaxSubtotal'); $taxtsubtotal = $taxtotal->appendChild($taxtsubtotal);
        $cbc = $xml->createElement('cbc:TaxAmount', $c40); $cbc = $taxtsubtotal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
        $taxtcategory = $xml->createElement('cac:TaxCategory'); $taxtcategory = $taxtsubtotal->appendChild($taxtcategory);
            $taxscheme = $xml->createElement('cac:TaxScheme'); $taxscheme = $taxtcategory->appendChild($taxscheme);
                $cbc = $xml->createElement('cbc:ID', '1000'); $cbc = $taxscheme->appendChild($cbc);
                $cbc = $xml->createElement('cbc:Name', 'IGV'); $cbc = $taxscheme->appendChild($cbc);
                $cbc = $xml->createElement('cbc:TaxTypeCode', 'VAT'); $cbc = $taxscheme->appendChild($cbc);

// 27.- Importe total de venta
$legal = $xml->createElement('cac:LegalMonetaryTotal'); $legal = $Invoice->appendChild($legal);
// Descuento a nivel global creo que se usara en franquisia
// $cbc = $xml->createElement('cbc:AllowanceTotalAmount', '0.00'); $cbc = $legal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
    if ($anticipo_current == 1){
        $cbc = $xml->createElement('cbc:PrepaidAmount', $anticipo_tot); $cbc = $legal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
    }
    $cbc = $xml->createElement('cbc:PayableAmount', $c43); $cbc = $legal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");






if ($c18 == 'COCRD') {
        $i=1;
        foreach ($dets as $det ) {
            // detalle de la factura
            $InvoiceLine = $xml->createElement('cac:InvoiceLine'); $InvoiceLine = $Invoice->appendChild($InvoiceLine);
            // id del item
                $cbc = $xml->createElement('cbc:ID', $i); $cbc = $InvoiceLine->appendChild($cbc);
                // cantidad
                $cbc = $xml->createElement('cbc:InvoicedQuantity', $det['CTDUNIDADITEM1']); $cbc = $InvoiceLine->appendChild($cbc); $cbc->setAttribute('unitCode', "NIU");
                // valor venta con descuento
                $cbc = $xml->createElement('cbc:LineExtensionAmount', $det['MTOVALORVENTAITEM12']); $cbc = $InvoiceLine->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                // precio unitario del producto con igv
                $pricing = $xml->createElement('cac:PricingReference'); $pricing = $InvoiceLine->appendChild($pricing);
                    $cac = $xml->createElement('cac:AlternativeConditionPrice'); $cac = $pricing->appendChild($cac);
                        // precio unitario con igv
                        $cbc = $xml->createElement('cbc:PriceAmount', round($det['MTOVALORUNITARIO5']*0.18+$det['MTOVALORUNITARIO5'],2)); $cbc = $cac->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                        // 01 con igv, 02 operaciones no onerosas
                        $cbc = $xml->createElement('cbc:PriceTypeCode', '01'); $cbc = $cac->appendChild($cbc);
                $allowance = $xml->createElement('cac:AllowanceCharge'); $allowance = $InvoiceLine->appendChild($allowance);
                    // false para descuento
                    $cbc = $xml->createElement('cbc:ChargeIndicator', 'false'); $cbc = $allowance->appendChild($cbc);
                    // descuento
                    $cbc = $xml->createElement('cbc:Amount', $det['MTODSCTOITEM6']); $cbc = $allowance->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");

                // igv del total del producto aplicado ya el descuento *0.18
                $taxtotal = $xml->createElement('cac:TaxTotal'); $taxtotal = $InvoiceLine->appendChild($taxtotal);
                    $cbc = $xml->createElement('cbc:TaxAmount', round($det['MTOVALORVENTAITEM12']*0.18,2)); $cbc = $taxtotal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                    $taxtsubtotal = $xml->createElement('cac:TaxSubtotal'); $taxtsubtotal = $taxtotal->appendChild($taxtsubtotal);
                        $cbc = $xml->createElement('cbc:TaxAmount', round($det['MTOVALORVENTAITEM12']*0.18,2)); $cbc = $taxtsubtotal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                        $taxtcategory = $xml->createElement('cac:TaxCategory'); $taxtcategory = $taxtsubtotal->appendChild($taxtcategory);
                            $cbc = $xml->createElement('cbc:TaxExemptionReasonCode', '10'); $cbc = $taxtcategory->appendChild($cbc);
                            $taxscheme = $xml->createElement('cac:TaxScheme'); $taxscheme = $taxtcategory->appendChild($taxscheme);
                                $cbc = $xml->createElement('cbc:ID', '1000'); $cbc = $taxscheme->appendChild($cbc);
                                $cbc = $xml->createElement('cbc:Name', 'IGV'); $cbc = $taxscheme->appendChild($cbc);
                                $cbc = $xml->createElement('cbc:TaxTypeCode', 'VAT'); $cbc = $taxscheme->appendChild($cbc);
                $item = $xml->createElement('cac:Item'); $item = $InvoiceLine->appendChild($item);
                    $cbc = $xml->createElement('cbc:Description', $det['DESITEM4']); $cbc = $item->appendChild($cbc);
                    $sellers = $xml->createElement('cac:SellersItemIdentification'); $sellers = $item->appendChild($sellers);
                        $cbc = $xml->createElement('cbc:ID', $det['CODPRODUCTO2']); $cbc = $sellers->appendChild($cbc);
                // precio sin igv ejm 83.05
                $price = $xml->createElement('cac:Price'); $price = $InvoiceLine->appendChild($price);
                    $cbc = $xml->createElement('cbc:PriceAmount', $det['MTOVALORUNITARIO5']); $cbc = $price->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
            $i++;
        }

    }elseif ($c18 == 'COCRR' || $c18 == 'AND'){
        // detalle de la factura
        $InvoiceLine = $xml->createElement('cac:InvoiceLine'); $InvoiceLine = $Invoice->appendChild($InvoiceLine);
            // id del item
            $cbc = $xml->createElement('cbc:ID', '1'); $cbc = $InvoiceLine->appendChild($cbc);
            // cantidad
            $cbc = $xml->createElement('cbc:InvoicedQuantity', '1'); $cbc = $InvoiceLine->appendChild($cbc); $cbc->setAttribute('unitCode', "NIU");
            // valor venta con descuento
            $cbc = $xml->createElement('cbc:LineExtensionAmount', $c31); $cbc = $InvoiceLine->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
            // precio unitario del producto con igv
            $pricing = $xml->createElement('cac:PricingReference'); $pricing = $InvoiceLine->appendChild($pricing);
                $cac = $xml->createElement('cac:AlternativeConditionPrice'); $cac = $pricing->appendChild($cac);
                // precio unitario con igv
                    $cbc = $xml->createElement('cbc:PriceAmount', round($c31*0.18+$c31,2)); $cbc = $cac->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                    // 01 con igv, 02 operaciones no onerosas
                    $cbc = $xml->createElement('cbc:PriceTypeCode', '01'); $cbc = $cac->appendChild($cbc);
            $allowance = $xml->createElement('cac:AllowanceCharge'); $allowance = $InvoiceLine->appendChild($allowance);
                // false para descuento
                $cbc = $xml->createElement('cbc:ChargeIndicator', 'false'); $cbc = $allowance->appendChild($cbc);
                // descuento
                $cbc = $xml->createElement('cbc:Amount', $c34); $cbc = $allowance->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");

            // igv del total del producto aplicado ya el descuento *0.18
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

        // precio sin igv ejm 83.05
        $price = $xml->createElement('cac:Price'); $price = $InvoiceLine->appendChild($price);
        $cbc = $xml->createElement('cbc:PriceAmount', $c37); $cbc = $price->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
        //echo $c31;
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
//echo $f7.'<br>'.$ruta;



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

if ($cab['CDG_TIP_DOC']!='B')
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