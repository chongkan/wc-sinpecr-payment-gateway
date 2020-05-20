<?php
/*
	Plugin Name: WC SINPECR Payment Gateway
	Description: Despliega varios botones para pagar vía SMS: BACSJ, BNCR, BCR, DAVIVIENDA 
	Version: 2.2.0
	Author: Eduardo Chongkan
	Author URI: http://chongkan.com/
	License:           MIT
 	License URI:       https://opensource.org/licenses/MIT
 	GitHub Plugin URI: Pending
*/

if ( ! defined( 'ABSPATH' ) )
	exit;

add_action( 'plugins_loaded', 'wc_sinpecr_init', 0 );

function wc_sinpecr_init() {

	if ( ! class_exists( 'WC_Payment_Gateway' ) ) return;

	/**
	 * SINPE SMS Payment Gateway.
	 *
	 * Facilitates a SINPE Bank Transfer Payment Gateway. Based on code by Mike Pepper.
	 *
	 * @class       WC_SINPECR_Gateway
	 * @extends     WC_Payment_Gateway
	 * @version     1.0.0
	 * @package     WooCommerce/Classes/Payment
	 */
	class WC_SINPECR_Gateway extends WC_Payment_Gateway {

		/**
		 * Array of locales
		 *
		 * @var array
		 */
		public $locale;

		/**
		 * Constructor for the gateway.
		 */
		public function __construct() {

			$this->id                 = 'wc_sinpecr_gateway';
			$this->icon               = apply_filters( 'wc_sinpecr_gateway', '/wp-content/plugins/wc-sinpecr-payment-gateway/assets/images/sinpe-sms.png' );
			$this->has_fields         = true;
			$this->method_title       = __( 'SINPE SMS', 'wc_sinpecr_gateway' );
			$this->method_description = __( 'Pagos fáciles a Vendedores vía SINPE SMS. Con un Click.', 'wc_sinpecr_gateway' );

			// Load the settings.
			$this->init_form_fields();
			$this->init_settings();

			// Define user set variables.
			$this->title        = $this->get_option( 'title' );
			$this->description  = $this->get_option( 'description' );
			$this->instructions = $this->get_option( 'instructions' );

			$this->bancos = array(
				'bcr' => [
					'numero_sms' => '2276', 
					'nombre_largo' => 	'Banco de Costa Rica', 
					'nombre_corto' => 'BCR', 
					'icono' => '/wp-content/plugins/wc-sinpecr-payment-gateway/assets/images/banco-bcr-icon.png'
				], 
				'bncr' => [
					'numero_sms' => '2627', 
					'nombre_largo' => 	'Banco Nacional de Costa Rica', 
					'nombre_corto' => 'BNCR', 
					'icono' => '/wp-content/plugins/wc-sinpecr-payment-gateway/assets/images/banco-bcr-icon.png'
				], 
				'bacsj' => [
					'numero_sms' => '2627', 
					'nombre_largo' => 	'BAC San José', 
					'nombre_corto' => 'BNCR', 
					'icono' => '/wp-content/plugins/wc-sinpecr-payment-gateway/assets/images/banco-bac-icon.png'
				], 
				'davivienda' => [
					'numero_sms' => '70707474', 
					'nombre_largo' => 	'Davivienda', 
					'nombre_corto' => 'Davivienda', 
					'icono' => '/wp-content/plugins/wc-sinpecr-payment-gateway/assets/images/banco-davivienda-icon.png'
				]
			);
			
			$this->secret = '7WAO342QFANY6IKBF7L7SWEUU79WL3VMT920VB5NQMW';

			// SINPECR account fields shown on the thanks page and in emails.
			$this->sinpe_details = get_option(
				'wc_sinpecr_details',
				array(
					array(
						'sinpe_mobile'   => $this->get_option( 'sinpe_mobile' ),
						'sinpe_name'      => $this->get_option( 'sinpe_mobile' ), 
						'clave'      => $this->get_option( 'clave' )
					),
				)
			);

			// Actions.
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'save_account_details' ) );	
			add_action( 'woocommerce_thankyou_' . $this->id,  array( $this, 'thankyou_page' ));


			// Customer Emails.
			add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
		
		}
		/**
		 * Initialise Gateway Settings Form Fields.
		 */
		public function init_form_fields() {

			$this->form_fields = array(
				'enabled'         => array(
					'title'   => __( 'Enable/Disable', 'wc_sinpecr_gateway' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable Pago Fácil SINPE Móvil', 'wc_sinpecr_gateway' ),
					'default' => 'no',
				),
				'title'           => array(
					'title'       => __( 'Title', 'wc_sinpecr_gateway' ),
					'type'        => 'text',
					'description' => __( 'This controls the title which the user sees during checkout.', 'wc_sinpecr_gateway' ),
					'default'     => __( 'Pago Fácil SINPE Móvil', 'wc_sinpecr_gateway' ),
					'desc_tip'    => true,
				),
				'description'     => array(
					'title'       => __( 'Description', 'wc_sinpecr_gateway' ),
					'type'        => 'textarea',
					'description' => __( 'What users see in the checkout ', 'wc_sinpecr_gateway' ),
					'default'     => __( 'Pague directamente a cada comercio, con un click!', 'wc_sinpecr_gateway' ),
					'desc_tip'    => true,
				),
				'instructions'    => array(
					'title'       => __( 'Instructions', 'wc_sinpecr_gateway' ),
					'type'        => 'textarea',
					'description' => 'Instrucciones para el Cliente',
					'default'     => __( 'Haga click en el botón de <strong>SU banco</strong> para pagarle a comercio mediante SINPE Móvil. ( Mensaje SMS ) <br> 
					Al hacer click, se abrirá automáticamente una plantilla para enviar un SMS al No. SINPE del banco de SU elección.', 'woocommerce' ),
					'desc_tip'    => true,
				),
				'sinpe_mobile'    => array(
					'title'       => __( 'Tel. Receptor SINPE', 'wc_sinpecr_gateway' ),
					'type'        => 'text',
					'description' => 'Número de Teléfono asociado a SINPE Móvil. No importa de que banco. Sin espacios. Ejemplo: 12345678',
					'default'     => __( 'Sin Configurar', 'wc_sinpecr_gateway' ),
					'desc_tip'    => true,
				),
				'sinpe_name'    => array(
					'title'       => __( 'Nombre de Receptor SINPE', 'wc_sinpecr_gateway' ),
					'type'        => 'text',
					'description' => 'Nombre asociado a la cuenta SINPE.',
					'default'     => __( 'Sin Configurar', 'wc_sinpecr_gateway' ),
					'desc_tip'    => true,
				),
				'clave'    => array(
					'title'       => __( 'Clave', 'wc_sinpecr_gateway' ),
					'type'        => 'text',
					'description' => 'Clave para confirmación de ordenes',
					'default'     => __( '2552', 'wc_sinpecr_gateway' ),
					'desc_tip'    => true,
				),
				
			);

		}

		/**
		 * Save account details table.
		 */
		public function save_account_details() {

			$accounts = array();

			// phpcs:disable WordPress.Security.NonceVerification.Missing -- Nonce verification already handled in WC_Admin_Settings::save()
			if ( isset( $_POST['sinpe_mobile'] ) && isset( $_POST['sinpe_name'] ) ) {

				$sinpe_details = array(
					'sinpe_mobile'   => wc_clean( wp_unslash( $_POST['sinpe_mobile'] ) ),
					'sinpe_name' => wc_clean( wp_unslash( $_POST['sinpe_name'] ) )
				);
			}
			// phpcs:enable

			update_option( 'wc_sinpecr_details', $accounts );
		}

		/**
		 * Output for the order received page.
		 *
		 * @param int $order_id Order ID.
		 */
		public function thankyou_page( $order_id ) {

			if ( $this->instructions ) {
				echo wp_kses_post( wpautop( wptexturize( wp_kses_post( $this->instructions ) ) ) );
			}
			$this->receipt_sinpe_details( $order_id );

		}

		/**
		 * Add content to the WC emails.
		 *
		 * @param WC_Order $order Order object.
		 * @param bool     $sent_to_admin Sent to admin.
		 * @param bool     $plain_text Email format: plain text or HTML.
		 */
		public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {

			if ( ! $sent_to_admin && 'wc_sinpecr_gateway' === $order->get_payment_method() && $order->has_status( 'on-hold' ) ) {
				// Not necesARY FOR EAIL
				// if ( $this->instructions ) {
				// 	echo wp_kses_post( wpautop( wptexturize( $this->instructions ) ) . PHP_EOL );
				// }
				$this->receipt_sinpe_details( $order->get_id() );
			}

		}

		/**
		 * Get SINPE details and place into a list format.
		 *
		 * @param int $order_id Order ID.
		 */
		private function receipt_sinpe_details( $order_id = '' ) {

			// Get order and store in $order.
			$order = wc_get_order( $order_id );
			$no_celular_sinpe = $this->get_option( 'sinpe_mobile' );
			$total = (float) $order->get_total();

						
			echo '<h2>Procede con el Pago Vía Sinpe Móvil (SMS):</h2>';
			echo '<hr><p>';
			echo 'Titular de cuenta_______:<strong> ' .  $this->get_option( 'sinpe_name' )  . '</strong> <br>';
			echo 'Teléfono a Transferir___: <strong>'. $no_celular_sinpe .'</strong> <br>';
			echo 'Monto___________________: <strong>'. wc_price($order->get_total()) .'</strong> <br>';
			echo '<h4>Haga click en el banco de su elección para pagar con SMS</h4>';
			echo '<div class="sinpe-sms-buttons">';

			foreach ($this->bancos as $key => $banco) {
				echo '<a class="sinpe-button button sinpe-'.$key.'" href="sms://'. $banco['numero_sms'] .'?&body=PASE%20'. $total .'%20'.  $no_celular_sinpe .'">'.$banco['nombre_largo'].'</a>';
			}
			$key = implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6));
			echo $key;
			echo '</div><hr>';
		}

		


		/**
		 * Process the payment and return the result.
		 * Sets the order on-hold until the vendor confirms the payment by clicking on the confirmation button sent in an email. 
		 *
		 * @param int $order_id Order ID.
		 * @return array
		 */
		public function process_payment( $order_id ) {

			$order = wc_get_order( $order_id );

			if ( $order->get_total() > 0 ) {
				// Mark as on-hold (we're awaiting the payment).
				$order->update_status( apply_filters( 'woocommerce_bacs_process_payment_order_status', 'on-hold', $order ), __( 'Esperando Transferencia SINPE', 'wc_sinpecr_gateway' ) );
			} else {
				// Free orders
				$order->payment_complete();
			}

			// Remove cart.
			WC()->cart->empty_cart();

			// Return thankyou redirect.
			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order ),
			);

		}
		


	} // End of Class


	/**
 	* Add SINPECR Gateway to WC
 	**/
	function wc_sinpecr_add_gateway( $methods ) {

		$methods[] = 'WC_SINPECR_Gateway';
		return $methods;

	}
	add_filter('woocommerce_payment_gateways', 'wc_sinpecr_add_gateway' );


	/**
	* Add Settings link to the plugin entry in the plugins menu
	**/
	function wc_sinpecr_plugin_action_links( $links, $file ) {

	    static $this_plugin;

	    if ( ! $this_plugin ) {

	        $this_plugin = plugin_basename( __FILE__ );

	    }

	    if ( $file == $this_plugin ) {

	        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=wc-settings&tab=checkout&section=wc_sinpecr_add_gateway">Configuración</a>';
	        array_unshift($links, $settings_link);

	    }

	    return $links;

	}
	add_filter( 'plugin_action_links', 'wc_sinpecr_plugin_action_links', 10, 2 );

	
	/**
	 * Shortcode: wc_sinpecr_confirma_pago 
	 * Used by the Vendor to confirm the order has been paid via SINPE. 
	 * TODO 
	 */
	
	function wc_sinpecr_confirma_pago_html(){
		
		$order_number = $_GET['orden'];
		$token = 		$_GET['token'];

		echo "<p>No. de Orden:" . $order_number . '</p>';
		echo "<p>No. de Orden:" . $_GET['token'] . '</p>';
	}

	add_shortcode('wc_sinpecr_confirma_pago', 'wc_sinpecr_confirma_pago_html');


}

