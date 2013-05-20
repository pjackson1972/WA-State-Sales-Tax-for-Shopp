<?php
/*
	Plugin name: Washington State Sales Tax for Shopp
	Plugin URI: http://www.ivycat.com/wordpress/wordpress-plugins/washington-state-sales-tax-for-shopp/
	Author: IvyCat Web Services
	Author URI: http://www.ivycat.com
	Description: Connect Shopp with Washington State's Department of Revenue API for on-the-fly sales tax rate lookup and calculation.
	Version: 1.0.4
	License: GNU General Public License v2.0
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
 
 ------------------------------------------------------------------------
 
	IvyCat Washington State Sales Tax for Shopp, Copyright 2013 IvyCat, Inc. (admins@ivycat.com)
	
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/

new ShoppWATaxCalc();

class ShoppWATaxCalc{
	
	protected $dor_url;
	protected $enabled;
	
	public function __construct(){
		add_action('admin_menu', array(&$this, 'options_page_init') );
		add_action( 'register_activaton_hook', array(&$this, 'upon_install') );
		$this->dor_url = 'http://dor.wa.gov';
		$this->enabled = get_option( 'shopp_wa_destination_tax_enabled' );
		if( $this->enabled === 'enable' ):
			add_action( 'shopp_cart_retotal', array( &$this , 'set_taxes' ) );
		endif;
	}
	
	public function upon_install(){
		add_option( 'shopp_wa_destination_tax_enabled', 'disable' );
	}
	
	public function set_taxes( ){
		global $Shopp;
		$Order =& ShoppOrder();
		$state = strtolower( $Shopp->Shopping->data->Order->Shipping->state );
		if( $state != 'wa'  ) return false;
		
		$address = urlencode( $Shopp->Shopping->data->Order->Shipping->address . ' ' . $Shopp->Shopping->data->Order->Shipping->saddress );
		$city = urlencode( $Shopp->Shopping->data->Order->Shipping->city );
		$zip = $Shopp->Shopping->data->Order->Shipping->postcode;
		$tax= self::getTax( $address , $city, $zip );
		$taxrate = (string) $tax->attributes()->rate;
		$location_code = (string) $tax->attributes()->loccode;
		
		if( isset( $Shopp->Order->data ) ) {
			$Shopp->Order->data['Location Code'] = $location_code;
		}

		$Shopp->Order->Cart->Totals->taxrate = $taxrate;
		$subtotal = $Shopp->Order->Cart->Totals->subtotal;
		$shipping = $Shopp->Order->Cart->Totals->shipping;
		$discount = is_numeric( $Shopp->Order->Cart->Totals->discount ) ? $Shopp->Order->Cart->Totals->discount : 0;
		
		// calculate taxes
		if( shopp_setting( 'taxes' ) === on ) {
			// include shipping in tax calc...
			if( shopp_setting( 'tax_shipping' ) === on ) {
				$Shopp->Order->Cart->Totals->tax = ( $subtotal + $shipping ) * $taxrate;
			} else {
				$Shopp->Order->Cart->Totals->tax = $subtotal * $taxrate;
			}
			$Shopp->Order->Cart->Totals->total = $Shopp->Order->Cart->Totals->tax + $subtotal + $shipping - $discount;
		} else { // no taxes
			$Shopp->Order->Cart->Totals->total = $subtotal + $shipping - $discount;
		}
	  
	}
	
	public function options_page_init(){
		 if( !current_user_can( 'administrator' ) ) return;
			$hooks = array();
			$hooks[] = add_options_page(__('WA Taxes for Shopp'), __('WA Taxes for Shopp'), 'read', 'destination-taxes', array($this, 'option_page'));

			foreach($hooks as $hook) {
				add_action("admin_print_styles-{$hook}", array($this, 'load_assets'));
			}
	}
	
	public function load_assets(){
		
	}
	
	public function set_status(){
		if( $_POST['wadbt_status'] === 'enable' ){
			update_option( 'shopp_wa_destination_tax_enabled', 'enable' );
		}else{
			update_option( 'shopp_wa_destination_tax_enabled', 'disable' );
		}
	}
	
	public function option_page(){
		if( $_SERVER['REQUEST_METHOD'] == 'POST' ) self::set_status();
		require_once 'assets/views/option-page-view.php';
	}
	
	protected function getTax( $addr, $city, $zip ){
		$req = $this->dor_url . "/AddressRates.aspx?output=xml&addr=$addr&city=$city&zip=$zip";
		return self::get_decoded_url( $req );
	}
	
	protected function get_decoded_url( $url ){
		$result = wp_remote_get( $url );
		if( !is_wp_error( $result ) ){
			$xml = new SimpleXMLElement( $result['body'] );
			
			switch($xml->attributes()->code){
				case 0:
					// Code 0 means address was perfect
					break;
				case 1:
					$xml->msg = "Warning: The address was not found, but the ZIP+4 was located.";
					break;
				case 2:
					$xml->msg = "Warning: Neither the address or ZIP+4 was found, but  the 5-digit ZIP was located.";
					break;
				case 3:
					$xml->msg = "Error: The address, ZIP+4, and ZIP could not be found.";
					break;
				case 4:
					$xml->msg = "Error: Invalid arguements.";
					break;
				case 5:
					$xml->msg = "Error: Internal error.";
			}
		}
		else $xml = "Error: Could not load XML.";

		return  $xml;
	}
}
