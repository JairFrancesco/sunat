<?php

require_once 'vendor/autoload.php';

use BigFish\PDF417\PDF417;
use BigFish\PDF417\Renderers\ImageRenderer;

// Cadena de Texto a Codificar
$text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur
imperdiet sit amet magna faucibus aliquet. Aenean in velit in mauris imperdiet
scelerisque. Maecenas a auctor erat.';

// Retorna un objeto BarcodeData
$pdf417 = new PDF417();
$data   = $pdf417->encode($text);

// Crea una imágen PNG
$renderer = new ImageRenderer([
    'format' => 'png',
]);

$image = $renderer->render($data);
$image->save('BarcodeDataPNG.png');
exit;
