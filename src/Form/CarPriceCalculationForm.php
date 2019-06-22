<?php

namespace Drupal\car_price_calculation\Form;

use Drupal\car_price_calculation\CarTotalPriceCalculator;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form controller for the car price calculation entity edit forms.
 */
class CarPriceCalculationForm extends ContentEntityForm {

  const NOT_ALLOWED_AGE = '<20';

  /**
   * The car total price calculator service.
   *
   * @var \Drupal\car_price_calculation\CarTotalPriceCalculator
   *   The car total price calculator service.
   */
  protected $carTotalPriceCalculator;

  /**
   * Constructs a CarPriceCalculationForm object.
   *
   * @param \Drupal\Core\Entity\EntityRepositoryInterface $entity_repository
   *   The entity repository service.
   * @param \Drupal\car_price_calculation\CarTotalPriceCalculator $car_total_price_calculator
   *   The car total price calculator service.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_type_bundle_info
   *   The entity type bundle service.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   */
  public function __construct(
    EntityRepositoryInterface $entity_repository,
    CarTotalPriceCalculator $car_total_price_calculator,
    EntityTypeBundleInfoInterface $entity_type_bundle_info = NULL,
    TimeInterface $time = NULL
    ) {
    parent::__construct($entity_repository, $entity_type_bundle_info, $time);
    $this->carTotalPriceCalculator = $car_total_price_calculator;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.repository'),
      $container->get('car_total_price_calculator'),
      $container->get('entity_type.bundle.info'),
      $container->get('datetime.time')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $form['age']['widget']['#ajax'] = $form['car_size']['widget']['#ajax'] = [
      'callback' => [$this, 'formAjaxCallback'],
      'progress' => [],
    ];
    $form['age']['widget']['#element_validate'][] = [$this, 'ageElementValidate'];
    $form['total_price']['widget'][0]['value']['#attributes']['readonly'] = TRUE;

    return $form;
  }

  /**
   * Validation handler for Age form element.
   */
  public function ageElementValidate(array &$element, FormStateInterface $form_state) {
    $value = $form_state->getValue($element['#name']);

    if (empty($value) || $value[0]['value'] != self::NOT_ALLOWED_AGE) {
      return;
    }

    // The limit validation errors should be NULL to throw the error but for a
    // some reason it returns an empty array instead.
    $limitValidationErrors = $form_state->getLimitValidationErrors();
    if (empty($limitValidationErrors) && is_array($limitValidationErrors)) {
      $form_state->setLimitValidationErrors(NULL);
    }
    $form_state->setError($element, $this->t('You must be at least 20 years old'));
  }

  /**
   * Ajax callback handler for Age and Car size form elements.
   */
  public function formAjaxCallback($form, FormStateInterface &$form_state) {
    if ($form_state->hasAnyErrors()) {
      $form['status_messages'] = [
        '#type' => 'status_messages',
        '#display' => 'error',
        '#weight' => -10,
      ];
    }
    else {
      $age = $form_state->getValue(['age', 0, 'value']);
      $car_size = $form_state->getValue(['car_size', 0, 'value']);

      if ($age && $car_size) {
        $total_price = $this->carTotalPriceCalculator->calculate($age, $car_size);
        $form['total_price']['widget'][0]['value']['#value'] = $total_price;
      }
    }

    $response = new AjaxResponse();
    $response->addCommand(new ReplaceCommand('.car-price-calculation-form', $form));

    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->getEntity();
    $result = $entity->save();
    $link = $entity->toLink($this->t('View'))->toRenderable();

    $message_arguments = ['%label' => $this->entity->label()];
    $logger_arguments = $message_arguments + ['link' => render($link)];

    if ($result == SAVED_NEW) {
      $this->messenger()->addStatus($this->t('Thank you for your submission.', $message_arguments));
      $this->logger('car_price_calculation')->notice('Created new car price calculation %label', $logger_arguments);
    }
    else {
      $this->messenger()->addStatus($this->t('The car price calculation %label has been updated.', $message_arguments));
      $this->logger('car_price_calculation')->notice('Updated the car price calculation %label.', $logger_arguments);
    }
  }

}
