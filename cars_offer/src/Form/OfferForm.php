<?php

namespace Drupal\cars_offer\Form;

use Drupal\Component\Utility\EmailValidatorInterface as UtilityEmailValidatorInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Datetime\DrupalDateTime;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Provides a form to sendo offer to owner.
 */
class OfferForm extends FormBase {

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The Entity Type Manager Interface.
   *
   * @var Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The Language Manager Interface.
   *
   * @var Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * The Request Stack service.
   *
   * @var Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * The render image service.
   *
   * @var Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * The email validator service.
   *
   * @var Drupal\Core\Utility\EmailValidatorInterface
   */
  protected $emailValidator;

  /**
   * The Drupal database.
   *
   * @var Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    MessengerInterface $messenger,
    EntityTypeManagerInterface $entityTypeManager,
    LanguageManagerInterface $languageManager,
    RequestStack $requestStack,
    RendererInterface $renderer,
    UtilityEmailValidatorInterface $emailValidator,
    Connection $database,
  ) {
    $this->messenger = $messenger;
    $this->entityTypeManager = $entityTypeManager;
    $this->languageManager = $languageManager;
    $this->requestStack = $requestStack;
    $this->renderer = $renderer;
    $this->emailValidator = $emailValidator;
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new static(
      $container->get('messenger'),
      $container->get('entity_type.manager'),
      $container->get('language_manager'),
      $container->get('request_stack'),
      $container->get('renderer'),
      $container->get('email.validator'),
      $container->get('database'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'offering_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $currentRequest = $this->requestStack->getCurrentRequest();
    $node_id = $currentRequest->query->get('nid');
    $currentLanguage = $this->currentLanguage()->getId();

    if ($currentLanguage == 'pt-br') {
      $kmOrMiles = 'KM';
      $currency = 'R$';
    }
    elseif ($currentLanguage == 'en' || $currentLanguage == 'en-us') {
      $kmOrMiles = 'Miles';
      $currency = '$';
    }

    if (!empty($node_id)) {
      // Load the node object.
      $node = $this->entityTypeManager->getStorage('node');
      $node = $node->load($node_id);
      $car_uri = $this->singleThumb($node_id);
      $car_brand = $node->get('car_brands')->value;
      $car_model = $node->get('field_car_models')->value;
      $car_engine = $node->get('engine_details')->value;
      $car_fuel = $node->get('fuel_type')->value;
      $car_gearshift = $node->get('engine_gearshift_type')->value;
      $car_kmOrMiles = number_format($node->get('field_car_km_miles')->value / 1000, 3);
      $car_sttyear = $node->get('field_start_year')->value;
      $car_endyear = $node->get('field_end_year')->value;
      $car_price = number_format($node->get('end_price')->value, 2, ',', '.');

      // Get the current site link.
      $siteUrl = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();

      $form['offer_to_car_wrapper'] = [
        '#type' => 'container',
        '#attributes' => ['class' => ['selected-car-wrapper-inside']],
        'offer_to_car' => [
          '#type' => 'textfield',
          '#title' => $this->t('Proposta para o carro'),
          '#prefix' => '<div class="car-details-to-offer">',
          '#suffix' => '</div>',
          '#default_value' => $this->t('ID:') . ' ' . $node_id . ' ' . $car_brand . ' ' . $car_model . ' ' . $car_engine . ' ' . $car_fuel . ' ' . $car_gearshift . ', ' . $kmOrMiles . ': ' . $car_kmOrMiles . ', Ano: ' . $car_sttyear . '/' . $car_endyear . ', ' . $currency . ': ' . $car_price,
          '#attributes' => [
            'readonly' => 'readonly',
          ],
        ],
        'offer_to_car_image' => [
          '#type' => 'container',
          '#prefix' => '<a href="' . $siteUrl . $this->getPathAlias($node_id) . '" target="_blank" class="offer-class-btn"><div class="new-offer-image">' . $car_uri,
          '#suffix' => '</div></a>',
          '#attributes' => [
            'id' => 'offer-image-wrapper',
          ],
        ],
        '#prefix' => '<div class="selected-car-wrapper">',
        '#suffix' => '</div>',
      ];
    }
    else {
      $allNodes = $this->getAllNodes();
      $carOptions = ['' => $this->t('Selecione um carro')];
      foreach ($allNodes as $allNode) {
        $carOptions += [
          $allNode['node_id'] => $this->t('ID:') . ' ' . $allNode['node_id'] . ' ' . $allNode['car_brand'] . ' ' . $allNode['car_model'] . ' ' . $allNode['car_engine'] . ' ' . $allNode['car_fuel'] . ' ' . $allNode['car_gearshift'] . ', ' . $kmOrMiles . ': ' . $allNode['car_kmOrMiles'] . ', Ano: ' . $allNode['car_sttyear'] . '/' . $allNode['car_endyear'] . ', ' . $currency . ': ' . $allNode['car_price'],
        ];
      }

      $form['offer_to_car_wrapper'] = [
        '#type' => 'container',
        '#attributes' => ['class' => ['selected-car-wrapper-inside']],
        'offer_to_car' => [
          '#type' => 'select',
          '#title' => $this->t('Proposta para o carro'),
          '#required' => TRUE,
          '#options' => $carOptions,
          '#prefix' => '<div class="car-details-to-offer">',
          '#suffix' => '</div>',
          '#ajax' => [
            'callback' => [$this, 'updateOfferToCarImageField'],
            'event' => 'change',
            'wrapper' => 'offer-image-wrapper',
            'progress' => [
              'type' => 'throbber',
              'message' => NULL,
            ],
          ],
        ],
        'offer_to_car_image' => [
          '#type' => 'container',
          '#prefix' => '<div class="new-offer-image">',
          '#suffix' => '</div>',
          '#attributes' => [
            'id' => 'offer-image-wrapper',
          ],
        ],
        '#prefix' => '<div class="selected-car-wrapper">',
        '#suffix' => '</div>',
      ];
    }

    $form['contact_info_wrapper_parent'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['contact-info-wrapper-inside']],

      'contact_info_wrapper' => [
        '#type' => 'container',
        '#attributes' => ['class' => ['contact-info-wrapper']],

        'name_offer' => [
          '#type' => 'textfield',
          '#title' => $this->t('Nome e sobrenome'),
          '#placeholder' => $this->t('João Silva'),
          '#required' => TRUE,
        ],
        'email_offer' => [
          '#type' => 'textfield',
          '#title' => $this->t('Email'),
          '#placeholder' => $this->t('example@example.com'),
          '#required' => TRUE,
        ],
        'phone_offer' => [
          '#type' => 'textfield',
          '#title' => $this->t('Telefone com DDD'),
          '#placeholder' => $this->t('(19) 99999-9999'),
          '#required' => TRUE,
        ],
        'bday_offer' => [
          '#type' => 'textfield',
          '#title' => $this->t('Data de Nascimento'),
          '#placeholder' => $this->t('01/01/2000'),
          '#required' => TRUE,
        ],
        'down_offer' => [
          '#type' => 'textfield',
          '#title' => $this->t('Valor da entrada'),
          '#placeholder' => $this->t('50.000'),
          '#required' => TRUE,
        ],
        'clt_id' => [
          '#type' => 'textfield',
          '#title' => $this->t('Se deseja simulação de financiamento, informe seu CPF ou CNPJ'),
          '#placeholder' => $this->t('Somente números'),
        ],
        'checkbox_with_car_offer' => [
          '#type' => 'checkbox',
          '#title' => $this->t('Com carro na troca?'),
          '#default_value' => FALSE,
        ],
        'clt_debits_offer' => [
          '#type' => 'checkbox',
          '#title' => $this->t('Mais informações ou comentários?'),
          '#default_value' => FALSE,
          '#states' => [
            'visible' => [
              ':input[name="checkbox_with_car_offer"]' => [
                'checked' => TRUE,
              ],
            ],
          ],
        ],
        'clt_extra_info' => [
          '#type' => 'textarea',
          '#title' => $this->t('Comentários extras'),
          '#placeholder' => $this->t('Detalhe extra, se falta parcela ou outra informação'),
          '#maxlength_js' => TRUE,
          '#attributes' =>
          [
            'maxlength' => 255,
          ],
          '#states' => [
            'visible' => [
              ':input[name="checkbox_with_car_offer"]' => [
                'checked' => TRUE,
              ],
              ':input[name="clt_debits_offer"]' => [
                'checked' => TRUE,
              ],
            ],
          ],
        ],
        '#prefix' => '<div class="contact-info-wrapper">',
        '#suffix' => '</div>',
      ],
      'clt_car_info' => [
        '#type' => 'container',
        '#attributes' => ['class' => ['clt-car-info-wrapper']],
        'clt_car_brand_offer' => [
          '#type' => 'select',
          // '#required' => TRUE,
          '#options' => [
            '' => $this->t('Selecione uma opção'),
            'Honda' => 'Honda',
            'Toyota' => 'Toyota',
            'Volkswagen' => 'Volkswagen',
            'Chevrolet' => 'Chevrolet',
            'Fiat' => 'Fiat',
            'Ford' => 'Ford',
            'Hyundai' => 'Hyundai',
            'Nissan' => 'Nissan',
            'Volvo' => 'Volvo',
            'Peugeot' => 'Peugeot',
            'Renault' => 'Renault',
            'Citroen' => 'Citroen',
            'Abarth' => 'Abarth',
            'Adamo' => 'Adamo',
            'Agrale' => 'Agrale',
            'Aldee' => 'Aldee',
            'Alfa Romeo' => 'Alfa Romeo',
            'Americar' => 'Americar',
            'Audi' => 'Audi',
            'Aurora' => 'Aurora',
            'Avallone' => 'Avallone',
            'Bianco' => 'Bianco',
            'BMW' => 'BMW',
            'Bola' => 'Bola',
            'Brasinca' => 'Brasinca',
            'CBP' => 'CBP',
            'CBT' => 'CBT',
            'Chamonix' => 'Chamonix',
            'Chery' => 'Chery',
            'Chrysler/Dodge' => 'Chrysler/Dodge',
            'Concorde' => 'Concorde',
            'Corona' => 'Corona',
            'Cross Lander' => 'Cross Lander',
            'Daewoo' => 'Daewoo',
            'Daihatsu' => 'Daihatsu',
            'Dankar' => 'Dankar',
            'DKW-Vemag' => 'DKW-Vemag',
            'Edra' => 'Edra',
            'Emis' => 'Emis',
            'Engerauto' => 'Engerauto',
            'Engesa' => 'Engesa',
            'Envemo' => 'Envemo',
            'Envesa' => 'Envesa',
            'Effa' => 'Effa',
            'Farus' => 'Farus',
            'FNM' => 'FNM',
            'Furglass' => 'Furglass',
            'Geely' => 'Geely',
            'Glaspac' => 'Glaspac',
            'GMC' => 'GMC',
            'Grancar' => 'Grancar',
            'Gurgel' => 'Gurgel',
            'Hofstetter' => 'Hofstetter',
            'Hummer' => 'Hummer',
            'IBAP' => 'IBAP',
            'Inbrave' => 'Inbrave',
            'Infiniti' => 'Infiniti',
            'Ita' => 'Ita',
            'JAC' => 'JAC',
            'Jaguar' => 'Jaguar',
            'Jeep' => 'Jeep',
            'JPX' => 'JPX',
            'Kadron' => 'Kadron',
            'Kia' => 'Kia',
            'Lada' => 'Lada',
            'Lafer' => 'Lafer',
            'Land Rover' => 'Land Rover',
            'Lexus' => 'Lexus',
            'LHM' => 'LHM',
            'Lifan' => 'Lifan',
            'Lincoln' => 'Lincoln',
            'Lobini' => 'Lobini',
            'Lorena' => 'Lorena',
            'Macan' => 'Macan',
            'Mahindra' => 'Mahindra',
            'Malzoni' => 'Malzoni',
            'Matra Veículos' => 'Matra Veículos',
            'Mazda' => 'Mazda',
            'Megastar Veículos' => 'Megastar Veículos',
            'Mercedes-Benz' => 'Mercedes-Benz',
            'MG' => 'MG',
            'Mini' => 'Mini',
            'Mirage' => 'Mirage',
            'Mitsubishi' => 'Mitsubishi',
            'Miura' => 'Miura',
            'Monarca' => 'Monarca',
            'NBM' => 'NBM',
            'Nobre' => 'Nobre',
            'PAG/Dacon' => 'PAG/Dacon',
            'Polystilo' => 'Polystilo',
            'Puma' => 'Puma',
            'Py Motors' => 'Py Motors',
            'Ragge' => 'Ragge',
            'Romi' => 'Romi',
            'Saab' => 'Saab',
            'Simca' => 'Simca',
            'Santa Matilde' => 'Santa Matilde',
            'San Vito' => 'San Vito',
            'SEAT' => 'SEAT',
            'Smart' => 'Smart',
            'STV' => 'STV',
            'Spiller Mattei' => 'Spiller Mattei',
            'SR Veículos Especiais' => 'SR Veículos Especiais',
            'Subaru' => 'Subaru',
            'Suzuki' => 'Suzuki',
            'SsangYong' => 'SsangYong',
            'TAC Motors' => 'TAC Motors',
            'Tanger' => 'Tanger',
            'Trimax' => 'Trimax',
            'Troller' => 'Troller',
            'Villa' => 'Villa',
            'WMV' => 'WMV',
            'Willys Overland' => 'Willys Overland',
          ],
          '#title' => $this->t('Marca do seu carro'),
          '#states' => [
            'visible' => [
              ':input[name="checkbox_with_car_offer"]' => ['checked' => TRUE],
            ],
            'required' => [
              ':input[name="checkbox_with_car_offer"]' => ['checked' => TRUE],
            ],
          ],
        ],
        'clt_car_model_offer' => [
          '#type' => 'textfield',
          // '#required' => TRUE,
          '#title' => $this->t('Modelo do seu carro'),
          '#placeholder' => $this->t('Civic Touring 1.5 Turbo'),
          '#states' => [
            'visible' => [
              ':input[name="checkbox_with_car_offer"]' => ['checked' => TRUE],
            ],
            'required' => [
              ':input[name="checkbox_with_car_offer"]' => ['checked' => TRUE],
            ],
          ],
        ],
        'clt_car_gearshift_offer' => [
          '#type' => 'select',
          // '#required' => TRUE,
          '#title' => $this->t('Câmbio'),
          '#options' => [
            '' => $this->t('Selecione uma opção'),
            'Manual' => 'Manual',
            'CVT' => 'CVT',
            'Automática' => 'Automática',
            'Automática sequencial' => 'Automática sequencial',
            'Automatizada' => 'Automatizada',
            'Automatizada dct' => 'Automatizada dct',
            'Semi-automática' => 'Semi-automática',
          ],
          '#states' => [
            'visible' => [
              ':input[name="checkbox_with_car_offer"]' => ['checked' => TRUE],
            ],
            'required' => [
              ':input[name="checkbox_with_car_offer"]' => ['checked' => TRUE],
            ],
          ],
        ],
        'clt_car_fuel_offer' => [
          '#type' => 'select',
          // '#required' => TRUE,
          '#title' => $this->t('Combustível'),
          '#options' => [
            '' => $this->t('Selecione uma opção'),
            'Flex' => 'Flex',
            'Gasolina' => 'Gasolina',
            'Etanol' => 'Etanol',
            'Diesel' => 'Diesel',
            'Híbrido' => 'Híbrido',
            'Elétrico' => 'Elétrico',
            'Gás' => 'Gás',
          ],
          '#states' => [
            'visible' => [
              ':input[name="checkbox_with_car_offer"]' => ['checked' => TRUE],
            ],
            'required' => [
              ':input[name="checkbox_with_car_offer"]' => ['checked' => TRUE],
            ],
          ],
        ],
        'clt_car_kmormiles_offer' => [
          '#type' => 'textfield',
          '#title' => $this->t('Quilometragem'),
          '#placeholder' => $this->t('25000'),
          '#states' => [
            'visible' => [
              ':input[name="checkbox_with_car_offer"]' => ['checked' => TRUE],
            ],
            'required' => [
              ':input[name="checkbox_with_car_offer"]' => ['checked' => TRUE],
            ],
          ],
        ],
        'clt_car_color_offer' => [
          '#type' => 'textfield',
          '#title' => $this->t('Cor'),
          '#placeholder' => $this->t('Branco, Cinza, Preto e etc'),
          '#states' => [
            'visible' => [
              ':input[name="checkbox_with_car_offer"]' => [
                'checked' => TRUE,
              ],
            ],
          ],
        ],
        'clt_car_sttyear_offer' => [
          '#type' => 'textfield',
          '#title' => $this->t('Ano fabricação'),
          '#placeholder' => $this->t('2019'),
          '#states' => [
            'visible' => [
              ':input[name="checkbox_with_car_offer"]' => ['checked' => TRUE],
            ],
            'required' => [
              ':input[name="checkbox_with_car_offer"]' => ['checked' => TRUE],
            ],
          ],
        ],
        'clt_car_endyear_offer' => [
          '#type' => 'textfield',
          '#title' => $this->t('Ano modelo'),
          '#placeholder' => $this->t('2020'),
          '#states' => [
            'visible' => [
              ':input[name="checkbox_with_car_offer"]' => ['checked' => TRUE],
            ],
            'required' => [
              ':input[name="checkbox_with_car_offer"]' => ['checked' => TRUE],
            ],
          ],
        ],
        'clt_car_id_offer' => [
          '#type' => 'textfield',
          '#title' => $this->t('Possui o código FIPE? <a href="https://veiculos.fipe.org.br/" target="_blank" class="fipe-link-class" ">Link FIPE</a> '),
          '#states' => [
            'visible' => [
              ':input[name="checkbox_with_car_offer"]' => [
                'checked' => TRUE,
              ],
            ],
          ],
        ],
        '#prefix' => '<div class="clt-car-info-wrapper">',
        '#suffix' => '</div>',
      ],
      '#prefix' => '<div class="contact-info-wrapper-parent">',
      '#suffix' => '</div>',
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Enviar Proposta'),
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
  public function updateOfferToCarImageField(array $form, FormStateInterface $form_state): AjaxResponse {

    $nid = $form_state->getValue('offer_to_car');

    $car_uri = $this->singleThumb($nid);

    $siteUrl = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();

    $car_uri = '<a href="' . $siteUrl . $this->getPathAlias($nid) .
      '" target="_blank" class="offer-class-btn"><div class="new-offer-image">' .
      $car_uri . '</div></a>';

    $response = new AjaxResponse();
    $response->addCommand(new HtmlCommand('#offer-image-wrapper', $car_uri));
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function singleThumb(int $node_id): string {
    // Check if the node was found.
    if (!empty($node_id)) {
      // Load the node object.
      $node = $this->entityTypeManager->getStorage('node');
      $node = $node->load($node_id);
      $media_id = $node->get('media_car')->getValue()[0]['target_id'];
      $file = $this->entityTypeManager->getStorage('file');
      $file = $file->load($media_id);

      if ($file) {
        // Get the URI of the image file.
        $image_url = $file->getFileUri();

        $image = [
          '#theme' => 'image_style',
          '#style_name' => 'thumbnail',
          '#uri' => $image_url,
        ];
      }

      return $this->renderer->render($image);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $email = $form_state->getValue('email_offer');

    // Check if the email address is valid.
    if (!$this->emailValidator->isValid($email)) {
      $form_state->setErrorByName('email', $this->t('Please enter a valid email address.'));
    }

    // Check if the email address is from a banned domain.
    $banned_domains = ['example.com', 'test.com'];
    $domain = substr(strrchr($email, "@"), 1);
    if (in_array($domain, $banned_domains)) {
      $form_state->setErrorByName('email', $this->t('Email inválido, ex: example@example.com.'));
    }

    $telephone = $form_state->getValue('phone_offer');

    // Remove all non-numeric characters from the telephone number.
    $telephone = preg_replace('/\D/', '', $telephone);

    // Check if the telephone number is valid.
    if (strlen($telephone) <= 7 || strlen($telephone) >= 12 || !preg_match('/^([0-9]{2})([0-9]{8,9})$/', $telephone)) {
      $form_state->setErrorByName('telephone', $this->t('Nro de telefone inválido, ex: (19) 99999-9999.'));
    }

    $birthdate = $form_state->getValue('bday_offer');

    // Check if the birthdate is valid.
    if (!\DateTime::createFromFormat('d/m/Y', $birthdate)) {
      $form_state->setErrorByName('birthdate', $this->t('Data de Nascimento inválida, ex: 01/01/2000.'));
    }

    $nameOffer = $form_state->getValue('name_offer');
    if (strlen($nameOffer) < 5) {
      $form_state->setErrorByName('nameOffer', $this->t('Insira nome com pelo menos 4 caracteres'));
    }

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {

    // Get the current time in the default timezone.
    $current_time = new DrupalDateTime();
    $formattedDate = $current_time->format('d-m-Y H:i:s');

    // This info comes from 2 different ways and need to be trait.
    $offer_to_car = $form_state->getValue('offer_to_car');
    // If the offer car is not numeric, trait it.
    if (!ctype_digit($offer_to_car)) {
      $parts = explode(" ", $offer_to_car);
      $offer_to_car = trim($parts[1]);
    }

    $name_offer = $form_state->getValue('name_offer');
    $email_offer = $form_state->getValue('email_offer');
    $phone_offer = $form_state->getValue('phone_offer');
    $bday_offer = $form_state->getValue('bday_offer');
    $down_offer = $form_state->getValue('down_offer');
    $clt_id = $form_state->getValue('clt_id');
    $checkbox_with_car_offer = $form_state->getValue('checkbox_with_car_offer');
    $clt_debits_offer = $form_state->getValue('clt_debits_offer');
    $clt_extra_info = $form_state->getValue('clt_extra_info');
    $clt_car_brand_offer = $form_state->getValue('clt_car_brand_offer');
    $clt_car_model_offer = $form_state->getValue('clt_car_model_offer');
    $clt_car_gearshift_offer = $form_state->getValue('clt_car_gearshift_offer');
    $clt_car_fuel_offer = $form_state->getValue('clt_car_fuel_offer');
    $clt_car_kmormiles_offer = $form_state->getValue('clt_car_kmormiles_offer');
    $clt_car_color_offer = $form_state->getValue('clt_car_color_offer');
    $clt_car_sttyear_offer = $form_state->getValue('clt_car_sttyear_offer');
    $clt_car_endyear_offer = $form_state->getValue('clt_car_endyear_offer');
    $clt_car_id_offer = $form_state->getValue('clt_car_id_offer');

    // Save the form data to the database.
    $this->database->insert('clt_offer_table')
      ->fields([
        'nodeId' => $offer_to_car,
        'create_time' => $formattedDate,
        'name_offer' => $name_offer,
        'email_offer' => $email_offer,
        'phone_offer' => $phone_offer,
        'bday_offer' => $bday_offer,
        'down_offer' => $down_offer,
        'clt_id' => $clt_id,
        'checkbox_with_car_offer' => $checkbox_with_car_offer,
        'clt_debits_offer' => $clt_debits_offer,
        'clt_extra_info' => $clt_extra_info,
        'clt_car_brand_offer' => $clt_car_brand_offer,
        'clt_car_model_offer' => $clt_car_model_offer,
        'clt_car_gearshift_offer' => $clt_car_gearshift_offer,
        'clt_car_fuel_offer' => $clt_car_fuel_offer,
        'clt_car_kmormiles_offer' => $clt_car_kmormiles_offer,
        'clt_car_color_offer' => $clt_car_color_offer,
        'clt_car_sttyear_offer' => $clt_car_sttyear_offer,
        'clt_car_endyear_offer' => $clt_car_endyear_offer,
        'clt_car_id_offer' => $clt_car_id_offer,
      ])
      ->execute();

    $this->messenger->addMessage($this->t('Proposta enviada com sucesso!'));

    // Redirect to sell page after form submission.
    $response = new RedirectResponse($this->getPathAlias($offer_to_car));
    $response->send();

  }

  /**
   * {@inheritdoc}
   */
  public function getAllNodes(): array {

    $nids = $this->getNodes();

    $nodes = $this->entityTypeManager->getStorage('node')->loadMultiple($nids);
    $nodeInfos = [];

    foreach ($nodes as $node) {
      // Filter to get only node type cars.
      if ($node->getType() !== 'cars') {
        continue;
      }
      $media_id = $node->get('media_car')->getValue()[0]['target_id'];
      // Load the file entity using the target ID.
      $file = $this->entityTypeManager->getStorage('file');
      $file = $file->load($media_id);
      if ($file) {
        // Get the URI of the image file.
        $image_url = $file->getFileUri();

        $image = [
          '#theme' => 'image_style',
          '#style_name' => 'thumbnail',
          '#uri' => $image_url,
        ];
        $new_item = [
          'node_id' => $node->id(),
          'car_uri' => $this->renderer->render($image),
          'car_brand' => $node->get('car_brands')->value,
          'car_model' => $node->get('field_car_models')->value,
          'car_engine' => $node->get('engine_details')->value,
          'car_fuel' => $node->get('fuel_type')->value,
          'car_gearshift' => $node->get('engine_gearshift_type')->value,
          'car_kmOrMiles' => number_format($node->get('field_car_km_miles')->value / 1000, 3),
          'car_sttyear' => $node->get('field_start_year')->value,
          'car_endyear' => $node->get('field_end_year')->value,
          'car_price' => number_format($node->get('end_price')->value, 2, ',', '.'),
        ];

        $nodeInfos[] = $new_item;
      }
    }
    return $nodeInfos;
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
   * Get all nodes.
   */
  public function getNodes(): array {
    $query = $this->entityTypeManager->getStorage('node')->getQuery();
    $query->condition('status', 1);
    // To avoid issues on Drupal 8.8 ++.
    $query->accessCheck(FALSE);
    return $query->execute();
  }

  /**
   * Custom validation function to block digits in the email field.
   */
  public function blockDigits(&$element, &$form_state, $complete_form): void {
    $value = $element['#value'];
    if (preg_match('/\d/', $value)) {
      $element['#error'] = $this->t('This field is blocked to edit.');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function currentLanguage(): mixed {
    return $this->languageManager->getCurrentLanguage();
  }

}
