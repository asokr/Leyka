<?php if( !defined('WPINC') ) die;
/**
 * Leyka Star Template code to load during page initialization.
 **/

// Revo campaigns have different elements order:
if( !is_admin() && is_main_query() && is_singular(Leyka_Campaign_Management::$post_type) ) {

    remove_filter('the_content', 'leyka_print_donation_elements');
    if( !leyka_options()->opt_template('do_not_display_donation_form') ) {
        add_filter('the_content', 'leyka_star_template_campaign_page');
    }

}

function leyka_star_template_campaign_page($content) {

    if( !is_singular(Leyka_Campaign_Management::$post_type) ) {
        return $content;
    }

    $campaign_id = get_queried_object_id();

    $before = leyka_inline_campaign(array('id' => $campaign_id, 'template' => 'star'));
    $after = leyka_inline_campaign_small($campaign_id);

    return $before.$content.$after;

}