<?php

namespace Drupal\ancient_location_qr\Plugin\views\style;

use Drupal\Core\Template\Attribute;
use Drupal\views\Plugin\views\style\StylePluginBase;

/**
 * Renders rows inline for Ancient Locations (Image | Title | QR).
 *
 * @ViewsStyle(
 *   id = "ancient_locations_inline",
 *   title = @Translation("Ancient locations inline (module)"),
 *   help = @Translation("Inline rows suitable for Image | Title | QR."),
 *   theme = "views_view_ancient_locations_inline",
 *   display_types = {"normal", "block"}
 * )
 */
class AncientLocationsInline extends StylePluginBase {

  /**
   * This style uses a Row plugin (e.g., Fields).
   *
   * @var bool
   */
  protected $usesRowPlugin = TRUE;

  /**
   * Allow adding row classes.
   *
   * @var bool
   */
  protected $usesRowClass = TRUE;

  /**
   * Build the render array for this style.
   *
   * We delegate the per-row rendering to the selected Row plugin (Fields),
   * then wrap the rows in our own theme/template.
   *
   * @return array
   *   A render array.
   */
  public function render(): array {
    $rows = [];
    if (!$this->view->rowPlugin) {
      return [];
    }
    foreach ($this->view->result as $row) {
      $rows[] = [
        'content' => $this->view->rowPlugin->render($row),
        'attributes' => new \Drupal\Core\Template\Attribute([]),
      ];
    }
  
    return [
      '#theme'   => $this->themeFunctions(),
      '#view'    => $this->view,
      '#options' => $this->options,
      '#rows'    => $rows,
      '#title'   => $this->view->getTitle(),
      '#attached' => [
        'library' => ['ancient_location_qr/qr_view_styles'],
      ],
    ];
  }

}
