<?php

/**
 * WP_Meteor
 *
 * @package   WP_Meteor
 * @author    Aleksandr Guidrevitch <alex@excitingstartup.com>
 * @copyright 2020 wp-meteor.com
 * @license   GPL 2.0+
 * @link      https://wp-meteor.com
 */
?>

<div class="wrap">

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
    <form id="settings" method="post">
        <input type="hidden" name="wpmeteor_action" value="save_settings" />
        <?php wp_nonce_field('wpmeteor_save_settings_nonce', 'wpmeteor_save_settings_nonce'); ?>

        <div id="tabs" class="settings-tab">
            <ul>
                <li><a href="#settings" class="tab-handle"><?php esc_html_e('Settings', WPMETEOR_TEXTDOMAIN); ?></a></li>
                <li><a href="#exclusions" class="tab-handle"><?php esc_html_e('Exclusions', WPMETEOR_TEXTDOMAIN); ?></a></li>
                <li><a href="#elementor" class="tab-handle"><?php esc_html_e('Elementor', WPMETEOR_TEXTDOMAIN); ?></a></li>
                <!-- <li><a href="#how-it-works" class="tab-handle"><?php esc_html_e('How it works', WPMETEOR_TEXTDOMAIN); ?></a></li> -->
                <!-- <li><a href="#faq" class="tab-handle"><?php esc_html_e('FAQ', WPMETEOR_TEXTDOMAIN); ?></a></li> -->
                <!-- <li><a href="#premium" class="tab-handle"><?php esc_html_e('GO PREMIUM', WPMETEOR_TEXTDOMAIN); ?></a></li> -->
                <li><a href="#author" class="tab-handle"><?php esc_html_e('Author', WPMETEOR_TEXTDOMAIN); ?></a></li>
            </ul>
            <div id="settings" class="tab">
                <?php do_action(WPMETEOR_TEXTDOMAIN . '-backend-display-settings-ultimate'); ?>
                <div className="field">
                    <input type="submit" name="submit" id="submit" class="button" value="Save Changes" />
                </div>
                <p>
                    <strong>Self Promotion</strong>: World-class full spectrum Wordpress development at $75/hour from the author of WP Meteor. <a href="#author">Details here</a>
                </p>
            </div>
            <div id="exclusions" class="tab">
                <?php do_action(WPMETEOR_TEXTDOMAIN . '-backend-display-settings-exclusions'); ?>
                <div className="field">
                    <input type="submit" name="submit" id="submit" class="button" value="Save Changes" />
                </div>
            </div>
            <div id="elementor" class="tab">
                <?php do_action(WPMETEOR_TEXTDOMAIN . '-backend-display-settings-elementor'); ?>
                <div className="field">
                    <input type="submit" name="submit" id="submit" class="button" value="Save Changes" />
                </div>
            </div>
            <div id="author" class="tab">
                <div class="row">
                    <div class="author-bio">

                        <h1>World-class full spectrum Wordpress development at $75/hour from the author of WP Meteor.</h1>

                        <p>Hello! I'm Alex, the creator of WP Meteor, trusted by 40,000+ users worldwide, and a seasoned Wordpress development expert with over 20 years of experience. For the last 11, I served as a co-owner and CTO of a digital agency, where I helped numerous businesses flourish online.</p>

                        <p>Here's what I bring to the table for you:</p>
                        <ul>
                            <li><strong>Unique Insight:</strong> As the author of WP Meteor, I bring a unique, in-depth understanding of Wordpress and cutting-edge performance issues troubleshooting expertise</li>
                            <li><strong>Comprehensive Experience:</strong> I have 11+ years of hands-on experience in running businesses and marketing on Wordpress - from analytics and A/B testing to integrations and design.</li>
                            <li><strong>Diverse Expertise:</strong> With over 20 years in software development and server maintenance, I can address a wide range of challenges that your business may encounter.</li>
                        </ul>

                        <p>If you <!-- have at least 20 hours of Wordpress work per month, and you --> know "WHAT" you need, then I know "HOW". I'd love to walk you through your journey and help you with design, support, and development. Let's talk about how I can help you - reach out at <a href="mailto:alex@excitingstartup.com">alex@excitingstartup.com</a> <button  title="Copy email to clipboard" onclick="navigator.clipboard.writeText('alex@excitingstartup.com'); return false">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2" />
                                                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                                                        </svg>
                                                    </button>
                        </p>

                        <p>Everyone can also reach out to me on <a href="https://www.linkedin.com/in/aguidrevitch/">LinkedIn</a> or <a href="https://twitter.com/aguidrevitch">Twitter</a></p>
                        <p><strong>PS. If you have issues with WP Meteor - I'm still available pro bono, ideally through the <a href="https://wordpress.org/support/plugin/wp-meteor/">issue board</a></strong></p>
                    </div>
                    <div class="author-image">
                        <img src="https://s3.us-east-1.amazonaws.com/guidrevitch.com/IMG-db913ca3e1ef3b891d3e43d5d1a33aaa-V%20%281%29.jpg" width="1280" height="851">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>