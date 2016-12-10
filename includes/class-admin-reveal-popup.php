<?php


class WPS_Minimum_Maximum_Order_Quantities {

    /**
     * WPS_Minimum_Maximum_Order_Quantities constructor
     */
    public function __construct() {
        $this->init();
    }

    public function init() {
        // Register Minimum, Maximum and Incremental Steps input fields
        add_action( 'woocommerce_product_options_inventory_product_data',       array( $this, 'register_min_max_input_fields' ) );
        
         add_action( 'woocommerce_product_options_general_product_data',       array( $this, 'option_box' ) );
        
        // Save the Minimum, Maximum and Incremental Steps when set
        add_action( 'save_post',                                                array( $this, 'save_custom_product_settings' ), 10, 1 );
        // Set the Minimum, Maximum and Incremental Steps for a product when it's clearly specified on the back-end.
        add_filter( 'woocommerce_quantity_input_args',                          array( $this, 'set_min_max_step_product_settings' ), 10, 2 );
    }

    /**
     * Register Minimum, Maximum and Incremental Steps input fields
     */
    public function register_min_max_input_fields() {
        echo '<div class="options_group show_if_simple show_if_variable">';

        woocommerce_wp_checkbox( array(
            'id'                => '_reveal_popup_status',
            'label'             => __( 'Reveal Popup', 'wps' ),
            'description'       => __( 'Enable this to Cross Sells list with Reveal Popup', 'wps' ),
            'type'              => 'number',
        ) );
        
      
        echo '</div>';
    }
    
    public function option_box() {
        echo '<div class="options_group show_if_simple show_if_grouped">';
        woocommerce_wp_select(
            array(
                'id' => '_calculation_type',
                'label' => __( 'Calculation Type ', 'woocommerce' ),
                'options' => array(
                'none' => __( 'none', 'woocommerce' ),
                'fix' => __( 'Fix', 'woocommerce' ),
                'fug' => __( 'Fug', 'woocommerce' ),
                'mosaik' => __( 'Mosaik', 'woocommerce' ),
                )
            )
        );
        echo '</div>';
        
    }
    

    /**
     * Save the Minimum, Maximum and Incremental Steps when set
     * TODO: Turn into a loop to decrease repeated code
     * @param $post_id
     */
    public function save_custom_product_settings( $post_id ) {
        if( 'product' == get_post_type( $post_id ) ) {
            // Some ternary operators
            isset( $_POST['_reveal_popup_status'] ) && ! empty( $_POST['_reveal_popup_status'] )
                ? update_post_meta( $post_id, '_reveal_popup_status', $_POST['_reveal_popup_status'] )
                : delete_post_meta( $post_id, '_reveal_popup_status' );
            
             isset( $_POST['_calculation_type'] ) && ! empty( $_POST['_calculation_type'] )
                ? update_post_meta( $post_id, '_calculation_type', $_POST['_calculation_type'] )
                : delete_post_meta( $post_id, '_calculation_type' );
         
        }
    }

    /**
     * Set the Minimum, Maximum and Incremental Steps for a product when it's clearly specified on the back-end.
     *
     * @param $prod_defaults
     * @param WC_Product $product
     * @return array
     */
    public function set_min_max_step_product_settings( $prod_defaults, WC_Product $product ) {
        // Check first to make sure the product type is either simple or grouped
        if( in_array( $product->get_type(), array( 'simple', 'grouped' ) ) ) {
            // Check for the minimum order quantity
            $min_order_qty = get_post_meta( $product->id, '_reveal_popup_status', true );
            ! empty( $min_order_qty ) ? $prod_defaults['min_value'] = $min_order_qty : 0;
            
            $calculation_type=get_post_meta( $product->id, '_calculation_type', true );
 
        }
        // Always return the product defaults
        return $prod_defaults;
    }
}

new WPS_Minimum_Maximum_Order_Quantities();