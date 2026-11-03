<?php
// -----------------------------------------------------------------------------
//  Section: FORM for CONTACT
//  Edited: BB, 27-OUT-2026
// -----------------------------------------------------------------------------

$result = null;
$message_submitted = false;


// -- Envio de e-mail
$gRecaptchaResponse = (!empty($_POST['g-recaptcha-response'])) ? $_POST['g-recaptcha-response'] : null;
$nomeContacto = (!empty($_POST['nomeContacto'])) ? $_POST['nomeContacto'] : null;
$email = (!empty($_POST['email'])) ? $_POST['email'] : null;
$mensagem = (!empty($_POST['mensagem'])) ? $_POST['mensagem'] : null;

// -- Check if fields were filled
if (!empty($gRecaptchaResponse) && !empty($nomeContacto) && !empty($email) && !empty($mensagem)):
  $message_submitted = true;
  $assunto = get_bloginfo('name').' (Contacto via Site)';

  // -- Ambiente PROD
  $para = array();
  array_push($para, SITE_CONTACT_NAME.' <'.SITE_CONTACT_EMAIL.'>');
  if ((!empty(SITE_CONTACT_NAME_2)) && (!empty(SITE_CONTACT_EMAIL_2))):
    array_push($para, SITE_CONTACT_NAME_2.' <'.SITE_CONTACT_EMAIL_2.'>');
  endif;

  $msg  = '<div style="font-family: Arial, Helvetica, sans-serif;">';
  $msg .= '<h1>Pedido de Contacto</h1>';
  $msg .= '<h3>Um contacto foi solicitado através do formulário no site.</h3><hr/>';
  $msg .= '<p><strong>Nome do Contacto</strong>: '.$nomeContacto.'</p>';
  $msg .= '<p><strong>E-mail</strong>: '.$email.'</p>';
  $msg .= '<p><strong>'.__('Message',TEXT_DOMAIN).'</strong>:<br/>';
  $msg .= $mensagem.'</p><hr/>';
  $msg .= '<p style="font-size: 14px;">Para responder, pode optar por fazer <strong>Reply</strong> diretamente a esta mensagem, ou em alternativa, escrever um e-mail para <strong>'.$email.'</strong>.</p>';
  $msg .= '</div>';

  $headers = array('Content-Type: text/html; charset=UTF-8','From: '.$nomeContacto.' <'.$email.'>');
  $result = wp_mail($para, $assunto, $msg, $headers);
endif;
?>

<?php
// -- Show FORM only if RECAPTCHA is defined
if ((!empty(RECAPTCHA_SITE_KEY)) || (!empty(RECAPTCHA_SECRET_KEY))):
?>
<section id="contacte-nos" class="uk-section uk-light">
  <div class="uk-container uk-container-xsmall">
    <h3><?php _e('Contact Us', TEXT_DOMAIN); ?></h3>
    <?php
    if ($message_submitted):
      if ($result):
        echo '<div class="uk-alert-success" data-uk-alert><a class="uk-alert-close" data-uk-close></a><p>'.__('<strong>Message sent</strong><br/> We will return your message as soon as possible.',TEXT_DOMAIN).'</p></div>';
      else:
        echo '<div class="uk-alert-warning" data-uk-alert><a class="uk-alert-close" data-uk-close></a><p>'.__('<strong>Message not sent</strong><br/> Please try again later.',TEXT_DOMAIN).'</p></div>';
      endif;
    endif;
    ?>
    <form class="uk-margin-medium-top" id="form-contacto" name="form-contacto" method="post" action="#contacte-nos">
      <div data-uk-grid class="uk-grid-small">
        <div class="uk-width-1-1 uk-width-1-2@s">
          <div class="uk-margin">
            <input class="uk-input" type="text" placeholder="<?php _e('Name',TEXT_DOMAIN); ?>" value="<?php if (isset($nomeContacto)) echo $nomeContacto; ?>" name="nomeContacto" id="nomeContacto" />
          </div>
        </div>
        <div class="uk-width-1-1 uk-width-1-2@s">
          <div class="uk-margin">
            <input class="uk-input" type="text" placeholder="<?php esc_attr_e('Email', TEXT_DOMAIN); ?>" value="<?php if (isset($email)) echo $email; ?>" name="email" id="email" />
          </div>
        </div>
        <div class="uk-width-1-1">
          <textarea class="uk-textarea" rows="8" placeholder="<?php _e('Leave us your message',TEXT_DOMAIN); ?>" name="mensagem" id="mensagem"><?php if (isset($mensagem)) echo $mensagem; ?></textarea>
        </div>
        <div class="uk-width-1-1">
          <button type="submit" id="btn-form-contacto" class="g-recaptcha<?php if (($message_submitted) && ($result)): ?> disabled<?php endif; ?> uk-button uk-button-large uk-button-primary uk-border-pill" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>" data-callback="onSubmit" data-action="submit" value="<?php _e('Request Information',TEXT_DOMAIN); ?>"><?php _e('Send Message',TEXT_DOMAIN); ?></button>
        </div>
      </div>
    </form>
  </div>
</section>
<?php
// -- Enqueue ReCaptcha JS
wp_enqueue_script('recaptcha');
?>
<script>
function onSubmit(token) {
  if (!$('#btn-form-contacto').hasClass('disabled')){
    if (($('#nomeContacto').val() != "") && ($('#email').val() != "") && ($('#mensagem').val() != "")) {
      $('#form-contacto').submit();
    }
  }
}

if (window.history.replaceState) {
  window.history.replaceState(null, null, window.location.href);
}
</script>
<?php
endif;
?>
