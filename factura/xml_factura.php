<?php
    require './../robrichards/src/xmlseclibs.php';
    use RobRichards\XMLSecLibs\XMLSecurityDSig;
    use RobRichards\XMLSecLibs\XMLSecurityKey;

    date_default_timezone_set('America/Lima');

    function exception_error_handler($errno, $errstr, $errfile, $errline ) {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
    set_error_handler("exception_error_handler");

    try {
        
        /*PROCESAMIENTO DOC
        *************************/
        include "__docs.php";

        if($cab_doc_gen['CDG_SUN_ENV']=='N') {

            /*Cliente Soap
            ***************/
            include "__soap.php";

            /******************************************** XML *********************************/
            //header('Content-Type: text/xml; charset=UTF-8');
            $xml = new DomDocument('1.0', 'ISO-8859-1');
            $xml->standalone = false;
            $xml->preserveWhiteSpace = false;
            $Invoice = $xml->createElement('Invoice');
            $Invoice = $xml->appendChild($Invoice);
            $Invoice->setAttribute('xmlns', 'urn:oasis:names:specification:ubl:schema:xsd:Invoice-2');
            $Invoice->setAttribute('xmlns:cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
            $Invoice->setAttribute('xmlns:cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
            $Invoice->setAttribute('xmlns:ccts', "urn:un:unece:uncefact:documentation:2");
            $Invoice->setAttribute('xmlns:ds', "http://www.w3.org/2000/09/xmldsig#");
            $Invoice->setAttribute('xmlns:ext', "urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2");
            $Invoice->setAttribute('xmlns:qdt', "urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2");
            $Invoice->setAttribute('xmlns:sac', "urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1");
            $Invoice->setAttribute('xmlns:udt', "urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2");

            $UBLExtension = $xml->createElement('ext:UBLExtensions');
            $UBLExtension = $Invoice->appendChild($UBLExtension);
            $ext = $xml->createElement('ext:UBLExtension');
            $ext = $UBLExtension->appendChild($ext);
            $contents = $xml->createElement('ext:ExtensionContent');
            $contents = $ext->appendChild($contents);
            $sac = $xml->createElement('sac:AdditionalInformation');
            $sac = $contents->appendChild($sac);
            // 2005 es Total descuentos
            $monetary = $xml->createElement('sac:AdditionalMonetaryTotal');
            $monetary = $sac->appendChild($monetary);
            $cbc = $xml->createElement('cbc:ID', '2005');
            $cbc = $monetary->appendChild($cbc);
            $cbc = $xml->createElement('cbc:PayableAmount', $descuentos);
            $cbc = $monetary->appendChild($cbc);
            $cbc->setAttribute('currencyID', $moneda);
            // 1001 operaciones gravadas
            $monetary = $xml->createElement('sac:AdditionalMonetaryTotal');
            $monetary = $sac->appendChild($monetary);
            $cbc = $xml->createElement('cbc:ID', '1001');
            $cbc = $monetary->appendChild($cbc);
            $cbc = $xml->createElement('cbc:PayableAmount', $gravadas);
            $cbc = $monetary->appendChild($cbc);
            $cbc->setAttribute('currencyID', $moneda);
            // 1002 operaciones inafectas
            $monetary = $xml->createElement('sac:AdditionalMonetaryTotal');
            $monetary = $sac->appendChild($monetary);
            $cbc = $xml->createElement('cbc:ID', '1002');
            $cbc = $monetary->appendChild($cbc);
            $cbc = $xml->createElement('cbc:PayableAmount', '0.00');
            $cbc = $monetary->appendChild($cbc);
            $cbc->setAttribute('currencyID', $moneda);

            // el 1003 total valor venta - operaciones exoneradas
            $monetary = $xml->createElement('sac:AdditionalMonetaryTotal');
            $monetary = $sac->appendChild($monetary);
            $cbc = $xml->createElement('cbc:ID', '1003');
            $cbc = $monetary->appendChild($cbc);
            $cbc = $xml->createElement('cbc:PayableAmount', '0.00');
            $cbc = $monetary->appendChild($cbc);
            $cbc->setAttribute('currencyID', $moneda);

            // Firma electronica
            $ext = $xml->createElement('ext:UBLExtension');
            $ext = $UBLExtension->appendChild($ext);
            $contents = $xml->createElement('ext:ExtensionContent', ' ');
            $contents = $ext->appendChild($contents);

            //Version del UBL
            $cbc = $xml->createElement('cbc:UBLVersionID', '2.0');
            $cbc = $Invoice->appendChild($cbc);
            //Version de la estructura del documento
            $cbc = $xml->createElement('cbc:CustomizationID', '1.0');
            $cbc = $Invoice->appendChild($cbc);
            //Numeracion , conformada por serie y numero correlativo B001-00012926
            $cbc = $xml->createElement('cbc:ID', $serie . '-' . $cab_doc_gen['CDG_NUM_DOC']);
            $cbc = $Invoice->appendChild($cbc);
            //Fecha de emision 2017-04-13
            $cbc = $xml->createElement('cbc:IssueDate', date("Y-m-d", strtotime($fecha)));
            $cbc = $Invoice->appendChild($cbc);
            //Tipo de Documento 01 Factura 03 Boleta 07 Nota credito - catalogo numero 06
            $cbc = $xml->createElement('cbc:InvoiceTypeCode', $doc);
            $cbc = $Invoice->appendChild($cbc);
            //Tipo de moneda en la cual se emite la factura electronica
            $cbc = $xml->createElement('cbc:DocumentCurrencyCode', $moneda);
            $cbc = $Invoice->appendChild($cbc);

            // 2.- Parte de la firma electronica. esto es de quien creo la firma electronica
            $cac_signature = $xml->createElement('cac:Signature');
            $cac_signature = $Invoice->appendChild($cac_signature);
            $cbc = $xml->createElement('cbc:ID', '20532710066');
            $cbc = $cac_signature->appendChild($cbc);
            $cac_signatory = $xml->createElement('cac:SignatoryParty');
            $cac_signatory = $cac_signature->appendChild($cac_signatory);
            $cac = $xml->createElement('cac:PartyIdentification');
            $cac = $cac_signatory->appendChild($cac);
            $cbc = $xml->createElement('cbc:ID', '20532710066');
            $cbc = $cac->appendChild($cbc);
            $cac = $xml->createElement('cac:PartyName');
            $cac = $cac_signatory->appendChild($cac);
            $cbc = $xml->createElement('cbc:Name', 'SURMOTRIZ S.R.L');
            $cbc = $cac->appendChild($cbc);
            $cac_digital = $xml->createElement('cac:DigitalSignatureAttachment');
            $cac_digital = $cac_signature->appendChild($cac_digital);
            $cac = $xml->createElement('cac:ExternalReference');
            $cac = $cac_digital->appendChild($cac);
            $cbc = $xml->createElement('cbc:URI', 'SIGN');
            $cbc = $cac->appendChild($cbc);

            // DATOS EMISOR
            $cac_accounting = $xml->createElement('cac:AccountingSupplierParty');
            $cac_accounting = $Invoice->appendChild($cac_accounting);
            $cbc = $xml->createElement('cbc:CustomerAssignedAccountID', '20532710066');
            $cbc = $cac_accounting->appendChild($cbc);
            $cbc = $xml->createElement('cbc:AdditionalAccountID', '6');
            $cbc = $cac_accounting->appendChild($cbc);
            $cac_party = $xml->createElement('cac:Party');
            $cac_party = $cac_accounting->appendChild($cac_party);
            $address = $xml->createElement('cac:PostalAddress');
            $address = $cac_party->appendChild($address);
            $cbc = $xml->createElement('cbc:ID', '220101');
            $cbc = $address->appendChild($cbc);//ubigeo
            $cbc = $xml->createElement('cbc:StreetName', 'AV. LEGUIA NRO. 1870');
            $cbc = $address->appendChild($cbc);// Direccion
            $cbc = $xml->createElement('cbc:CitySubdivisionName', 'FRENTE A I.E. JOSE ROSA ARA');
            $cbc = $address->appendChild($cbc);// urbanizacion
            $cbc = $xml->createElement('cbc:CityName', 'TACNA');
            $cbc = $address->appendChild($cbc);//departamento
            $cbc = $xml->createElement('cbc:CountrySubentity', 'TACNA');
            $cbc = $address->appendChild($cbc);
            $cbc = $xml->createElement('cbc:District', 'TACNA');
            $cbc = $address->appendChild($cbc);
            $country = $xml->createElement('cac:Country');
            $country = $address->appendChild($country);// pais
            $cbc = $xml->createElement('cbc:IdentificationCode', 'PE');
            $cbc = $country->appendChild($cbc);
            $legal = $xml->createElement('cac:PartyLegalEntity');
            $legal = $cac_party->appendChild($legal);// razon social
            $cbc = $xml->createElement('cbc:RegistrationName', 'SURMOTRIZ S.R.L.');
            $cbc = $legal->appendChild($cbc);

            //DATOS CLIENTE
            $cac_accounting = $xml->createElement('cac:AccountingCustomerParty');
            $cac_accounting = $Invoice->appendChild($cac_accounting);
            $cbc = $xml->createElement('cbc:CustomerAssignedAccountID', trim($cab_doc_gen['CDG_DOC_CLI']));
            $cbc = $cac_accounting->appendChild($cbc);
            $cbc = $xml->createElement('cbc:AdditionalAccountID', $tipo_doc_num);
            $cbc = $cac_accounting->appendChild($cbc);
            $cac_party = $xml->createElement('cac:Party');
            $cac_party = $cac_accounting->appendChild($cac_party);

            // anticipos
            if ($reference == 3){
            $PrepaidPayment = $xml->createElement('cac:PrepaidPayment'); $PrepaidPayment = $Invoice->appendChild($PrepaidPayment);
                $cbc = $xml->createElement('cbc:ID',$anticipo_serie_numero_doc); $cbc = $PrepaidPayment->appendChild($cbc); $cbc->setAttribute('schemeID', $anticipo_tipo_doc);
                $cbc = $xml->createElement('cbc:PaidAmount',$anticipo_total); $cbc = $PrepaidPayment->appendChild($cbc); $cbc->setAttribute('currencyID', $anticipo_moneda);
                $cbc = $xml->createElement('cbc:InstructionID',$anticipo_documento); $cbc = $PrepaidPayment->appendChild($cbc); $cbc->setAttribute('schemeID', $anticipo_tipo_documento);
            }

            // nombre o razon zocial
            $legal = $xml->createElement('cac:PartyLegalEntity');
            $legal = $cac_party->appendChild($legal);
            $cbc = $xml->createElement('cbc:RegistrationName', htmlspecialchars($cab_doc_gen['CDG_NOM_CLI']));
            $cbc = $legal->appendChild($cbc);

            // Sumatoria IGV
            $taxtotal = $xml->createElement('cac:TaxTotal');
            $taxtotal = $Invoice->appendChild($taxtotal);
            $cbc = $xml->createElement('cbc:TaxAmount', $igv);
            $cbc = $taxtotal->appendChild($cbc);
            $cbc->setAttribute('currencyID', $moneda);
            $taxtsubtotal = $xml->createElement('cac:TaxSubtotal');
            $taxtsubtotal = $taxtotal->appendChild($taxtsubtotal);
            $cbc = $xml->createElement('cbc:TaxAmount', $igv);
            $cbc = $taxtsubtotal->appendChild($cbc);
            $cbc->setAttribute('currencyID', $moneda);
            $taxtcategory = $xml->createElement('cac:TaxCategory');
            $taxtcategory = $taxtsubtotal->appendChild($taxtcategory);
            $taxscheme = $xml->createElement('cac:TaxScheme');
            $taxscheme = $taxtcategory->appendChild($taxscheme);
            $cbc = $xml->createElement('cbc:ID', '1000');
            $cbc = $taxscheme->appendChild($cbc);
            $cbc = $xml->createElement('cbc:Name', 'IGV');
            $cbc = $taxscheme->appendChild($cbc);
            $cbc = $xml->createElement('cbc:TaxTypeCode', 'VAT');
            $cbc = $taxscheme->appendChild($cbc);

            // Importe total de venta
            $legal = $xml->createElement('cac:LegalMonetaryTotal'); $legal = $Invoice->appendChild($legal);
            if($reference == 3){ // anticipo
                $cbc = $xml->createElement('cbc:PrepaidAmount', $anticipo_total); $cbc = $legal->appendChild($cbc); $cbc->setAttribute('currencyID', $anticipo_moneda);
            }
            $cbc = $xml->createElement('cbc:PayableAmount', $total); $cbc = $legal->appendChild($cbc); $cbc->setAttribute('currencyID', $moneda);

            $i = 1;
            foreach ($items as $item) {
                $InvoiceLine = $xml->createElement('cac:InvoiceLine');
                $InvoiceLine = $Invoice->appendChild($InvoiceLine);
                $cbc = $xml->createElement('cbc:ID', $i);
                $cbc = $InvoiceLine->appendChild($cbc);// id del item
                $cbc = $xml->createElement('cbc:InvoicedQuantity', $item['cantidad']);
                $cbc = $InvoiceLine->appendChild($cbc);
                $cbc->setAttribute('unitCode', "NIU");// cantidad
                $cbc = $xml->createElement('cbc:LineExtensionAmount', $item['venta']);
                $cbc = $InvoiceLine->appendChild($cbc);
                $cbc->setAttribute('currencyID', $moneda);// valor venta con descuento
                $pricing = $xml->createElement('cac:PricingReference');
                $pricing = $InvoiceLine->appendChild($pricing);// precio unitario del producto con igv
                $cac = $xml->createElement('cac:AlternativeConditionPrice');
                $cac = $pricing->appendChild($cac);
                $cbc = $xml->createElement('cbc:PriceAmount', $item['unitario']);
                $cbc = $cac->appendChild($cbc);
                $cbc->setAttribute('currencyID', $moneda);// precio unitario con igv
                $cbc = $xml->createElement('cbc:PriceTypeCode', '01');
                $cbc = $cac->appendChild($cbc);// 01 con igv, 02 operaciones no onerosas
                $allowance = $xml->createElement('cac:AllowanceCharge');
                $allowance = $InvoiceLine->appendChild($allowance);
                $cbc = $xml->createElement('cbc:ChargeIndicator', 'false');
                $cbc = $allowance->appendChild($cbc);// false para descuento
                $cbc = $xml->createElement('cbc:Amount', $item['descuento']);
                $cbc = $allowance->appendChild($cbc);
                $cbc->setAttribute('currencyID', $moneda);// descuento
                $taxtotal = $xml->createElement('cac:TaxTotal');
                $taxtotal = $InvoiceLine->appendChild($taxtotal);// igv del total del producto aplicado ya el descuento *0.18
                $cbc = $xml->createElement('cbc:TaxAmount', number_format($item['venta'] * 0.18, 2, '.', ''));
                $cbc = $taxtotal->appendChild($cbc);
                $cbc->setAttribute('currencyID', $moneda);
                $taxtsubtotal = $xml->createElement('cac:TaxSubtotal');
                $taxtsubtotal = $taxtotal->appendChild($taxtsubtotal);
                $cbc = $xml->createElement('cbc:TaxAmount', number_format($item['venta'] * 0.18, 2, '.', ''));
                $cbc = $taxtsubtotal->appendChild($cbc);
                $cbc->setAttribute('currencyID', $moneda);
                $taxtcategory = $xml->createElement('cac:TaxCategory');
                $taxtcategory = $taxtsubtotal->appendChild($taxtcategory);
                $cbc = $xml->createElement('cbc:TaxExemptionReasonCode', '10');
                $cbc = $taxtcategory->appendChild($cbc);
                $taxscheme = $xml->createElement('cac:TaxScheme');
                $taxscheme = $taxtcategory->appendChild($taxscheme);
                $cbc = $xml->createElement('cbc:ID', '1000');
                $cbc = $taxscheme->appendChild($cbc);
                $cbc = $xml->createElement('cbc:Name', 'IGV');
                $cbc = $taxscheme->appendChild($cbc);
                $cbc = $xml->createElement('cbc:TaxTypeCode', 'VAT');
                $cbc = $taxscheme->appendChild($cbc);
                $item1 = $xml->createElement('cac:Item');
                $item1 = $InvoiceLine->appendChild($item1);
                $cbc = $xml->createElement('cbc:Description', preg_replace('([^A-Za-z0-9/\s\s+])', '', $item['descripcion']));
                $cbc = $item1->appendChild($cbc);
                $sellers = $xml->createElement('cac:SellersItemIdentification');
                $sellers = $item1->appendChild($sellers);
                $cbc = $xml->createElement('cbc:ID', $item['codigo']);
                $cbc = $sellers->appendChild($cbc);
                $price = $xml->createElement('cac:Price');
                $price = $InvoiceLine->appendChild($price);// precio unitario sin igv ejm 83.05
                $cbc = $xml->createElement('cbc:PriceAmount', $item['unitario']);
                $cbc = $price->appendChild($cbc);
                $cbc->setAttribute('currencyID', $moneda);
                $i++;
            }

            $xml->formatOutput = true;
            //$strings_xml = $xml->saveXML(); asigna el xml a un string
            //echo $strings_xml;
            //$xml->save('./20532710066-'.$doc.'-'.$serie.'-'.$cab_doc_gen['CDG_NUM_DOC'].'.xml');
            //print_r($items);


            // Cargar el XML a firmar
            $nom = '20532710066-' . $doc . '-' . $serie . '-' . $cab_doc_gen['CDG_NUM_DOC'];
            $doc = new DOMDocument();
            $doc->loadXML($xml->saveXML());
            $objDSig = new XMLSecurityDSig();
            $objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);
            $objDSig->addReference(
                $doc,
                XMLSecurityDSig::SHA1,
                array('http://www.w3.org/2000/09/xmldsig#enveloped-signature'),
                array('force_uri' => true)
            );
            //Crear una nueva clave de seguridad (privada)
            $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type' => 'private'));

            //Cargamos la clave privada
            $objKey->loadKey('../archivos_pem/private_key.pem', true);
            $objDSig->sign($objKey);

            // Agregue la clave pública asociada a la firma
            $objDSig->add509Cert(file_get_contents('../archivos_pem/public_key.pem'), true, false, array('subjectName' => true)); // array('issuerSerial' => true, 'subjectName' => true));

            // Anexar la firma al XML
            $objDSig->appendSignature($doc->getElementsByTagName('ExtensionContent')->item(1));
            $strings_xml = $doc->saveXML();

            

            ## Creación del archivo .ZIP
            $zip = new ZipArchive;
            $res = $zip->open($ruta.$nom.'.zip', ZipArchive::CREATE);
            $zip->addFromString($nom.'.xml', $strings_xml);
            $zip->close();

            
            $wsdlURL = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';
            //20532710066SURMOTR1  TOYOTA2051
            //$wsdlURL = "billService.wsdl";
            $XMLString = '<?xml version="1.0" encoding="UTF-8"?>
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
             <soapenv:Header>
                 <wsse:Security>
                     <wsse:UsernameToken>
                         <wsse:Username>20532710066MODDATOS</wsse:Username>
                         <wsse:Password>MODDATOS</wsse:Password>
                     </wsse:UsernameToken>
                 </wsse:Security>
             </soapenv:Header>
             <soapenv:Body>
                 <ser:sendBill>
                    <fileName>'.$nom.'.zip</fileName>
                    <contentFile>'.base64_encode(file_get_contents($ruta.$nom.'.zip')).'</contentFile>
                 </ser:sendBill>
             </soapenv:Body>
            </soapenv:Envelope>';

            //Enviamos y recibimos el documento, guardamos
            $result = soapCall($wsdlURL, $callFunction = "sendBill", $XMLString);
            preg_match_all('/<applicationResponse>(.*?)<\/applicationResponse>/is', $result, $matches);
            $archivo = fopen($ruta.'R-'.$nom.'.zip', 'w+');
            fputs($archivo, base64_decode($matches[1][0]));
            fclose($archivo);
            chmod($ruta.'R-'.$nom.'.zip', 0777);            
            sleep(2);
            header('Location: comprobar.php?gen='.$gen.'&emp='.$emp.'&tip='.$cab_doc_gen['CDG_TIP_DOC'].'&num='.$cab_doc_gen['CDG_NUM_DOC'].'');
            //echo '<div style="text-align: center"><img src="images/ok.png"><br>El documento fue enviado a Sunat Exitosamente..!</div>';            
        }
        else{
            echo '<div style="text-align: center"><img src="images/ok.png"><br>El documento ya fue enviado y Aceptado</div>';
        }
    }catch (Exception $e) {
        echo '<div style="text-align: center"><img src="images/error.png"><br>Sucedio un error al enviar el documento'.$e->getMessage().$e->getLine().'</div>';
    }


?>

