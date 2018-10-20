<?php if( !defined('WPINC') ) die; // If this file is called directly, abort ?>

<div class="main-area-top">
    <a href="<?php echo admin_url('admin.php?page=leyka_settings&stage=payment');?>" class="settings-return-link">К списку платёжных операторов</a>
</div>

<?php $gateway = leyka_get_gateway_by_id($_GET['gateway']); /** @var $gateway Leyka_Gateway */
if( !$gateway ) {?>
    <p class="error"><?php _e('Unknown gateway.', 'leyka');?></p>
<?php } else { // Gateway settings area ?>

<div class="main-area single-gateway-settings gateway-<?php echo $_GET['gateway'];?>">

    <div class="gateway-settings-header">

        <div class="gateway-title">

            <?php leyka_show_gateway_logo($gateway, true, 'gateway-header-element');?>

            <h2 class="gateway-header-element"><?php echo $gateway->title;?></h2>

            <?php if($gateway->registration_url) {?>
                <a href="<?php echo $gateway->registration_url;?>" class="gateway-link gateway-registration-link">
                    <img src="<?php echo LEYKA_PLUGIN_BASE_URL;?>img/icon-outer-link.svg">
                </a>
            <?php }?>

            <?php if($gateway->docs_url) {?>
                <a href="<?php echo $gateway->docs_url;?>" class="gateway-link gateway-docs-link">
                    <img src="<?php echo LEYKA_PLUGIN_BASE_URL;?>img/icon-docs-link.svg">
                </a>
            <?php }?>

        </div>

        <div class="gateway-details">

            <?php if($gateway->min_commission && $gateway->min_commission > 0.0) {?>

                <div class="details-element gateway-commission">
                    <div class="details-pic">
                        от <span class="commission-size"><?php echo $gateway->min_commission;?>%</span>
                    </div>
                    <div class="details-label">комиссия</div>
                </div>

            <?php }

            if($gateway->has_recurring) {?>

                <div class="details-element gateway-has-recurring">
                    <div class="details-pic has-recurring-icon">
                        <img src="<?php echo LEYKA_PLUGIN_BASE_URL;?>img/icon-recurring.svg">
                    </div>
                    <div class="details-label">рекурренты</div>
                </div>

            <?php }

            if($gateway->receiver_types) {?>

                <div class="details-element gateway-receiver-types">
                    <div class="details-pic receiver-type-icon">
                        <img src="<?php echo LEYKA_PLUGIN_BASE_URL;?>img/icon-receiver-type-<?php echo count($gateway->receiver_types) > 1 ? 'all' : $gateway->receiver_types[0];?>.svg">
                    </div>
                    <div class="details-label">получатель</div>
                </div>

            <?php }?>

        </div>

    </div>

    <div class="gateway-settings-wrapper">

        <div class="gateway-settings">

            <h3>Настройки оператора</h3>

        <?php foreach($gateway->get_options_names() as $option_id) {

            $option_info = leyka_options()->get_info_of($option_id);?>

            <div id="<?php echo $option_id;?>" class="settings-block option-block type-<?php echo $option_info['type'];?>">
                <?php do_action("leyka_render_{$option_info['type']}", $option_id, $option_info);?>
                <div class="field-errors"></div>
            </div>

        <?php }

        if($gateway->has_wizard) {?>
            <a class="gateway-header-element gateway-wizard-link" href="<?php echo $gateway->wizard_url;?>" title="Открыть Мастер пошагового подключения к платёжному оператору">
                <img src="<?php echo LEYKA_PLUGIN_BASE_URL;?>img/icon-wizard.svg"> Пошаговая установка
            </a>
        <?php }?>

        </div>

        <div class="gateway-pm-list">

            <h3>Способы платежа</h3>

            <?php $pm_list_by_categories = $gateway->get_payment_methods(null, false, true);
            $pm_active = leyka_options()->opt('pm_available');

            foreach($pm_list_by_categories as $category_id => $pm_list) {

                if( !$pm_list ) {
                    continue;
                }?>

                <h4><?php echo leyka_get_pm_category_label($category_id);?></h4>

                <?php foreach($pm_list as $pm) { /** @var $pm Leyka_Payment_Method */ ?>
                <div id="<?php echo $pm->full_id;?>" class="settings-block option-block type-checkbox">
                    <input type="checkbox" name="leyka_pm_available[]" value="<?php echo $pm->full_id;?>" class="pm-active" id="<?php echo $pm->full_id;?>" data-pm-label="<?php echo $pm->title_backend;?>" data-pm-label-backend="<?php echo $pm->label_backend;?>" <?php echo in_array($pm->full_id, $pm_active) ? 'checked="checked"' : '';?>>
                    <label for="<?php echo $pm->full_id;?>"><?php echo $pm->title_backend;?></label>
                </div>
                <?php }?>

            <?php }?>
        </div>

    </div>

    <div class="gateway-settings-submit">

        <a href="#" class="gateway-turn-off">Отключить платёжный оператор</a>

        <input type="submit" name="<?php echo "leyka_settings_{$_GET['stage']}_submit";?>" value="<?php _e('Save settings', 'leyka');?>" class="button-primary">

    </div>

</div>

<?php }