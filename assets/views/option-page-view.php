<?php
if ( !empty( $_POST['wadbt_status'] ) ) {
    $enabled = $_POST['wadbt_status'];
} else {
    $enabled = $this->enabled;
}
?>
<div class="wrap">
    <div id="icon-edit" class="icon32"></div>
    <h2>WA State Sales Tax for Shopp Settings</h2>
    <div id="options-wrapper">
        <h3>Enable / Disable Washington State Tax Lookup and Calculation and optional features</h3>
        <form action="" method="post">
           <table class"form-table">
                <tr>
                    <td>
                        <ul>
                            <li>
                            <h4>Washington Sales Tax:</h4>
                            <p>Enable to connect to the Washington Department of Revenue to lookup tax rates and calculate destination-based sales tax on the fly.</p>
                                <input id="wadbt_enable_tax" class="tog" type="radio" name="wadbt_status[tax_toggle]" value="enable" <?php checked( $enabled['tax_toggle'], 'enable' ); ?> />
                                <label for="wadbt_enable_tax">Enabled</label>
                                <input id="wadbt_disable_tax" class="tog" type="radio" name="wadbt_status[tax_toggle]" value="disable" <?php checked( $enabled['tax_toggle'], 'disable' ); ?> />
                                <label for="wadbt_disable_tax">Disabled</label>
                            </li>
                            <li>
                            <h4> Tax Downloads: </h4>
                            <p>Enable to apply destination-based sales tax to downloadable products.</p>
                                <input id="wadbt_enable_downloads" class="tog" type="radio" name="wadbt_status[downloads_toggle]" value="enable" <?php checked( $enabled['downloads_toggle'], 'enable' ); ?> />
                                <label for="wadbt_enable_downloads">Enabled</label>
                                <input id="wadbt_disable_downloads" class="tog" type="radio" name="wadbt_status[downloads_toggle]" value="disable" <?php checked( $enabled['downloads_toggle'], 'disable' ); ?> />
                                <label for="wadbt_disable_downloads">Disabled</label>
                            </li>

                            <li class="submit">
                                <input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes"/>
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
