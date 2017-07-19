<?php


    // 2.- Firmar documento xml
    // ========================
    $nameXml = '20532710066-RA-20170716-1';

    require '../../robrichards/src/xmlseclibs.php';
    use RobRichards\XMLSecLibs\XMLSecurityDSig;
    use RobRichards\XMLSecLibs\XMLSecurityKey;
    // Cargar el XML a firmar
    $doc = new DOMDocument();
    $doc->load($nameXml.'.xml');
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
    $objKey->loadKey('../../archivos_pem/private_key.pem', true);
    $objDSig->sign($objKey);
    // Agregue la clave pública asociada a la firma
    $objDSig->add509Cert(file_get_contents('../../archivos_pem/public_key.pem'), true, false, array('subjectName' => true)); // array('issuerSerial' => true, 'subjectName' => true));
    // Anexar la firma al XML
    $objDSig->appendSignature($doc->getElementsByTagName('ExtensionContent')->item(0));
    // Guardar el XML firmado
    $doc->save($nameXml.'.xml');
    chmod($nameXml.'.xml', 0777);
    // 3.- Enviar documento xml y obtener respuesta
    // ============================================
    require('../../lib/pclzip.lib.php'); // Librería que comprime archivos en .ZIP
    ## Creación del archivo .ZIP
    $zip = new PclZip($nameXml.'.zip');
    $zip->create($nameXml.'.xml',PCLZIP_OPT_REMOVE_ALL_PATH);
    chmod($nameXml.'.zip', 0777);


?>