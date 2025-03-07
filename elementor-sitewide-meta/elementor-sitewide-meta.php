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
define('ESM_VERSION', '1.0.0');
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
            'brand_name' => [
                'id' => 'brand_name',
                'label' => 'Brand Name',
                'type' => 'text',
                'value' => '',
                'description' => 'Your brand or blog name (text)'
            ],
            'blog_niche' => [
                'id' => 'blog_niche',
                'label' => 'Blog Niche',
                'type' => 'text',
                'value' => '',
                'description' => 'Your blog niche or category (text)'
            ],
            'about_us' => [
                'id' => 'about_us',
                'label' => 'About Us',
                'type' => 'textarea',
                'value' => '',
                'description' => 'About your blog or company (textarea)'
            ],
            'legal_info' => [
                'id' => 'legal_info',
                'label' => 'Legal Information',
                'type' => 'textarea',
                'value' => '',
                'description' => 'Legal disclaimers and information (textarea)'
            ],
            'facebook_url' => [
                'id' => 'facebook_url',
                'label' => 'Facebook URL',
                'type' => 'text',
                'value' => '',
                'description' => 'Your Facebook page URL (text)'
            ],
            'twitter_url' => [
                'id' => 'twitter_url',
                'label' => 'Twitter (X) URL',
                'type' => 'text',
                'value' => '',
                'description' => 'Your Twitter/X profile URL (text)'
            ],
            'youtube_url' => [
                'id' => 'youtube_url',
                'label' => 'YouTube URL',
                'type' => 'text',
                'value' => '',
                'description' => 'Your YouTube channel URL (text)'
            ],
            'instagram_url' => [
                'id' => 'instagram_url',
                'label' => 'Instagram URL',
                'type' => 'text',
                'value' => '',
                'description' => 'Your Instagram profile URL (text)'
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
                    'description' => sanitize_text_field($field['description'])
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
            case 'number':
                return intval($value);
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
        
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <p><?php _e('Manage your blog info fields that can be used in Elementor.', 'elementor-sitewide-meta'); ?></p>
            
            <form method="post" action="options.php">
                <?php settings_fields('esm_settings_group'); ?>
                
                <div class="esm-fields-container" style="max-width: 800px;">
                    <?php foreach ($meta_fields as $field_id => $field) : ?>
                    <div class="esm-field-row" style="margin-bottom: 20px; padding: 15px; background: #f9f9f9; border: 1px solid #e5e5e5; border-radius: 5px;">
                        <h3 style="margin-top: 0;"><?php echo esc_html($field['label']); ?></h3>
                        <p><em><?php echo esc_html($field['description']); ?></em></p>
                        
                        <input type="hidden" name="esm_meta_fields[<?php echo esc_attr($field_id); ?>][id]" 
                               value="<?php echo esc_attr($field['id']); ?>" />
                        <input type="hidden" name="esm_meta_fields[<?php echo esc_attr($field_id); ?>][label]" 
                               value="<?php echo esc_attr($field['label']); ?>" />
                        <input type="hidden" name="esm_meta_fields[<?php echo esc_attr($field_id); ?>][type]" 
                               value="<?php echo esc_attr($field['type']); ?>" />
                        <input type="hidden" name="esm_meta_fields[<?php echo esc_attr($field_id); ?>][description]" 
                               value="<?php echo esc_attr($field['description']); ?>" />
                        
                        <div style="margin-top: 10px;">
                            <?php if ($field['type'] === 'textarea') : ?>
                                <textarea name="esm_meta_fields[<?php echo esc_attr($field_id); ?>][value]" 
                                          rows="3" style="width: 100%;"><?php echo esc_textarea($field['value']); ?></textarea>
                            <?php else : ?>
                                <input type="<?php echo esc_attr($field['type']); ?>" 
                                       name="esm_meta_fields[<?php echo esc_attr($field_id); ?>][value]" 
                                       value="<?php echo esc_attr($field['value']); ?>" style="width: 100%;" />
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <?php submit_button(__('Save Changes', 'elementor-sitewide-meta')); ?>
            </form>
            
            <div style="margin-top: 30px; padding: 20px; background: #f5f5f5; border-left: 4px solid #dc3232; max-width: 800px;">
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
        
        // Register the dynamic tag
        $dynamic_tags_manager->register(new \Elementor_Sitewide_Meta_Tag());
    }
    
    /**
     * Get meta field value
     */
    public function get_meta_field_value($field_id) {
        $meta_fields = get_option('esm_meta_fields', []);
        
        if (isset($meta_fields[$field_id])) {
            return $meta_fields[$field_id]['value'];
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