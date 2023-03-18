<?php

namespace Drupal\cars_offer\Form;

use Drupal\Component\Utility\EmailValidatorInterface as UtilityEmailValidatorInterface;

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

/**
 * Provides a form to sendo offer to owner.
 */
class DofferForm extends FormBase {

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
    return 'doffer_id_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $currentRequest = $this->requestStack->getCurrentRequest();
    $offerId = $currentRequest->query->get('offerId');

    if (empty($offerId)) {
      return FALSE;
    }

    // Retrieve data from the database using Drupal's database API.
    $query = $this->database->select('clt_offer_table', 'oft');
    $query->fields('oft');
    // Add a WHERE clause to the query.
    $query->condition('oft.id', $offerId, '=');
    $results = $query->execute()->fetchAll();

    $checkbox_with_car_offer = TRUE;

    if ($results[0]->checkbox_with_car_offer != 1) {
      $checkbox_with_car_offer = FALSE;
    }

    $clt_debits_offer = TRUE;
    if ($results[0]->clt_debits_offer != 1) {
      $clt_debits_offer = FALSE;
    }

    // Get the current site link.
    $siteUrl = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();

    $form['contact_info_wrapper_parent'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['contact-info-wrapper-inside']],

      'contact_info_wrapper' => [
        '#type' => 'container',
        '#attributes' => ['class' => ['contact-info-wrapper']],

        'name_offer' => [
          '#type' => 'textfield',
          '#title' => $this->t('Nome e sobrenome'),
          '#default_value' => $results[0]->name_offer,
          '#disabled' => TRUE,
          '#attributes' => [
            'readonly' => 'readonly',
          ],
        ],
        'email_offer' => [
          '#type' => 'textfield',
          '#title' => $this->t('Email'),
          '#default_value' => $results[0]->email_offer,
          '#disabled' => TRUE,
          '#attributes' => [
            'readonly' => 'readonly',
          ],
        ],
        'phone_offer' => [
          '#type' => 'textfield',
          '#title' => $this->t('Telefone com DDD'),
          '#default_value' => $results[0]->phone_offer,
          '#disabled' => TRUE,
          '#attributes' => [
            'readonly' => 'readonly',
          ],
        ],
        'bday_offer' => [
          '#type' => 'textfield',
          '#title' => $this->t('Data de Nascimento'),
          '#default_value' => $results[0]->bday_offer,
          '#disabled' => TRUE,
          '#attributes' => [
            'readonly' => 'readonly',
          ],
        ],
        'down_offer' => [
          '#type' => 'textfield',
          '#title' => $this->t('Valor da entrada'),
          '#default_value' => $results[0]->down_offer,
          '#disabled' => TRUE,
          '#attributes' => [
            'readonly' => 'readonly',
          ],
        ],
        'clt_id' => [
          '#type' => 'textfield',
          '#title' => $this->t('Se deseja simulação de financiamento, informe seu CPF ou CNPJ'),
          '#default_value' => $results[0]->clt_id,
          '#disabled' => TRUE,
          '#attributes' => [
            'readonly' => 'readonly',
          ],
        ],
        'checkbox_with_car_offer' => [
          '#type' => 'checkbox',
          '#title' => $this->t('Com carro na troca?'),
          '#default_value' => $checkbox_with_car_offer,
          '#disabled' => TRUE,
        ],
        'clt_debits_offer' => [
          '#type' => 'checkbox',
          '#title' => $this->t('Mais informações ou comentários?'),
          '#default_value' => $clt_debits_offer,
          '#disabled' => TRUE,
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
          '#default_value' => $results[0]->clt_extra_info,
          '#disabled' => TRUE,
          '#attributes' => [
            'readonly' => 'readonly',
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

        'offer_to_car' => [
          '#type' => 'link',
          '#title' => t('Proposta para o carro'),
          '#url' => Url::fromUri($siteUrl . $this->getPathAlias($results[0]->nodeId)),
          '#attributes' => [
            'class' => ['doffer-to-car'],
          ],
        ],

        '#prefix' => '<div class="contact-info-wrapper">',
        '#suffix' => '</div>',
      ],
      'clt_car_info' => [
        '#type' => 'container',
        '#attributes' => ['class' => ['clt-car-info-wrapper']],
        'clt_car_brand_offer' => [
          '#type' => 'textfield',
          '#disabled' => TRUE,
          '#attributes' => [
            'readonly' => 'readonly',
          ],
          '#default_value' => $results[0]->clt_car_brand_offer,
          '#title' => $this->t('Marca do seu carro'),
          '#states' => [
            'visible' => [
              ':input[name="checkbox_with_car_offer"]' => ['checked' => TRUE],
            ],
          ],
        ],
        'clt_car_model_offer' => [
          '#type' => 'textfield',
          '#title' => $this->t('Modelo do seu carro'),
          '#disabled' => TRUE,
          '#default_value' => $results[0]->clt_car_model_offer,
          '#attributes' => [
            'readonly' => 'readonly',
          ],
          '#states' => [
            'visible' => [
              ':input[name="checkbox_with_car_offer"]' => ['checked' => TRUE],
            ],
          ],
        ],
        'clt_car_gearshift_offer' => [
          '#type' => 'textfield',
          '#title' => $this->t('Câmbio'),
          '#disabled' => TRUE,
          '#default_value' => $results[0]->clt_car_gearshift_offer,
          '#attributes' => [
            'readonly' => 'readonly',
          ],
          '#states' => [
            'visible' => [
              ':input[name="checkbox_with_car_offer"]' => ['checked' => TRUE],
            ],
          ],
        ],
        'clt_car_fuel_offer' => [
          '#type' => 'textfield',
          '#title' => $this->t('Combustível'),
          '#disabled' => TRUE,
          '#default_value' => $results[0]->clt_car_fuel_offer,
          '#attributes' => [
            'readonly' => 'readonly',
          ],
          '#states' => [
            'visible' => [
              ':input[name="checkbox_with_car_offer"]' => ['checked' => TRUE],
            ],
          ],
        ],
        'clt_car_kmormiles_offer' => [
          '#type' => 'textfield',
          '#title' => $this->t('Quilometragem'),
          '#disabled' => TRUE,
          '#default_value' => $results[0]->clt_car_kmormiles_offer,
          '#attributes' => [
            'readonly' => 'readonly',
          ],
          '#states' => [
            'visible' => [
              ':input[name="checkbox_with_car_offer"]' => ['checked' => TRUE],
            ],
          ],
        ],
        'clt_car_color_offer' => [
          '#type' => 'textfield',
          '#title' => $this->t('Cor'),
          '#disabled' => TRUE,
          '#default_value' => $results[0]->clt_car_color_offer,
          '#attributes' => [
            'readonly' => 'readonly',
          ],
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
          '#disabled' => TRUE,
          '#default_value' => $results[0]->clt_car_sttyear_offer,
          '#attributes' => [
            'readonly' => 'readonly',
          ],
          '#states' => [
            'visible' => [
              ':input[name="checkbox_with_car_offer"]' => ['checked' => TRUE],
            ],
          ],
        ],
        'clt_car_endyear_offer' => [
          '#type' => 'textfield',
          '#title' => $this->t('Ano modelo'),
          '#disabled' => TRUE,
          '#default_value' => $results[0]->clt_car_endyear_offer,
          '#attributes' => [
            'readonly' => 'readonly',
          ],
          '#states' => [
            'visible' => [
              ':input[name="checkbox_with_car_offer"]' => ['checked' => TRUE],
            ],
          ],
        ],
        'clt_car_id_offer' => [
          '#type' => 'textfield',
          '#title' => $this->t('Código FIPE'),
          '#disabled' => TRUE,
          '#default_value' => $results[0]->clt_car_id_offer,
          '#attributes' => [
            'readonly' => 'readonly',
          ],
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

    return $form;
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

}
