<?php $enabled = ( $_POST['wadbt_status'] ) ? $_POST['wadbt_status'] : $this->enabled; ?>
<div class="wrap">
    <div id="icon-edit" class="icon32"></div>
    <h2>WA State Sales Tax for Shopp Settings</h2>
    <div id="options-wrapper">
        <h3>Enable / Disable Washington State Tax Lookup and Calculation</h3>
        <form action="" method="post">
           <table class"form-table">
                <tr>
                    <td>
                        <ul>
                            <li>
                                <input id="wadbt_enable" class="tog" type="radio" name="wadbt_status" value="enable"<?php echo ( $enabled == 'enable'  )? ' checked="checked"' : ''; ?> />
                                <label for="wadbt_enable">Enabled</label>
                            </li>
                            <li>
                                <input id="wadbt_disable" class="tog" type="radio" name="wadbt_status" value="disable"<?php echo ( $enabled == 'enable' )? '' : ' checked="checked"'; ?> />
                                <label for="wadbt_disable">Disabled</label>
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