<?php
/*
 * @author Netpeak Dev 
 * @since 1.0.0
 * Elementor Generate Alt Title
*/

class Image_Widget_Custom_Alt_Title {

    public $element;

    public function __construct() {
        add_action( 'elementor/element/image/section_image/before_section_end', [ $this, 'add_controls' ], 10, 2 );
        add_action( 'elementor/frontend/widget/before_render', [ $this, 'before_render' ] );
        add_action( 'elementor/frontend/widget/after_render', [ $this, 'after_render' ] );
    }

    public function add_controls( $element, $args ) {
        $is_auto_enabled = get_option('netpeak_seo_alt_title_auto_enabled', 0);

        // Options for ALT text
        $alt_options = [
            'attachment' => esc_html__( 'Attachment Alt Text', 'netpeak-seo' ),
            'none' => esc_html__( 'None', 'netpeak-seo' ),
            'custom' => esc_html__( 'Custom', 'netpeak-seo' ),
        ];

        // Add Auto if the option is enabled
        if ($is_auto_enabled) {
            $alt_options = ['auto' => esc_html__( 'Auto', 'netpeak-seo' )] + $alt_options;
        }

        // Add control for ALT text
        $element->add_control(
            'alt_text_type',
            [
                'label' => esc_html__( 'Alternative Text', 'netpeak-seo' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => $is_auto_enabled ? 'auto' : 'attachment',  // Default auto or attachment
                'options' => $alt_options,
            ]
        );

        $element->add_control(
            'custom_alt_text',
            [
                'label' => esc_html__( 'Custom Alt Text', 'netpeak-seo' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true
                ],
                'condition' => [
                    'alt_text_type' => 'custom',
                ],
            ]
        );

        // Options for TITLE text
        $title_options = [
            'attachment' => esc_html__( 'Attachment Title Text', 'netpeak-seo' ),
            'none' => esc_html__( 'None', 'netpeak-seo' ),
            'custom' => esc_html__( 'Custom', 'netpeak-seo' ),
        ];

        // Add Auto if the option is enabled
        if ($is_auto_enabled) {
            $title_options = ['auto' => esc_html__( 'Auto', 'netpeak-seo' )] + $title_options;
        }

        // Add control for TITLE text
        $element->add_control(
            'title_text_type',
            [
                'label' => esc_html__( 'Title Text', 'netpeak-seo' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => $is_auto_enabled ? 'auto' : 'attachment',  // Default auto or attachment
                'options' => $title_options,
            ]
        );

        $element->add_control(
            'custom_title_text',
            [
                'label' => esc_html__( 'Custom Title Text', 'netpeak-seo' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true
                ],
                'condition' => [
                    'title_text_type' => 'custom',
                ],
            ]
        );
    }

    public function after_render( $element ) {
        if ( 'image' === $element->get_name() ) {
            remove_filter( 'elementor/image_size/get_attachment_image_html', [ $this, 'replace_alt_title_text' ] );
        }
    }

    public function before_render( $element ) {
        if ( 'image' === $element->get_name() ) {
            $this->element = $element;
            add_filter( 'elementor/image_size/get_attachment_image_html', [ $this, 'replace_alt_title_text' ], 10, 4 );
        }
    }

    public function replace_alt_title_text( $html, $settings, $image_size_key, $image_key ) {
        $image = $settings[ $image_key ];
        $alt = get_post_meta( $settings['image']['id'], '_wp_attachment_image_alt', true );
        $title = get_the_title( $settings['image']['id'] );

        // ALT text replacement
        switch ( $this->element->get_settings( 'alt_text_type' ) ) {
            case 'auto':
                // Logic for ALT auto generation
                $alt = get_the_title(); 
                $html = str_replace('alt="' . esc_attr( $alt ) . '"', 'alt="' . esc_attr( $alt ) . '"', $html);
                break;
            case 'custom':
                $html = str_replace(
                    'alt="' . esc_attr( $alt ) . '"',
                    'alt="' . esc_attr( $this->element->get_settings_for_display( 'custom_alt_text' ) ) . '"',
                    $html
                );
                break;
            case 'none':
                $html = str_replace(
                    'alt="' . esc_attr( $alt ) . '"',
                    'alt=""',
                    $html
                );
                break;
            default:
                // Using an attribute from the media library
                break;
        }

        // TITLE text replacement
        $custom_title = $this->element->get_settings_for_display( 'custom_title_text' );
        switch ( $this->element->get_settings( 'title_text_type' ) ) {
            case 'auto':
                $title = get_the_title(); 
                $html = preg_replace('/<img(.*?)>/', '<img$1 title="' . esc_attr($title) . '">', $html);
                break;
            case 'custom':
                $html = preg_replace('/<img(.*?)class="(.*?)"(.*?)>/', '<img$1class="$2 netpeak_image_title"$3 title="' . esc_attr($custom_title) . '">', $html);
                break;
            case 'none':
                $html = preg_replace('/<img(.*?)class="(.*?)"(.*?)>/', '<img$1class="$2 netpeak_image_title"$3>', $html);
                break;
            default:
                $html = preg_replace('/<img(.*?)class="(.*?)"(.*?)>/', '<img$1class="$2 netpeak_image_title"$3 title="' . esc_attr($title) . '">', $html);
                break;
        }

        return $html;

    }

}

global $image_widget_custom_alt_title;
$image_widget_custom_alt_title = new Image_Widget_Custom_Alt_Title();
