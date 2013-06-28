# Washington State Sales Tax for Shopp #
**Contributors:** ivycat, sewmyheadon, gehidore, dgilfoy  
**Donate link:** http://www.ivycat.com/contribute/  
**Tags:** tax, taxes, destination based, sales tax, ecommerce, shopp, washington  
**Requires at least:** 3.4  
**Tested up to:** 3.6-beta3  
**Stable tag:** 1.0.4  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

This plugin ties Shopp, an e-commerce plugin for WordPress, into Washington State's Department of Revenue API to lookup destination-based sales tax and calculate it on the fly.

## Description ##

Washington State is a [destination-based sales tax](http://dor.wa.gov/Content/FindTaxesAndRates/RetailSalesTax/DestinationBased/MoreSST.aspx) state.  Meaning, you charge the tax rate of the destination you're shipping to, rather than the rate of where you're shipping from.

Most e-commerce platforms have relatively rudimentary tax calculation tools and tables.  [Shopp](https://shopplugin.net/), while robust, doesn't have the ability to calculate the actual sales tax in Washington State based on _destination_ address and zip.

When enabled, the customer's zip and address information is passed up to the WA DOR along with some basic information about the order and it returns the actual tax rate, and amount for the ship-to address.

[Find out more about Washington's Destination-based Sales Tax](http://dor.wa.gov/Content/FindTaxesAndRates/RetailSalesTax/DestinationBased/MoreSST.aspx)

## Installation ##

Like most WordPress plugins, you can install from within the WordPress Dashboard under Plugins/Add New.

If you wish to install manually:

1. Upload the `washington-state-sales-tax-for-shopp` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to Settings/WA Taxes for Shopp and click enable
1. Shopp will automatically get the destination-based sales tax for all orders shipped to WA State.

## Frequently Asked Questions ##

### Does this plugin work for Oregon, California, Idaho, Florida, etc? ###

Nope, just Washington State.

### What about Washington DC? ###

Sorry, just Washington State.

### I'm not sure if I need to use this plugin.  I live in Washington, but don't know if I need to collect sales tax.  Can you help? ###

Unfortunately, we can't advise you what taxes you're liable to pay the State of Washington.

We recommend that you contact the [Washington Department of Revenue](http://dor.wa.gov/content/ContactUs/default.aspx), or your accountant.

### My accountant says I should use this plugin; can you help make sure it's installed correctly, provide customization, or help me with other Shopp questions?

Absolutely.  Please [contact us](http://www.ivycat.com/contact/) and let us know what you need and we'll be happy to provide an estimate.
###
## Screenshots ##

###1. Enabling the plugin screenshot-1.png###
![Enabling the plugin screenshot-1.png](http://s.wordpress.org/extend/plugins/washington-state-sales-tax-for-shopp/screenshot-1.png)

###2. WA State Tax for Shopp in action screenshot-2.png###
![WA State Tax for Shopp in action screenshot-2.png](http://s.wordpress.org/extend/plugins/washington-state-sales-tax-for-shopp/screenshot-2.png)


## Changelog ##

### 1.0.4 ###
*** Bug fix:** plugin now honors `tax shipping` selection under Shopp Setup/Taxes/Settings.  

### 1.0.3 ###
* Minor plugin housekeeping only; no bug fixes.
* Style updates in WP Dashboard.

### 1.0.2 ###
* Minor housekeeping, bug fix for tax collection on promotional pricing.

### 1.0 ###
* Initial release.

## Upgrade Notice ##

### 1.0.4 ###
**Please upgrade:** importan bug fix.  

### 1.0.3 ###
Not urgent; no change to functionality; just plugin housekeeping and documentation.

### 1.0.2 ###
Fixed tax collection bug on promo items; important update.

### 1.0 ###
* You just installed, yeah?