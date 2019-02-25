<?php if( !defined('WPINC') ) die;
/**
 * Leyka Template: Star
 * Description: A modern and lightweight form template
 * Debug only: true
 * 
 * $campaign - current campaign
 * 
 **/

$template_data = Leyka_Star_Template_Controller::getInstance()->getTemplateData($campaign);
?>

<form id="<?php echo leyka_pf_get_form_id($campaign->id).'-star-form';?>" class="leyka-inline-campaign-form leyka-star-form" data-template="star" action="<?php echo Leyka_Payment_Form::get_form_action();?>" method="post" novalidate="novalidate">

    <div class="step step--periodicity">
    
        <?php if(true || leyka_is_recurring_supported()) {?>
            <div class="step__fields periodicity">
                <a href="#" class="active" data-periodicity="monthly">Ежемесячно</a>
                <a href="#" class="" data-periodicity="once">Разово</a>
            </div>
        <?php }?>
        
    </div>


    <div class="step step--amount">
        
        <div class="step__fields amount">

        <?php echo Leyka_Payment_Form::get_common_hidden_fields($campaign, array(
            'leyka_template_id' => 'star',
            'leyka_amount_field_type' => 'custom',
        ));

        $form_api = new Leyka_Payment_Form();
        echo $form_api->get_hidden_amount_fields();?>

            <div class="amount__figure star-swiper">
                <a class="swiper-arrow swipe-left"></a>
                <a class="swiper-arrow swipe-right"></a>
                
                <?php foreach($template_data['amount_variants'] as $i => $amount) {?>
                    <div class="swiper-item" data-value="<?php echo (int)$amount;?>"><span class="amount"><?php echo (int)$amount;?></span><span class="currency"><?php echo $template_data['currency_label'];?></span></div>
                <?php }?>

                <?php if($template_data['amount_mode'] != 'fixed') {?>
                    <div class="swiper-item">
                        <input type="text" title="Введите вашу сумму" name="leyka_donation_amount" class="donate_amount_flex" value="<?php echo esc_attr($template_data['amount_default']);?>" maxlength="6">
                    </div>
                <?php }?>
            </div>
            
            <input type="hidden" class="leyka_donation_currency" name="leyka_donation_currency" data-currency-label="<?php echo $template_data['currency_label'];?>" value="<?php echo leyka_options()->opt('main_currency');?>">
            <input type="hidden" name="leyka_recurring" class="is-recurring-chosen" value="0">

        </div>

    </div>
    

    <div class="step step--cards">

        <div class="step__fields payments-grid">
            <div class="star-swiper">
                <a class="swiper-arrow swipe-left"></a>
                <a class="swiper-arrow swipe-right"></a>

        <?php $max_pm_number = leyka_options()->opt_template('show_donation_comment_field') ? 6 : 4;
        foreach($template_data['pm_list'] as $number => $pm) { /** @var $pm Leyka_Payment_Method */


            // Max. 4 PM blocks for forms without comment field, or max. 6 PM blocks otherwise:
            if($number > $max_pm_number) {
                break;
            }?>

            <div class="payment-opt swiper-item">
                <label class="payment-opt__button">
                    <input class="payment-opt__radio" name="leyka_payment_method" value="<?php echo esc_attr($pm->full_id);?>" type="radio" data-processing="<?php echo $pm->processing_type;?>" data-has-recurring="<?php echo $pm->has_recurring_support() ? '1' : '0';?>" data-ajax-without-form-submission="<?php echo $pm->ajax_without_form_submission ? '1' : '0';?>">
                    <span class="payment-opt__icon">
                        <?php foreach($pm->icons ? $pm->icons : array($pm->main_icon_url) as $icon_url) {?>
                            <img class="pm-icon" src="<?php echo $icon_url;?>" alt="">
                        <?php }?>
                    </span>
                </label>
                <span class="payment-opt__label"><?php echo $pm->label;?></span>
            </div>
        <?php }?>
        
            </div>
        </div>

    </div>


    <?php foreach($template_data['pm_list'] as $pm) { /** @var $pm Leyka_Payment_Method */

        if($pm->processing_type != 'static') {
            continue;
        }?>
        
    <div class="step step--static <?php echo $pm->full_id;?>">
        <div class="step__border">

        	<div class="step__fields static-text">
        		<?php $pm->display_static_data();?>

                <div class="static__complete-donation">
                    <input class="leyka-js-complete-donation" value="<?php echo leyka_options()->opt_safe('revo_donation_complete_button_text');?>">
                </div>

        	</div>

    	</div>
    </div>

    <?php }?>


    <!-- donor data -->
    <div class="step step--person">

        <div class="step__border">
            <div class="step__fields donor">

                <?php $field_id = 'leyka-'.wp_rand();?>
                <div class="donor__textfield donor__textfield--name ">
                    <label for="<?php echo $field_id;?>">
                        <span class="donor__textfield-label leyka_donor_name-label"><?php _e('Your name', 'leyka');?></span>
                        <span class="donor__textfield-error leyka_donor_name-error">
                            <?php _e('Enter your name', 'leyka');?>
                        </span>
                    </label>
                    <input id="<?php echo $field_id;?>" type="text" name="leyka_donor_name" value="" autocomplete="off">
                </div>

                <?php $field_id = 'leyka-'.wp_rand();?>
                <div class="donor__textfield donor__textfield--email">
                    <label for="<?php echo $field_id;?>">
                        <span class="donor__textfield-label leyka_donor_name-label"><?php _e('Your email', 'leyka');?></span>
                        <span class="donor__textfield-error leyka_donor_email-error">
                            <?php _e('Enter an email in the some@email.com format', 'leyka');?>
                        </span>
                    </label>
                    <input type="email" id="<?php echo $field_id;?>" name="leyka_donor_email" value="" autocomplete="off">
                </div>

                <?php if(leyka_options()->opt_template('show_donation_comment_field')) { $field_id = 'leyka-'.wp_rand();?>
                <div class="donor__textfield donor__textfield--comment leyka-field">
                    <label for="<?php echo $field_id;?>">
                        <span class="donor__textfield-label leyka_donor_comment-label"><?php echo leyka_options()->opt_template('donation_comment_max_length') ? sprintf(__('Your comment (<span class="donation-comment-current-length">0</span> / <span class="donation-comment-max-length">%d</span> symbols)', 'leyka'), leyka_options()->opt_template('donation_comment_max_length')) : __('Your comment', 'leyka');?></span>
                        <span class="donor__textfield-error leyka_donor_comment-error"><?php _e('Entered value is too long', 'leyka');?></span>
                    </label>
                    <textarea id="<?php echo $field_id;?>" class="leyka-donor-comment" name="leyka_donor_comment" data-max-length="<?php echo leyka_options()->opt_template('donation_comment_max_length');?>"></textarea>
                </div>
                <?php }?>

                <div class="donor__submit">
                    <?php echo apply_filters('leyka_revo_template_final_submit', '<input type="submit" class="leyka-default-submit" value="'.leyka_options()->opt_template('donation_submit_text').'">');?>
                </div>

                <?php if(leyka_options()->opt('agree_to_terms_needed') || leyka_options()->opt('agree_to_pd_terms_needed')) {?>
                <div class="donor__oferta">
                    <span>
                    <?php if(leyka_options()->opt('agree_to_terms_needed')) {?>
                        <input type="checkbox" name="leyka_agree" id="leyka_agree" class="required" value="1" <?php echo leyka_options()->opt('terms_agreed_by_default') ? 'checked="checked"' : '';?>>
                        <label for="leyka_agree">
                        <?php echo apply_filters('agree_to_terms_text_text_part', leyka_options()->opt('agree_to_terms_text_text_part')).' ';

                        if(leyka_options()->opt('agree_to_terms_link_action') === 'popup') {?>
                            <a href="#" class="leyka-js-oferta-trigger">
                        <?php } else {?>
                            <a target="_blank" href="<?php echo leyka_get_terms_of_service_page_url();?>">
                        <?php }?>
                                <?php echo apply_filters('agree_to_terms_text_link_part', leyka_options()->opt('agree_to_terms_text_link_part'));?>
                            </a>
                        </label>
                    <?php if(leyka_options()->opt('agree_to_pd_terms_needed')) {?>

                        <input type="checkbox" name="leyka_agree_pd" id="leyka_agree_pd" class="required" value="1" <?php echo leyka_options()->opt('pd_terms_agreed_by_default') ? 'checked="checked"' : '';?>>
                        <label for="leyka_agree_pd">
                        <?php echo apply_filters('agree_to_pd_terms_text_text_part', leyka_options()->opt('agree_to_pd_terms_text_text_part')).' ';?>
                            <a href="#" class="leyka-js-pd-trigger">
                                <?php echo apply_filters('agree_to_pd_terms_text_link_part', leyka_options()->opt('agree_to_pd_terms_text_link_part'));?>
                            </a>
                        </label>

                    <?php }?>
                    </span>
                    <div class="donor__oferta-error leyka_agree-error leyka_agree_pd-error">
                        <?php _e('You should accept Terms of Service to donate', 'leyka');?>
                    </div>
                    <?php }?>
                </div>
                <?php }?>

            </div>
        </div>

        <div class="step__note">
			<p><?php _e('We will send the donation success notice to this address', 'leyka');?></p>
        </div>

    </div>

</form>