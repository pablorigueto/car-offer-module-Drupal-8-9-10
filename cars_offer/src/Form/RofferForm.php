<?php

namespace Drupal\cars_offer\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Url;

/**
 * Provides a controll of proposal to owner.
 */
class RofferForm extends FormBase {

  /**
   * The Drupal database.
   *
   * @var Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    Connection $database,
    MessengerInterface $messenger,
  ) {
    $this->database = $database;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new static(
      $container->get('database'),
      $container->get('messenger'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'roffer_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['select_offers_container'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['select-offers-wrapper-in']],

      'select-offers-number' => [
        '#type' => 'select',
        '#required' => TRUE,
        '#title' => $this->t('Quantidade de propostas'),
        '#ajax' => [
          'callback' => [$this, 'updateOfferNumber'],
          'event' => 'change',
          'wrapper' => 'offer-results-wrapper',
          'progress' => [
            'type' => 'throbber',
            'message' => NULL,
          ],
        ],
        '#options' => [
          '' => '',
          'all' => 'Todas',
          'today' => 'Hoje',
          '5' => '5',
          '10' => '10',
          '20' => '20',
          '30' => '30',
          '40' => '40',
          '50' => '50',
        ],
      ],
      '#prefix' => '<div class="select-offers-wrapper-out">',
      '#suffix' => '</div>',
    ];

    // Add a new element to the form for displaying the query results.
    $form['offer_results'] = [
      '#type' => 'container',
      '#attributes' => [
        'id' => 'offer-results-wrapper',
      ],
      '#prefix' => '<div class="offer-results-wrapper">',
      '#suffix' => '</div>',
    ];

    return $form;
  }

  /**
   * Ajax callback to update the player options list.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function updateOfferNumber(array $form, FormStateInterface $form_state): AjaxResponse {

    $number_of_offers = $form_state->getValue('select-offers-number');

    // Retrieve data from the database using Drupal's database API.
    $query = $this->database->select('clt_offer_table', 'oft');
    $query->fields('oft');
    if ($number_of_offers === 'today') {
      // Get the current time in the default timezone.
      $current_time = new DrupalDateTime();
      $formattedDate = $current_time->format('d-m-Y');
      // Add a WHERE clause to the query.
      $query->condition('oft.create_time', '%' . $formattedDate . '%', 'LIKE');
    }

    // Limit the query based on the number of offers to display.
    if ($number_of_offers !== 'all' && is_numeric($number_of_offers)) {
      $query->range(0, $number_of_offers);
    }

    $results = $query->execute()->fetchAll();

    $offer_results = [
      '#type' => 'table',
      '#header' => [
        $this->t('Proposta'),
        $this->t('Recebido em'),
        $this->t('Carro'),
        $this->t('Nome Cliente'),
        $this->t('Email'),
        $this->t('Telefone'),
        $this->t('Nascimento'),
        $this->t('Valor Entrada R$'),
        $this->t('CPF ou CNPJ'),
        $this->t('Com carro?'),
        $this->t('Quitado?'),
        $this->t('Marca Carro'),
        $this->t('Modelo'),
        $this->t('Câmbio'),
        $this->t('Combustível'),
        $this->t('KM Atual'),
        $this->t('Cor'),
        $this->t('Ano Fab.'),
        $this->t('Ano Modelo'),
        $this->t('Cód. FIPE'),
      ],
      '#prefix' => '<div class="table-header-class">'
      . $this->t('Sua busca retornou: ') .
      count($results)
      . $this->t(' resultados'),
      '#suffix' => '</div>',
    ];
    foreach ($results as $result) {

      if ($result->checkbox_with_car_offer == 0) {
        $withCar = $this->t('Não');
      }
      else {
        $withCar = $this->t('Sim');
      }

      if ($result->clt_debits_offer == 0) {
        $carDebits = $this->t('Não');
      }
      else {
        $carDebits = $this->t('Sim');
      }

      $offer_results[] = [
        'proposalId' => [
          '#markup' =>
          '<a target="_blank" class="details-offer" href="/doffer?offerId=' . $result->id . '">Proposta detalhada</a>',
        ],
        'datetime' => ['#markup' => $result->create_time],
        'nid' => ['#markup' => '<a target="_blank" class="link-offer-class" href="' . $this->getPathAlias($result->nodeId) . '"> Link </a>'],
        'clientName' => ['#markup' => $result->name_offer],
        'email' => ['#markup' => $result->email_offer],
        'phone' => ['#markup' => $result->phone_offer],
        'bday' => ['#markup' => $result->bday_offer],
        'downOffer' => ['#markup' => $result->down_offer],
        'cltId' => ['#markup' => $result->clt_id],
        'withCar' => ['#markup' => $withCar],
        'debits' => ['#markup' => $carDebits],
        'brandCar' => ['#markup' => $result->clt_car_brand_offer],
        'model' => ['#markup' => $result->clt_car_model_offer],
        'gearShift' => ['#markup' => $result->clt_car_gearshift_offer],
        'fuelType' => ['#markup' => $result->clt_car_fuel_offer],
        'kmOrMiles' => ['#markup' => $result->clt_car_kmormiles_offer],
        'color' => ['#markup' => $result->clt_car_color_offer],
        'sttYear'  => ['#markup' => $result->clt_car_sttyear_offer],
        'endYear' => ['#markup' => $result->clt_car_endyear_offer],
        'id' => ['#markup' => $result->clt_car_id_offer],
      ];
    }
    $form['submit'] = $offer_results;

    $response = new AjaxResponse();
    $response->addCommand(new HtmlCommand('#offer-results-wrapper', $form['submit']));
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // $this->messenger->addMessage($this->t('Proposta enviada com sucesso!'));
  }

  /**
   * Get the path alias through the node id.
   */
  public function getPathAlias(int $node_id): string {
    // Get the URL object for the node using its ID.
    $url = Url::fromRoute('entity.node.canonical', ['node' => $node_id]);
    // Get the path alias from the URL object.
    return $url->toString();
  }

  /**
   * Patterns to CPF format. Unused, because already add on JS.
   */
  public function formatCpf(string $cpf): string {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
    return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
  }

  /**
   * Patterns to CNPJ format, because already add on JS.
   */
  public function formatCnpj(string $cnpj): string {
    $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
    $cnpj = str_pad($cnpj, 14, '0', STR_PAD_LEFT);
    return substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12, 2);
  }

}
