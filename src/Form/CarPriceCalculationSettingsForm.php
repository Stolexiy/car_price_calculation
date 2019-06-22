<?php

namespace Drupal\car_price_calculation\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configuration form for a car price calculation entity type.
 */
class CarPriceCalculationSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'car_price_calculation_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['car_price_calculation.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('car_price_calculation.settings');

    $form['settings'] = [
      '#markup' => $this->t('Settings form for a car price calculation entity type.'),
    ];

    $form['fixed_price'] = [
      '#type' => 'number',
      '#title' => $this->t('Fixed price'),
      '#step' => 'any',
      '#min' => 0,
      '#default_value' => $config->get('fixed_price'),
    ];
    $form['variable_price'] = [
      '#type' => 'number',
      '#title' => $this->t('Variable price'),
      '#step' => 'any',
      '#min' => 0,
      '#default_value' => $config->get('variable_price'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('car_price_calculation.settings')
      ->set('fixed_price', $form_state->getValue('fixed_price'))
      ->set('variable_price', $form_state->getValue('variable_price'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
