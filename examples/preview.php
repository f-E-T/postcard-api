<?php

use Fet\PostcardApi\PostcardCreator;

include_once '../vendor/autoload.php';

if (!isset($_GET['card-key'])) {
    return;
}

$cardKey = $_GET['card-key'];

$config = include 'config.php';

$postcardCreator = PostcardCreator::factory(
  $config['url'],
  $config['campaign_key'],
  $config['client_id'],
  $config['client_secret'],
);

$postcard = $postcardCreator->get($cardKey);

if (isset($_GET['side'])) {
    $side = $_GET['side'];

    switch ($side) {
        case 'back':
            $previewImage = $postcard->getBackPreview()->getImageData();
            break;
        default:
            $previewImage = $postcard->getFrontPreview()->getImageData();
            break;
    }

    header('Content-Type: image/jpg');
    echo base64_decode($previewImage);
}
