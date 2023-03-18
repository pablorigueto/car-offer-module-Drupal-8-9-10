<?php

namespace Drupal\cars_offer\Plugin\Field\FieldType;

use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldItemInterface;

/**
 * Plugin implementation of the 'cars_offer' field type.
 *
 * @FieldType(
 * id = "cars_offer",
 * label = @Translation("Year only"),
 * description = @Translation("This field provide the ways to collect year only in provided date range."),
 * default_widget = "cars_offer_default",
 * default_formatter = "cars_offer_default",
 * )
 */
class CarsOfferItem extends FieldItemBase implements FieldItemInterface {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'value' => [
          'type' => 'int',
          'length' => 50,
        ],
      ],
      'indexes' => [
        'value' => [
          'value',
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties = [];
    $properties['value'] = DataDefinition::create('integer')
      ->setLabel(t('Year'))
      ->setRequired(TRUE);
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('value')->getValue();
    return $value === NULL || $value === '';
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    return [
      'year_from' => '',
      'year_to' => '',
    ] + parent::defaultFieldSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    $element = [];

    $element['#markup'] = '<strong>' . $this->t('Select year field range. This field will display the range as selected below on form.') . '</strong>';

    $options = array_combine(
    range(1900, date('Y') - 1),
    range(1900, date('Y') - 1));
    $element['year_from'] = [
      '#type' => 'select',
      '#title' => $this->t('From'),
      '#default_value' => $this->getSetting('year_from'),
      '#options' => $options,
      '#description' => $this->t('Select starting year.'),
      '#weight' => 1,
    ];

    $options = [
      'now' => 'NOW',
    ];
    $options += array_combine(
      range(date('Y') + 1, date('Y') + 50),
      range(date('Y') + 1, date('Y') + 50));
    $element['year_to'] = [
      '#type' => 'select',
      '#title' => $this->t('To'),
      '#default_value' => $this->getSetting('year_to'),
      '#options' => $options,
      '#description' => $this->t('Select last year.'),
      '#weight' => 1,
    ];

    return $element;
  }

}
