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
	<?php
	if ( !empty( $_POST ) ) {
		if ( true === $response ) {
			echo '<div id="message" class="updated">Settings saved successfully</div>';
		} else {
			echo '<div id="message" class="error">Oops, there was an error saving settings.</div>';
		}
	}
	?>
		<form action="" method="post">
			<h3>Washington Sales Tax:</h3>
			<p>Enable to connect to the Washington Department of Revenue to lookup tax rates and calculate destination-based sales tax on the fly.</p>
			<table class="form-table">
				<tbody>
				<tr>
					<th scope="row" valign="top">Washington Sales Tax:</th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span>Enable Washington State Sales Tax</span></legend>
							<input id="wadbt_enable_tax" class="tog" type="radio" name="wadbt_status[tax_toggle]" value="enable" <?php checked( $enabled['tax_toggle'], 'enable' ); ?> />
							<label for="wadbt_enable_tax">Enabled</label>
							<br>
							<input id="wadbt_disable_tax" class="tog" type="radio" name="wadbt_status[tax_toggle]" value="disable" <?php checked( $enabled['tax_toggle'], 'disable' ); ?> />
							<label for="wadbt_disable_tax">Disabled</label>
						</fieldset>
					</td>
				</tr>
				</tbody>
			</table>
			<br />
			<h3>Downloadable Digital Products</h3>
			<p>Enable to apply destination-based sales tax to downloadable products.</p>
			<table class="form-table">
				<tbody>
				<tr>
					<th scope="row" valign="top">Tax Downloads:</th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span>Tax Downloads</span></legend>
							<input id="wadbt_enable_downloads" class="tog" type="radio" name="wadbt_status[downloads_toggle]" value="enable" <?php checked( $enabled['downloads_toggle'], 'enable' ); ?> />
							<label for="wadbt_enable_downloads">Enabled</label>
							<br>
							<input id="wadbt_disable_downloads" class="tog" type="radio" name="wadbt_status[downloads_toggle]" value="disable" <?php checked( $enabled['downloads_toggle'], 'disable' ); ?> />
							<label for="wadbt_disable_downloads">Disabled</label>
						</fieldset>
					</td>
				</tr>
				</tbody>
			</table>
			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes"/>
			</p>
		</form>
	</div>
</div>
