<?php

namespace Drupal\ancient_location_qr\Service;

use Drupal\Core\File\FileSystemInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Color\Color;

class QRCodeGenerator {

  protected $fileSystem;

  public function __construct(FileSystemInterface $file_system) {
    $this->fileSystem = $file_system;
  }

  public function generateQrCodeImageUrl($data) {
    $build = Builder::create()
      ->writer(new PngWriter())
      ->data($data)
      ->encoding(new Encoding('UTF-8'))
      ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
      ->size(300)
      ->margin(10)
      ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
      ->backgroundColor(new Color(255, 255, 255))
      ->build();

    $hash = md5($data);
    $directory = 'public://qr_codes';
    $this->fileSystem->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);

    $filename = "$directory/qr_$hash.png";
    file_put_contents($filename, $build->getString());

    return $filename;
  }

}
