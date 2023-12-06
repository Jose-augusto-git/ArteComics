<?php
/**
 * Astra Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra Child
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_ASTRA_CHILD_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_ASTRA_CHILD_VERSION, 'all' );

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

// Minha conta - Nome do usuário
function my_user_name() {
  $current_user = wp_get_current_user();
  return trim( 'Bem-vindo ' . $current_user->user_firstname . ' ' . $current_user->user_lastname );
}
add_shortcode( 'my-user-name', 'my_user_name' );

// URL Logout
function my_url_logout() {
  return wp_logout_url();
}
add_shortcode( 'my-url-logout', 'my_url_logout' );

/**
 * Construtor de Perfil de Usuário para WordPress
 * https://www.ramirolobo.com/construtor-de-perfil-de-usuario-para-wordpress/
 */
 
 class miro_upb_class {
	public function __construct() {
		
		// Filtra os erros encontrados quando um novo usuário está sendo registrado no WordPress
		add_filter( "registration_errors", array($this,"validate_registration_errors"), 10, 3 );
		
		// Grava os dados dos campos extras no banco de dados (WordPress).
		add_action( "user_register", array($this, "user_register_custom_fields") );
		add_action( "profile_update", array($this, "user_register_custom_fields") );
		
		// Dispara no final do novo formulário do usuário.
		add_action( "user_new_form", array($this,"admin_registration_form") );
		
		// Dispara após a tabela de configurações "Sobre você" na tela de edição de "Perfil".
		add_action( "show_user_profile", array($this,"show_extra_profile_fields") );
		
		// Dispara após a tabela de configurações "Sobre o usuário" na tela "Editar usuário".
		add_action( "edit_user_profile", array($this,"show_extra_profile_fields") );

		// Esta ação dispara após o campo "E-mail" no formulário de registro do usuário do WordPress.
		add_action( "register_form", array($this, "add_fields_register_form") );

		// Adiciona campos extras antes do botão no formulário de registro do WooCommerce
		add_action( "wmsc_step_content_billing", array($this, "wc_register_form") );
		
		// Salva os campos extras do formulário de registro de usuários do WooCommerce
		add_action( "woocommerce_created_customer", array($this, "user_register_custom_fields"), 12, 1 );
		
		// Adiciona campos extras na página "Minha conta".
		add_action( "woocommerce_edit_account_form", array($this, "wc_edit_account_form") );
		
		// Salva os campos extras na página "Minha conta".
		add_action( "woocommerce_save_account_details", array($this, "user_register_custom_fields"), 12, 1 );

		// Valida campos do registro de usuários
		add_action( "woocommerce_registration_errors", array($this, "wc_validate_extra_register_fields"), 10 );
		
		// Valida campos da página "Minha Conta".
		add_filter( "woocommerce_save_account_details_errors", array($this, "wc_validate_extra_register_fields"), 10 );

		// Inicialização
		add_action( "init", array($this, "initialization") );

	}
 
	
	public function initialization() {
		// Somente se não tiver cadastro
		if (!is_user_logged_in()) {
			// Adiciona campos na página de Checkout
			add_action( "woocommerce_after_order_notes", array($this, "wc_custom_checkout_field") );
			
			// Valida campos do Checkout
			add_action( "woocommerce_checkout_process", array($this, "wc_custom_checkout_field_process") );
			
			// Salva os campos extras do Ckeckout
			add_action( "woocommerce_checkout_update_user_meta", array($this, "user_register_custom_fields") );
		}

	}
		
	public function wc_custom_checkout_field_process() {
	
		if ( isset( $_POST["miro_upb_whatsapp"] ) && empty( $_POST["miro_upb_whatsapp"] ) ) {
			wc_add_notice( '<strong>ERRO</strong>: Preencimento obrigatório do campo "Seu número de WhatsApp".', "error" );
		}
	
	}

	public function wc_validate_extra_register_fields( $errors ) {
	
		if ( isset( $_POST["miro_upb_whatsapp"] ) && empty( $_POST["miro_upb_whatsapp"] ) ) {
			$errors->add( "miro_upb_whatsapp_error", '<strong>ERRO</strong>: Preencimento obrigatório do campo "Seu número de WhatsApp".', "woocommerce" );
		}

		return $errors;
	}

	public function wc_custom_checkout_field( $checkout ) {
		
		echo "<!-- <h3>Título da seção se desejar</h3> -->";

		woocommerce_form_field( "miro_upb_whatsapp", array(
			"type"          => "text",
			"class"         => array("my-field-class form-row-wide"),
			"label"         => "Seu número de WhatsApp",
			"placeholder"   => "Seu número de WhatsApp",
			"required" => 1
		), $checkout->get_value( "miro_upb_whatsapp" ));

		woocommerce_form_field( "miro_upb_aceite_email", array(
			"type"          => "checkbox",
			"class"         => array("my-field-class form-row-wide"),
			"label"         => "Desejo receber ofertas por e-mail",
			"required" => 0
		), $checkout->get_value( "miro_upb_aceite_email" ));

		woocommerce_form_field( "miro_upb_aceite_whatsapp", array(
			"type"          => "checkbox",
			"class"         => array("my-field-class form-row-wide"),
			"label"         => "Desejo receber ofertas por WhatsApp",
			"required" => 0
		), $checkout->get_value( "miro_upb_aceite_whatsapp" ));

	}

	public function wc_edit_account_form() {
		$user = wp_get_current_user();
		$miro_upb_whatsapp = isset( $_POST["miro_upb_whatsapp"] ) ? $_POST["miro_upb_whatsapp"] : $user->miro_upb_whatsapp;
		$miro_upb_aceite_email = isset( $_POST["miro_upb_aceite_email"] ) ? $_POST["miro_upb_aceite_email"] : $user->miro_upb_aceite_email;
		$miro_upb_aceite_whatsapp = isset( $_POST["miro_upb_aceite_whatsapp"] ) ? $_POST["miro_upb_aceite_whatsapp"] : $user->miro_upb_aceite_whatsapp;
?>

<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
<label for="miro_upb_whatsapp">Seu número de WhatsApp</label>
<input type="text"
       id="miro_upb_whatsapp"
       name="miro_upb_whatsapp"
       value="<?php echo esc_attr( $miro_upb_whatsapp ); ?>"
       class="woocommerce-Input woocommerce-Input--text input-text"
/>
</p>

<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
<label for="miro_upb_aceite_email">Aceite e-mail</label>
<input type="checkbox" 
	name="miro_upb_aceite_email"
	id="miro_upb_aceite_email"<?php checked( $miro_upb_aceite_email, "1" ); ?>
	class="woocommerce-Input woocommerce-Input--checkbox input-checkbox"
	value="1"> Desejo receber ofertas por e-mail
</p>

<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
<label for="miro_upb_aceite_whatsapp">Aceite WhatsApp</label>
<input type="checkbox" 
	name="miro_upb_aceite_whatsapp"
	id="miro_upb_aceite_whatsapp"<?php checked( $miro_upb_aceite_whatsapp, "1" ); ?>
	class="woocommerce-Input woocommerce-Input--checkbox input-checkbox"
	value="1"> Desejo receber ofertas por WhatsApp
</p>

<?php
	}

	public function wc_register_form() {
		$miro_upb_whatsapp = !empty( $_POST["miro_upb_whatsapp"] ) ? $_POST["miro_upb_whatsapp"] : "";
		$miro_upb_aceite_email = !empty( $_POST["miro_upb_aceite_email"] ) ? $_POST["miro_upb_aceite_email"] : "";
		$miro_upb_aceite_whatsapp = !empty( $_POST["miro_upb_aceite_whatsapp"] ) ? $_POST["miro_upb_aceite_whatsapp"] : "";
?>

<p class="form-row form-row-wide">
<label for="miro_upb_whatsapp">Seu número de WhatsApp</label>
<input type="text"
       id="miro_upb_whatsapp"
       name="miro_upb_whatsapp"
       value="<?php echo esc_attr( $miro_upb_whatsapp ); ?>"
       class="input-text"
/>
</p>

<p class="form-row form-row-wide">
<label for="miro_upb_aceite_email">Aceite e-mail</label>
<input type="checkbox" 
	name="miro_upb_aceite_email"
	id="miro_upb_aceite_email"<?php checked( $miro_upb_aceite_email, "1" ); ?>
	class="input-checkbox"
	value="1"> Desejo receber ofertas por e-mail
</p>

<p class="form-row form-row-wide">
<label for="miro_upb_aceite_whatsapp">Aceite WhatsApp</label>
<input type="checkbox" 
	name="miro_upb_aceite_whatsapp"
	id="miro_upb_aceite_whatsapp"<?php checked( $miro_upb_aceite_whatsapp, "1" ); ?>
	class="input-checkbox"
	value="1"> Desejo receber ofertas por WhatsApp
</p>

<div class="clear"></div>
<?php
	}

	function add_fields_register_form() {
		$miro_upb_whatsapp = !empty( $_POST["miro_upb_whatsapp"] ) ? $_POST["miro_upb_whatsapp"] : "";
		$miro_upb_aceite_email = !empty( $_POST["miro_upb_aceite_email"] ) ? $_POST["miro_upb_aceite_email"] : "";
		$miro_upb_aceite_whatsapp = !empty( $_POST["miro_upb_aceite_whatsapp"] ) ? $_POST["miro_upb_aceite_whatsapp"] : "";
?>

<p>
<label for="miro_upb_whatsapp">Seu número de WhatsApp<br/>
<input type="text"
       id="miro_upb_whatsapp"
       name="miro_upb_whatsapp"
       value="<?php echo esc_attr( $miro_upb_whatsapp ); ?>"
       class="input"
/>
</label>
</p>

<p>
<label for="miro_upb_aceite_email">Aceite e-mail<br/>
<input type="checkbox" 
	name="miro_upb_aceite_email"
	id="miro_upb_aceite_email" value="1"> Desejo receber ofertas por e-mail
</label>
</p>

<p>
<label for="miro_upb_aceite_whatsapp">Aceite WhatsApp<br/>
<input type="checkbox" 
	name="miro_upb_aceite_whatsapp"
	id="miro_upb_aceite_whatsapp" value="1"> Desejo receber ofertas por WhatsApp
</label>
</p>

<?php
	}

	function validate_registration_errors( $errors, $sanitized_user_login, $user_email ) {

			if ( isset( $_POST["miro_upb_whatsapp"] ) && empty( $_POST["miro_upb_whatsapp"] ) ) {
				$errors->add( "miro_upb_whatsapp_error", '<strong>ERRO</strong>: Preencimento obrigatório do campo "Seu número de WhatsApp".' );
			}

		return $errors;
	}

	function user_register_custom_fields( $user_id ) {
		$miro_upb_whatsapp = isset( $_POST["miro_upb_whatsapp"] ) ? sanitize_text_field($_POST["miro_upb_whatsapp"]) : "";
		$miro_upb_aceite_email = isset( $_POST["miro_upb_aceite_email"] ) ? sanitize_text_field($_POST["miro_upb_aceite_email"]) : "0";
		$miro_upb_aceite_whatsapp = isset( $_POST["miro_upb_aceite_whatsapp"] ) ? sanitize_text_field($_POST["miro_upb_aceite_whatsapp"]) : "0";

		update_user_meta( $user_id, "miro_upb_whatsapp", $miro_upb_whatsapp );

		update_user_meta( $user_id, "miro_upb_aceite_email", $miro_upb_aceite_email );

		update_user_meta( $user_id, "miro_upb_aceite_whatsapp", $miro_upb_aceite_whatsapp );

	}

	/**
	* $operation também pode ser "add-existing-user"
	*
	* Você pode desejar executar alguma ação neste ponto,
	* como por exemplo cadastrar o usuário no seu sistema de 
	* automação ou e-mail marketing.
	*/
	function admin_registration_form( $operation ) {
		if ( "add-new-user" !== $operation ) {
			return;
		}
?>
<!-- <h3>Título da seção se desejar</h3> -->
<?
		$miro_upb_whatsapp = !empty( $_POST["miro_upb_whatsapp"] ) ? $_POST["miro_upb_whatsapp"] : "";
?>

<table class="form-table">
<tr>
<th><label for="miro_upb_whatsapp">Seu número de WhatsApp</label> <span class="description">*</span></th>
<td>
<input type="text"
       id="miro_upb_whatsapp"
       name="miro_upb_whatsapp"
       value="<?php echo esc_attr( $miro_upb_whatsapp ); ?>"
       class="regular-text"
/>
</td>
</tr>
</table>
<?php
		$miro_upb_aceite_email = !empty( $_POST["miro_upb_aceite_email"] ) ? $_POST["miro_upb_aceite_email"] : "";
?>

<table class="form-table">
<tr>
<th><label for="miro_upb_aceite_email">Aceite e-mail</label> <span class="description">*</span></th>
<td>
<input type="checkbox" 
	name="miro_upb_aceite_email"
	id="miro_upb_aceite_email" value="1"> Desejo receber ofertas por e-mail
</td>
</tr>
</table>
<?php
		$miro_upb_aceite_whatsapp = !empty( $_POST["miro_upb_aceite_whatsapp"] ) ? $_POST["miro_upb_aceite_whatsapp"] : "";
?>

<table class="form-table">
<tr>
<th><label for="miro_upb_aceite_whatsapp">Aceite WhatsApp</label> <span class="description">*</span></th>
<td>
<input type="checkbox" 
	name="miro_upb_aceite_whatsapp"
	id="miro_upb_aceite_whatsapp" value="1"> Desejo receber ofertas por WhatsApp
</td>
</tr>
</table>

<?php
	}

	function show_extra_profile_fields( $user ) {
?>
<!-- <h3>Título da seção se desejar</h3> -->

<table class="form-table">
	<tr>
		<th><label for="miro_upb_whatsapp">Seu número de WhatsApp</label></th>
		<td>
<input type="text"
       id="miro_upb_whatsapp"
       name="miro_upb_whatsapp"
       value="<?php echo esc_html( get_the_author_meta( "miro_upb_whatsapp", $user->ID ) ); ?>"
       class="regular-text"
/>
		</td>
	</tr>
</table>

<table class="form-table">
	<tr>
		<th><label for="miro_upb_aceite_email">Aceite e-mail</label></th>
		<td>
<input type="checkbox" 
	name="miro_upb_aceite_email"
	id="miro_upb_aceite_email"<?php checked( get_the_author_meta( "miro_upb_aceite_email", $user->ID ), "1" ); ?>
	value="1"> Desejo receber ofertas por e-mail
		</td>
	</tr>
</table>

<table class="form-table">
	<tr>
		<th><label for="miro_upb_aceite_whatsapp">Aceite WhatsApp</label></th>
		<td>
<input type="checkbox" 
	name="miro_upb_aceite_whatsapp"
	id="miro_upb_aceite_whatsapp"<?php checked( get_the_author_meta( "miro_upb_aceite_whatsapp", $user->ID ), "1" ); ?>
	value="1"> Desejo receber ofertas por WhatsApp
		</td>
	</tr>
</table>

<?php
	}

}
$miro_upb_obj = new miro_upb_class();

/* Dados para importação - Copie sem as tags de comentários
{"upb":"1","type_of_output":"function","projectprefix":"miro_upb","plugin_wordpress":"1","plugin_woocommerce":"1","plugin_woocommerce_mscfw":"1","plugin_woocommerce_registration":"1","field-type":["text","checkbox","checkbox"],"field-name":["whatsapp","aceite_email","aceite_whatsapp"],"field-required":["1"],"field-label":["Seu n\u00famero de WhatsApp","Aceite e-mail","Aceite WhatsApp"],"field-descriptions":["","Desejo receber ofertas por e-mail","Desejo receber ofertas por WhatsApp"],"field-options":["","",""],"LjBExJNMKTRPhtXm":"mSW8bT0yu","uhcyJoaSwXWHnv":"xDeq0V","dALQ-Sg":"CUgHXGW52QPz4b","kVUxciEwyRFQS":"P6*qdtU"}
*/
