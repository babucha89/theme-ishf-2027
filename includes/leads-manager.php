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
defined('ISHF2027_DB_VERSION') or define('ISHF2027_DB_VERSION', '1.1.0');

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
    webhook_status varchar(20) NOT NULL DEFAULT '',
    webhook_message varchar(255) NOT NULL DEFAULT '',
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
    webhook_status varchar(20) NOT NULL DEFAULT '',
    webhook_message varchar(255) NOT NULL DEFAULT '',
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
  // -- Optional result of the external API (webhook) call: ok | error | skipped
  $webhook_status  = isset($data['webhook_status']) ? sanitize_key($data['webhook_status']) : '';
  $webhook_message = isset($data['webhook_message']) ? mb_substr(sanitize_text_field($data['webhook_message']), 0, 255) : '';

  // Validation
  if (empty($nome) || empty($email) || empty($empresa) || empty($cargo)) {
    return array(
      'success' => false,
      'message' => __('Please fill in all required fields (*).', TEXT_DOMAIN),
    );
  }

  if (!is_email($email)) {
    return array(
      'success' => false,
      'message' => __('Please enter a valid email address.', TEXT_DOMAIN),
    );
  }

  if ($gdpr_consent !== 1) {
    return array(
      'success' => false,
      'message' => __('You must authorise the processing of personal data (GDPR).', TEXT_DOMAIN),
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
      'message' => __('Your partnership request has already been received.', TEXT_DOMAIN),
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
      'webhook_status'  => $webhook_status,
      'webhook_message' => $webhook_message,
      'created_at'   => current_time('mysql'),
    ),
    array('%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s')
  );

  if ($inserted === false) {
    return array(
      'success' => false,
      'message' => __('Error saving your data. Please try again later.', TEXT_DOMAIN),
    );
  }

  return array(
    'success' => true,
    'id'      => $wpdb->insert_id,
    'message' => __('Thank you for your interest! Your partnership request has been registered successfully.', TEXT_DOMAIN),
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
  // -- Optional result of the external API (webhook) call: ok | error | skipped
  $webhook_status  = isset($data['webhook_status']) ? sanitize_key($data['webhook_status']) : '';
  $webhook_message = isset($data['webhook_message']) ? mb_substr(sanitize_text_field($data['webhook_message']), 0, 255) : '';

  // Validation
  if (empty($nome) || empty($email)) {
    return array(
      'success' => false,
      'message' => __('Please fill in your name and email.', TEXT_DOMAIN),
    );
  }

  if (!is_email($email)) {
    return array(
      'success' => false,
      'message' => __('Please enter a valid email address.', TEXT_DOMAIN),
    );
  }

  if ($gdpr_consent !== 1) {
    return array(
      'success' => false,
      'message' => __('You must authorise the sending of newsletters (GDPR).', TEXT_DOMAIN),
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
      'message' => __('You are already subscribed.', TEXT_DOMAIN),
    );
  }

  $inserted = $wpdb->insert(
    $table_name,
    array(
      'nome'         => $nome,
      'email'        => $email,
      'gdpr_consent' => $gdpr_consent,
      'webhook_status'  => $webhook_status,
      'webhook_message' => $webhook_message,
      'created_at'   => current_time('mysql'),
    ),
    array('%s', '%s', '%d', '%s', '%s', '%s')
  );

  if ($inserted === false) {
    return array(
      'success' => false,
      'message' => __('Error registering your subscription. Please try again later.', TEXT_DOMAIN),
    );
  }

  return array(
    'success' => true,
    'id'      => $wpdb->insert_id,
    'message' => __('Subscription successful! Thank you for following ISHF 2027.', TEXT_DOMAIN),
  );
}


// =============================================================================
// 2b. WEBHOOK RESULT LOG (per contact)
// =============================================================================

/**
 * Converts a wp_remote_post() response into array(status, message).
 * status: ok | error
 *
 * @param  array|WP_Error $response
 * @return array
 */
function ishf2027_webhook_outcome($response)
{
  if (is_wp_error($response)) {
    return array('error', $response->get_error_message());
  }
  $code = (int) wp_remote_retrieve_response_code($response);
  if ($code >= 200 && $code < 300) {
    return array('ok', 'HTTP ' . $code);
  }
  $json = json_decode(wp_remote_retrieve_body($response), true);
  $msg  = !empty($json['message']) && is_string($json['message']) ? $json['message'] : '';
  return array('error', trim('HTTP ' . $code . ' ' . $msg));
}

/**
 * Stores the webhook result on an existing contact row.
 *
 * @param  string $type    'partnerships' or 'newsletter'
 * @param  int    $lead_id Row ID (0 = ignore)
 * @param  string $status  ok | error | skipped
 * @param  string $message Short detail
 * @return void
 */
function ishf2027_log_webhook($type, $lead_id, $status, $message = '')
{
  global $wpdb;
  $lead_id = (int) $lead_id;
  if ($lead_id < 1) {
    return;
  }
  $wpdb->update(
    ishf2027_get_table_name($type),
    array(
      'webhook_status'  => sanitize_key($status),
      'webhook_message' => mb_substr(sanitize_text_field($message), 0, 255),
    ),
    array('id' => $lead_id),
    array('%s', '%s'),
    array('%d')
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
      'message' => __('Security check expired. Please refresh the page.', TEXT_DOMAIN),
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
  $data['lead_id'] = (int) $result['id'];
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
      'message' => __('Security check expired. Please refresh the page.', TEXT_DOMAIN),
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
  $data['lead_id'] = (int) $result['id'];
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

  $data['lead_id'] = (int) $result['id'];
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

  $data['lead_id'] = (int) $result['id'];
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
  $email_body .= '<p style="font-size:12px; color:#64748b;">' . esc_html__('Registo guardado na base de dados ISHF Contacts.', TEXT_DOMAIN) . '</p>';
  $email_body .= '</div>';

  $headers = array('Content-Type: text/html; charset=UTF-8');
  @wp_mail($recipients, $subject, $email_body, $headers);

  // Dispatch webhook to Boost Solutions API if token defined
  ishf2027_send_partnership_webhook($data);
}

function ishf2027_send_partnership_webhook(array $data)
{
  if (!defined('PARTNERSHIP_PROJECT_TOKEN') || empty(PARTNERSHIP_PROJECT_TOKEN)) {
    ishf2027_log_webhook('partnerships', $data['lead_id'] ?? 0, 'skipped', 'No project token configured');
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

  list($wh_status, $wh_msg) = ishf2027_webhook_outcome($response);
  ishf2027_log_webhook('partnerships', $data['lead_id'] ?? 0, $wh_status, $wh_msg);

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
    ishf2027_log_webhook('newsletter', $data['lead_id'] ?? 0, 'skipped', 'No project token configured');
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

  list($wh_status, $wh_msg) = ishf2027_webhook_outcome($response);
  ishf2027_log_webhook('newsletter', $data['lead_id'] ?? 0, $wh_status, $wh_msg);

  if (is_wp_error($response)) {
    $assunto = (defined('SITE_NAME') ? SITE_NAME : 'ISHF 2027') . ' (Erro Newsletter)';
    $msg = 'Erro ao subscrever a newsletter (wp_error): ' . $response->get_error_message();
    @wp_mail('boostwebdesign@medialivre.pt', $assunto, $msg);
  }
}


// =============================================================================
// 4. ADMIN MENU & BACKOFFICE ("ISHF Contacts")
// =============================================================================
//  Safety net only: every Partnership and Newsletter submission is kept here
//  with all its data. The team that works the contacts does it outside
//  WordPress, so there is deliberately no status/workflow per contact.
// =============================================================================

/**
 * Admin screen slug (kept from the first version so existing links still work).
 */
defined('ISHF2027_CONTACTS_SLUG') or define('ISHF2027_CONTACTS_SLUG', 'ishf2027-leads');

/**
 * Number of contacts received since the current user last opened the screen.
 * Returns 0 until the screen is opened for the first time.
 *
 * @return int
 */
function ishf2027_count_new_contacts()
{
  global $wpdb;

  $last_seen = get_user_meta(get_current_user_id(), 'ishf2027_contacts_seen', true);
  if (empty($last_seen)) {
    return 0;
  }

  $total = 0;
  foreach (array('partnerships', 'newsletter') as $type) {
    $table  = ishf2027_get_table_name($type);
    $total += (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$table} WHERE created_at > %s", $last_seen));
  }
  return $total;
}

/**
 * Registers the "ISHF Contacts" menu in the WordPress Admin Dashboard.
 *
 * @return void
 */
function ishf2027_register_admin_menu()
{
  $new   = ishf2027_count_new_contacts();
  $label = __('ISHF Contacts', TEXT_DOMAIN);
  $badge = $new > 0 ? ' <span class="awaiting-mod">' . (int) $new . '</span>' : '';

  // Main menu in sidebar (dashicons-id-alt = contact card; not used by any CPT)
  add_menu_page(
    __('ISHF Contacts - Lisbon 2027', TEXT_DOMAIN),
    $label . $badge,
    'manage_options',
    ISHF2027_CONTACTS_SLUG,
    'ishf2027_render_leads_admin_page',
    'dashicons-id-alt',
    30
  );

  // Also as a submenu under Dashboard for convenience
  add_dashboard_page(
    __('ISHF Contacts - Lisbon 2027', TEXT_DOMAIN),
    $label,
    'manage_options',
    ISHF2027_CONTACTS_SLUG,
    'ishf2027_render_leads_admin_page'
  );
}
add_action('admin_menu', 'ishf2027_register_admin_menu');

/**
 * Reads and sanitises the screen filters (tab, search, dates, page size, page).
 *
 * @param  array $src Usually $_REQUEST
 * @return array
 */
function ishf2027_contacts_get_filters(array $src)
{
  $valid_date = function ($v) {
    $v = is_string($v) ? trim($v) : '';
    return preg_match('/^\d{4}-\d{2}-\d{2}$/', $v) ? $v : '';
  };
  $per_page = isset($src['per_page']) ? (int) $src['per_page'] : 50;

  return array(
    'tab'      => (isset($src['tab']) && $src['tab'] === 'newsletter') ? 'newsletter' : 'partnerships',
    's'        => isset($src['s']) ? sanitize_text_field(wp_unslash($src['s'])) : '',
    'from'     => $valid_date($src['from'] ?? ''),
    'to'       => $valid_date($src['to'] ?? ''),
    'per_page' => in_array($per_page, array(20, 50, 100), true) ? $per_page : 50,
    'paged'    => max(1, (int) ($src['paged'] ?? 1)),
  );
}

/**
 * Builds a prepared WHERE clause for the filters.
 *
 * @param  string $type    'partnerships' or 'newsletter'
 * @param  array  $filters Output of ishf2027_contacts_get_filters()
 * @return string
 */
function ishf2027_contacts_where($type, array $filters)
{
  global $wpdb;

  $where  = array('1=1');
  $params = array();

  if ($filters['s'] !== '') {
    $cols = ($type === 'newsletter')
      ? array('nome', 'email')
      : array('nome', 'email', 'empresa', 'cargo', 'telefone', 'mensagem');
    $like = '%' . $wpdb->esc_like($filters['s']) . '%';
    $parts = array();
    foreach ($cols as $col) {
      $parts[]  = "{$col} LIKE %s";
      $params[] = $like;
    }
    $where[] = '(' . implode(' OR ', $parts) . ')';
  }
  if ($filters['from'] !== '') {
    $where[]  = 'created_at >= %s';
    $params[] = $filters['from'] . ' 00:00:00';
  }
  if ($filters['to'] !== '') {
    $where[]  = 'created_at <= %s';
    $params[] = $filters['to'] . ' 23:59:59';
  }

  $sql = implode(' AND ', $where);
  return $params ? $wpdb->prepare($sql, $params) : $sql;
}

/**
 * Small "API" badge for the webhook result column.
 *
 * @param  object $row
 * @return string HTML (escaped)
 */
function ishf2027_webhook_badge($row)
{
  $status = $row->webhook_status ?? '';
  $map = array(
    'ok'      => array('#e6f4ea', '#137333', __('Sent', TEXT_DOMAIN)),
    'error'   => array('#fce8e6', '#c5221f', __('Failed', TEXT_DOMAIN)),
    'skipped' => array('#f1f3f4', '#5f6368', __('Not configured', TEXT_DOMAIN)),
  );
  if (!isset($map[$status])) {
    return '<span style="color:#94a3b8;">&mdash;</span>';
  }
  list($bg, $fg, $text) = $map[$status];
  $html = '<span style="background:' . $bg . ';color:' . $fg . ';padding:3px 8px;border-radius:12px;font-weight:600;font-size:11px;white-space:nowrap;">' . esc_html($text) . '</span>';
  if (!empty($row->webhook_message)) {
    $html .= '<div style="font-size:11px;color:#64748b;margin-top:3px;word-break:break-word;">' . esc_html($row->webhook_message) . '</div>';
  }
  return $html;
}

/**
 * Renders the ISHF Contacts management page in the backoffice.
 *
 * @return void
 */
function ishf2027_render_leads_admin_page()
{
  if (!current_user_can('manage_options')) {
    wp_die(esc_html__('Restricted to site administrators.', TEXT_DOMAIN));
  }

  global $wpdb;

  $filters = ishf2027_contacts_get_filters($_GET);
  $tab     = $filters['tab'];
  $table   = ishf2027_get_table_name($tab);

  $total_partnerships = (int) $wpdb->get_var('SELECT COUNT(*) FROM ' . ishf2027_get_table_name('partnerships'));
  $total_newsletter   = (int) $wpdb->get_var('SELECT COUNT(*) FROM ' . ishf2027_get_table_name('newsletter'));

  $where       = ishf2027_contacts_where($tab, $filters);
  $total_match = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$table} WHERE {$where}");
  $pages       = max(1, (int) ceil($total_match / $filters['per_page']));
  $paged       = min($filters['paged'], $pages);
  $offset      = ($paged - 1) * $filters['per_page'];
  $items       = $wpdb->get_results($wpdb->prepare(
    "SELECT * FROM {$table} WHERE {$where} ORDER BY id DESC LIMIT %d OFFSET %d",
    $filters['per_page'],
    $offset
  ));

  $date_format = get_option('date_format') . ' ' . get_option('time_format');
  $base_args   = array_filter(array(
    'page'     => ISHF2027_CONTACTS_SLUG,
    'tab'      => $tab,
    's'        => $filters['s'],
    'from'     => $filters['from'],
    'to'       => $filters['to'],
    'per_page' => $filters['per_page'] !== 50 ? $filters['per_page'] : '',
  ), function ($v) { return $v !== ''; });
  $has_filter  = ($filters['s'] !== '' || $filters['from'] !== '' || $filters['to'] !== '');
  $columns     = ($tab === 'partnerships') ? 11 : 7;

  $notice = isset($_GET['ishf_notice']) ? sanitize_key($_GET['ishf_notice']) : '';
  $purged = isset($_GET['purged']) ? (int) $_GET['purged'] : 0;
  ?>
  <div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e('ISHF Contacts - Lisbon 2027', TEXT_DOMAIN); ?></h1>
    <hr class="wp-header-end" />

    <?php if ($notice === 'deleted'): ?>
      <div class="notice notice-success is-dismissible"><p><?php esc_html_e('Contact deleted.', TEXT_DOMAIN); ?></p></div>
    <?php elseif ($notice === 'purged'): ?>
      <div class="notice notice-success is-dismissible"><p><?php printf(esc_html__('%d contact(s) deleted.', TEXT_DOMAIN), $purged); ?></p></div>
    <?php endif; ?>

    <p class="description" style="margin-bottom: 20px;">
      <?php esc_html_e('Safety copy of everyone who submits the Partnership or Newsletter form. Data is stored in the website database; the contacts are worked on outside WordPress. Contacts are personal data (GDPR): export what is needed and delete what is no longer required.', TEXT_DOMAIN); ?>
    </p>

    <h2 class="nav-tab-wrapper" style="margin-bottom: 20px;">
      <a href="<?php echo esc_url(add_query_arg(array('page' => ISHF2027_CONTACTS_SLUG, 'tab' => 'partnerships'), admin_url('admin.php'))); ?>"
         class="nav-tab <?php echo $tab === 'partnerships' ? 'nav-tab-active' : ''; ?>">
        <?php esc_html_e('Parcerias Institucionais', TEXT_DOMAIN); ?>
        <span style="margin-left: 6px; padding: 2px 7px; border-radius: 10px; background: #023256; color: #fff; font-size: 11px;"><?php echo esc_html($total_partnerships); ?></span>
      </a>
      <a href="<?php echo esc_url(add_query_arg(array('page' => ISHF2027_CONTACTS_SLUG, 'tab' => 'newsletter'), admin_url('admin.php'))); ?>"
         class="nav-tab <?php echo $tab === 'newsletter' ? 'nav-tab-active' : ''; ?>">
        <?php esc_html_e('Subscrições Newsletter', TEXT_DOMAIN); ?>
        <span style="margin-left: 6px; padding: 2px 7px; border-radius: 10px; background: #4a8d80; color: #fff; font-size: 11px;"><?php echo esc_html($total_newsletter); ?></span>
      </a>
    </h2>

    <!-- Filters + CSV export -->
    <form method="get" action="<?php echo esc_url(admin_url('admin.php')); ?>" style="display:flex;flex-wrap:wrap;gap:8px;align-items:flex-end;margin-bottom:15px;">
      <input type="hidden" name="page" value="<?php echo esc_attr(ISHF2027_CONTACTS_SLUG); ?>" />
      <input type="hidden" name="tab" value="<?php echo esc_attr($tab); ?>" />
      <label>
        <span class="screen-reader-text"><?php esc_html_e('Search', TEXT_DOMAIN); ?></span>
        <input type="search" name="s" value="<?php echo esc_attr($filters['s']); ?>" placeholder="<?php esc_attr_e('Search name, email, company...', TEXT_DOMAIN); ?>" style="min-width:260px;" />
      </label>
      <label><?php esc_html_e('From', TEXT_DOMAIN); ?> <input type="date" name="from" value="<?php echo esc_attr($filters['from']); ?>" /></label>
      <label><?php esc_html_e('To', TEXT_DOMAIN); ?> <input type="date" name="to" value="<?php echo esc_attr($filters['to']); ?>" /></label>
      <label><?php esc_html_e('Per page', TEXT_DOMAIN); ?>
        <select name="per_page">
          <?php foreach (array(20, 50, 100) as $n): ?>
            <option value="<?php echo (int) $n; ?>" <?php selected($filters['per_page'], $n); ?>><?php echo (int) $n; ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <button type="submit" class="button"><?php esc_html_e('Filter', TEXT_DOMAIN); ?></button>
      <?php if ($has_filter): ?>
        <a class="button-link" href="<?php echo esc_url(add_query_arg(array('page' => ISHF2027_CONTACTS_SLUG, 'tab' => $tab), admin_url('admin.php'))); ?>"><?php esc_html_e('Clear filters', TEXT_DOMAIN); ?></a>
      <?php endif; ?>
    </form>

    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:12px;">
      <strong>
        <?php
        printf(
          esc_html__('Showing %1$d of %2$d contacts', TEXT_DOMAIN),
          count($items),
          $total_match
        );
        echo $has_filter ? ' ' . esc_html__('(filtered)', TEXT_DOMAIN) : '';
        ?>
      </strong>
      <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="ishf2027_export_csv" />
        <input type="hidden" name="export_type" value="<?php echo esc_attr($tab); ?>" />
        <input type="hidden" name="s" value="<?php echo esc_attr($filters['s']); ?>" />
        <input type="hidden" name="from" value="<?php echo esc_attr($filters['from']); ?>" />
        <input type="hidden" name="to" value="<?php echo esc_attr($filters['to']); ?>" />
        <?php wp_nonce_field('ishf2027_export_csv', 'ishf_export_nonce'); ?>
        <button type="submit" class="button button-primary" style="display:inline-flex;align-items:center;gap:6px;">
          <span class="dashicons dashicons-download" style="margin-top:4px;"></span>
          <?php echo $has_filter ? esc_html__('Export filtered results to CSV', TEXT_DOMAIN) : esc_html__('Export all to CSV', TEXT_DOMAIN); ?>
        </button>
      </form>
    </div>

    <!-- Table -->
    <table class="wp-list-table widefat fixed striped table-view-list">
      <thead>
        <tr>
          <th scope="col" style="width:50px;">ID</th>
          <th scope="col" style="width:150px;"><?php esc_html_e('Nome', TEXT_DOMAIN); ?></th>
          <th scope="col" style="width:190px;"><?php esc_html_e('Email', TEXT_DOMAIN); ?></th>
          <?php if ($tab === 'partnerships'): ?>
            <th scope="col" style="width:150px;"><?php esc_html_e('Empresa', TEXT_DOMAIN); ?></th>
            <th scope="col" style="width:130px;"><?php esc_html_e('Cargo', TEXT_DOMAIN); ?></th>
            <th scope="col" style="width:110px;"><?php esc_html_e('Telefone', TEXT_DOMAIN); ?></th>
            <th scope="col"><?php esc_html_e('Mensagem', TEXT_DOMAIN); ?></th>
          <?php endif; ?>
          <th scope="col" style="width:80px;text-align:center;"><?php esc_html_e('RGPD', TEXT_DOMAIN); ?></th>
          <th scope="col" style="width:130px;"><?php esc_html_e('API', TEXT_DOMAIN); ?></th>
          <th scope="col" style="width:130px;"><?php esc_html_e('Data Registo', TEXT_DOMAIN); ?></th>
          <th scope="col" style="width:70px;"><span class="screen-reader-text"><?php esc_html_e('Actions', TEXT_DOMAIN); ?></span></th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($items)): ?>
          <?php foreach ($items as $row): ?>
            <tr>
              <td><strong>#<?php echo esc_html($row->id); ?></strong></td>
              <td><strong><?php echo esc_html($row->nome); ?></strong></td>
              <td><a href="mailto:<?php echo esc_attr($row->email); ?>"><?php echo esc_html($row->email); ?></a></td>
              <?php if ($tab === 'partnerships'): ?>
                <td><?php echo esc_html($row->empresa); ?></td>
                <td><?php echo esc_html($row->cargo); ?></td>
                <td><?php echo !empty($row->telefone) ? esc_html($row->telefone) : '<span style="color:#94a3b8;">&mdash;</span>'; ?></td>
                <td>
                  <?php if (!empty($row->mensagem)): ?>
                    <div style="max-height:80px;overflow-y:auto;font-size:13px;color:#334155;line-height:1.4;"><?php echo nl2br(esc_html($row->mensagem)); ?></div>
                  <?php else: ?>
                    <span style="color:#94a3b8;">&mdash;</span>
                  <?php endif; ?>
                </td>
              <?php endif; ?>
              <td style="text-align:center;">
                <?php if ($row->gdpr_consent): ?>
                  <span style="background:#e6f4ea;color:#137333;padding:3px 8px;border-radius:12px;font-weight:600;font-size:11px;">&#10003; <?php esc_html_e('Sim', TEXT_DOMAIN); ?></span>
                <?php else: ?>
                  <span style="background:#fce8e6;color:#c5221f;padding:3px 8px;border-radius:12px;font-weight:600;font-size:11px;">&#10005; <?php esc_html_e('Não', TEXT_DOMAIN); ?></span>
                <?php endif; ?>
              </td>
              <td><?php echo ishf2027_webhook_badge($row); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inside helper ?></td>
              <td><?php echo esc_html(date_i18n($date_format, strtotime($row->created_at))); ?></td>
              <td>
                <?php
                $delete_url = wp_nonce_url(
                  add_query_arg(array('action' => 'ishf2027_delete_contact', 'type' => $tab, 'id' => (int) $row->id), admin_url('admin-post.php')),
                  'ishf2027_delete_' . $tab . '_' . (int) $row->id
                );
                ?>
                <a href="<?php echo esc_url($delete_url); ?>" class="submitdelete" style="color:#b32d2e;"
                   onclick="return confirm('<?php echo esc_js(__('Delete this contact permanently? This cannot be undone.', TEXT_DOMAIN)); ?>');"><?php esc_html_e('Delete', TEXT_DOMAIN); ?></a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="<?php echo (int) $columns; ?>" style="text-align:center;padding:30px;color:#64748b;">
              <span class="dashicons dashicons-info" style="font-size:28px;width:28px;height:28px;display:block;margin:0 auto 10px;"></span>
              <?php echo $has_filter ? esc_html__('No contacts match these filters.', TEXT_DOMAIN) : esc_html__('No contacts received yet.', TEXT_DOMAIN); ?>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <?php if ($pages > 1): ?>
      <div class="tablenav bottom">
        <div class="tablenav-pages">
          <span class="displaying-num"><?php printf(esc_html__('%d items', TEXT_DOMAIN), $total_match); ?></span>
          <?php
          echo paginate_links(array( // phpcs:ignore WordPress.Security.EscapeOutput -- core helper
            'base'      => add_query_arg('paged', '%#%', add_query_arg($base_args, admin_url('admin.php'))),
            'format'    => '',
            'current'   => $paged,
            'total'     => $pages,
            'prev_text' => '&lsaquo;',
            'next_text' => '&rsaquo;',
          ));
          ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- Data retention: delete old contacts -->
    <div class="card" style="max-width:none;margin-top:30px;">
      <h2 style="margin-top:0;"><?php esc_html_e('Data retention', TEXT_DOMAIN); ?></h2>
      <p class="description"><?php esc_html_e('Permanently delete the contacts of the selected tab received before a given date (for example after the event, once the team has the data). Export a CSV first.', TEXT_DOMAIN); ?></p>
      <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
            onsubmit="return confirm('<?php echo esc_js(__('Permanently delete all contacts of this tab received before the chosen date? This cannot be undone.', TEXT_DOMAIN)); ?>');">
        <input type="hidden" name="action" value="ishf2027_purge_contacts" />
        <input type="hidden" name="type" value="<?php echo esc_attr($tab); ?>" />
        <?php wp_nonce_field('ishf2027_purge_contacts', 'ishf_purge_nonce'); ?>
        <label><?php esc_html_e('Delete contacts received before', TEXT_DOMAIN); ?>
          <input type="date" name="before" required />
        </label>
        <button type="submit" class="button" style="color:#b32d2e;border-color:#b32d2e;"><?php esc_html_e('Delete', TEXT_DOMAIN); ?></button>
      </form>
    </div>
  </div>
  <?php
  // Everything shown here counts as seen (resets the menu badge on next load)
  update_user_meta(get_current_user_id(), 'ishf2027_contacts_seen', current_time('mysql'));
}


// =============================================================================
// 5. DELETE / PURGE ACTIONS (GDPR)
// =============================================================================

/**
 * Redirects back to the contacts screen.
 *
 * @param  string $type   Tab
 * @param  array  $extra  Extra query args
 * @return void
 */
function ishf2027_contacts_redirect($type, array $extra = array())
{
  wp_safe_redirect(add_query_arg(array_merge(array('page' => ISHF2027_CONTACTS_SLUG, 'tab' => $type), $extra), admin_url('admin.php')));
  exit;
}

/**
 * Deletes one contact. Hooked to `admin_post_ishf2027_delete_contact`.
 *
 * @return void
 */
function ishf2027_handle_delete_contact()
{
  if (!current_user_can('manage_options')) {
    wp_die(esc_html__('Unauthorised access.', TEXT_DOMAIN));
  }
  $type = (isset($_GET['type']) && $_GET['type'] === 'newsletter') ? 'newsletter' : 'partnerships';
  $id   = isset($_GET['id']) ? absint($_GET['id']) : 0;
  check_admin_referer('ishf2027_delete_' . $type . '_' . $id);

  global $wpdb;
  if ($id > 0) {
    $wpdb->delete(ishf2027_get_table_name($type), array('id' => $id), array('%d'));
  }
  ishf2027_contacts_redirect($type, array('ishf_notice' => 'deleted'));
}
add_action('admin_post_ishf2027_delete_contact', 'ishf2027_handle_delete_contact');

/**
 * Deletes every contact of a tab received before a date.
 * Hooked to `admin_post_ishf2027_purge_contacts`.
 *
 * @return void
 */
function ishf2027_handle_purge_contacts()
{
  if (!current_user_can('manage_options')) {
    wp_die(esc_html__('Unauthorised access.', TEXT_DOMAIN));
  }
  check_admin_referer('ishf2027_purge_contacts', 'ishf_purge_nonce');

  $type   = (isset($_POST['type']) && $_POST['type'] === 'newsletter') ? 'newsletter' : 'partnerships';
  $before = isset($_POST['before']) ? sanitize_text_field(wp_unslash($_POST['before'])) : '';
  $purged = 0;

  if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $before)) {
    global $wpdb;
    $table  = ishf2027_get_table_name($type);
    $purged = (int) $wpdb->query($wpdb->prepare("DELETE FROM {$table} WHERE created_at < %s", $before . ' 00:00:00'));
  }
  ishf2027_contacts_redirect($type, array('ishf_notice' => 'purged', 'purged' => $purged));
}
add_action('admin_post_ishf2027_purge_contacts', 'ishf2027_handle_purge_contacts');


// =============================================================================
// 6. CSV EXPORTER
// =============================================================================

/**
 * Handles CSV export for Partnerships and Newsletter contacts, honouring the
 * search and date filters. Hooked to `admin_post_ishf2027_export_csv`.
 *
 * @return void Streams CSV and terminates.
 */
function ishf2027_handle_csv_export()
{
  if (!current_user_can('manage_options')) {
    wp_die(esc_html__('Unauthorised access.', TEXT_DOMAIN));
  }

  check_admin_referer('ishf2027_export_csv', 'ishf_export_nonce');

  global $wpdb;

  $filters = ishf2027_contacts_get_filters($_POST);
  $type    = $filters['tab'] = (isset($_POST['export_type']) && $_POST['export_type'] === 'newsletter') ? 'newsletter' : 'partnerships';
  $where   = ishf2027_contacts_where($type, $filters);
  $table   = ishf2027_get_table_name($type);
  $date    = date('Y-m-d_H-i');
  $suffix  = ($filters['s'] !== '' || $filters['from'] !== '' || $filters['to'] !== '') ? '-filtered' : '';

  if ($type === 'newsletter') {
    $filename = 'ishf2027-newsletter-contacts' . $suffix . '-' . $date . '.csv';
    $headers  = array('ID', 'Nome', 'Email', 'Consentimento RGPD', 'Data Subscricao', 'Envio API', 'Detalhe API');
    $rows     = $wpdb->get_results("SELECT id, nome, email, gdpr_consent, created_at, webhook_status, webhook_message FROM {$table} WHERE {$where} ORDER BY id DESC", ARRAY_A);
  } else {
    $filename = 'ishf2027-parcerias-contacts' . $suffix . '-' . $date . '.csv';
    $headers  = array('ID', 'Nome', 'Email', 'Empresa', 'Cargo', 'Telefone', 'Mensagem', 'Consentimento RGPD', 'Data Registo', 'Envio API', 'Detalhe API');
    $rows     = $wpdb->get_results("SELECT id, nome, email, empresa, cargo, telefone, mensagem, gdpr_consent, created_at, webhook_status, webhook_message FROM {$table} WHERE {$where} ORDER BY id DESC", ARRAY_A);
  }

  // Clear output buffers
  if (ob_get_level()) {
    ob_end_clean();
  }

  header('Content-Type: text/csv; charset=UTF-8');
  header('Content-Disposition: attachment; filename="' . $filename . '"');
  header('Pragma: no-cache');
  header('Expires: 0');

  $output = fopen('php://output', 'w');

  // UTF-8 BOM for seamless Microsoft Excel compatibility with European accents/characters
  fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

  // Semicolon delimiter (Portuguese/European standard for Excel)
  fputcsv($output, $headers, ';');

  foreach ((array) $rows as $row) {
    $row['gdpr_consent'] = $row['gdpr_consent'] ? 'Sim' : 'Nao';
    // Neutralise spreadsheet formula injection in user-provided text
    foreach ($row as $k => $v) {
      if (is_string($v) && $v !== '' && strpbrk($v[0], "=+-@\t\r") !== false && !preg_match('/^\+[\d\s().-]+$/', $v)) {
        $row[$k] = "'" . $v;
      }
    }
    fputcsv($output, array_values($row), ';');
  }

  fclose($output);
  exit;
}
add_action('admin_post_ishf2027_export_csv', 'ishf2027_handle_csv_export');
