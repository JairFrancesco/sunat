<?php

    require './../robrichards/src/xmlseclibs.php';
    use RobRichards\XMLSecLibs\XMLSecurityDSig;
    use RobRichards\XMLSecLibs\XMLSecurityKey;    
    date_default_timezone_set('America/Lima');
    function exception_error_handler($errno, $errstr, $errfile, $errline ) {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
    set_error_handler("exception_error_handler");

    try{
        
        include "conexion.php";
        include "__docs.php";
        include "__soap.php";
        
        
        if($cab_doc_gen['CDG_SUN_ENV']=='N') {
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
                            $cbc = $xml->createElement('cbc:PayableAmount', $descuentos); $cbc = $monetary->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                            // El 1001 total velor venta - operaciones gravadas1
                            $monetary = $xml->createElement('sac:AdditionalMonetaryTotal'); $monetary = $sac->appendChild($monetary);
                            $cbc = $xml->createElement('cbc:ID', '1001'); $cbc = $monetary->appendChild($cbc);
                            $cbc = $xml->createElement('cbc:PayableAmount', $gravadas); $cbc = $monetary->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                            // el 1002 total valor venta - operaciones inafectas
                            $monetary = $xml->createElement('sac:AdditionalMonetaryTotal'); $monetary = $sac->appendChild($monetary);
                            $cbc = $xml->createElement('cbc:ID', '1002'); $cbc = $monetary->appendChild($cbc);
                            $cbc = $xml->createElement('cbc:PayableAmount', '0.00'); $cbc = $monetary->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                            // el 1003 total valor venta - operaciones exoneradas
                            $monetary = $xml->createElement('sac:AdditionalMonetaryTotal'); $monetary = $sac->appendChild($monetary);
                            $cbc = $xml->createElement('cbc:ID', '1003'); $cbc = $monetary->appendChild($cbc);
                            $cbc = $xml->createElement('cbc:PayableAmount', '0.00'); $cbc = $monetary->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                // 2.- Firma electronica
                $ext = $xml->createElement('ext:UBLExtension'); $ext = $UBLExtension->appendChild($ext);
                $contents = $xml->createElement('ext:ExtensionContent', ' '); $contents = $ext->appendChild($contents);

            // 36. Version del UBL
            $cbc = $xml->createElement('cbc:UBLVersionID', '2.0'); $cbc = $CreditNote->appendChild($cbc);
            // 37.- Version de la estructura del documento
            $cbc = $xml->createElement('cbc:CustomizationID', '1.0'); $cbc = $CreditNote->appendChild($cbc);
            // 8.- Numeracion , conformada por serie y numero correlativo B001-00012926
            $cbc = $xml->createElement('cbc:ID', $serie.'-'.$cab_doc_gen['CDG_NUM_DOC']); $cbc = $CreditNote->appendChild($cbc);
            // 1.- Fecha de emision 2017-04-13
            $cbc = $xml->createElement('cbc:IssueDate', date("Y-m-d", strtotime($fecha))); $cbc = $CreditNote->appendChild($cbc);
            // 28.- Tipo de moneda en la cual se emite la factura electronica $c19
            $cbc = $xml->createElement('cbc:DocumentCurrencyCode', 'PEN'); $cbc = $CreditNote->appendChild($cbc);

            $cac = $xml->createElement('cac:DiscrepancyResponse'); $cac = $CreditNote->appendChild($cac);
            $cbc = $xml->createElement('cbc:ReferenceID',$ref_doc); $cbc = $cac->appendChild($cbc);
            $cbc = $xml->createElement('cbc:ResponseCode','03'); $cbc = $cac->appendChild($cbc);
            $cbc = $xml->createElement('cbc:Description',$cab_doc_gen['CDG_NOT_001'].' '.$cab_doc_gen['CDG_NOT_002'].' '.$cab_doc_gen['CDG_NOT_003']); $cbc = $cac->appendChild($cbc);

            $BillingReference = $xml->createElement('cac:BillingReference'); $BillingReference = $CreditNote->appendChild($BillingReference);
            $cac = $xml->createElement('cac:InvoiceDocumentReference'); $cac = $BillingReference->appendChild($cac);
            $cbc = $xml->createElement('cbc:ID',$ref_doc); $cbc = $cac->appendChild($cbc);
            $cbc = $xml->createElement('cbc:DocumentTypeCode',$doc_ref_tipo); $cbc = $cac->appendChild($cbc);


            // 2.- Parte de la firma electronica. esto es de quien creo la firma electronica
            $cac_signature = $xml->createElement('cac:Signature'); $cac_signature = $CreditNote->appendChild($cac_signature);
            $cbc = $xml->createElement('cbc:ID', '20532710066'); $cbc = $cac_signature->appendChild($cbc);
            $cac_signatory = $xml->createElement('cac:SignatoryParty'); $cac_signatory = $cac_signature->appendChild($cac_signatory);
            $cac = $xml->createElement('cac:PartyIdentification'); $cac = $cac_signatory->appendChild($cac);
            $cbc = $xml->createElement('cbc:ID', '20532710066'); $cbc = $cac->appendChild($cbc);
            $cac = $xml->createElement('cac:PartyName'); $cac = $cac_signatory->appendChild($cac);
            $cbc = $xml->createElement('cbc:Name', 'SURMOTRIZ S.R.L'); $cbc = $cac->appendChild($cbc);
            $cac_digital = $xml->createElement('cac:DigitalSignatureAttachment'); $cac_digital = $cac_signature->appendChild($cac_digital);
            $cac = $xml->createElement('cac:ExternalReference'); $cac = $cac_digital->appendChild($cac);
            $cbc = $xml->createElement('cbc:URI', 'SIGN'); $cbc = $cac->appendChild($cbc);


            // DATOS EMISOR
            $cac_accounting = $xml->createElement('cac:AccountingSupplierParty'); $cac_accounting = $CreditNote->appendChild($cac_accounting);
            $cbc = $xml->createElement('cbc:CustomerAssignedAccountID', '20532710066'); $cbc = $cac_accounting->appendChild($cbc);
            $cbc = $xml->createElement('cbc:AdditionalAccountID', '6'); $cbc = $cac_accounting->appendChild($cbc);
            $cac_party = $xml->createElement('cac:Party'); $cac_party = $cac_accounting->appendChild($cac_party);
            $address = $xml->createElement('cac:PostalAddress'); $address = $cac_party->appendChild($address);
            $cbc = $xml->createElement('cbc:ID', '220101'); $cbc = $address->appendChild($cbc);//ubigeo
            $cbc = $xml->createElement('cbc:StreetName', 'AV. LEGUIA NRO. 1870'); $cbc = $address->appendChild($cbc);// Direccion
            $cbc = $xml->createElement('cbc:CitySubdivisionName', 'FRENTE A I.E. JOSE ROSA ARA'); $cbc = $address->appendChild($cbc);// urbanizacion
            $cbc = $xml->createElement('cbc:CityName', 'TACNA'); $cbc = $address->appendChild($cbc);//departamento
            $cbc = $xml->createElement('cbc:CountrySubentity', 'TACNA'); $cbc = $address->appendChild($cbc);
            $cbc = $xml->createElement('cbc:District', 'TACNA'); $cbc = $address->appendChild($cbc);
            $country = $xml->createElement('cac:Country'); $country = $address->appendChild($country);// pais
            $cbc = $xml->createElement('cbc:IdentificationCode', 'PE'); $cbc = $country->appendChild($cbc);
            $legal = $xml->createElement('cac:PartyLegalEntity'); $legal = $cac_party->appendChild($legal);// razon social
            $cbc = $xml->createElement('cbc:RegistrationName', 'SURMOTRIZ S.R.L.'); $cbc = $legal->appendChild($cbc);

            //DATOS CLIENTE
            $cac_accounting = $xml->createElement('cac:AccountingCustomerParty'); $cac_accounting = $CreditNote->appendChild($cac_accounting);
            $cbc = $xml->createElement('cbc:CustomerAssignedAccountID', $cab_doc_gen['CDG_DOC_CLI']); $cbc = $cac_accounting->appendChild($cbc);
            $cbc = $xml->createElement('cbc:AdditionalAccountID', $tipo_doc_num); $cbc = $cac_accounting->appendChild($cbc);
            $cac_party = $xml->createElement('cac:Party'); $cac_party = $cac_accounting->appendChild($cac_party);
            // nombre o razon zocial
            $legal = $xml->createElement('cac:PartyLegalEntity'); $legal = $cac_party->appendChild($legal);
            $cbc = $xml->createElement('cbc:RegistrationName', $cab_doc_gen['CDG_NOM_CLI']); $cbc = $legal->appendChild($cbc);

            // Sumatoria IGV
            $taxtotal = $xml->createElement('cac:TaxTotal'); $taxtotal = $CreditNote->appendChild($taxtotal);
            $cbc = $xml->createElement('cbc:TaxAmount', $igv); $cbc = $taxtotal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
            $taxtsubtotal = $xml->createElement('cac:TaxSubtotal'); $taxtsubtotal = $taxtotal->appendChild($taxtsubtotal);
            $cbc = $xml->createElement('cbc:TaxAmount', $igv); $cbc = $taxtsubtotal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
            $taxtcategory = $xml->createElement('cac:TaxCategory'); $taxtcategory = $taxtsubtotal->appendChild($taxtcategory);
            $taxscheme = $xml->createElement('cac:TaxScheme'); $taxscheme = $taxtcategory->appendChild($taxscheme);
            $cbc = $xml->createElement('cbc:ID', '1000'); $cbc = $taxscheme->appendChild($cbc);
            $cbc = $xml->createElement('cbc:Name', 'IGV'); $cbc = $taxscheme->appendChild($cbc);
            $cbc = $xml->createElement('cbc:TaxTypeCode', 'VAT'); $cbc = $taxscheme->appendChild($cbc);

            // Importe total de venta
            $legal = $xml->createElement('cac:LegalMonetaryTotal'); $legal = $CreditNote->appendChild($legal);
            $cbc = $xml->createElement('cbc:PayableAmount', $total); $cbc = $legal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");

            $i=1;
            foreach($items as $item){
                $CreditNoteLine = $xml->createElement('cac:CreditNoteLine'); $CreditNoteLine = $CreditNote->appendChild($CreditNoteLine);
                    $cbc = $xml->createElement('cbc:ID', $i); $cbc = $CreditNoteLine->appendChild($cbc);
                    $cbc = $xml->createElement('cbc:CreditedQuantity', $item['cantidad']); $cbc = $CreditNoteLine->appendChild($cbc); $cbc->setAttribute('unitCode', "NIU"); // cantidad x item:  1
                    $cbc = $xml->createElement('cbc:LineExtensionAmount', $item['venta']); $cbc = $CreditNoteLine->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                    // precio unitario del producto con igv
                    $pricing = $xml->createElement('cac:PricingReference'); $pricing = $CreditNoteLine->appendChild($pricing);
                        $cac = $xml->createElement('cac:AlternativeConditionPrice'); $cac = $pricing->appendChild($cac);
                            // precio unitario con igv
                            $cbc = $xml->createElement('cbc:PriceAmount', $item['unitario']*0.18+$item['unitario']); $cbc = $cac->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                            // 01 con igv, 02 operaciones no onerosas
                            $cbc = $xml->createElement('cbc:PriceTypeCode', '01'); $cbc = $cac->appendChild($cbc);
                    // igv del total del producto aplicado ya el descuento *0.18
                    $taxtotal = $xml->createElement('cac:TaxTotal'); $taxtotal = $CreditNoteLine->appendChild($taxtotal);
                        $cbc = $xml->createElement('cbc:TaxAmount', round($item['venta']*0.18,2)); $cbc = $taxtotal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                        $taxtsubtotal = $xml->createElement('cac:TaxSubtotal'); $taxtsubtotal = $taxtotal->appendChild($taxtsubtotal);
                            $cbc = $xml->createElement('cbc:TaxAmount', round($item['venta']*0.18,2)); $cbc = $taxtsubtotal->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                            $taxtcategory = $xml->createElement('cac:TaxCategory'); $taxtcategory = $taxtsubtotal->appendChild($taxtcategory);
                                $cbc = $xml->createElement('cbc:TaxExemptionReasonCode', '10'); $cbc = $taxtcategory->appendChild($cbc);
                                $taxscheme = $xml->createElement('cac:TaxScheme'); $taxscheme = $taxtcategory->appendChild($taxscheme);
                                    $cbc = $xml->createElement('cbc:ID', '1000'); $cbc = $taxscheme->appendChild($cbc);
                                    $cbc = $xml->createElement('cbc:Name', 'IGV'); $cbc = $taxscheme->appendChild($cbc);
                                    $cbc = $xml->createElement('cbc:TaxTypeCode', 'VAT'); $cbc = $taxscheme->appendChild($cbc);

                $item1 = $xml->createElement('cac:Item'); $item1 = $CreditNoteLine->appendChild($item1);
                    $cbc = $xml->createElement('cbc:Description', $item['descripcion']); $cbc = $item1->appendChild($cbc);
                    $sellers = $xml->createElement('cac:SellersItemIdentification'); $sellers = $item1->appendChild($sellers);
                        $cbc = $xml->createElement('cbc:ID', $item['codigo']); $cbc = $sellers->appendChild($cbc);
                // precio sin igv ejm 83.05
                $price = $xml->createElement('cac:Price'); $price = $CreditNoteLine->appendChild($price);
                $cbc = $xml->createElement('cbc:PriceAmount', $item['venta']); $cbc = $price->appendChild($cbc); $cbc->setAttribute('currencyID', "PEN");
                $i++;
            }


            $xml->formatOutput = true;

            $nom = '20532710066-'.$doc.'-'.$serie.'-'.$cab_doc_gen['CDG_NUM_DOC'];
            $docu = new DOMDocument();
            $docu->loadXML($xml->saveXML());
            $objDSig = new XMLSecurityDSig();
            $objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);
            $objDSig->addReference(
                $docu,
                XMLSecurityDSig::SHA1,
                array('http://www.w3.org/2000/09/xmldsig#enveloped-signature'),
                array('force_uri' => true)
            );
            $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type' => 'private'));
            $objKey->loadKey('../archivos_pem/private_key.pem', true);
            $objDSig->sign($objKey);
            $objDSig->add509Cert(file_get_contents('../archivos_pem/public_key.pem'), true, false, array('subjectName' => true));
            $objDSig->appendSignature($docu->getElementsByTagName('ExtensionContent')->item(1));
            $strings_xml = $docu->saveXML();
            
            ## Creación del archivo .ZIP
            $zip = new ZipArchive;
            $res = $zip->open($ruta.$nom.'.zip', ZipArchive::CREATE);
            $zip->addFromString($nom.'.xml', $strings_xml);
            $zip->close();


            $wsdlURL = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';
            // 20532710066SURMOTR1 TOYOTA2051
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

            $result = soapCall($wsdlURL, $callFunction = "sendBill", $XMLString);
            preg_match_all('/<applicationResponse>(.*?)<\/applicationResponse>/is', $result, $matches);
            $archivo = fopen($ruta.'R-'.$nom.'.zip', 'w+');
            fputs($archivo, base64_decode($matches[1][0]));
            fclose($archivo);
            chmod($ruta.'R-'.$nom.'.zip', 0777);
            sleep(2);
            header('Location: comprobar.php?gen='.$gen.'&emp='.$emp.'&tip='.$cab_doc_gen['CDG_TIP_DOC'].'&num='.$cab_doc_gen['CDG_NUM_DOC'].'');
            //echo '<div style="text-align: center"><img src="images/ok.png"><br>El documento fue enviado Exitosamente..!</div>';
        }else{
            echo '<div style="text-align: center"><img src="images/ok.png"><br>El documento ya fue enviado y Aceptado</div>';
        }

    }catch(Exception $e){
        echo '<div style="text-align: center"><img src="images/error.png"><br>Sucedio un error al enviar el documento'.$e->getMessage().$e->getLine().'</div>';
    }


?>