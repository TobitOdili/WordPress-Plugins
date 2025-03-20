<?php
/**
 * Sitewide Meta Tag for Elementor
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Sitewide Meta Tag class for text fields
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
            if ($field['type'] !== 'image') {
                $options[$field_id] = $field['label'];
            }
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

/**
 * Sitewide Meta Tag class for image fields
 */
class Elementor_Sitewide_Meta_Image_Tag extends \Elementor\Core\DynamicTags\Data_Tag {
    
    /**
     * Get tag name
     */
    public function get_name() {
        return 'sitewide-meta-image';
    }
    
    /**
     * Get tag title
     */
    public function get_title() {
        return __('Blog Info Image', 'elementor-sitewide-meta');
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
        return [\Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY];
    }
    
    /**
     * Register controls
     */
    protected function register_controls() {
        $this->add_control(
            'field_id',
            [
                'label' => __('Image Field', 'elementor-sitewide-meta'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_field_options(),
                'default' => '',
            ]
        );

        // Add fallback image control
        $this->add_control(
            'fallback_image',
            [
                'label' => __('Fallback Image', 'elementor-sitewide-meta'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                    'id' => ''
                ],
            ]
        );
    }
    
    /**
     * Get field options
     */
    private function get_field_options() {
        $options = ['' => __('Select Image Field', 'elementor-sitewide-meta')];
        
        $meta_fields = Elementor_Sitewide_Meta::get_instance()->get_all_meta_fields();
        
        foreach ($meta_fields as $field_id => $field) {
            if ($field['type'] === 'image') {
                $options[$field_id] = $field['label'];
            }
        }
        
        return $options;
    }
    
    /**
     * Get value
     */
    public function get_value(array $options = []) {
        $field_id = $this->get_settings('field_id');
        $fallback = $this->get_settings('fallback_image');
        
        if (empty($field_id)) {
            return $fallback;
        }
        
        $meta_fields = Elementor_Sitewide_Meta::get_instance()->get_all_meta_fields();
        
        if (!isset($meta_fields[$field_id]) || $meta_fields[$field_id]['type'] !== 'image') {
            return $fallback;
        }
        
        $field = $meta_fields[$field_id];
        
        if (empty($field['value'])) {
            return $fallback;
        }
        
        $attachment = get_post($field['value']);
        
        if (!$attachment) {
            return $fallback;
        }
        
        return [
            'id' => $field['value'],
            'url' => wp_get_attachment_url($field['value']),
        ];
    }
}