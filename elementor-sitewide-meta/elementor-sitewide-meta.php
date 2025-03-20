<?php
/**
 * Plugin Name: Blog Info
 * Plugin URI: https://example.com/plugins/blog-info
 * Description: Creates custom meta fields that can be used sitewide in Elementor.
 * Version: 1.0.1
 * Author: SCAI team
 * Author URI: https://example.com
 * Text Domain: elementor-sitewide-meta
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('ESM_VERSION', '1.0.1');
define('ESM_FILE', __FILE__);
define('ESM_PATH', plugin_dir_path(ESM_FILE));
define('ESM_URL', plugin_dir_url(ESM_FILE));

// Main plugin class
class Elementor_Sitewide_Meta {
    
    // Singleton instance
    private static $instance = null;
    
    // Meta fields array
    private $meta_fields = [];
    
    /**
     * Get singleton instance
     */
    public static function get_instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        // Initialize default meta fields
        $this->init_meta_fields();
        
        // Admin hooks
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
        
        // Elementor hooks
        add_action('elementor/dynamic_tags/register', [$this, 'register_dynamic_tags']);
        
        // Register activation hook
        register_activation_hook(ESM_FILE, [$this, 'activate']);
    }
    
    /**
     * Initialize default meta fields
     */
    private function init_meta_fields() {
        $default_fields = [
            // Brand Info Tab
            'brand_name' => [
                'id' => 'brand_name',
                'label' => 'Brand Name',
                'type' => 'text',
                'value' => '',
                'description' => 'Your brand or blog name',
                'tab' => 'brand'
            ],
            'blog_niche' => [
                'id' => 'blog_niche',
                'label' => 'Blog Niche',
                'type' => 'text',
                'value' => '',
                'description' => 'Your blog niche or category',
                'tab' => 'brand'
            ],
            'about_us_sidebar' => [
                'id' => 'about_us_sidebar',
                'label' => 'About Us (Sidebar)',
                'type' => 'textarea',
                'value' => '',
                'description' => 'About your blog or company',
                'tab' => 'brand'
            ],
            'legal_info_sidebar' => [
                'id' => 'legal_info_sidebar',
                'label' => 'Legal Info (Sidebar)',
                'type' => 'textarea',
                'value' => '',
                'description' => 'Legal disclaimers and information',
                'tab' => 'brand'
            ],
            'welcome_intro' => [
                'id' => 'welcome_intro',
                'label' => 'Welcome Page (Intro)',
                'type' => 'textarea',
                'value' => '',
                'description' => 'Introduction section of the welcome page',
                'tab' => 'brand'
            ],
            'welcome_recommendations' => [
                'id' => 'welcome_recommendations',
                'label' => 'Welcome Page (Recommendations)',
                'type' => 'textarea',
                'value' => '',
                'description' => 'Recommendations section of the welcome page',
                'tab' => 'brand'
            ],
            'welcome_contact' => [
                'id' => 'welcome_contact',
                'label' => 'Welcome Page (Contact)',
                'type' => 'textarea',
                'value' => '',
                'description' => 'Contact section of the welcome page',
                'tab' => 'brand'
            ],
            
            // Social Media Tab
            'facebook_url' => [
                'id' => 'facebook_url',
                'label' => 'Facebook URL',
                'type' => 'text',
                'value' => '',
                'description' => 'Your Facebook page URL',
                'tab' => 'social'
            ],
            'twitter_url' => [
                'id' => 'twitter_url',
                'label' => 'Twitter (X) URL',
                'type' => 'text',
                'value' => '',
                'description' => 'Your Twitter/X profile URL',
                'tab' => 'social'
            ],
            'youtube_url' => [
                'id' => 'youtube_url',
                'label' => 'YouTube URL',
                'type' => 'text',
                'value' => '',
                'description' => 'Your YouTube channel URL',
                'tab' => 'social'
            ],
            'instagram_url' => [
                'id' => 'instagram_url',
                'label' => 'Instagram URL',
                'type' => 'text',
                'value' => '',
                'description' => 'Your Instagram profile URL',
                'tab' => 'social'
            ],
            
            // Blog Images Tab
            'get_started_image' => [
                'id' => 'get_started_image',
                'label' => 'Get Started Image',
                'type' => 'image',
                'value' => '',
                'description' => 'Featured image for Get Started section',
                'tab' => 'blog_images'
            ],
            'category_1_image' => [
                'id' => 'category_1_image',
                'label' => 'Category 1 Featured Image',
                'type' => 'image',
                'value' => '',
                'description' => 'Featured image for category 1',
                'tab' => 'blog_images'
            ],
            'category_2_image' => [
                'id' => 'category_2_image',
                'label' => 'Category 2 Featured Image',
                'type' => 'image',
                'value' => '',
                'description' => 'Featured image for category 2',
                'tab' => 'blog_images'
            ],
            'category_3_image' => [
                'id' => 'category_3_image',
                'label' => 'Category 3 Featured Image',
                'type' => 'image',
                'value' => '',
                'description' => 'Featured image for category 3',
                'tab' => 'blog_images'
            ],
            'category_4_image' => [
                'id' => 'category_4_image',
                'label' => 'Category 4 Featured Image',
                'type' => 'image',
                'value' => '',
                'description' => 'Featured image for category 4',
                'tab' => 'blog_images'
            ],
            'category_5_image' => [
                'id' => 'category_5_image',
                'label' => 'Category 5 Featured Image',
                'type' => 'image',
                'value' => '',
                'description' => 'Featured image for category 5',
                'tab' => 'blog_images'
            ],
            'category_6_image' => [
                'id' => 'category_6_image',
                'label' => 'Category 6 Featured Image',
                'type' => 'image',
                'value' => '',
                'description' => 'Featured image for category 6',
                'tab' => 'blog_images'
            ],
            'category_7_image' => [
                'id' => 'category_7_image',
                'label' => 'Category 7 Featured Image',
                'type' => 'image',
                'value' => '',
                'description' => 'Featured image for category 7',
                'tab' => 'blog_images'
            ],
            'category_8_image' => [
                'id' => 'category_8_image',
                'label' => 'Category 8 Featured Image',
                'type' => 'image',
                'value' => '',
                'description' => 'Featured image for category 8',
                'tab' => 'blog_images'
            ],
            
            // Page Contents Tab
            'about_page' => [
                'id' => 'about_page',
                'label' => 'About Page Content',
                'type' => 'wysiwyg',
                'value' => '',
                'description' => 'Main content for the About page',
                'tab' => 'pages'
            ],
            'contact_page' => [
                'id' => 'contact_page',
                'label' => 'Contact Page Content',
                'type' => 'wysiwyg',
                'value' => '',
                'description' => 'Main content for the Contact page',
                'tab' => 'pages'
            ],
            'faq_page' => [
                'id' => 'faq_page',
                'label' => 'FAQ Page Content',
                'type' => 'wysiwyg',
                'value' => '',
                'description' => 'Main content for the FAQ page',
                'tab' => 'pages'
            ],
            'partnerships_page' => [
                'id' => 'partnerships_page',
                'label' => 'Partnerships Page Content',
                'type' => 'wysiwyg',
                'value' => '',
                'description' => 'Main content for the Partnerships page',
                'tab' => 'pages'
            ],
            'guest_post_page' => [
                'id' => 'guest_post_page',
                'label' => 'Guest Post Page Content',
                'type' => 'wysiwyg',
                'value' => '',
                'description' => 'Main content for the Guest Post page',
                'tab' => 'pages'
            ],
            'do_not_sell_page' => [
                'id' => 'do_not_sell_page',
                'label' => 'Do Not Sell My Info Page Content',
                'type' => 'wysiwyg',
                'value' => '',
                'description' => 'Main content for the Do Not Sell My Info page',
                'tab' => 'pages'
            ],
            'terms_page' => [
                'id' => 'terms_page',
                'label' => 'Terms of Service Page Content',
                'type' => 'wysiwyg',
                'value' => '',
                'description' => 'Main content for the Terms of Service page',
                'tab' => 'pages'
            ]
        ];
        
        // Get saved fields
        $saved_fields = get_option('esm_meta_fields', []);
        
        // Merge saved values with default fields
        foreach ($default_fields as $key => $field) {
            if (isset($saved_fields[$key]['value'])) {
                $default_fields[$key]['value'] = $saved_fields[$key]['value'];
            }
        }
        
        // Update option and class property
        update_option('esm_meta_fields', $default_fields);
        $this->meta_fields = $default_fields;
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if ('toplevel_page_elementor-sitewide-meta' !== $hook) {
            return;
        }
        
        wp_enqueue_media();
        
        wp_enqueue_style(
            'esm-admin',
            ESM_URL . 'assets/css/admin.css',
            [],
            ESM_VERSION
        );
        
        wp_enqueue_script(
            'esm-admin',
            ESM_URL . 'assets/js/admin.js',
            ['jquery'],
            ESM_VERSION,
            true
        );
        
        // Enqueue WordPress editor scripts
        wp_enqueue_editor();
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Initialize meta fields on activation only if they don't exist
        if (!get_option('esm_meta_fields')) {
            $this->init_meta_fields();
        }
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            'Blog Info',
            'Blog Info',
            'manage_options',
            'elementor-sitewide-meta',
            [$this, 'admin_page'],
            'dashicons-database',
            30
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('esm_settings_group', 'esm_meta_fields', [$this, 'sanitize_meta_fields']);
    }
    
    /**
     * Sanitize meta fields
     */
    public function sanitize_meta_fields($fields) {
        $sanitized_fields = [];
        
        if (is_array($fields)) {
            foreach ($fields as $key => $field) {
                $sanitized_fields[$key] = [
                    'id' => sanitize_key($field['id']),
                    'label' => sanitize_text_field($field['label']),
                    'type' => sanitize_text_field($field['type']),
                    'value' => $this->sanitize_field_value($field['value'], $field['type']),
                    'description' => sanitize_text_field($field['description']),
                    'tab' => sanitize_key($field['tab'])
                ];
            }
        }
        
        return $sanitized_fields;
    }
    
    /**
     * Sanitize field value based on type
     */
    private function sanitize_field_value($value, $type) {
        switch ($type) {
            case 'email':
                return sanitize_email($value);
            case 'url':
                return esc_url_raw($value);
            case 'textarea':
                return sanitize_textarea_field($value);
            case 'wysiwyg':
                return wp_kses_post($value);
            case 'number':
                return intval($value);
            case 'image':
                return absint($value);
            default:
                return sanitize_text_field($value);
        }
    }
    
    /**
     * Reset all field values
     */
    public function reset_field_values() {
        $meta_fields = get_option('esm_meta_fields', []);
        
        foreach ($meta_fields as $key => $field) {
            $meta_fields[$key]['value'] = '';
        }
        
        update_option('esm_meta_fields', $meta_fields);
        
        return true;
    }
    
    /**
     * Admin page
     */
    public function admin_page() {
        // Get current meta fields
        $meta_fields = get_option('esm_meta_fields', []);
        
        // Handle reset action
        if (isset($_POST['reset_fields']) && $_POST['reset_fields'] === 'true') {
            $this->reset_field_values();
            echo '<div class="notice notice-success is-dismissible"><p>' . __('All field values have been reset.', 'elementor-sitewide-meta') . '</p></div>';
            $meta_fields = get_option('esm_meta_fields', []);
        }
        
        // Define tabs
        $tabs = [
            'brand' => __('Brand Info', 'elementor-sitewide-meta'),
            'social' => __('Social Media', 'elementor-sitewide-meta'),
            'blog_images' => __('Blog Images', 'elementor-sitewide-meta'),
            'pages' => __('Page Contents', 'elementor-sitewide-meta')
        ];
        
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <p><?php _e('Manage your blog info fields that can be used in Elementor.', 'elementor-sitewide-meta'); ?></p>
            
            <h2 class="nav-tab-wrapper esm-tab-nav">
                <?php foreach ($tabs as $tab_id => $tab_name) : ?>
                    <a href="#" class="nav-tab esm-tab" data-tab="tab-<?php echo esc_attr($tab_id); ?>">
                        <?php echo esc_html($tab_name); ?>
                    </a>
                <?php endforeach; ?>
            </h2>
            
            <form method="post" action="options.php">
                <?php settings_fields('esm_settings_group'); ?>
                
                <?php foreach ($tabs as $tab_id => $tab_name) : ?>
                    <div id="tab-<?php echo esc_attr($tab_id); ?>" class="esm-tab-content">
                        <div class="esm-fields-container">
                            <?php foreach ($meta_fields as $field_id => $field) : 
                                if ($field['tab'] !== $tab_id) continue;
                            ?>
                            <div class="esm-field-row">
                                <h3><?php echo esc_html($field['label']); ?></h3>
                                <p><em><?php echo esc_html($field['description']); ?></em></p>
                                
                                <input type="hidden" name="esm_meta_fields[<?php echo esc_attr($field_id); ?>][id]" 
                                       value="<?php echo esc_attr($field['id']); ?>" />
                                <input type="hidden" name="esm_meta_fields[<?php echo esc_attr($field_id); ?>][label]" 
                                       value="<?php echo esc_attr($field['label']); ?>" />
                                <input type="hidden" name="esm_meta_fields[<?php echo esc_attr($field_id); ?>][type]" 
                                       value="<?php echo esc_attr($field['type']); ?>" />
                                <input type="hidden" name="esm_meta_fields[<?php echo esc_attr($field_id); ?>][description]" 
                                       value="<?php echo esc_attr($field['description']); ?>" />
                                <input type="hidden" name="esm_meta_fields[<?php echo esc_attr($field_id); ?>][tab]" 
                                       value="<?php echo esc_attr($field['tab']); ?>" />
                                
                                <div style="margin-top: 10px;">
                                    <?php if ($field['type'] === 'textarea') : ?>
                                        <textarea name="esm_meta_fields[<?php echo esc_attr($field_id); ?>][value]" 
                                                  rows="10" style="width: 100%;"><?php echo esc_textarea($field['value']); ?></textarea>
                                    <?php elseif ($field['type'] === 'wysiwyg') : ?>
                                        <?php wp_editor(
                                            wp_kses_post($field['value']),
                                            'esm_meta_fields_' . $field_id,
                                            [
                                                'textarea_name' => 'esm_meta_fields[' . esc_attr($field_id) . '][value]',
                                                'textarea_rows' => 10,
                                                'media_buttons' => true,
                                                'teeny' => false,
                                                'tinymce' => true,
                                                'quicktags' => true
                                            ]
                                        ); ?>
                                    <?php elseif ($field['type'] === 'image') : ?>
                                        <div class="esm-image-field">
                                            <input type="hidden" name="esm_meta_fields[<?php echo esc_attr($field_id); ?>][value]" 
                                                   value="<?php echo esc_attr($field['value']); ?>" class="esm-image-id" />
                                            
                                            <div class="esm-image-preview" style="margin-bottom: 10px;">
                                                <?php if (!empty($field['value'])) : 
                                                    $image = wp_get_attachment_image($field['value'], 'medium');
                                                    if ($image) {
                                                        echo $image;
                                                    }
                                                endif; ?>
                                            </div>
                                            
                                            <button type="button" class="button esm-upload-image">
                                                <?php _e('Select Image', 'elementor-sitewide-meta'); ?>
                                            </button>
                                            
                                            <?php if (!empty($field['value'])) : ?>
                                                <button type="button" class="button esm-remove-image">
                                                    <?php _e('Remove Image', 'elementor-sitewide-meta'); ?>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    <?php else : ?>
                                        <input type="<?php echo esc_attr($field['type']); ?>" 
                                               name="esm_meta_fields[<?php echo esc_attr($field_id); ?>][value]" 
                                               value="<?php echo esc_attr($field['value']); ?>" style="width: 100%;" />
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <?php submit_button(__('Save Changes', 'elementor-sitewide-meta')); ?>
            </form>
            
            <div style="margin-top: 30px; padding: 20px; background: #f5f5f5; border-left: 4px solid #dc3232;">
                <h3><?php _e('Reset All Field Values', 'elementor-sitewide-meta'); ?></h3>
                <p><?php _e('This will clear all field values but keep the field structure intact.', 'elementor-sitewide-meta'); ?></p>
                <form method="post">
                    <input type="hidden" name="reset_fields" value="true" />
                    <?php submit_button(__('Reset All Values', 'elementor-sitewide-meta'), 'secondary', 'reset-button', false, ['onclick' => 'return confirm("Are you sure you want to reset all field values? This cannot be undone.");']); ?>
                </form>
            </div>
        </div>
        <?php
    }
    
    /**
     * Register dynamic tags for Elementor
     */
    public function register_dynamic_tags($dynamic_tags_manager) {
        // Include dynamic tag class
        require_once ESM_PATH . 'includes/dynamic-tags/sitewide-meta-tag.php';
        
        // Register tag group
        $dynamic_tags_manager->register_group(
            'esm-tags',
            [
                'title' => 'Blog Info Fields'
            ]
        );
        
        // Register the dynamic tags
        $dynamic_tags_manager->register(new \Elementor_Sitewide_Meta_Tag());
        $dynamic_tags_manager->register(new \Elementor_Sitewide_Meta_Image_Tag());
    }
    
    /**
     * Get meta field value
     */
    public function get_meta_field_value($field_id) {
        $meta_fields = get_option('esm_meta_fields', []);
        
        if (isset($meta_fields[$field_id])) {
            $field = $meta_fields[$field_id];
            if ($field['type'] === 'image' && !empty($field['value'])) {
                return wp_get_attachment_url($field['value']);
            }
            return $field['value'];
        }
        
        return '';
    }
    
    /**
     * Get all meta fields
     */
    public function get_all_meta_fields() {
        return get_option('esm_meta_fields', []);
    }
}

// Initialize the plugin
function elementor_sitewide_meta_init() {
    // Check if Elementor is active
    if (!did_action('elementor/loaded')) {
        add_action('admin_notices', function() {
            $message = sprintf(
                __('Blog Info requires Elementor to be installed and activated. <a href="%s">Install Elementor</a>', 'elementor-sitewide-meta'),
                admin_url('plugin-install.php?s=elementor&tab=search&type=term')
            );
            echo '<div class="notice notice-error"><p>' . $message . '</p></div>';
        });
        return;
    }
    
    // Initialize the plugin
    Elementor_Sitewide_Meta::get_instance();
}
add_action('plugins_loaded', 'elementor_sitewide_meta_init');