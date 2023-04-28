<?php

/**
 * @file
 */

use Drupal\Core\Form\FormStateInterface;

/**
 * @file
 * Custom setting for carsOffer theme.
 */

/**
 *
 */
function carsOffer_form_system_theme_settings_alter(&$form, FormStateInterface $form_state) {
  $img_path = $GLOBALS['base_url'] . '/' . \Drupal::service('extension.list.theme')->getPath('carsOffer') . '/images/carsOffer.jpg';
  $img = '<img src="' . $img_path . '" alt="carsOffer" />';
  $form['carsOffer'] = [
    '#type'       => 'vertical_tabs',
    '#title'      => '<h3>' . t('carsOffer Theme Settings') . '</h3>',
    '#default_tab' => 'general',
  ];

  // Slider tab.
  $form['slider'] = [
    '#type'  => 'details',
    '#title' => t('Slider inicial'),
    '#description' => t('<h3>Manage Homepage Slider</h3>'),
    '#group' => 'carsOffer',
  ];

  // Social tab.
  $form['social'] = [
    '#type'  => 'details',
    '#title' => t('Social'),
    '#description' => t('Social icons settings. These icons appear in header and footer region.'),
    '#group' => 'carsOffer',
  ];

  // General settings tab.
  $form['general'] = [
    '#type'  => 'details',
    '#title' => t('General'),
    '#description' => t('<h3>Obrigado por usar este tema</h3>Desenvolvi para Drupal 8, 9, 10 contribua para a comunidade em: <a href="https://www.drupal.org/" target="_blank">Drupal</a>'),
    '#group' => 'carsOffer',
  ];

  // // Theme Color tab.
  // $form['color'] = [
  //   '#type'  => 'details',
  //   '#title' => t('Theme Color'),
  //   '#group' => 'carsOffer',
  // ];
  // Header tab.
  $form['header'] = [
    '#type'  => 'details',
    '#title' => t('Header'),
    '#group' => 'carsOffer',
  ];

  // Sidebar tab.
  $form['sidebar'] = [
    '#type'  => 'details',
    '#title' => t('Sidebar'),
    '#group' => 'carsOffer',
  ];

  // Content tab.
  $form['content'] = [
    '#type'  => 'details',
    '#title' => t('Content'),
    '#group' => 'carsOffer',
  ];

  // Footer tab.
  $form['footer'] = [
    '#type'  => 'details',
    '#title' => t('Footer'),
    '#group' => 'carsOffer',
  ];

  // // Insert codes
  // $form['insert_codes'] = [
  //   '#type'  => 'details',
  //   '#title' => t('Insert Codes'),
  //   '#group' => 'carsOffer',
  // ];
  // Support tab.
  $form['support'] = [
    '#type'  => 'details',
    '#title' => t('Support'),
    '#group' => 'carsOffer',
  ];

  // // Upgrade to carsOfferpro tab.
  // $form['upgrade'] = [
  //   '#type'  => 'details',
  //   '#title' => t('Upgrade to carsOfferPro'),
  //   '#description'  => t('<h3>Upgrade To carsOfferPro For $29 Only.</h3>'),
  //   '#group' => 'carsOffer',
  // ];
  // Content under general tab.
  // $form['general']['general_info'] = [
  //   '#type'        => 'fieldset',
  //   '#title'       => t('Theme Info'),
  //   '#description' => t('<a href="https://drupar.com/theme/carsOffer" target="_blank">Theme Homepage</a> || <a href="//demo2.drupar.com/carsOffer/" target="_blank">Theme Demo</a> || <a href="https://drupar.com/carsOffer-theme-documentation" target="_blank">Theme Documentation</a> || <a href="https://drupar.com/support" target="_blank">Theme Support</a>'),
  // ];.
  // $form['general']['general_info_upgrade'] = [
  //   '#type'        => 'fieldset',
  //   '#title'       => t('Upgrade To carsOfferPro for $29 only'),
  //   '#description' => t('<a href="https://drupar.com/theme/carsOfferpro" target="_blank">Purchase carsOfferPro</a> || <a href="//demo2.drupar.com/carsOfferpro/" target="_blank">carsOfferPro Demo</a>'),
  // ];
  // // Color tab -> Settings.
  // $form['color']['theme_color'] = [
  //   '#type'        => 'fieldset',
  //   '#title'       => t('Theme Color'),
  //   '#description'   => t('This feature is available in the premium version of this theme. <a href="https://drupar.com/theme/carsOfferpro" target="_blank">Buy carsOfferPro for $29 only.</a>'),
  // ];
  // Settings under social tab.
  // Show or hide all icons.
  $form['social']['all_icons'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Show Social Icons'),
  ];

  $form['social']['all_icons']['all_icons_show'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Show social icons in header and footer'),
    '#default_value' => theme_get_setting('all_icons_show', 'carsOffer'),
    '#description'   => t("Check this option to show social icons in header and footer. Uncheck to hide."),
  ];

  // $form['social']['only_header_icons'] = [
  //   '#type'        => 'fieldset',
  //   '#title'       => t('Show Social Icons only in header'),
  //   '#description'   => t('This feature is available in the premium version of this theme. <a href="https://drupar.com/theme/carsOfferpro" target="_blank">Buy carsOfferPro for $29 only.</a>'),
  // ];
  // $form['social']['only_footer_icons'] = [
  //   '#type'        => 'fieldset',
  //   '#title'       => t('Show Social Icons only in footer'),
  //   '#description'   => t('This feature is available in the premium version of this theme. <a href="https://drupar.com/theme/carsOfferpro" target="_blank">Buy carsOfferPro for $29 only.</a>'),
  // ];
  // Facebook.
  $form['social']['facebook'] = [
    '#type'        => 'details',
    '#title'       => t("Facebook"),
  ];

  $form['social']['facebook']['facebook_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('Facebook Url'),
    '#description'   => t("Enter yours facebook profile or page url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('facebook_url', 'carsOffer'),
  ];

  // Twitter.
  $form['social']['twitter'] = [
    '#type'        => 'details',
    '#title'       => t("Twitter"),
  ];

  $form['social']['twitter']['twitter_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('Twitter Url'),
    '#description'   => t("Enter yours twitter page url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('twitter_url', 'carsOffer'),
  ];

  // Instagram.
  $form['social']['instagram'] = [
    '#type'        => 'details',
    '#title'       => t("Instagram"),
  ];

  $form['social']['instagram']['instagram_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('Instagram Url'),
    '#description'   => t("Enter yours instagram page url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('instagram_url', 'carsOffer'),
  ];

  // Linkedin.
  $form['social']['linkedin'] = [
    '#type'        => 'details',
    '#title'       => t("Linkedin"),
  ];

  $form['social']['linkedin']['linkedin_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('Linkedin Url'),
    '#description'   => t("Enter yours linkedin page url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('linkedin_url', 'carsOffer'),
  ];

  // YouTube.
  $form['social']['youtube'] = [
    '#type'        => 'details',
    '#title'       => t("YouTube"),
  ];

  $form['social']['youtube']['youtube_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('YouTube Url'),
    '#description'   => t("Enter yours youtube.com page url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('youtube_url', 'carsOffer'),
  ];

  // Vimeo.
  $form['social']['vimeo'] = [
    '#type'        => 'details',
    '#title'       => t("Vimeo"),
  ];

  $form['social']['vimeo']['vimeo_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('YouTube Url'),
    '#description'   => t("Enter yours vimeo.com page url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('vimeo_url', 'carsOffer'),
  ];

  // Social -> vk.com url.
  $form['social']['vk'] = [
    '#type'        => 'details',
    '#title'       => t("vk.com"),
  ];
  $form['social']['vk']['vk_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('vk.com'),
    '#description'   => t("Enter yours vk.com page url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('vk_url', 'carsOffer'),
  ];

  // Social -> whatsapp.
  $form['social']['whatsapp'] = [
    '#type'        => 'details',
    '#title'       => t("whatsapp"),
  ];
  $form['social']['whatsapp']['whatsapp_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('WhatsApp'),
    '#description'   => t("Enter yours whatsapp url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('whatsapp_url', 'carsOffer'),
  ];

  // Social -> github.
  $form['social']['github'] = [
    '#type'        => 'details',
    '#title'       => t("Github"),
  ];
  $form['social']['github']['github_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('Github'),
    '#description'   => t("Enter yours github url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('github_url', 'carsOffer'),
  ];

  // Social -> telegram.
  $form['social']['telegram'] = [
    '#type'        => 'details',
    '#title'       => t("Telegram"),
  ];
  $form['social']['telegram']['telegram_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('Telegram'),
    '#description'   => t("Enter yours telegram url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('telegram_url', 'carsOffer'),
  ];

  /**
   * Slider Settings
   */
  // Show or hide slider on homepage.
  $form['slider']['slider_enable_option'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Enable Slider'),
  ];

  $form['slider']['slider_enable_option']['slider_show'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Show Slider on Homepage'),
    '#default_value' => theme_get_setting('slider_show', 'carsOffer'),
    '#description'   => t("Check this option to show slider on homepage. Uncheck to hide."),
  ];
  /* Slider -> Image upload */
  $form['slider']['slider_image_section'] = [
    '#type'          => 'fieldset',
    '#title'         => t('Slider Background Image'),
  ];
  $form['slider']['slider_image_section']['slider_image'] = [
    '#type'          => 'managed_file',
    '#upload_location' => 'public://',
    '#upload_validators' => [
      'file_validate_extensions' => ['gif png jpg jpeg svg'],
    ],
    '#title'  => t('<p>Upload Slider Image</p>'),
    '#default_value'  => theme_get_setting('slider_image', 'carsOffer'),
    '#description'   => t('carsOffer theme has limitation of single image for slider.</a>'),
  ];
  $form['slider']['slider_time_field'] = [
    '#type'          => 'fieldset',
    '#title'         => t('Autoplay Interval Time'),
  ];
  $form['slider']['slider_time_field']['slider_time'] = [
    '#type'          => 'number',
    '#default_value' => theme_get_setting('slider_time', 'carsOffer'),
    '#title'         => t('Enter slider interval time between two slides'),
    '#description'   => t('Time interval between two slides. Default value is 5000, this means 5 seconds.'),
  ];

  $form['slider']['slider_dots_field'] = [
    '#type'          => 'fieldset',
    '#title'         => t('Slider Dots Navigation'),
  ];

  $form['slider']['slider_dots_field']['slider_dots'] = [
    '#type'          => 'select',
    '#title'         => t('Show or Hide Slider Dots Navigation'),
    '#options' => [
      'true' => t('Show'),
      'false' => t('Hide'),
    ],
    '#default_value' => theme_get_setting('slider_dots', 'carsOffer'),
    '#description'   => t('Show or hide slider dots navigation that appears at the bottom of slider.'),
  ];

  // $form['slider']['slider_code'] = [
  //   '#type'          => 'textarea',
  //   '#title'         => t('Slider Code'),
  //   '#default_value' => theme_get_setting('slider_code', 'carsOffer'),
  //   '#description'   => t('Please refer to this <a href="https://drupal.org" target="_blank">Drupal org page</a>.'),
  // ];
  $form['slider']['title_1'] = [
    '#type'          => 'textarea',
    '#title'         => t('Título 1'),
    '#default_value' => theme_get_setting('title_1', 'carsOffer'),
    '#description'   => t('Será exibido no carousel da página inicial.'),
  ];

  $form['slider']['description_1'] = [
    '#type'          => 'textarea',
    '#title'         => t('Descrição Título 1'),
    '#default_value' => theme_get_setting('description_1', 'carsOffer'),
    '#description'   => t('Será exibido no carousel da página inicial.'),
  ];

  $form['slider']['title_2'] = [
    '#type'          => 'textarea',
    '#title'         => t('Título 2'),
    '#default_value' => theme_get_setting('title_2', 'carsOffer'),
    '#description'   => t('Será exibido no carousel da página inicial.'),
  ];

  $form['slider']['description_2'] = [
    '#type'          => 'textarea',
    '#title'         => t('Descrição Título 2'),
    '#default_value' => theme_get_setting('description_2', 'carsOffer'),
    '#description'   => t('Será exibido no carousel da página inicial.'),
  ];

  $form['slider']['title_3'] = [
    '#type'          => 'textarea',
    '#title'         => t('Título 3'),
    '#default_value' => theme_get_setting('title_3', 'carsOffer'),
    '#description'   => t('Será exibido no carousel da página inicial.'),
  ];

  $form['slider']['description_3'] = [
    '#type'          => 'textarea',
    '#title'         => t('Descrição Título 3'),
    '#default_value' => theme_get_setting('description_3', 'carsOffer'),
    '#description'   => t('Será exibido no carousel da página inicial.'),
  ];

  // // Settings under header tab.
  // $form['header']['sticky_header'] = [
  //   '#type'        => 'fieldset',
  //   '#title'       => t('Sticky Header'),
  //   '#description'   => t('This feature is available in the premium version of this theme. <a href="https://drupar.com/theme/carsOfferpro" target="_blank">Buy carsOfferPro for $29 only.</a>'),
  // ];
  // Settings under sidebar.
  // Sidebar -> Frontpage sidebar.
  $form['sidebar']['front_sidebars'] = [
    '#type'          => 'fieldset',
    '#title'         => t('Homepage Sidebar'),
  ];
  $form['sidebar']['front_sidebars']['front_sidebar'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Show Sidebars On Homepage'),
    '#default_value' => theme_get_setting('front_sidebar', 'carsOffer'),
    '#description'   => t('Check this option to enable left and right sidebar on homepage.'),
  ];
  // $form['sidebar']['animated_sidebar'] = [
  //   '#type'        => 'fieldset',
  //   '#title'       => t('Animated Sidebar'),
  //   '#description'   => t('This feature is available in the premium version of this theme. <a href="https://drupar.com/theme/carsOfferpro" target="_blank">Buy carsOfferPro for $29 only.</a>'),
  // ];

  /**
   * Content
   */
  $form['content']['content_tab'] = [
    '#type'  => 'vertical_tabs',
  ];
  // // content -> Homepage  content
  // $form['content_tab']['home_content'] = [
  //   '#type'        => 'details',
  //   '#title'       => t('Homepage content'),
  //   '#description' => t('Please follow this tutorial to add content on homepage. <a href="https://drupar.com/carsOffer-theme-documentation/how-add-content-homepage" target="_blank">How to add content on homepage</a>'),
  //   '#group' => 'content_tab',
  // ];
  // // content -> Page loader
  // $form['content_tab']['preloader'] = [
  //   '#type'        => 'details',
  //   '#title'       => t('Pre Page Loader'),
  //   '#description' => t('This feature is available in the premium version of this theme. <a href="https://drupar.com/theme/carsOfferpro" target="_blank">Buy carsOfferPro for $29 only.</a>'),
  //   '#group' => 'content_tab',
  // ];
  // // content -> Animated Content
  // $form['content_tab']['animated_content'] = [
  //   '#type'        => 'details',
  //   '#title'       => t('Animated Content'),
  //   '#description' => t('This feature is available in the premium version of this theme. <a href="https://drupar.com/theme/carsOfferpro" target="_blank">Buy carsOfferPro for $29 only.</a>'),
  //   '#group' => 'content_tab',
  // ];
  // content -> Google fonts
  $form['content_tab']['font_tab'] = [
    '#type'        => 'details',
    '#title'       => t('Google Fonts'),
    '#description' => t(''),
    '#group' => 'content_tab',
  ];
  // Content -> Font icons.
  $form['content_tab']['icon_tab'] = [
    '#type'        => 'details',
    '#title'       => t('Font Icon'),
    '#description' => t(''),
    '#group' => 'content_tab',
  ];
  // Content -> shortcodes
  // $form['content_tab']['shortcode'] = [
  //   '#type'        => 'details',
  //   '#title'       => t('Shortcodes'),
  //   '#description' => t('carsOffer theme has some custom shortcodes. You can create some styling content using these shortcodes.<br />Please visit this tutorial page for details. <a href="https://drupar.com/carsOffer-theme-documentation/carsOffer-shortcodes" target="_blank">Shortcodes in carsOffer theme</a>.'),
  //   '#group' => 'content_tab',
  // ];
  // content -> comment.
  $form['content_tab']['comment'] = [
    '#type'        => 'details',
    '#title'       => t('Comment'),
    '#description' => t(''),
    '#group' => 'content_tab',
  ];
  // Content -> node.
  $form['content_tab']['node'] = [
    '#type'        => 'details',
    '#title'       => t('Node'),
    '#description' => t(''),
    '#group' => 'content_tab',
  ];
  // // content -> share page
  // $form['content_tab']['node_share'] = [
  //   '#type'        => 'details',
  //   '#title'       => t('Share Page'),
  //   '#description' => t('<h3>Share Page On Social Media</h3><p>This feature is available in the premium version of this theme. <a href="https://drupar.com/theme/carsOfferpro" target="_blank">Buy carsOfferPro for $29 only.</a></p>'),
  //   '#group' => 'content_tab',
  // ];
  // content -> Google fonts options
  $form['content_tab']['font_tab']['font_section'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Google Fonts'),
  ];
  $form['content_tab']['font_tab']['font_section']['google_font'] = [
    '#type'          => 'select',
    '#title'         => t('Select Google Fonts Location'),
    '#options' => [
      'local' => t('Local Self Hosted'),
      'googlecdn' => t('Google CDN Server'),
    ],
    '#default_value' => theme_get_setting('google_font', 'carsOffer'),
    '#description'   => t('carsOffer theme uses following Google fonts: Open Sans, Roboto and Poppins. You can serve these fonts locally or from Google server.'),
  ];
  // Content -> Font icons -> FontAwesome 4
  // $form['content_tab']['icon_tab']['font_icons'] = [
  //   '#type'        => 'fieldset',
  //   '#title'       => t('FontAwesome 4 Font Icons'),
  //   '#description'   => t('carsOffer theme has included FontAwesome v4.7 font icons. You can use 600+ icons with carsOffer theme.<br />Please visit this tutorial page for details. <a href="https://drupar.com/custom-shortcodes-set-two/fontawesome-font-icons" target="_blank">How To Use FontAwesome Icons</a>.'),
  // ];
  // // content -> Font icons -> FontAwesome 5
  // $form['content_tab']['icon_tab']['fontawesome5'] = [
  //   '#type'        => 'fieldset',
  //   '#title'       => t('FontAwesome 5'),
  //   '#description'   => t('This feature is available in the premium version of this theme. <a href="https://drupar.com/theme/carsOfferpro" target="_blank">Buy carsOfferPro for $29 only.</a>'),
  // ];
  // // content -> Font icons -> FontAwesome 6
  // $form['content_tab']['icon_tab']['fontawesome6'] = [
  //   '#type'        => 'fieldset',
  //   '#title'       => t('FontAwesome 6'),
  //   '#description'   => t('This feature is available in the premium version of this theme. <a href="https://drupar.com/theme/carsOfferpro" target="_blank">Buy carsOfferPro for $29 only.</a>'),
  // ];
  // content -> Font icons -> Bootstrap Font Icons.
  $form['content_tab']['icon_tab']['bootstrap_icons'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Bootstrap Font Icons'),
  ];
  $form['content_tab']['icon_tab']['bootstrap_icons']['bootstrapicons'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Enable Bootstrap Icons'),
    '#default_value' => theme_get_setting('bootstrapicons'),
    '#description'   => t('Check this option to enable Bootstrap Font Icons. Read more about <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a>'),
  ];
  // // content -> Font icons -> Google material font icons
  // $form['content_tab']['icon_tab']['material'] = [
  //   '#type'        => 'fieldset',
  //   '#title'       => t('Google Material Font Icons'),
  //   '#description'   => t('This feature is available in the premium version of this theme. <a href="https://drupar.com/theme/carsOfferpro" target="_blank">Buy carsOfferPro for $29 only.</a>'),
  // ];
  // // content -> Font icons -> iconmonstr
  // $form['content_tab']['icon_tab']['iconmonstr'] = [
  //   '#type'        => 'fieldset',
  //   '#title'       => t('Iconmonstr Font Icons'),
  //   '#description'   => t('This feature is available in the premium version of this theme. <a href="https://drupar.com/theme/carsOfferpro" target="_blank">Buy carsOfferPro for $29 only.</a>'),
  // ];
  // content -> comment -> user picture in comment
  $form['content_tab']['comment']['comment_section'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Comment'),
  ];
  $form['content_tab']['comment']['comment_section']['comment_user_pic'] = [
    '#type'          => 'checkbox',
    '#title'         => t('User Picture in comments'),
    '#default_value' => theme_get_setting('comment_user_pic', 'carsOffer'),
    '#description'   => t("Check this option to show user picture in comment. Uncheck to hide."),
  ];
  // Content -> node -> Node author picture.
  $form['content_tab']['node']['node_section'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Node'),
  ];
  $form['content_tab']['node']['node_section']['node_author_pic'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Node Author Picture'),
    '#default_value' => theme_get_setting('node_author_pic', 'carsOffer'),
    '#description'   => t("Check this option to show node author picture in submitted details. Uncheck to hide."),
  ];
  // Show tags in node submitted.
  $form['content_tab']['node']['node_section']['node_tags'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Node Tags'),
    '#default_value' => theme_get_setting('node_tags', 'carsOffer'),
    '#description'   => t("Check this option to show node tags (if any) in submitted details. Uncheck to hide."),
  ];

  // Settings under footer tab.
  // Scroll to top.
  $form['footer']['scrolltotop'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Scroll To Top'),
  ];

  $form['footer']['scrolltotop']['scrolltotop_on'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Enable scroll to top feature.'),
    '#default_value' => theme_get_setting('scrolltotop_on', 'carsOffer'),
    '#description'   => t("Check this option to enable scroll to top feature. Uncheck to disable this fearure and hide scroll to top icon."),
  ];

  // Footer -> Copyright.
  $form['footer']['copyright'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Website Copyright Text'),
  ];

  $form['footer']['copyright']['copyright_text'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Show website copyright text in footer.'),
    '#default_value' => theme_get_setting('copyright_text', 'carsOffer'),
    '#description'   => t("Check this option to show website copyright text in footer. Uncheck to hide."),
  ];

  // Footer -> Copyright -> custom copyright text
  // $form['footer']['copyright']['copyright_text_custom'] = [
  //   '#type'          => 'fieldset',
  //   '#title'         => t('Custom copyright text'),
  //   '#description'   => t('This feature is available in the premium version of this theme. <a href="https://drupar.com/theme/carsOfferpro" target="_blank">Buy carsOfferPro for $29 only.</a>'),
  // ];.
  // // Footer -> Cookie message.
  // $form['footer']['cookie'] = [
  //   '#type'        => 'fieldset',
  //   '#title'       => t('Cookie Consent message'),
  //   '#description'   => t('This feature is available in the premium version of this theme. <a href="https://drupar.com/theme/carsOfferpro" target="_blank">Buy carsOfferPro for $29 only.</a>'),
  // ];.
  $form['footer']['cookie']['cookie_message'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Show Cookie Consent Message'),
    '#description'   => t('Make your website EU Cookie Law Compliant. According to EU cookies law, websites need to get consent from visitors to store or retrieve cookies.'),
  ];
  /**
   * Insert Codes
   * //  */
  // $form['insert_codes']['insert_codes_tab'] = [
  //   '#type'  => 'vertical_tabs',
  // ];
  // // Insert Codes -> Head
  // $form['insert_codes']['head'] = [
  //   '#type'        => 'details',
  //   '#title'       => t('Head'),
  //   '#description' => t('<h3>Insert Codes Before &lt;/HEAD&gt;</h3><hr />'),
  //   '#group' => 'insert_codes_tab',
  // ];
  // // Insert Codes -> Body
  // $form['insert_codes']['body'] = [
  //   '#type'        => 'details',
  //   '#title'       => t('Body'),
  //   '#group' => 'insert_codes_tab',
  // ];
  // // Insert Codes -> CSS
  // $form['insert_codes']['css'] = [
  //   '#type'        => 'details',
  //   '#title'       => t('CSS Codes'),
  //   '#group'       => 'insert_codes_tab',
  // ];
  // // Insert Codes -> Head -> Head codes
  // $form['insert_codes']['head']['insert_head'] = [
  //   '#type'          => 'fieldset',
  //   // '#description'   => t('This feature is available in the premium version of this theme. <a href="https://drupar.com/theme/carsOfferpro" target="_blank">Buy carsOfferPro for $29 only.</a>'),
  // ];
  // // Insert Codes -> Body -> Body start codes
  // $form['insert_codes']['body']['insert_body_start_section'] = [
  //   '#type'        => 'fieldset',
  //   '#title'       => t('Insert code after &lt;BODY&gt; tag'),
  //   // '#description'   => t('This feature is available in the premium version of this theme. <a href="https://drupar.com/theme/carsOfferpro" target="_blank">Buy carsOfferPro for $29 only.</a>'),
  // ];
  // // Insert Codes -> Body -> Body ENd codes
  // $form['insert_codes']['body']['insert_body_end_section'] = [
  //   '#type'        => 'fieldset',
  //   '#title'       => t('Insert code before &lt;/BODY&gt; tag'),
  //   // '#description'   => t('This feature is available in the premium version of this theme. <a href="https://drupar.com/theme/carsOfferpro" target="_blank">Buy carsOfferPro for $29 only.</a>'),
  // ];
  // // Insert Codes -> css
  // $form['insert_codes']['css']['css_custom'] = [
  //   '#type'        => 'fieldset',
  //   '#title'       => t('Addtional CSS'),
  // ];
  // $form['insert_codes']['css']['css_custom']['css_extra'] = [
  //   '#type'          => 'checkbox',
  //   '#title'         => t('Enable Addtional CSS'),
  //   '#default_value' => theme_get_setting('css_extra', 'carsOffer'),
  //   '#description'   => t("Check this option to enable additional styling / css. Uncheck to disable this feature."),
  // ];
  // $form['insert_codes']['css']['css_code'] = [
  //   '#type'          => 'textarea',
  //   '#title'         => t('Addtional CSS Codes'),
  //   '#default_value' => theme_get_setting('css_code', 'carsOffer'),
  //   // '#description'   => t('Add your own CSS codes here to customize the appearance of your site. Please refer to this tutorial for detail: <a href="https://drupar.com/carsOffer-theme-documentation/custom-css" target="_blank">Custom CSS</a>'),
  // ];
  // // Settings under support tab.
  // $form['support']['info'] = [
  //   '#type'        => 'fieldset',
  //   '#title'         => t('Theme Support'),
  //   '#description' => t('<h4>Documentation</h4>
  //   <p>We have a detailed documentation about how to use theme. Please read the <a href="https://drupar.com/carsOffer-theme-documentation" target="_blank">carsOffer Theme Documentation</a>.</p>
  //   <hr />
  //   <h4>Open An Issue</h4>
  //   <p>If you need support that is beyond our theme documentation, please <a href="https://www.drupal.org/project/issues/carsOffer?categories=All" target="_blank">open an issue</a> at project page.</p>
  //   <hr />
  //   <h4>Contact Us</h4>
  //   <p>If you need some specific customization in theme, please contact us<br><a href="https://drupar.com/contact" target="_blank">drupar.com/contact</a></p>'),
  // ];
  // // Settings under upgrade tab.
  // $form['upgrade']['info'] = [
  //   '#type'        => 'fieldset',
  //   '#title'       => t('<a href="https://demo2.drupar.com/carsOfferpro/" target="_blank">carsOfferPro Demo</a> | <a href="https://drupar.com/theme/carsOfferpro" target="_blank">Purchase carsOfferPro for $29 only</a>'),
  //   '#description' => t("$img<br /><a href='https://demo2.drupar.com/carsOfferpro/' target='_blank'>carsOfferPro Demo</a> | <a href='https://drupar.com/theme/carsOfferpro' target='_blank'>Purchase carsOfferPro for $29 only</a>"),
  // ];
  // End form.
}
