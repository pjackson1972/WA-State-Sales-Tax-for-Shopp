<?php
/**
 * Washington State Sales Tax for Shopp Plugin
 * 
 * Connect Shopp with Washington State's Department of Revenue API
 * for on-the-fly sales tax rate lookup and calculation.
 * 
 * @package shopp-wa-tax-calc
 * @author Daniel Gilfoy
 * @author Eric Amundson <eric@ivycat.com>
 * @author Jordan Beaver
 * @author Patrick Jackson <patrick@ivycat.com>
 * @version 2.0.0
 * 
 * 
 * 
 * Plugin name: Washington State Sales Tax for Shopp
 * Plugin URI: http://www.ivycat.com/wordpress/wordpress-plugins/washington-state-sales-tax-for-shopp/
 * Author: IvyCat
 * Author URI: http://www.ivycat.com/wordpress/
 * Description: Connect Shopp with Washington State's Department of Revenue API for on-the-fly sales tax rate lookup and calculation.
 * Version: 2.0.0
 * License: GNU General Public License v2.0
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * 
 * ---------------------------------------------------------------------------
 * 
 * IvyCat Washington State Sales Tax for Shopp, Copyright 2013 IvyCat, Inc. (admins@ivycat.com)
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 * 
 * ------------------------------------------------------------------------
 * 
 * 
 */

new Shopp_WA_Tax_Calc();

/**
 * Shopp Washington Tax Calculator class
 * 
 * This class contains all functionality for this plugin.
 * 
 * @package shopp-wa-tax-calc
 * @author Daniel Gilfoy
 * @version 2.0.0
 * @since 1.0.0
 * @access   public
 * 
 */
class Shopp_WA_Tax_Calc {

    protected $dor_url; // URL for the DOR = (Washington State) Department of Revenue
    protected $enabled;

    /**
     * Constructor
     * 
     * Initialize this class
     * 
     * @author Daniel Gilfoy
     * @version 1.0.1
     * @since 1.0.0
     * 
     */
    public function __construct() {
        
        // initialize class variables
        $this->dor_url = 'http://dor.wa.gov';
        $this->enabled = get_option( 'shopp_wa_destination_tax_enabled' );
        
        // add the tax calculation callbacks - these are the main methods for this plugin
        $this->add_tax_calculation_callbacks();
        
        // add the options page callbacks for this plugin - adds the options page
        // to the Shopp menu
        $this->add_plugin_options_page_callbacks();
        
        // add callbacks that add functionality during key plugin lifecycle 
        // events such as activation and updates
        $this->add_wordpress_events_callbacks();

    }
    
    /**
     * Add a submenu item to the shopp menu
     * 
     * @author Patrick Jackson <patrick@ivycat.com>
     * @version 1.0.0
     * @since 2.0.0
     */
    function add_shopp_menu_item(){
        shopp_admin_add_submenu ( __( 'WA State Tax' ), 
                'washington-taxes',
                'shopp-orders',
                array( &$this, 'option_page' ),
                'administrator');
    }

    /**
     * On Activation, initialize this plugin's options to disabled.  Admin must
     * explicitly activate this plugin's features in the newly added shopp menu item
     * 
     * @version 1.0.0
     * @since 1.0.0
     */
    public function on_activation() {
        
            $db_option = array(
                'tax_toggle'        => 'disable',
                'downloads_toggle'  => 'disable'
            );
            
            add_option( 'shopp_wa_destination_tax_enabled', $db_option );
    }
    
    /**
     * If this plugin is being updated, update the option settings format
     * 
     * @version 1.0.0
     * @since 1.0.0
     */
    public function on_update() {
        
        $option_name = 'shopp_wa_destination_tax_enabled';

        $db_option = get_option( $option_name );

        // if they are still using the old version
        if ( false !== $db_option AND ! is_array( $db_option ) ) {
            // update to new option layout preserving the settings
            $db_option = array(
                    'tax_toggle'        => $db_option,
                    'downloads_toggle'  => 'disable'
            );
            update_option( $option_name, $db_option );
        }
    }

    /**
     * 
     * Enqueue's the javascript for this plugin's options page when the page
     * is loaded
     * 
     * @param string $hook  The internal name of the hook for this plugin's
     *                      options page.
     * @version 2.0.0
     * @since 1.0.0
     */
    public function admin_script( $hook ) {
        
        if ( 'shopp_page_washington-taxes' == $hook ) {
            wp_enqueue_script( 'watax_admin_js', plugins_url( 'assets/js/watax-admin.js', __FILE__ ), array( 'jquery' ) );
        }
        
    }

    /**
     * Add tax to downloadable products by changing their type to "shipped"
     * 
     * 
     * @param type $result
     * @param type $options
     * @param type $tag
     * @param type $Cart
     * @return type
     */
    public function downloads_tax_filter( $result, $options, $tag, $Cart ) {
        
        // if this is a downloadable make it shipped so it's taxed

        if ( 'needsshippingestimates' == $tag ) {
                $result = true; // force enable estimates
        }

        if ( 'shippingestimates' == $tag ) { // return a zip field for zip calculation
            ob_start();
            ?>
                <div class="ship-estimates">
                    <input type="hidden" name="shipping[country]" id="shipping-country" value="US">
                    <span>
                        <?php shopp( 'customer.shipping-postcode' ); ?>
                    </span>
                    <input type="submit" name="update" value="Estimate Shipping &amp; Taxes" class="update-button">
                </div>
            <?php
            $result = ob_get_clean();
        }

        return $result;
    }

    /**
     * Enqueue validation script that ensures a zip code was entered
     * 
     * @version 1.0.0
     * @since 1.0.0
     */
    public function force_zip_tax_script() {
        if ( is_shopp_cart_page() ) {
            wp_enqueue_script( 'zip_check', plugins_url( 'assets/js/zip-required.js', __FILE__ ), array( 'jquery' ) );
            wp_localize_script( 'zip_check', 'checkoutUrl', shopp( 'checkout', 'url', 'return=true' ) );
        }
    }

    /**
     * 
     * This is the method that switches the rates
     * 
     * @param array $tax_rate
     * @return array $tax_rate
     * 
     * @version 2.0.0
     * @since 1.0.0
     */
    public function set_taxes( $tax_rate ) {
                    
        // get the Order object that holds most of the order data
        $order =& ShoppOrder();

        // for convenience, pull out the Shipping object with the destination data
        $shipping = $order->Shipping;

        // get the destination state
        $state = strtolower( $shipping->state );

        // if the destination isn't washington, then don't update the taxes
        if ( 'wa' !== $state ) {
            return $tax_rate;
        }

        // get location identifiers used to pull tax rates
        $address = urlencode( $shipping->address . ' ' . $shipping->xaddress );
        $city = urlencode( $shipping->city );
        $zip = $shipping->postcode;

        /*
         * get the destination tax rates, and set the location code
         */
        $tax_xml_element = $this->get_destination_tax( $address , $city, $zip );
        $dest_tax_rate = ( string ) $tax_xml_element->attributes()->rate;
        $location_code = ( string ) $tax_xml_element->attributes()->loccode;

        /*
         * Data element: "Location Code"
         * 
         * Store the location code as an element in the Order Data parameter
         * this will be stored in the database and may be retrieved for
         * further analysis by external tools.
         */
        if ( isset( $order->data ) ) {
                $order->data['Location Code'] = $location_code;
        }

        /**
         * Assuming there's only one tax rate being used, replace that rate
         * with our new one.
         * 
         * @todo what happens if there's more than one element? - PJ
         * 
         * Here an example of the $tax_rate
         * 
         * array(1) {
         *  ["4373e4a1"]=>
         *   object(ShoppItemTax)#778 (5) {
         *     ["label"]=>
         *     string(3) "Tax"
         *     ["rate"]=>
         *     float(0.095)
         *     ["amount"]=>
         *     float(0)
         *     ["total"]=>
         *     float(0)
         *     ["compound"]=>
         *     string(3) "off"
         *   }
         * }
         * 
         */
        $tax_rate_keys = array_keys( $tax_rate );
        $first_tax_rate_key = $tax_rate_keys[0];

        $new_tax_rate = $tax_rate;
        $new_tax_rate[$first_tax_rate_key]->rate = $dest_tax_rate;

        return $new_tax_rate;

    }

    /**
     * Update the status of this plugin's state based on submission from the 
     * options page.
     * 
     * @return boolean  true when the options were updated successfully, otherwise
     *                  false
     * 
     * @version 1.0.0
     * @since 1.0.0
     */
    public function set_status() {
        
        $option_name = 'shopp_wa_destination_tax_enabled';
        
        if ( ! empty( $_POST['wadbt_status'] ) ) {
            if ( $_POST['wadbt_status']['tax_toggle'] == 'disable' ) {
                $_POST['wadbt_status']['downloads_toggle'] = 'disable';
            }
            
            // loop and build the array to save
            foreach ( $_POST['wadbt_status'] as $key => $status ) {
                if ( $status === 'enable' ) {
                        $db_option[ $key ] = 'enable';
                } else {
                        $db_option[ $key ] = 'disable';
                }
            }
        }
        
        // check for options to be saved
        $prior_status = get_option( $option_name );
        if ( $prior_status == $db_option ) { // already set
            $result = true;
        } else { // save it
            $result = update_option( $option_name, $db_option );
        }
        
        // return the result for user notification
        return $result;
    }
    
    /**
     * Retrieves the View for this plugin's options page.  Calls for a status
     * update to handle the case where this page's form was submitted.
     * 
     * 
     * @version 1.0.0
     * @since 1.0.0
     * 
     */
    public function option_page() {
        
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
                $response = $this->set_status();
        }
        var_dump($response);
        
        require_once 'assets/views/option-page-view.php';
    }

    /**
     * Given the address, city, and zip code for a destination, returns
     * the tax rate for the destination
     * 
     * @param string $addr  Destination street address
     * @param string $city  Destination city
     * @param string $zip   Destination zip code
     * @return SimpleXMLElement Tax rate for the destination
     * 
     * @access protected
     * @version 1.0.1
     * @since 1.0.0
     */
    protected function get_destination_tax( $addr, $city, $zip ) {
        
        $request = $this->dor_url . "/AddressRates.aspx?output=xml&addr=$addr&city=$city&zip=$zip";
        
        return $this->get_decoded_url( $request );
    }

    /**
     * Retrieve the tax data from the DOR site, and encode it as a SimpleXMLElement
     * DOR = (Washington State) Department of Revenue
     * 
     * @param string $url   The URL for the DOR with the destination address
     * @return SimpleXMLElement Response from the DOR given the destination address
     */
    protected function get_decoded_url( $url ) {

        $result = wp_remote_get( $url );

        if ( ! is_wp_error( $result ) ) {

            $xml = new SimpleXMLElement( $result['body'] );

            switch( $xml->attributes()->code ) {
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
        } else {
            $xml = "Error: Could not load XML.";
        }

        return  $xml;
    }
    
    /**
     * Contains the main methods for this plugin.  These callbacks are used to
     * retrieve the correct local tax rates, and update the rate at runtime.
     * 
     * @access protected
     * @author Patrick Jackson <patrick@ivycat.com>
     * @version 1.0.0
     * @since 2.0.0
     * 
     */
    protected function add_tax_calculation_callbacks(){
        
        // if plugin function is enabled, update the tax rates
        if ( $this->enabled['tax_toggle'] === 'enable' ) {
            add_filter( 'shopp_cart_taxrate', array( &$this , 'set_taxes' ) );
        }
        
        // filter cart template output
        if ( $this->enabled['downloads_toggle'] === 'enable' ) {
            add_filter( 'shopp_themeapi_cart', array( &$this, 'downloads_tax_filter' ), 11, 4 );
            //add_action( 'wp_enqueue_scripts', array( &$this, 'force_zip_tax_script' ) );
        }
    }
    
    /**
     * These callbacks are used to add the options page for this
     * plugin to the Shopp admin menu.
     * 
     * @access protected
     * @author Patrick Jackson <patrick@ivycat.com>
     * @version 1.0.0
     * @since 2.0.0
     * 
     */
    protected function add_plugin_options_page_callbacks(){
        
        // add a submenu item for this plugin to shopp's menu
        add_action( 'shopp_admin_menu', array( &$this, 'add_shopp_menu_item' ) );
        
        // enqueue the admin script for the plugin options page
        add_action( 'admin_enqueue_scripts', array( &$this, 'admin_script' ) );
    }
    
    
    /**
     * These callbacks are used to add functionality for key events during the
     * life of this plugin such as when it is activated or updates.
     * 
     * @access protected
     * @author Patrick Jackson <patrick@ivycat.com>
     * @version 1.0.0
     * @since 2.0.0
     * 
     */
    protected function add_wordpress_events_callbacks(){
        
        // register the activation behavior - sets initial state of this plugin's
        // features to disabled state
        add_action( 'register_activaton_hook', array( &$this, 'on_activation' ) );
        
        // update option formats if this plugin is being updated from an old version
        add_action( 'admin_init', array( &$this, 'on_update' ) );
    }
}
