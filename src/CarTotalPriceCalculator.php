<?php

namespace Drupal\car_price_calculation;

use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * CarTotalPriceCalculator service.
 */
class CarTotalPriceCalculator {

  protected static $ageMap = [
    '<20' => 0,
    '20-24' => 0.2,
    '25+' => 0,
  ];

  protected static $carSizeMap = [
    'small' => 0,
    'medium' => 0.5,
    'large' => 1,
  ];

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a CarTotalPriceCalculator object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * Calculate car total price.
   *
   * @param string $age
   *   The age option name.
   * @param string $car_size
   *   The car size option name.
   *
   * @return int
   *   The car total price value.
   */
  public function calculate($age, $car_size) {
    $total_price = $this->getFixedPrice() + $this->getVariablePrice() * (1 + $this->getAgeValue($age) + $this->getCarSizeValue($car_size));
    return round($total_price, 0);
  }

  /**
   * Get car price calculation settings.
   */
  protected function settings() {
    return $this->configFactory->get('car_price_calculation.settings');
  }

  /**
   * Get the fixed price value defined in the settings.
   */
  protected function getFixedPrice() {
    return $this->settings()->get('fixed_price');
  }

  /**
   * Get the variable price value defined in the settings.
   */
  protected function getVariablePrice() {
    return $this->settings()->get('variable_price');
  }

  /**
   * Get age value according to the mapping.
   *
   * @param string $age
   *   The age option name.
   *
   * @return float|int
   *   The age value.
   */
  protected function getAgeValue($age) {
    return isset(self::$ageMap[$age]) ? self::$ageMap[$age] : 0;
  }

  /**
   * Get car size value according to the mapping.
   *
   * @param string $car_size
   *   The car size option name.
   *
   * @return float|int
   *   The car size value.
   */
  protected function getCarSizeValue($car_size) {
    return isset(self::$carSizeMap[$car_size]) ? self::$carSizeMap[$car_size] : 0;
  }

}
