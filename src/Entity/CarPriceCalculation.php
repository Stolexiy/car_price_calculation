<?php

namespace Drupal\car_price_calculation\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\car_price_calculation\CarPriceCalculationInterface;

/**
 * Defines the car price calculation entity class.
 *
 * @ContentEntityType(
 *   id = "car_price_calculation",
 *   label = @Translation("Car price calculation"),
 *   label_collection = @Translation("Car price calculations"),
 *   handlers = {
 *     "list_builder" = "Drupal\car_price_calculation\CarPriceCalculationListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\car_price_calculation\Form\CarPriceCalculationForm",
 *       "edit" = "Drupal\car_price_calculation\Form\CarPriceCalculationForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "access" = "Drupal\car_price_calculation\CarPriceCalculationAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "car_price_calculation",
 *   admin_permission = "administer car price calculation",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/car-price-calculation/{car_price_calculation}",
 *     "add-form" = "/admin/content/car-price-calculation/add",
 *     "edit-form" = "/admin/content/car-price-calculation/{car_price_calculation}/edit",
 *     "delete-form" = "/admin/content/car-price-calculation/{car_price_calculation}/delete",
 *     "collection" = "/admin/content/car-price-calculation"
 *   },
 * )
 */
class CarPriceCalculation extends ContentEntityBase implements CarPriceCalculationInterface {

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getAge() {
    return $this->get('age')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getAgeFormatted() {
    return $this->get('age')->view(['label' => 'hidden']);
  }

  /**
   * {@inheritdoc}
   */
  public function setAge($age) {
    $this->set('age', $age);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCarSize() {
    return $this->get('car_size')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getCarSizeFormatted() {
    return $this->get('car_size')->view(['label' => 'hidden']);
  }

  /**
   * {@inheritdoc}
   */
  public function setCarSize($car_size) {
    $this->set('car_size', $car_size);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTotalPrice() {
    return $this->get('total_price')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getTotalPriceFormatted() {
    return $this->get('total_price')->view(['label' => 'hidden']);
  }

  /**
   * {@inheritdoc}
   */
  public function setTotalPrice($total_price) {
    $this->set('total_price', $total_price);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the car price calculation was created.'));

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', ['type' => 'string_textfield']);
    $fields['age'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Age'))
      ->setTranslatable(FALSE)
      ->setRequired(TRUE)
      ->setSettings([
        'allowed_values' => [
          '<20' => '&lt;20',
          '20-24' => '20-24',
          '25+' => '25+',
        ],
      ])
      ->setDisplayOptions('form', ['type' => 'options_select'])
      ->setDisplayOptions('view', ['type' => 'list_default']);

    $fields['car_size'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Car size'))
      ->setTranslatable(FALSE)
      ->setRequired(TRUE)
      ->setSettings([
        'allowed_values' => [
          'small' => t('Small'),
          'medium' => t('Medium'),
          'large' => t('Large'),
        ],
      ])
      ->setDisplayOptions('form', ['type' => 'options_select'])
      ->setDisplayOptions('view', ['type' => 'list_default']);

    $fields['total_price'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Total price'))
      ->setTranslatable(FALSE)
      ->setRequired(TRUE)
      ->setDisplayOptions('form', ['type' => 'number'])
      ->setSettings(['prefix' => '$'])
      ->setDisplayOptions('view', ['type' => 'number_decimal']);

    return $fields;
  }

}
