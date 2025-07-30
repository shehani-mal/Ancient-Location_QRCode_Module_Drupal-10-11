<?php

namespace Drupal\ancient_location_qr\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\ancient_location_qr\Service\QRCodeGenerator;

/**
 * Plugin implementation of the 'qr_code_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "qr_code_formatter",
 *   label = @Translation("QR Code Formatter"),
 *   field_types = {
 *     "string",
 *     "string_long",
 *     "uri"
 *   }
 * )
 */
class QRCodeFormatter extends FormatterBase implements ContainerFactoryPluginInterface {

  protected $qrService;

  public function __construct(
    $plugin_id,
    $plugin_definition,
    FieldDefinitionInterface $field_definition,
    array $settings,
    $label,
    $view_mode,
    array $third_party_settings,
    QRCodeGenerator $qr_service
  ) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
    $this->qrService = $qr_service;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('ancient_location_qr.generator')
    );
  }

  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $url = $item->value;

      $qrCodeUrl = $this->qrService->generateQrCodeImageUrl($url);
      $elements[$delta] = [
        '#theme' => 'image',
        '#uri' => $qrCodeUrl,
        '#alt' => $this->t('QR code for @url', ['@url' => $url]),
        '#title' => $this->t('Scan to visit'),
      ];
    }

    return $elements;
  }

}
