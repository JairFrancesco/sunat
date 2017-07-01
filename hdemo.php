<?php

$objClient = new soapclient(null,array('uri'=>'https://www.sunat.gob.pe:443/ol-ti-itcpgem-sqa/billService?wsdl'));
$objClient->__call('senBill',array());

?>