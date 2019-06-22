<?php

namespace Drupal\car_price_calculation;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface defining a car price calculation entity type.
 */
interface CarPriceCalculationInterface extends ContentEntityInterface {

  /**
   * Gets the car price calculation name.
   *
   * @return string
   *   Name of the car price calculation.
   */
  public function getName();

  /**
   * Sets the car price calculation name.
   *
   * @param string $name
   *   The car price calculation name.
   *
   * @return \Drupal\car_price_calculation\CarPriceCalculationInterface
   *   The called car price calculation entity.
   */
  public function setName($name);

  /**
   * Gets the car price calculation creation timestamp.
   *
   * @return int
   *   Creation timestamp of the car price calculation.
   */
  public function getCreatedTime();

  /**
   * Sets the car price calculation creation timestamp.
   *
   * @param int $timestamp
   *   The car price calculation creation timestamp.
   *
   * @return \Drupal\car_price_calculation\CarPriceCalculationInterface
   *   The called car price calculation entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the car price calculation age.
   *
   * @return string
   *   Age of the car price calculation.
   */
  public function getAge();

  /**
   * Gets the car price calculation formatted age.
   *
   * @return string
   *   Formatted age of the car price calculation.
   */
  public function getAgeFormatted();

  /**
   * Sets the car price calculation age.
   *
   * @param string $age
   *   The car price calculation age.
   *
   * @return \Drupal\car_price_calculation\CarPriceCalculationInterface
   *   The called car price calculation entity.
   */
  public function setAge($age);

  /**
   * Gets the car price calculation car size.
   *
   * @return string
   *   Car size of the car price calculation.
   */
  public function getCarSize();

  /**
   * Gets the car price calculation formatted car size.
   *
   * @return string
   *   Formatted car size of the car price calculation.
   */
  public function getCarSizeFormatted();

  /**
   * Sets the car price calculation car size.
   *
   * @param string $car_size
   *   The car price calculation car size.
   *
   * @return \Drupal\car_price_calculation\CarPriceCalculationInterface
   *   The called car price calculation entity.
   */
  public function setCarSize($car_size);

  /**
   * Gets the car price calculation total price.
   *
   * @return string
   *   Total price of the car price calculation.
   */
  public function getTotalPrice();

  /**
   * Gets the car price calculation formatted total price.
   *
   * @return string
   *   Formatted total price of the car price calculation.
   */
  public function getTotalPriceFormatted();

  /**
   * Sets the car price calculation total price.
   *
   * @param string $total_price
   *   The car price calculation total price.
   *
   * @return \Drupal\car_price_calculation\CarPriceCalculationInterface
   *   The called car price calculation entity.
   */
  public function setTotalPrice($total_price);

}
