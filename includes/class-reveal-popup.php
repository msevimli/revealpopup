<?php

   class revealPopUpClass {
       
       public $product_title=null;
       public $product_desc=null;
       public $product_short_desc=null;
       public $product_price_regular=null;
       public $product_price_sale=null;
       public $product_price=null;
       public $product_images=null; 
       public $add_to_cart_url=null;
       public $product_link=null;
       public $product_short_desc_strip=null;
       public $product_desc_strip=null;
       public $crosssellProductId=null;
       public $formatted_attributes=null;
       public $cart_url=null;
    
       
    public function getCartUrl() {
        global $woocommerce;
        $cart_url = $woocommerce->cart->get_cart_url();
        return $cart_url;
    }   
       
    public function getCatName($id) {
          $terms = get_the_terms( $id, 'product_cat' );
           foreach ($terms as $term) {
            $product_cat_id = $term->term_id;
        break;
        }
           return $product_cat_id;
       }
       
     public  function get_product_category_by_id( $category_id ) {
         //   $term = get_term_by( 'id', $category_id, 'product_cat', 'ARRAY_A' );
            $term = get_term_by( 'id',  $category_id, 'product_cat', 'ARRAY_A' );
            return $term['name'];
         
     }
       
       public function getProductUnderCat() {
                 $args = array( 'post_type' => 'product', 'posts_per_page' => 10, 'product_cat' => 'fuge', 'orderby' => 'rand' );
                 $loop = new WP_Query( $args );
                 $i=0;
                while ( $loop->have_posts() ) : $loop->the_post(); 
                    global $product;
                 //   $val[$i]= get_permalink( $loop->post->ID );
           
           
                    $val[$i]=  $loop->post->ID;
                $i++;
               endwhile;
           
           wp_reset_query();
           return $val[0];
        }
       
       public function removeUrl($id) {
           global $woocommerce;
           $i=0;
            foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
            //remove single product
            $val[$i] = $cart_item_key;
            $i++;        
        }
           return $val;  
       }
          
       public function get_remove_url( $cart_item_key ) {
        $cart_page_url = wc_get_page_permalink( 'cart' );
         return apply_filters( 'woocommerce_get_remove_url', $cart_page_url ? wp_nonce_url( add_query_arg( 'remove_item', $cart_item_key, $cart_page_url ), 'woocommerce-cart' ) : '' );
        }

       public function getProductCalcType($id) {
           $type = get_post_meta( $id, '_calculation_type', true );
           return $type;
           
       }
       
       public function getRevealPopupStatus($id) {
           $status = get_post_meta( $id, '_reveal_popup_status', true );
           return $status;
       }
       
       public function getProductAttr($id) {
           
                $product 	   = wc_get_product( $id );  
                $formatted_attributes = array();
                $attributes = $product->get_attributes();

            foreach($attributes as $attr=>$attr_deets){

                    $attribute_label = wc_attribute_label($attr);
                if ( isset( $attributes[ $attr ] ) || isset( $attributes[ 'pa_' . $attr ] ) ) {

                    $attribute = isset( $attributes[ $attr ] ) ? $attributes[ $attr ] : $attributes[ 'pa_' . $attr ];

                    if ( $attribute['is_taxonomy'] ) {

                        $formatted_attributes[$attribute_label] = implode( ', ', wc_get_product_terms( $product->id, $attribute['name'], array( 'fields' => 'names' ) ) );

                    } else {

                        $formatted_attributes[$attribute_label] = $attribute['value'];
                    }

                }
           }

           return $formatted_attributes;
  
       }
       
       public function getCrossProductId($id) {
         $crosssells = get_post_meta( $id, '_crosssell_ids',true);
                    $i=0;
                    foreach($crosssells as $pid) {
                        	$product 	   = wc_get_product( $pid );    
				            $crosssellProductId[$i] = $product->id;
                        $i++;
                    }
                return $crosssellProductId;
       }
       
       public function getProductTitle($id) {
           $crosssells = get_post_meta( $id, '_crosssell_ids',true);
                    $i=0;
                    foreach($crosssells as $pid) {
                        	$product 	   = wc_get_product( $pid );    
				            $product_title[$i] = $product->post->post_title;
                        $i++;
                    }
                return $product_title;
       }
       
       public function getProductDesc($id) {
            $crosssells = get_post_meta( $id, '_crosssell_ids',true);
                    $i=0;
                    foreach($crosssells as $pid) {
                        	$product 	   = wc_get_product( $pid );    
				            $product_desc[$i]  = $product->post->post_content;
                        $i++;
                    }
                return $product_desc;
       }
       
        public function getProductShortDesc($id) {
            $crosssells = get_post_meta( $id, '_crosssell_ids',true);
                    $i=0;
                    foreach($crosssells as $pid) {
                        $product 	   = wc_get_product( $pid );  
                        $product_short_desc[$i] = $product->post->post_excerpt;
                        $i++;
                    }
                return  $product_short_desc;
        }
        
        public function getPriceRegular($id) { 
              $crosssells = get_post_meta( $id, '_crosssell_ids',true);
                    $i=0;
                    foreach($crosssells as $pid) {
                        $product 	   = wc_get_product( $pid );  
                        $product_price_regular[$i] = wc_price( $product->get_regular_price());
                        $i++;
                    }
                return  $product_price_regular;
        }
       
        public function getPriceSale($id) { 
              $crosssells = get_post_meta( $id, '_crosssell_ids',true);
                    $i=0;
                    foreach($crosssells as $pid) {
                        $product 	   = wc_get_product( $pid );  
                        $product_price_sale[$i]    = wc_price( $product->get_sale_price());
                        $i++;
                    }
            return $product_price_sale;
        } 
        
        public function getProductImages($id) {
             $crosssells = get_post_meta( $id, '_crosssell_ids',true);
                    $i=0;
                    foreach($crosssells as $pid) {
                        	$product 	   = wc_get_product( $pid ); 
                            $product_image      = wp_get_attachment_image_src( get_post_thumbnail_id( $pid ), 'shop_thumbnail' );
                            
				    if(isset($product_image[0])){
					   $product_images[$i] = $product_image[0];	
				    }else{
					   $product_images = 'null';
				    }
                        $i++;
                    }
            return $product_images;
        }
       
        public function getAddToCartUrl($id) { 
             $crosssells = get_post_meta( $id, '_crosssell_ids',true);
                    $i=0;
                    foreach($crosssells as $pid) {
                    $add_to_cart_url[$i] = get_permalink(get_option( 'woocommerce_cart_page_id' )).'?add-to-cart='.$pid;
                        $i++;
                    }
            return $add_to_cart_url;
        }
       
        public function getProductUrl($id) { 
            $crosssells = get_post_meta( $id, '_crosssell_ids',true);
                    $i=0;
                    foreach($crosssells as $pid) {
                        	$product 	   = wc_get_product( $pid );  
                            $product_link[$i] = get_permalink($pid);
                            $i++;
                    }
            return $product_link;
        }
       
        public function getProductShortDescStrip($id) { 
            $crosssells = get_post_meta( $id, '_crosssell_ids',true);
                    $i=0;
                    foreach($crosssells as $pid) {
                        	$product 	   = wc_get_product( $pid );
                            $product_short_desc[$i] = $product->post->post_excerpt;
                            //$product_short_desc_strip[$i] =  substr(strip_tags($product_short_desc[i]),0,50);
                            
                            $i++;
                    }
            return $product_short_desc;
        }
           
        public function buildProduct($id) {
               
                 $crosssells = get_post_meta( $id, '_crosssell_ids',true);
                    $i=0;
                    foreach($crosssells as $pid) {
                        	$product 	   = wc_get_product( $pid );    
				            $product_title[$i] = $product->post->post_title;
				            $product_desc[$i]  = $product->post->post_content;
				            $product_short_desc[$i] = $product->post->post_excerpt;
				            $product_price_regular[$i] = wc_price( $product->get_regular_price());
				            $product_price_sale[$i]    = wc_price( $product->get_sale_price());
				            $product_price[$i]         = $product->get_price_html();
				            $product_image      = wp_get_attachment_image_src( get_post_thumbnail_id( $pid ), 'shop_thumbnail' );
                            
				    if(isset($product_image[0])){
					   $product_images[$i] = $product_image[0];	
				    }else{
					   $product_images = 'null';
				    }
				
				        $add_to_cart_url[$i] = get_permalink(get_option( 'woocommerce_cart_page_id' )).'?add-to-cart='.$pid;
                    
				        $product_link[$i] = get_permalink($pid);
								        
				        $product_short_desc_strip[$i] =  substr(strip_tags($product_short_desc[i]),0,50);
                      
				        $product_desc_strip[$i] =  substr(strip_tags($product_desc[i]),0,100);				
                $i++;
                        
                    }
      }
       
   }
?>