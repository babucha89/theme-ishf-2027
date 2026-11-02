<?php
/**
 * =============================================================================
 * ISHF Lisboa 2027 - Leads Manager & Database Handler
 * =============================================================================
 *
 * Senior-level WordPress custom database management, submission endpoints,
 * backoffice administration dashboard, and CSV exporter for the ISHF 2027 portal.
 *
 * Tables Managed:
 *  - {$wpdb->prefix}ishf_partnerships
 *  - {$wpdb->prefix}ishf_newsletter
 *
 * @package   ISHF_2027
 * @author    Senior WordPress Developer
 * @version   1.0.0
 * =============================================================================
 */

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

// Database schema version for seamless migrations
defined('ISHF2027_DB_VERSION') or define('ISHF2027_DB_VERSION', '1.0.0');

// =============================================================================
// 1. DATABASE TABLES SETUP (dbDelta)
// =============================================================================

/**
 * Returns the prefixed table name for ISHF custom tables.
 *
 * @param  string $type 'partnerships' or 'newsletter'
 * @return string
 */
function ishf2027_get_table_name($type = 'partnerships')
{
  global $wpdb;
  return $wpdb->prefix . 'ishf_' . ($type === 'newsletter' ? 'newsletter' : 'partnerships');
}

/**
 * Creates or updates the custom database tables using dbDelta.
 * Hooked to `after_switch_theme` and checked on `admin_init`.
 *
 * @return void
 */
function ishf2027_setup_custom_tables()
{
  global $wpdb;

  require_once ABSPATH . 'wp-admin/includes/upgrade.php';

  $charset_collate     = $wpdb->get_charset_collate();
  $table_partnerships  = ishf2027_get_table_name('partnerships');
  $table_newsletter    = ishf2027_get_table_name('newsletter');

  /**
   * 1. wp_ishf_partnerships
   * Stores institutional partnership leads.
   *
   * Rules for dbDelta:
   * - Each field on its own line.
   * - Two spaces after PRIMARY KEY.
   * - KEY must have an identifier.
   */
  $sql_partnerships = "CREATE TABLE {$table_partnerships} (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    nome varchar(255) NOT NULL DEFAULT '',
    email varchar(255) NOT NULL DEFAULT '',
    empresa varchar(255) NOT NULL DEFAULT '',
    cargo varchar(255) NOT NULL DEFAULT '',
    telefone varchar(50) NOT NULL DEFAULT '',
    mensagem text NOT NULL,
    gdpr_consent tinyint(1) NOT NULL DEFAULT 0,
    created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY  (id),
    KEY email (email),
    KEY created_at (created_at)
  ) {$charset_collate};";

  /**
   * 2. wp_ishf_newsletter
   * Stores newsletter subscriber leads.
   */
  $sql_newsletter = "CREATE TABLE {$table_newsletter} (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    nome varchar(255) NOT NULL DEFAULT '',
    email varchar(255) NOT NULL DEFAULT '',
    gdpr_consent tinyint(1) NOT NULL DEFAULT 0,
    created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY  (id),
    KEY email (email),
    KEY created_at (created_at)
  ) {$charset_collate};";

  // Execute schema creation
  dbDelta($sql_partnerships);
  dbDelta($sql_newsletter);

  // Store version in options table
  update_option('ishf2027_db_version', ISHF2027_DB_VERSION);
}
add_action('after_switch_theme', 'ishf2027_setup_custom_tables');

/**
 * Self-healing check: verifies on admin_init if tables have been created,
 * avoiding the need to manually deactivate/reactivate the theme during development.
 *
 * @return void
 */
function ishf2027_check_custom_tables_version()
{
  if (get_option('ishf2027_db_version') !== ISHF2027_DB_VERSION) {
    ishf2027_setup_custom_tables();
  }
}
add_action('admin_init', 'ishf2027_check_custom_tables_version');


// =============================================================================
// 2. CORE INSERTION & SANITIZATION SERVICES
// =============================================================================

/**
 * Inserts a partnership lead into the database.
 *
 * @param  array $data Raw submission array.
 * @return array Result array with 'success' (bool), 'id' (int|null), and 'message' (string).
 */
function ishf2027_insert_partnership_lead(array $data)
{
  global $wpdb;

  // Strict sanitization
  $nome         = isset($data['nome']) ? sanitize_text_field(wp_unslash($data['nome'])) : '';
  $email        = isset($data['email']) ? sanitize_email(wp_unslash($data['email'])) : '';
  $empresa      = isset($data['empresa']) ? sanitize_text_field(wp_unslash($data['empresa'])) : '';
  $cargo        = isset($data['cargo']) ? sanitize_text_field(wp_unslash($data['cargo'])) : '';
  $telefone     = isset($data['telefone']) ? sanitize_text_field(wp_unslash($data['telefone'])) : '';
  $mensagem     = isset($data['mensagem']) ? sanitize_textarea_field(wp_unslash($data['mensagem'])) : '';
  $gdpr_consent = !empty($data['gdpr_consent']) ? 1 : 0;

  // Validation
  if (empty($nome) || empty($email) || empty($empresa) || empty($cargo)) {
    return array(
      'success' => false,
      'message' => __('Por favor, preencha todos os campos obrigatórios (*).', TEXT_DOMAIN),
    );
  }

  if (!is_email($email)) {
    return array(
      'success' => false,
      'message' => __('Por favor, insira um endereço de email válido.', TEXT_DOMAIN),
    );
  }

  if ($gdpr_consent !== 1) {
    return array(
      'success' => false,
      'message' => __('É obrigatório autorizar o tratamento de dados pessoais (RGPD).', TEXT_DOMAIN),
    );
  }

  $table_name = ishf2027_get_table_name('partnerships');

  // Anti-duplication check: prevent identical insertions within 60 seconds
  $recent_check = $wpdb->get_var($wpdb->prepare(
    "SELECT id FROM {$table_name} WHERE email = %s AND created_at >= %s LIMIT 1",
    $email,
    date('Y-m-d H:i:s', time() - 60)
  ));
  if ($recent_check) {
    return array(
      'success' => true,
      'id'      => (int) $recent_check,
      'message' => __('O seu pedido de parceria já foi recebido.', TEXT_DOMAIN),
    );
  }

  $inserted = $wpdb->insert(
    $table_name,
    array(
      'nome'         => $nome,
      'email'        => $email,
      'empresa'      => $empresa,
      'cargo'        => $cargo,
      'telefone'     => $telefone,
      'mensagem'     => $mensagem,
      'gdpr_consent' => $gdpr_consent,
      'created_at'   => current_time('mysql'),
    ),
    array('%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s')
  );

  if ($inserted === false) {
    return array(
      'success' => false,
      'message' => __('Erro ao guardar os dados na base de dados. Tente novamente mais tarde.', TEXT_DOMAIN),
    );
  }

  return array(
    'success' => true,
    'id'      => $wpdb->insert_id,
    'message' => __('Obrigado pelo seu interesse! O seu pedido de parceria foi registado com sucesso.', TEXT_DOMAIN),
  );
}

/**
 * Inserts a newsletter lead into the database.
 *
 * @param  array $data Raw submission array.
 * @return array Result array with 'success' (bool), 'id' (int|null), and 'message' (string).
 */
function ishf2027_insert_newsletter_lead(array $data)
{
  global $wpdb;

  // Strict sanitization
  $nome         = isset($data['nome']) ? sanitize_text_field(wp_unslash($data['nome'])) : '';
  $email        = isset($data['email']) ? sanitize_email(wp_unslash($data['email'])) : '';
  $gdpr_consent = !empty($data['gdpr_consent']) ? 1 : 0;

  // Validation
  if (empty($nome) || empty($email)) {
    return array(
      'success' => false,
      'message' => __('Por favor, preencha o seu nome e email.', TEXT_DOMAIN),
    );
  }

  if (!is_email($email)) {
    return array(
      'success' => false,
      'message' => __('Por favor, insira um endereço de email válido.', TEXT_DOMAIN),
    );
  }

  if ($gdpr_consent !== 1) {
    return array(
      'success' => false,
      'message' => __('É obrigatório autorizar o envio de newsletters (RGPD).', TEXT_DOMAIN),
    );
  }

  $table_name = ishf2027_get_table_name('newsletter');

  // Anti-duplication check: prevent identical insertions within 60 seconds
  $recent_check = $wpdb->get_var($wpdb->prepare(
    "SELECT id FROM {$table_name} WHERE email = %s AND created_at >= %s LIMIT 1",
    $email,
    date('Y-m-d H:i:s', time() - 60)
  ));
  if ($recent_check) {
    return array(
      'success' => true,
      'id'      => (int) $recent_check,
      'message' => __('Subscrição já registada.', TEXT_DOMAIN),
    );
  }

  $inserted = $wpdb->insert(
    $table_name,
    array(
      'nome'         => $nome,
      'email'        => $email,
      'gdpr_consent' => $gdpr_consent,
      'created_at'   => current_time('mysql'),
    ),
    array('%s', '%s', '%d', '%s')
  );

  if ($inserted === false) {
    return array(
      'success' => false,
      'message' => __('Erro ao registar a sua subscrição. Tente novamente mais tarde.', TEXT_DOMAIN),
    );
  }

  return array(
    'success' => true,
    'id'      => $wpdb->insert_id,
    'message' => __('Subscrição efetuada com sucesso! Obrigado por acompanhar o ISHF 2027.', TEXT_DOMAIN),
  );
}


// =============================================================================
// 3. SUBMISSION ENDPOINTS (AJAX & REST API)
// =============================================================================

/**
 * Verifies submission nonces for security across various nonce field names.
 *
 * @param  string $action Expected action name.
 * @return bool
 */
function ishf2027_verify_submission_nonce($action = 'ishf2027_form_nonce')
{
  // Check primary AJAX nonce
  if (!empty($_REQUEST['nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['nonce'])), $action)) {
    return true;
  }
  // Check header X-WP-Nonce (standard REST/AJAX)
  if (!empty($_SERVER['HTTP_X_WP_NONCE']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_SERVER['HTTP_X_WP_NONCE'])), 'wp_rest')) {
    return true;
  }
  // Check partnership nonce fallback
  if (!empty($_POST['partnership_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['partnership_nonce'])), 'partnership_form_action')) {
    return true;
  }
  // Check newsletter nonce fallback
  if (!empty($_POST['newsletter_landing_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['newsletter_landing_nonce'])), 'newsletter_landing_action')) {
    return true;
  }
  return false;
}

/**
 * AJAX Handler: Partnership Submission
 * Responds to `wp_ajax_ishf2027_partnership_submit` & `wp_ajax_nopriv_ishf2027_partnership_submit`.
 *
 * @return void Sends JSON and terminates.
 */
function ishf2027_ajax_partnership_submit()
{
  if (!ishf2027_verify_submission_nonce('ishf2027_form_nonce')) {
    wp_send_json_error(array(
      'message' => __('Validação de segurança expirada. Por favor, atualize a página.', TEXT_DOMAIN),
    ), 403);
  }

  $data = array(
    'nome'         => $_POST['partnership_name'] ?? $_POST['nome'] ?? '',
    'email'        => $_POST['partnership_email'] ?? $_POST['email'] ?? '',
    'empresa'      => $_POST['partnership_company'] ?? $_POST['empresa'] ?? '',
    'cargo'        => $_POST['partnership_position'] ?? $_POST['cargo'] ?? '',
    'telefone'     => $_POST['partnership_phone'] ?? $_POST['telefone'] ?? '',
    'mensagem'     => $_POST['partnership_message'] ?? $_POST['mensagem'] ?? '',
    'gdpr_consent' => !empty($_POST['partnership_consent']) || !empty($_POST['gdpr_consent']),
  );

  $result = ishf2027_insert_partnership_lead($data);

  if (!$result['success']) {
    wp_send_json_error(array('message' => $result['message']), 400);
  }

  // Trigger email notification to festival team
  ishf2027_send_partnership_notification($data);

  wp_send_json_success(array(
    'message' => $result['message'],
    'lead_id' => $result['id'],
  ));
}
add_action('wp_ajax_ishf2027_partnership_submit', 'ishf2027_ajax_partnership_submit');
add_action('wp_ajax_nopriv_ishf2027_partnership_submit', 'ishf2027_ajax_partnership_submit');

/**
 * AJAX Handler: Newsletter Submission
 * Responds to `wp_ajax_ishf2027_newsletter_submit` & `wp_ajax_nopriv_ishf2027_newsletter_submit`.
 *
 * @return void Sends JSON and terminates.
 */
function ishf2027_ajax_newsletter_submit()
{
  if (!ishf2027_verify_submission_nonce('ishf2027_form_nonce')) {
    wp_send_json_error(array(
      'message' => __('Validação de segurança expirada. Por favor, atualize a página.', TEXT_DOMAIN),
    ), 403);
  }

  $data = array(
    'nome'         => $_POST['newsletter_name'] ?? $_POST['nome'] ?? '',
    'email'        => $_POST['newsletter_email'] ?? $_POST['email'] ?? '',
    'gdpr_consent' => !empty($_POST['newsletter_consent']) || !empty($_POST['gdpr_consent']),
  );

  $result = ishf2027_insert_newsletter_lead($data);

  if (!$result['success']) {
    wp_send_json_error(array('message' => $result['message']), 400);
  }

  // Trigger email notification
  ishf2027_send_newsletter_notification($data);

  wp_send_json_success(array(
    'message' => $result['message'],
    'lead_id' => $result['id'],
  ));
}
add_action('wp_ajax_ishf2027_newsletter_submit', 'ishf2027_ajax_newsletter_submit');
add_action('wp_ajax_nopriv_ishf2027_newsletter_submit', 'ishf2027_ajax_newsletter_submit');


// -----------------------------------------------------------------------------
// WordPress REST API Endpoints (Modern Alternative for fetch/XHR)
// -----------------------------------------------------------------------------

/**
 * Registers REST API endpoints under `/wp-json/ishf2027/v1/`.
 *
 * @return void
 */
function ishf2027_register_rest_routes()
{
  // POST /wp-json/ishf2027/v1/partnerships
  register_rest_route('ishf2027/v1', '/partnerships', array(
    'methods'             => 'POST',
    'callback'            => 'ishf2027_rest_partnership_submit',
    'permission_callback' => '__return_true', // Public form with internal nonce/reCAPTCHA check
  ));

  // POST /wp-json/ishf2027/v1/newsletter
  register_rest_route('ishf2027/v1', '/newsletter', array(
    'methods'             => 'POST',
    'callback'            => 'ishf2027_rest_newsletter_submit',
    'permission_callback' => '__return_true',
  ));
}
add_action('rest_api_init', 'ishf2027_register_rest_routes');

/**
 * REST callback for partnerships.
 *
 * @param  WP_REST_Request $request
 * @return WP_REST_Response
 */
function ishf2027_rest_partnership_submit(WP_REST_Request $request)
{
  $params = $request->get_params();

  $data = array(
    'nome'         => $params['partnership_name'] ?? $params['nome'] ?? '',
    'email'        => $params['partnership_email'] ?? $params['email'] ?? '',
    'empresa'      => $params['partnership_company'] ?? $params['empresa'] ?? '',
    'cargo'        => $params['partnership_position'] ?? $params['cargo'] ?? '',
    'telefone'     => $params['partnership_phone'] ?? $params['telefone'] ?? '',
    'mensagem'     => $params['partnership_message'] ?? $params['mensagem'] ?? '',
    'gdpr_consent' => !empty($params['partnership_consent']) || !empty($params['gdpr_consent']),
  );

  $result = ishf2027_insert_partnership_lead($data);

  if (!$result['success']) {
    return new WP_REST_Response(array(
      'success' => false,
      'message' => $result['message'],
    ), 400);
  }

  ishf2027_send_partnership_notification($data);

  return new WP_REST_Response(array(
    'success' => true,
    'message' => $result['message'],
    'lead_id' => $result['id'],
  ), 200);
}

/**
 * REST callback for newsletter.
 *
 * @param  WP_REST_Request $request
 * @return WP_REST_Response
 */
function ishf2027_rest_newsletter_submit(WP_REST_Request $request)
{
  $params = $request->get_params();

  $data = array(
    'nome'         => $params['newsletter_name'] ?? $params['nome'] ?? '',
    'email'        => $params['newsletter_email'] ?? $params['email'] ?? '',
    'gdpr_consent' => !empty($params['newsletter_consent']) || !empty($params['gdpr_consent']),
  );

  $result = ishf2027_insert_newsletter_lead($data);

  if (!$result['success']) {
    return new WP_REST_Response(array(
      'success' => false,
      'message' => $result['message'],
    ), 400);
  }

  ishf2027_send_newsletter_notification($data);

  return new WP_REST_Response(array(
    'success' => true,
    'message' => $result['message'],
    'lead_id' => $result['id'],
  ), 200);
}


// -----------------------------------------------------------------------------
// Helper Email Notifications
// -----------------------------------------------------------------------------

function ishf2027_send_partnership_notification(array $data)
{
  $site_name = defined('SITE_NAME') ? SITE_NAME : 'ISHF 2027';
  $subject   = '[' . $site_name . '] ' . __('New Partnership Inquiry', TEXT_DOMAIN) . ' - ' . esc_html($data['empresa']);

  $recipients = array();
  if (defined('SITE_CONTACT_EMAIL') && !empty(SITE_CONTACT_EMAIL)) {
    $contact_name = defined('SITE_CONTACT_NAME') ? SITE_CONTACT_NAME : 'Festival Secretariat';
    $recipients[] = $contact_name . ' <' . SITE_CONTACT_EMAIL . '>';
  }
  if (empty($recipients)) {
    $recipients[] = get_option('admin_email');
  }

  $email_body  = '<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #1e293b; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px;">';
  $email_body .= '<h2 style="color: #023256; margin-top: 0;">' . esc_html__('Novo Pedido de Parceria', TEXT_DOMAIN) . '</h2>';
  $email_body .= '<p><strong>' . esc_html__('Nome', TEXT_DOMAIN) . ':</strong> ' . esc_html($data['nome']) . '</p>';
  $email_body .= '<p><strong>' . esc_html__('Email', TEXT_DOMAIN) . ':</strong> ' . esc_html($data['email']) . '</p>';
  $email_body .= '<p><strong>' . esc_html__('Empresa / Organização', TEXT_DOMAIN) . ':</strong> ' . esc_html($data['empresa']) . '</p>';
  $email_body .= '<p><strong>' . esc_html__('Cargo', TEXT_DOMAIN) . ':</strong> ' . esc_html($data['cargo']) . '</p>';
  if (!empty($data['telefone'])) {
    $email_body .= '<p><strong>' . esc_html__('Telefone', TEXT_DOMAIN) . ':</strong> ' . esc_html($data['telefone']) . '</p>';
  }
  if (!empty($data['mensagem'])) {
    $email_body .= '<p><strong>' . esc_html__('Mensagem', TEXT_DOMAIN) . ':</strong><br />' . nl2br(esc_html($data['mensagem'])) . '</p>';
  }
  $email_body .= '<hr style="border:0; border-top:1px solid #e2e8f0; margin:20px 0;" />';
  $email_body .= '<p style="font-size:12px; color:#64748b;">' . esc_html__('Registo guardado na base de dados ISHF Leads.', TEXT_DOMAIN) . '</p>';
  $email_body .= '</div>';

  $headers = array('Content-Type: text/html; charset=UTF-8');
  @wp_mail($recipients, $subject, $email_body, $headers);

  // Dispatch webhook to Boost Solutions API if token defined
  ishf2027_send_partnership_webhook($data);
}

function ishf2027_send_partnership_webhook(array $data)
{
  if (!defined('PARTNERSHIP_PROJECT_TOKEN') || empty(PARTNERSHIP_PROJECT_TOKEN)) {
    return;
  }

  $full_name   = trim($data['nome'] ?? '');
  $name_parts  = preg_split('/\s+/', $full_name);
  $first_name  = !empty($name_parts[0]) ? $name_parts[0] : '';
  $last_name   = count($name_parts) > 1 ? end($name_parts) : '';
  $middle_name = count($name_parts) > 2 ? implode(' ', array_slice($name_parts, 1, -1)) : '';
  $consent_str = !empty($data['gdpr_consent']) ? '1' : '0';

  $api_body = array(
    'email_address' => (string) ($data['email'] ?? ''),
    'title'         => '',
    'first_name'    => (string) $first_name,
    'middle_name'   => (string) $middle_name,
    'last_name'     => (string) $last_name,
    'full_name'     => (string) $full_name,
    'function'      => (string) ($data['cargo'] ?? ''),
    'company'       => (string) ($data['empresa'] ?? ''),
    'phone'         => (string) ($data['telefone'] ?? ''),
    'consent'       => (string) $consent_str,
    'message'       => (string) ($data['mensagem'] ?? ''),
    'project_token' => (string) PARTNERSHIP_PROJECT_TOKEN,
  );

  $api_endpoint = defined('NEWSLETTER_API_ENDPOINT') ? NEWSLETTER_API_ENDPOINT : 'https://api-mm.boostsolutions.pt/api/webhooks/gravity-forms';

  $response = wp_remote_post($api_endpoint, array(
    'timeout'     => 15,
    'httpversion' => '1.1',
    'blocking'    => true,
    'headers'     => array(
      'Content-Type' => 'application/json; charset=utf-8',
      'Accept'       => 'application/json',
    ),
    'body'        => wp_json_encode($api_body),
    'data_format' => 'body',
    'sslverify'   => false,
  ));

  if (is_wp_error($response)) {
    $assunto = (defined('SITE_NAME') ? SITE_NAME : 'ISHF 2027') . ' (Erro API Parcerias)';
    $msg = 'Erro ao enviar lead de parceria para a API (wp_error): ' . $response->get_error_message();
    @wp_mail('boostwebdesign@medialivre.pt', $assunto, $msg);
  }
}

function ishf2027_send_newsletter_notification(array $data)
{
  $site_name = defined('SITE_NAME') ? SITE_NAME : 'ISHF 2027';
  $subject   = '[' . $site_name . '] ' . __('Nova Subscrição de Newsletter', TEXT_DOMAIN);

  $recipients = array();
  if (defined('SITE_CONTACT_EMAIL') && !empty(SITE_CONTACT_EMAIL)) {
    $recipients[] = SITE_CONTACT_EMAIL;
  } else {
    $recipients[] = get_option('admin_email');
  }

  $email_body  = '<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #1e293b; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px;">';
  $email_body .= '<h2 style="color: #023256; margin-top: 0;">' . esc_html__('Nova Subscrição de Newsletter', TEXT_DOMAIN) . '</h2>';
  $email_body .= '<p><strong>' . esc_html__('Nome', TEXT_DOMAIN) . ':</strong> ' . esc_html($data['nome']) . '</p>';
  $email_body .= '<p><strong>' . esc_html__('Email', TEXT_DOMAIN) . ':</strong> ' . esc_html($data['email']) . '</p>';
  $email_body .= '<p><strong>' . esc_html__('Data', TEXT_DOMAIN) . ':</strong> ' . date_i18n('Y-m-d H:i:s') . '</p>';
  $email_body .= '</div>';

  $headers = array('Content-Type: text/html; charset=UTF-8');
  @wp_mail($recipients, $subject, $email_body, $headers);

  // Dispatch webhook to Boost Solutions API if token defined
  ishf2027_send_newsletter_webhook($data);
}

function ishf2027_send_newsletter_webhook(array $data)
{
  if (!defined('NEWSLETTER_PROJECT_TOKEN') || empty(NEWSLETTER_PROJECT_TOKEN)) {
    return;
  }

  $full_name   = trim($data['nome'] ?? '');
  $name_parts  = preg_split('/\s+/', $full_name);
  $first_name  = !empty($name_parts[0]) ? $name_parts[0] : '';
  $last_name   = count($name_parts) > 1 ? end($name_parts) : '';
  $middle_name = count($name_parts) > 2 ? implode(' ', array_slice($name_parts, 1, -1)) : '';
  $consent_str = !empty($data['gdpr_consent']) ? '1' : '0';

  $api_body = array(
    'email_address' => (string) ($data['email'] ?? ''),
    'title'         => '',
    'first_name'    => (string) $first_name,
    'middle_name'   => (string) $middle_name,
    'last_name'     => (string) $last_name,
    'full_name'     => (string) $full_name,
    'function'      => '',
    'company'       => '',
    'phone'         => '',
    'consent'       => (string) $consent_str,
    'project_token' => (string) NEWSLETTER_PROJECT_TOKEN,
  );

  $api_endpoint = defined('NEWSLETTER_API_ENDPOINT') ? NEWSLETTER_API_ENDPOINT : 'https://api-mm.boostsolutions.pt/api/webhooks/gravity-forms';

  $response = wp_remote_post($api_endpoint, array(
    'timeout'     => 15,
    'httpversion' => '1.1',
    'blocking'    => true,
    'headers'     => array(
      'Content-Type' => 'application/json; charset=utf-8',
      'Accept'       => 'application/json',
    ),
    'body'        => wp_json_encode($api_body),
    'data_format' => 'body',
    'sslverify'   => false,
  ));

  if (is_wp_error($response)) {
    $assunto = (defined('SITE_NAME') ? SITE_NAME : 'ISHF 2027') . ' (Erro Newsletter)';
    $msg = 'Erro ao subscrever a newsletter (wp_error): ' . $response->get_error_message();
    @wp_mail('boostwebdesign@medialivre.pt', $assunto, $msg);
  }
}


// =============================================================================
// 4. ADMIN MENU & BACKOFFICE ("Leads ISHF")
// =============================================================================

/**
 * Registers the "Leads ISHF" menu in the WordPress Admin Dashboard.
 *
 * @return void
 */
function ishf2027_register_admin_menu()
{
  // Main menu in sidebar
  add_menu_page(
    __('Leads ISHF 2027', TEXT_DOMAIN),
    __('Leads ISHF', TEXT_DOMAIN),
    'manage_options',
    'ishf2027-leads',
    'ishf2027_render_leads_admin_page',
    'dashicons-groups',
    30
  );

  // Also as a submenu under Dashboard for convenience
  add_dashboard_page(
    __('Leads ISHF 2027', TEXT_DOMAIN),
    __('Leads ISHF', TEXT_DOMAIN),
    'manage_options',
    'ishf2027-leads',
    'ishf2027_render_leads_admin_page'
  );
}
add_action('admin_menu', 'ishf2027_register_admin_menu');

/**
 * Renders the Leads ISHF management page in the backoffice.
 *
 * @return void
 */
function ishf2027_render_leads_admin_page()
{
  if (!current_user_can('manage_options')) {
    wp_die(__('Acesso restrito aos administradores do portal.', TEXT_DOMAIN));
  }

  global $wpdb;

  $table_partnerships = ishf2027_get_table_name('partnerships');
  $table_newsletter   = ishf2027_get_table_name('newsletter');

  $active_tab = isset($_GET['tab']) && $_GET['tab'] === 'newsletter' ? 'newsletter' : 'partnerships';

  // Total counts
  $total_partnerships = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$table_partnerships}");
  $total_newsletter   = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$table_newsletter}");

  // Fetch results for active tab
  if ($active_tab === 'newsletter') {
    $items = $wpdb->get_results("SELECT * FROM {$table_newsletter} ORDER BY id DESC LIMIT 200");
  } else {
    $items = $wpdb->get_results("SELECT * FROM {$table_partnerships} ORDER BY id DESC LIMIT 200");
  }

  $date_format = get_option('date_format') . ' ' . get_option('time_format');
  ?>
  <div class="wrap">
    <h1 class="wp-heading-inline">
      <?php esc_html_e('Leads & Contactos ISHF Lisboa 2027', TEXT_DOMAIN); ?>
    </h1>
    <hr class="wp-header-end" />

    <p class="description" style="margin-bottom: 20px;">
      <?php esc_html_e('Gestão nativa de leads dos formulários oficiais do portal (Parcerias e Newsletter). Os dados são gravados diretamente nas tabelas customizadas sem recurso a plugins.', TEXT_DOMAIN); ?>
    </p>

    <!-- Navigation Tabs -->
    <h2 class="nav-tab-wrapper" style="margin-bottom: 20px;">
      <a href="<?php echo esc_url(admin_url('admin.php?page=ishf2027-leads&tab=partnerships')); ?>"
         class="nav-tab <?php echo $active_tab === 'partnerships' ? 'nav-tab-active' : ''; ?>">
        <?php esc_html_e('Parcerias Institucionais', TEXT_DOMAIN); ?>
        <span class="update-plugins count-<?php echo esc_attr($total_partnerships); ?>" style="margin-left: 6px; padding: 2px 7px; border-radius: 10px; background: #023256; color: #fff; font-size: 11px;">
          <?php echo esc_html($total_partnerships); ?>
        </span>
      </a>

      <a href="<?php echo esc_url(admin_url('admin.php?page=ishf2027-leads&tab=newsletter')); ?>"
         class="nav-tab <?php echo $active_tab === 'newsletter' ? 'nav-tab-active' : ''; ?>">
        <?php esc_html_e('Subscrições Newsletter', TEXT_DOMAIN); ?>
        <span class="update-plugins count-<?php echo esc_attr($total_newsletter); ?>" style="margin-left: 6px; padding: 2px 7px; border-radius: 10px; background: #4a8d80; color: #fff; font-size: 11px;">
          <?php echo esc_html($total_newsletter); ?>
        </span>
      </a>
    </h2>

    <!-- Actions Bar: CSV Export -->
    <div class="tablenav top" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
      <div class="alignleft actions">
        <strong>
          <?php
          if ($active_tab === 'partnerships') {
            printf(esc_html__('A apresentar os últimos %d pedidos de parceria (Total: %d)', TEXT_DOMAIN), count($items), $total_partnerships);
          } else {
            printf(esc_html__('A apresentar as últimas %d subscrições de newsletter (Total: %d)', TEXT_DOMAIN), count($items), $total_newsletter);
          }
          ?>
        </strong>
      </div>

      <div class="alignright actions">
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display: inline-block;">
          <input type="hidden" name="action" value="ishf2027_export_csv" />
          <input type="hidden" name="export_type" value="<?php echo esc_attr($active_tab); ?>" />
          <?php wp_nonce_field('ishf2027_export_csv', 'ishf_export_nonce'); ?>
          <button type="submit" class="button button-primary" style="display: inline-flex; align-items: center; gap: 6px;">
            <span class="dashicons dashicons-download" style="margin-top: 4px;"></span>
            <?php
            if ($active_tab === 'partnerships') {
              esc_html_e('Exportar Parcerias para CSV', TEXT_DOMAIN);
            } else {
              esc_html_e('Exportar Newsletter para CSV', TEXT_DOMAIN);
            }
            ?>
          </button>
        </form>
      </div>
    </div>

    <!-- Data Tables -->
    <?php if ($active_tab === 'partnerships'): ?>
      <table class="wp-list-table widefat fixed striped table-view-list">
        <thead>
          <tr>
            <th scope="col" style="width: 50px;">ID</th>
            <th scope="col" style="width: 150px;"><?php esc_html_e('Nome', TEXT_DOMAIN); ?></th>
            <th scope="col" style="width: 180px;"><?php esc_html_e('Email', TEXT_DOMAIN); ?></th>
            <th scope="col" style="width: 160px;"><?php esc_html_e('Empresa', TEXT_DOMAIN); ?></th>
            <th scope="col" style="width: 140px;"><?php esc_html_e('Cargo', TEXT_DOMAIN); ?></th>
            <th scope="col" style="width: 120px;"><?php esc_html_e('Telefone', TEXT_DOMAIN); ?></th>
            <th scope="col"><?php esc_html_e('Mensagem', TEXT_DOMAIN); ?></th>
            <th scope="col" style="width: 90px; text-align: center;"><?php esc_html_e('RGPD', TEXT_DOMAIN); ?></th>
            <th scope="col" style="width: 140px;"><?php esc_html_e('Data Registo', TEXT_DOMAIN); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($items)): ?>
            <?php foreach ($items as $row): ?>
              <tr>
                <td><strong>#<?php echo esc_html($row->id); ?></strong></td>
                <td><strong><?php echo esc_html($row->nome); ?></strong></td>
                <td><a href="mailto:<?php echo esc_attr($row->email); ?>"><?php echo esc_html($row->email); ?></a></td>
                <td><?php echo esc_html($row->empresa); ?></td>
                <td><?php echo esc_html($row->cargo); ?></td>
                <td><?php echo !empty($row->telefone) ? esc_html($row->telefone) : '<span style="color:#94a3b8;">—</span>'; ?></td>
                <td>
                  <?php if (!empty($row->mensagem)): ?>
                    <div style="max-height: 80px; overflow-y: auto; font-size: 13px; color: #334155; line-height: 1.4;">
                      <?php echo nl2br(esc_html($row->mensagem)); ?>
                    </div>
                  <?php else: ?>
                    <span style="color: #94a3b8;">—</span>
                  <?php endif; ?>
                </td>
                <td style="text-align: center;">
                  <?php if ($row->gdpr_consent): ?>
                    <span class="badge" style="background: #e6f4ea; color: #137333; padding: 3px 8px; border-radius: 12px; font-weight: 600; font-size: 11px;">
                      ✓ Sim
                    </span>
                  <?php else: ?>
                    <span class="badge" style="background: #fce8e6; color: #c5221f; padding: 3px 8px; border-radius: 12px; font-weight: 600; font-size: 11px;">
                      ✕ Não
                    </span>
                  <?php endif; ?>
                </td>
                <td><?php echo esc_html(date_i18n($date_format, strtotime($row->created_at))); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="9" style="text-align: center; padding: 30px; color: #64748b;">
                <span class="dashicons dashicons-info" style="font-size: 28px; width: 28px; height: 28px; display: block; margin: 0 auto 10px;"></span>
                <?php esc_html_e('Nenhum pedido de parceria recebido até ao momento.', TEXT_DOMAIN); ?>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

    <?php else: // Newsletter tab ?>
      <table class="wp-list-table widefat fixed striped table-view-list">
        <thead>
          <tr>
            <th scope="col" style="width: 60px;">ID</th>
            <th scope="col" style="width: 250px;"><?php esc_html_e('Nome', TEXT_DOMAIN); ?></th>
            <th scope="col"><?php esc_html_e('Email', TEXT_DOMAIN); ?></th>
            <th scope="col" style="width: 120px; text-align: center;"><?php esc_html_e('RGPD', TEXT_DOMAIN); ?></th>
            <th scope="col" style="width: 180px;"><?php esc_html_e('Data Registo', TEXT_DOMAIN); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($items)): ?>
            <?php foreach ($items as $row): ?>
              <tr>
                <td><strong>#<?php echo esc_html($row->id); ?></strong></td>
                <td><strong><?php echo esc_html($row->nome); ?></strong></td>
                <td><a href="mailto:<?php echo esc_attr($row->email); ?>"><?php echo esc_html($row->email); ?></a></td>
                <td style="text-align: center;">
                  <?php if ($row->gdpr_consent): ?>
                    <span class="badge" style="background: #e6f4ea; color: #137333; padding: 3px 8px; border-radius: 12px; font-weight: 600; font-size: 11px;">
                      ✓ Sim
                    </span>
                  <?php else: ?>
                    <span class="badge" style="background: #fce8e6; color: #c5221f; padding: 3px 8px; border-radius: 12px; font-weight: 600; font-size: 11px;">
                      ✕ Não
                    </span>
                  <?php endif; ?>
                </td>
                <td><?php echo esc_html(date_i18n($date_format, strtotime($row->created_at))); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" style="text-align: center; padding: 30px; color: #64748b;">
                <span class="dashicons dashicons-email-alt" style="font-size: 28px; width: 28px; height: 28px; display: block; margin: 0 auto 10px;"></span>
                <?php esc_html_e('Nenhuma subscrição de newsletter registada até ao momento.', TEXT_DOMAIN); ?>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    <?php endif; ?>

  </div>
  <?php
}


// =============================================================================
// 5. CSV EXPORTER (BÓNUS SENIOR)
// =============================================================================

/**
 * Handles CSV export for Partnerships and Newsletter leads.
 * Hooked to `admin_post_ishf2027_export_csv`.
 *
 * @return void Streams CSV and terminates.
 */
function ishf2027_handle_csv_export()
{
  if (!current_user_can('manage_options')) {
    wp_die(__('Acesso não autorizado.', TEXT_DOMAIN));
  }

  check_admin_referer('ishf2027_export_csv', 'ishf_export_nonce');

  global $wpdb;

  $type = isset($_POST['export_type']) && $_POST['export_type'] === 'newsletter' ? 'newsletter' : 'partnerships';
  $date = date('Y-m-d_H-i');

  if ($type === 'newsletter') {
    $table_name = ishf2027_get_table_name('newsletter');
    $filename   = 'ishf2027-newsletter-leads-' . $date . '.csv';
    $headers    = array('ID', 'Nome', 'Email', 'Consentimento RGPD', 'Data Subscricao');

    $rows = $wpdb->get_results("SELECT id, nome, email, gdpr_consent, created_at FROM {$table_name} ORDER BY id DESC", ARRAY_A);
  } else {
    $table_name = ishf2027_get_table_name('partnerships');
    $filename   = 'ishf2027-parcerias-leads-' . $date . '.csv';
    $headers    = array('ID', 'Nome', 'Email', 'Empresa', 'Cargo', 'Telefone', 'Mensagem', 'Consentimento RGPD', 'Data Registo');

    $rows = $wpdb->get_results("SELECT id, nome, email, empresa, cargo, telefone, mensagem, gdpr_consent, created_at FROM {$table_name} ORDER BY id DESC", ARRAY_A);
  }

  // Clear output buffers
  if (ob_get_level()) {
    ob_end_clean();
  }

  // Stream headers
  header('Content-Type: text/csv; charset=UTF-8');
  header('Content-Disposition: attachment; filename="' . $filename . '"');
  header('Pragma: no-cache');
  header('Expires: 0');

  $output = fopen('php://output', 'w');

  // UTF-8 BOM for seamless Microsoft Excel compatibility with European accents/characters
  fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

  // Write header row with semicolon delimiter (Portuguese/European standard for Excel)
  fputcsv($output, $headers, ';');

  if (!empty($rows)) {
    foreach ($rows as $row) {
      // Format GDPR consent
      if (isset($row['gdpr_consent'])) {
        $row['gdpr_consent'] = $row['gdpr_consent'] ? 'Sim' : 'Nao';
      }
      fputcsv($output, array_values($row), ';');
    }
  }

  fclose($output);
  exit;
}
add_action('admin_post_ishf2027_export_csv', 'ishf2027_handle_csv_export');
