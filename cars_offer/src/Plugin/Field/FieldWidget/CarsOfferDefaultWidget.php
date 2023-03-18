<?php

namespace Drupal\cars_offer\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\WidgetInterface;

/**
 * Plugin implementation of the 'cars_offer_default' widget.
 *
 * @FieldWidget(
 * id = "cars_offer_default",
 * label = @Translation("Select Year"),
 * field_types = {
 * "cars_offer"
 * }
 * )
 */
class CarsOfferDefaultWidget extends WidgetBase implements WidgetInterface {

  /**
   * {@inheritdoc}
   */
  public function formElement(
    FieldItemListInterface $items,
    $delta, array $element,
    array &$form, FormStateInterface $form_state
  ) {
    $field_settings = $this->getFieldSettings();
    if ($field_settings['year_to'] == 'now') {
      $field_settings['year_to'] = date('Y');
    }

    $options = array_combine(range($field_settings['year_to'], $field_settings['year_from']), range($field_settings['year_to'], $field_settings['year_from']));
    $element['value'] = $element + [
      '#type' => 'select',
      '#options' => $options,
      '#empty_value' => '',
      '#default_value' => $items[$delta]->value ?? '',
      '#description' => $this->t('Select year'),
    ];
    return $element;
  }

}
