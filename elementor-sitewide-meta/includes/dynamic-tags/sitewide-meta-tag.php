<?php
/**
 * Sitewide Meta Tag for Elementor
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Sitewide Meta Tag class
 */
class Elementor_Sitewide_Meta_Tag extends \Elementor\Core\DynamicTags\Tag {
    
    /**
     * Get tag name
     */
    public function get_name() {
        return 'sitewide-meta-field';
    }
    
    /**
     * Get tag title
     */
    public function get_title() {
        return __('Blog Info Field', 'elementor-sitewide-meta');
    }
    
    /**
     * Get tag groups
     */
    public function get_group() {
        return ['esm-tags'];
    }
    
    /**
     * Get tag categories
     */
    public function get_categories() {
        return [
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
            \Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
            \Elementor\Modules\DynamicTags\Module::POST_META_CATEGORY,
        ];
    }
    
    /**
     * Register controls
     */
    protected function register_controls() {
        $this->add_control(
            'field_id',
            [
                'label' => __('Field', 'elementor-sitewide-meta'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_field_options(),
                'default' => '',
            ]
        );
    }
    
    /**
     * Get field options
     */
    private function get_field_options() {
        $options = ['' => __('Select Field', 'elementor-sitewide-meta')];
        
        $meta_fields = Elementor_Sitewide_Meta::get_instance()->get_all_meta_fields();
        
        foreach ($meta_fields as $field_id => $field) {
            $options[$field_id] = $field['label'];
        }
        
        return $options;
    }
    
    /**
     * Render tag
     */
    public function render() {
        $field_id = $this->get_settings('field_id');
        
        if (empty($field_id)) {
            return '';
        }
        
        $value = Elementor_Sitewide_Meta::get_instance()->get_meta_field_value($field_id);
        echo wp_kses_post($value);
    }
}