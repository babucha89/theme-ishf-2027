<?php
// -----------------------------------------------------------------------------
//  File: FORM HANDLERS (Partnership & Newsletter)
//  Follows the uikit-boostsolutions parent template architecture
//  Edited: BB, 27-OUT-2026
// -----------------------------------------------------------------------------

add_action('wp_loaded', 'ishf_process_landing_forms');
function ishf_process_landing_forms() {
  if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    return;
  }

  // =========================================================================
  // 1. PARTNERSHIP CONTACT FORM HANDLER
  // =========================================================================
  if (isset($_POST['partnership_form_submitted'])) {
    $context = !empty($_POST['form_context']) ? sanitize_text_field($_POST['form_context']) : 'partnership';
    $result = array(
      'success' => null,
      'error'   => '',
      'context' => $context,
      'fields'  => $_POST,
    );

    // Nonce validation
    if (!isset($_POST['partnership_nonce']) || !wp_verify_nonce($_POST['partnership_nonce'], 'partnership_form_action')) {
      $result['success'] = false;
      $result['error'] = __('Security validation failed. Please refresh the page and try again.', TEXT_DOMAIN);
      $GLOBALS['partnership_result'] = $result;
      return;
    }

    // Sanitize fields
    $name     = !empty($_POST['partnership_name']) ? sanitize_text_field($_POST['partnership_name']) : '';
    $email    = !empty($_POST['partnership_email']) ? sanitize_email($_POST['partnership_email']) : '';
    $company  = !empty($_POST['partnership_company']) ? sanitize_text_field($_POST['partnership_company']) : '';
    $position = !empty($_POST['partnership_position']) ? sanitize_text_field($_POST['partnership_position']) : '';
    $phone    = !empty($_POST['partnership_phone']) ? sanitize_text_field($_POST['partnership_phone']) : '';
    $message  = !empty($_POST['partnership_message']) ? sanitize_textarea_field($_POST['partnership_message']) : '';
    $consent  = !empty($_POST['partnership_consent']);

    // Granular validation of required fields
    $field_errors = array();

    if (empty($name)) {
      $field_errors['partnership_name'] = __('Full Name is required.', TEXT_DOMAIN);
    }

    if (empty($email)) {
      $field_errors['partnership_email'] = __('Corporate Email is required.', TEXT_DOMAIN);
    } elseif (!is_email($email)) {
      $field_errors['partnership_email'] = __('Please provide a valid email address.', TEXT_DOMAIN);
    }

    if (empty($company)) {
      $field_errors['partnership_company'] = __('Company / Organization is required.', TEXT_DOMAIN);
    }

    if (empty($position)) {
      $field_errors['partnership_position'] = __('Job Title / Role is required.', TEXT_DOMAIN);
    }

    if (empty($consent)) {
      $field_errors['partnership_consent'] = __('You must authorize data processing to submit this form.', TEXT_DOMAIN);
    }

    if (!empty($field_errors)) {
      $result['success']      = false;
      $result['field_errors'] = $field_errors;
      $result['error']        = __('Please correct the highlighted fields and try again.', TEXT_DOMAIN);
      $GLOBALS['partnership_result'] = $result;
      return;
    }

    // Optional reCAPTCHA v3 check if keys defined
    $is_local = in_array(($_SERVER['SERVER_NAME'] ?? ''), array('localhost', '127.0.0.1'));
    if (!$is_local && defined('RECAPTCHA_SITE_KEY') && !empty(RECAPTCHA_SITE_KEY) && defined('RECAPTCHA_SECRET_KEY') && !empty(RECAPTCHA_SECRET_KEY)) {
      $token = isset($_POST['g-recaptcha-response']) ? sanitize_text_field(wp_unslash($_POST['g-recaptcha-response'])) : '';
      if (!empty($token)) {
        $verify = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
          'timeout' => 10,
          'body' => array(
            'secret'   => RECAPTCHA_SECRET_KEY,
            'response' => $token,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
          ),
        ));
        $verify_data = is_wp_error($verify) ? array() : json_decode(wp_remote_retrieve_body($verify), true);
        if (empty($verify_data['success']) || (isset($verify_data['score']) && $verify_data['score'] < 0.5)) {
          $result['success'] = false;
          $result['error'] = __('Security validation failed. Please confirm you are not a robot.', TEXT_DOMAIN);
          $GLOBALS['partnership_result'] = $result;
          return;
        }
      }
    }

    // Compose notification email
    $site_name = defined('SITE_NAME') ? SITE_NAME : 'ISHF 2027';
    $subject   = '[' . $site_name . '] ' . __('New Partnership Inquiry', TEXT_DOMAIN) . ' - ' . $company;

    $recipients = array();
    if (defined('SITE_CONTACT_EMAIL') && !empty(SITE_CONTACT_EMAIL)) {
      $contact_name = defined('SITE_CONTACT_NAME') ? SITE_CONTACT_NAME : 'Festival Secretariat';
      $recipients[] = $contact_name . ' <' . SITE_CONTACT_EMAIL . '>';
    }
    if (defined('SITE_CONTACT_EMAIL_2') && !empty(SITE_CONTACT_EMAIL_2)) {
      $contact_name_2 = defined('SITE_CONTACT_NAME_2') ? SITE_CONTACT_NAME_2 : '';
      $recipients[]   = $contact_name_2 . ' <' . SITE_CONTACT_EMAIL_2 . '>';
    }
    if (empty($recipients)) {
      $recipients[] = get_option('admin_email');
    }

    $email_body  = '<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #1e293b; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px;">';
    $email_body .= '<h2 style="color: #023256; margin-top: 0;">' . esc_html__('Partnership Inquiry', TEXT_DOMAIN) . '</h2>';
    $email_body .= '<p style="color: #64748b;">' . sprintf(esc_html__('A new partnership inquiry was submitted through %s.', TEXT_DOMAIN), esc_html($site_name)) . '</p>';
    $email_body .= '<hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;" />';
    $email_body .= '<p><strong>' . esc_html__('Name', TEXT_DOMAIN) . ':</strong> ' . esc_html($name) . '</p>';
    $email_body .= '<p><strong>' . esc_html__('Company / Organization', TEXT_DOMAIN) . ':</strong> ' . esc_html($company) . '</p>';
    $email_body .= '<p><strong>' . esc_html__('Job Title / Role', TEXT_DOMAIN) . ':</strong> ' . esc_html($position) . '</p>';
    $email_body .= '<p><strong>' . esc_html__('Email', TEXT_DOMAIN) . ':</strong> <a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a></p>';
    if (!empty($phone)) {
      $email_body .= '<p><strong>' . esc_html__('Phone Number', TEXT_DOMAIN) . ':</strong> ' . esc_html($phone) . '</p>';
    }
    if (!empty($message)) {
      $email_body .= '<p><strong>' . esc_html__('Message / Areas of Interest', TEXT_DOMAIN) . ':</strong><br />' . nl2br(esc_html($message)) . '</p>';
    }
    $email_body .= '<hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;" />';
    $email_body .= '<p style="font-size: 13px; color: #94a3b8;">' . esc_html__('Direct reply will be sent to the contact email address.', TEXT_DOMAIN) . '</p>';
    $email_body .= '</div>';

    $headers = array(
      'Content-Type: text/html; charset=UTF-8',
      'From: ' . $name . ' <' . $email . '>',
      'Reply-To: ' . $email,
    );

    @wp_mail($recipients, $subject, $email_body, $headers);

    // Post to external API webhook if PARTNERSHIP_PROJECT_TOKEN is configured
    $webhook_status  = 'skipped';
    $webhook_message = 'No project token configured';
    if (defined('PARTNERSHIP_PROJECT_TOKEN') && !empty(PARTNERSHIP_PROJECT_TOKEN)) {
      $full_name   = trim($name);
      $name_parts  = preg_split('/\s+/', $full_name);
      $first_name  = !empty($name_parts[0]) ? $name_parts[0] : '';
      $last_name   = count($name_parts) > 1 ? end($name_parts) : '';
      $middle_name = count($name_parts) > 2 ? implode(' ', array_slice($name_parts, 1, -1)) : '';
      $consent_str = !empty($consent) ? '1' : '0';

      $api_body = array(
        'email_address' => (string) $email,
        'title'         => '',
        'first_name'    => (string) $first_name,
        'middle_name'   => (string) $middle_name,
        'last_name'     => (string) $last_name,
        'full_name'     => (string) $full_name,
        'function'      => (string) $position,
        'company'       => (string) $company,
        'phone'         => (string) $phone,
        'consent'       => (string) $consent_str,
        'message'       => '',
        'project_token' => defined('PARTNERSHIP_PROJECT_TOKEN') ? (string) PARTNERSHIP_PROJECT_TOKEN : '',
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

      if (function_exists('ishf2027_webhook_outcome')) {
        list($webhook_status, $webhook_message) = ishf2027_webhook_outcome($response);
      }

      if (is_wp_error($response)) {
        $assunto = (defined('SITE_NAME') ? SITE_NAME : 'ISHF 2027') . ' (Erro API Parcerias)';
        $msg = 'Erro ao enviar lead de parceria para a API (wp_error): ' . $response->get_error_message();
        @wp_mail('boostwebdesign@medialivre.pt', $assunto, $msg);
      } else {
        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code < 200 || $response_code >= 300) {
          $raw_body = wp_remote_retrieve_body($response);
          $json_data = json_decode($raw_body, true);
          $api_msg = !empty($json_data['message']) ? $json_data['message'] : 'Sem mensagem da API';
          $assunto = (defined('SITE_NAME') ? SITE_NAME : 'ISHF 2027') . ' (Erro API Parcerias)';
          $msg = 'Erro ao enviar lead de parceria para a API (HTTP ' . $response_code . '): ' . $api_msg;
          @wp_mail('boostwebdesign@medialivre.pt', $assunto, $msg);
        }
      }
    }

    // Save lead to custom database table (wp_ishf_partnerships)
    if (function_exists('ishf2027_insert_partnership_lead')) {
      ishf2027_insert_partnership_lead(array(
        'nome'         => $name,
        'email'        => $email,
        'empresa'      => $company,
        'cargo'        => $position,
        'telefone'     => $phone,
        'mensagem'     => $message,
        'gdpr_consent' => $consent,
        'webhook_status'  => $webhook_status,
        'webhook_message' => $webhook_message,
      ));
    }

    $result['success'] = true;
    $result['fields']  = array(); // Clear on success

    $GLOBALS['partnership_result'] = $result;
    return;
  }

  // =========================================================================
  // 2. NEWSLETTER SUBSCRIPTION FORM HANDLER
  // =========================================================================
  if (isset($_POST['newsletter_landing_submitted'])) {
    $context = !empty($_POST['form_context']) ? sanitize_text_field($_POST['form_context']) : 'newsletter';
    $result = array(
      'success' => null,
      'error'   => '',
      'context' => $context,
      'fields'  => $_POST,
    );

    // Nonce validation
    if (!isset($_POST['newsletter_landing_nonce']) || !wp_verify_nonce($_POST['newsletter_landing_nonce'], 'newsletter_landing_action')) {
      $result['success'] = false;
      $result['error'] = __('Security validation failed. Please refresh the page and try again.', TEXT_DOMAIN);
      $GLOBALS['newsletter_landing_result'] = $result;
      return;
    }

    // Sanitize fields
    $name    = !empty($_POST['newsletter_name']) ? sanitize_text_field($_POST['newsletter_name']) : '';
    $email   = !empty($_POST['newsletter_email']) ? sanitize_email($_POST['newsletter_email']) : '';
    $consent = !empty($_POST['newsletter_consent']);

    // Granular validation of required fields
    $field_errors = array();

    if (empty($name)) {
      $field_errors['newsletter_name'] = __('Full Name is required.', TEXT_DOMAIN);
    }

    if (empty($email)) {
      $field_errors['newsletter_email'] = __('Email is required.', TEXT_DOMAIN);
    } elseif (!is_email($email)) {
      $field_errors['newsletter_email'] = __('Please provide a valid email address.', TEXT_DOMAIN);
    }

    if (empty($consent)) {
      $field_errors['newsletter_consent'] = __('You must authorize data processing to subscribe.', TEXT_DOMAIN);
    }

    if (!empty($field_errors)) {
      $result['success']      = false;
      $result['field_errors'] = $field_errors;
      $result['error']        = __('Please correct the highlighted fields and try again.', TEXT_DOMAIN);
      $GLOBALS['newsletter_landing_result'] = $result;
      return;
    }

    // Post to API if token is configured (like parent template)
    $api_success = false;
    $webhook_status  = 'skipped';
    $webhook_message = 'No project token configured';
    if (defined('NEWSLETTER_PROJECT_TOKEN') && !empty(NEWSLETTER_PROJECT_TOKEN)) {
      $full_name   = trim($name);
      $name_parts  = preg_split('/\s+/', $full_name);
      $first_name  = !empty($name_parts[0]) ? $name_parts[0] : '';
      $last_name   = count($name_parts) > 1 ? end($name_parts) : '';
      $middle_name = count($name_parts) > 2 ? implode(' ', array_slice($name_parts, 1, -1)) : '';
      $consent_str = !empty($consent) ? '1' : '0';

      $api_body = array(
        'email_address' => (string) $email,
        'title'         => '',
        'first_name'    => (string) $first_name,
        'middle_name'   => (string) $middle_name,
        'last_name'     => (string) $last_name,
        'full_name'     => (string) $full_name,
        'function'      => '',
        'company'       => '',
        'phone'         => '',
        'consent'       => (string) $consent_str,
        'project_token' => defined('NEWSLETTER_PROJECT_TOKEN') ? (string) NEWSLETTER_PROJECT_TOKEN : '',
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

      if (function_exists('ishf2027_webhook_outcome')) {
        list($webhook_status, $webhook_message) = ishf2027_webhook_outcome($response);
      }

      if (is_wp_error($response)) {
        $assunto = (defined('SITE_NAME') ? SITE_NAME : 'ISHF 2027') . ' (Erro Newsletter)';
        $msg = 'Erro ao subscrever a newsletter (wp_error): ' . $response->get_error_message();
        @wp_mail('boostwebdesign@medialivre.pt', $assunto, $msg);
      } else {
        $code = wp_remote_retrieve_response_code($response);
        if ($code >= 200 && $code < 300) {
          $api_success = true;
        } else {
          $raw_body = wp_remote_retrieve_body($response);
          $json_data = json_decode($raw_body, true);
          $api_msg = !empty($json_data['message']) ? $json_data['message'] : 'Sem mensagem da API';
          $assunto = (defined('SITE_NAME') ? SITE_NAME : 'ISHF 2027') . ' (Erro Newsletter)';
          $msg = 'Erro ao subscrever a newsletter (HTTP ' . $code . '): ' . $api_msg;
          @wp_mail('boostwebdesign@medialivre.pt', $assunto, $msg);
        }
      }
    }

    // Also send email notification to secretariat to guarantee record
    $site_name = defined('SITE_NAME') ? SITE_NAME : 'ISHF 2027';
    $subject   = '[' . $site_name . '] ' . __('New Newsletter Subscription', TEXT_DOMAIN);

    $recipients = array();
    if (defined('SITE_CONTACT_EMAIL') && !empty(SITE_CONTACT_EMAIL)) {
      $contact_name = defined('SITE_CONTACT_NAME') ? SITE_CONTACT_NAME : 'Festival Secretariat';
      $recipients[] = $contact_name . ' <' . SITE_CONTACT_EMAIL . '>';
    }
    if (empty($recipients)) {
      $recipients[] = get_option('admin_email');
    }

    $email_body  = '<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #1e293b; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px;">';
    $email_body .= '<h2 style="color: #023256; margin-top: 0;">' . esc_html__('New Newsletter Subscription', TEXT_DOMAIN) . '</h2>';
    $email_body .= '<p style="color: #64748b;">' . sprintf(esc_html__('A new visitor subscribed to the %s newsletter.', TEXT_DOMAIN), esc_html($site_name)) . '</p>';
    $email_body .= '<hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;" />';
    $email_body .= '<p><strong>' . esc_html__('Name', TEXT_DOMAIN) . ':</strong> ' . esc_html($name) . '</p>';
    $email_body .= '<p><strong>' . esc_html__('Email', TEXT_DOMAIN) . ':</strong> <a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a></p>';
    $email_body .= '<p><strong>' . esc_html__('Date', TEXT_DOMAIN) . ':</strong> ' . date_i18n('Y-m-d H:i:s') . '</p>';
    $email_body .= '<hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;" />';
    $email_body .= '</div>';

    $headers = array(
      'Content-Type: text/html; charset=UTF-8',
      'From: ' . $site_name . ' <' . (defined('SITE_CONTACT_EMAIL') ? SITE_CONTACT_EMAIL : get_option('admin_email')) . '>',
    );

    @wp_mail($recipients, $subject, $email_body, $headers);

    // Save subscriber to custom database table (wp_ishf_newsletter)
    if (function_exists('ishf2027_insert_newsletter_lead')) {
      ishf2027_insert_newsletter_lead(array(
        'nome'         => $name,
        'email'        => $email,
        'gdpr_consent' => $consent,
        'webhook_status'  => $webhook_status,
        'webhook_message' => $webhook_message,
      ));
    }

    $result['success'] = true;
    $result['fields']  = array(); // Clear on success
    $GLOBALS['newsletter_landing_result'] = $result;
    $GLOBALS['newsletter_result'] = $result;
    return;
  }
}
