<?php

namespace Drupal\cars_offer\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'cars_offer_default' formatter.
 *
 * @FieldFormatter (
 * id = "cars_offer_default",
 * label = @Translation("Cars Offer"),
 * field_types = {
 * "cars_offer"
 * }
 * )
 */
class CarsOfferDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      $element[$delta] = [
        '#type' => 'markup',
        '#markup' => $item->value,
      ];
    }

    return $element;
  }

}
