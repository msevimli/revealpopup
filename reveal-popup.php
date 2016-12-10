<?php
/**
 * Plugin Name: Reveal Pop Up 
 * Plugin URI: http://plife.se
 * Description: This Plugin shows cross sells products to customers with reveal popup
 * Version: 1.0.0
 * Author: Deniz
 * Author URI: http://plife.se
 * License: GPL2
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
 wp_register_script( 'my_plugin_script', plugins_url('js/xjquery-reveal.js', __FILE__), array('jquery'));
 wp_enqueue_script( 'my_plugin_script' );

 wp_register_script( 'kernell', plugins_url('js/kernell.js', __FILE__), array('jquery'));
 wp_enqueue_script( 'kernell' );

 wp_register_style( 'my-plugin', plugins_url('css/reveal.css',__FILE__) );
 wp_enqueue_style( 'my-plugin' );
function bootIt () {


 ob_start();

    if( $_SESSION['myKey']=="loaded")
    {
        $getSquare=$_SESSION['square'];
     //  session_start();
     //   session_destroy();
        echo $getSquare;
?>

    <script>
        
        jQuery(document).ready(function($){
            
                var symbol="DKK";
                var square=$(".input-text.qty.text").val();
                var productTitle=$(".productTitle").html();
                var calcType=$(".getCalctype").html();
                if(calcType=="mosaik") {
                   $(".popSquare").html(parseFloat(square)*2.14); 
                }
                else {
                    $(".popSquare").html(square); 
                }
                
                $(".popProductTitle").html(productTitle);
                var lock=false;
                
                $(".close-reveal-modal").click(function(){
                   _closeIt();
                })
              
                $(".nejButton").click(function() {
                    $('.close-reveal-modal').trigger('click');
                
                });
                
                $(".continueButton").click(function(){
                   _closeIt(); 
                });
                $(".button-class").click(function() {
                  
                    var id=$(this).attr("map-id");
                    
                    var qnt=$("#qnt-"+id).val();
                    var link=$(this).attr("carturl");
                    _add_to_cart(link,qnt);
                    if(lock==false) {
                        _php_function();
                        lock=true;
                    }
                     $(".nejButton").fadeOut();
                    /*
                    var _cartList=$(".cartList").html();
                    var _title=$( "myTag[att-id*='x-"+id+"']" ).text();
                    var _realPrice=$("#prc-"+id).html();
                    
                    var _items=$(".myCartList").text();
                  // alert(_get_items(_items));
                    console.log(_items);
                    var _items_new=parseInt(_get_items(_items))+1;
                    var _amounts_new=parseInt(_get_amounts(_items))+_realPrice*qnt;
                  //  alert (_items_new.toString());
                    console.log(_amounts_new.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " "));
                    var filter_str=_items_new+" items - "+_amounts_new.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ")+" "+symbol;
                    
                    $(".myCartList").text(filter_str);
            
                    
                    var _str='<div class="cartListCon"><mytag class="titleTextCart">'+_title+'</mytag><div class="coverQntPrc"><div class="qnt"> Quantity: '+qnt+' </div><div class="cartListPrice">  Price: '+_realPrice+'</div></div></div><div class="cartLine"></div>';
                    $(".cartList").append(_str);
                */
                $("#row-"+id).animate({
                    height:"0px"
               
                },function() {
                     $(this).fadeOut(1000);
                      $(".myBasket").css({opacity: 0.0, visibility: "visible"}).animate({opacity: 1.0});
                })
            });
            
                $(".kurvButton").click(function() {
                    var _cart_url=$(".cartUrl").text();
                    window.location=_cart_url;
                });
            
                $( ".option-select-fix" ).change(function(){
                    var x=$(this).attr("fix-door");
                    var _x=$( ".option-select-fix[fix-door*='"+x+"']" ).val();
                    _calc_fix(_x);
            });
                
                $(".option-select-fug").change(function() {
                    var x=$(this).attr("fug-door");
                    var _x=$( ".option-select-fug[fug-door*='"+x+"']" ).val();
                    _calc_fug(_x);
            });
            
                $(".fugColor").change(function() {
                    
                    var _id=$(this).attr("map-id");
                    var _title=$('option:selected',this).attr("title");
                    var _cart_url=$('option:selected',this).attr("cart_url");
                    var _img=$('option:selected',this).attr("img");
                    var _sale_price=$('option:selected',this).attr("sale_price");
                    var _regular_price=$('option:selected',this).attr("regular_price");
                    var _product_url=$('option:selected',this).attr("product_url");
                    
                   
                    $("myTag[att-id*='x-"+_id+"']").text(_title);
                    $("div[att-id*='p-"+_id+"']").text(_regular_price+" "+symbol);
                    $("div[att-id*='s-"+_id+"']").text(_sale_price+" "+symbol);
                        if(parseInt(_sale_price)>0) {
                            $("div[att-id*='prc-"+_id+"']").text(_sale_price);
                        } else {
                            $("div[att-id*='prc-"+_id+"']").text(_regular_price);
                        }
                    $("img[att-id*='img-"+_id+"']").attr("src",_img);
                    $("button[map-id*='"+_id+"']").attr("cartUrl",_cart_url);
                    $("a[att-id*='link-1-"+_id+"']").attr("href",_product_url);
                    $("a[att-id*='link-2-"+_id+"']").attr("href",_product_url);
                    $("a[att-id*='link-3-"+_id+"']").attr("href",_product_url);
                    $("a[att-id*='link-4-"+_id+"']").attr("href",_product_url);
                    
                
                });
            
            _closeIt=function() {
                // $(".myBasket").css({opacity: 0.0, visibility: "hidden"});
                // $(".myrow").css({opacity: 0.0, visibility: "hidden"});
                $(".myBasket").fadeOut();
                $(".myrow").fadeOut();
                
                    if(lock==true) {
                        window.location=window.location.href;
                    }
            }
   
            _add_to_cart=function($link,$qnt) {
                $.ajax({
                method: "GET",
                url: $link,
                data: { quantity: $qnt }
                })
                .done(function( msg ) {
                   _ajax_func();
                });
            }
            
            _php_function=function() {
                var result="<?php getCart(); ?>";
               
                $("div.cartList").html(result);
            }
            
            _ajax_func=function() {
   
                var way = 'item';
                $.ajax({
                        url: ajaxurl,
                        data: {
                            'action':'my_ajax_request',
                            'way' : way
            
                        },
                    success:function(data) {
            
                            var temp = new Array();
                            temp = data.split('[sperate]');
                            $(".cartList").html(temp[0]);
                            $(".myCartList").html(temp[1]);
                            $(".cartList").animate({ scrollTop: $(".cartList").get(0).scrollHeight }, "slow");
                    },
                    error: function(errorThrown){
                            console.log(errorThrown);
                    }
                });
        }
                   
            _calc_fug=function($x) {
                var A=parseFloat($(".size-width").html());
                var B=parseFloat($(".size-height").html());
                var C=parseFloat($(".tick").html());
                var calcType=$(".getCalctype").html();
                var _square;
                if(calcType=="mosaik") {
                    _square=parseFloat(square)*2.14;
                }
                else {
                      _square=parseFloat(square);
                }
                var _x=parseInt($x);
                var up=A+B;
                
                var down=A*B;
                var middle=up/down;
                
                var calculation=middle*C*_x*1.6;
                
                var last=calculation*_square;
               
                var _calculation=Math.ceil(last/5);
                $(".quantity-box-fug").val(_calculation);
            }
            
            _calc_fix=function($x) {
                var _x=parseInt($x);
                var _square=parseFloat(square);
                var calculation=_square*1.2*_x;
                var _calculation=Math.ceil(calculation/20);
                $(".quantity-box-fix").val(_calculation);
            }
            
            $(window).resize(function() {
                _setIt($(window).width()); 
            });
            
            _bootIt=function() {
                $('a[data-reveal-id]').trigger('click');
                 
            }            
            _setIt=function($x) {
                if($x>480 && $x<800) {
                    $(".col-12.col-1.col-m-1").attr("class","col-m-8");
                } else {
                    $(".col-m-8").attr("class","col-12 col-1 col-m-1");
                }
            }
            
            _bootIt();    
            _setIt($(window).width()); 
            _calc_fix(6);
            _calc_fug(3);
            
           var lastScrollTop = 0;
            $(window).scroll(function (event) {
                /*
                var st = $(this).scrollTop();
                  if(st<150) {
                    $('.reveal-modal').animate({top: '0'}, 10);
                } else {
                        if(st>1300) {
                        $('.reveal-modal').animate({top: '1650'}, 10);
                    } else 
                    if (st > lastScrollTop) {
                $('.reveal-modal').animate({top: '+=15'}, 10);
                } else {
                    
                        $('.reveal-modal').animate({top: '-=5'}, 10);
                    }
                    
                }
                 lastScrollTop = st;
                 */
            
   
                var x=$(window).width();
                if(x>500) {
                if($(this).scrollTop()>x/2) {
                    $('.reveal-modal').animate({top: '1650'}, 10);
                } else {
                     $('.reveal-modal').animate({top: '0'}, 10);
                }
                }
        
            });
  });
        
</script>



<?php 
    // session_start();
   // session_destroy ();
    }
   // session_start();
  //  session_destroy ();
    
    include_once( plugin_dir_path( __FILE__ ) . 'includes/class-reveal-popup.php');
    
    ?>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    
	<a id="reveal-link" href="#" class="big-link" data-reveal-id="myModal"  data-animation="fade">
			boot
        
    </a>	

		<div id="myModal" class="reveal-modal col-1x col-12x">
			           
		     <p>
                <p>
                    <div class="div-header">
                        
                        Du har bestilt  &nbsp; <span class="popSquare" style="color:yellow;">X</span> &nbsp; antal Kvm. af  &nbsp; <span class="popProductTitle" style="color:yellow;">?</span> &nbsp; , og til det produkt behøver du også ?
                        
                        <p><div class="buttonCover"><button class="nejButton"> Ingen !</button></div></p>
                    </div>
                    <div class="cartUrl" style="display:none;">
                        <?php
                            $exam = new revealPopUpClass() ;
                            echo $exam->getCartUrl();
                        ?>
                    </div>
                    <div>
                        <?php
                        //    global $product;
                           // $exam =new revealPopUpClass();
                            //echo $exam->get_product_category_by_id($exam->getCatName($product->id));
                            
                               global $post, $product;
                            $titleProduct  = wc_get_product( $product->id ); 
                             
                            echo '<span class="productTitle" style="display:none;">'.$titleProduct->post->post_title.'</span>';

                        ?>
                    </div>
                </p>
                  <div class="mycontainer">
                                                  
                            <?php 
                                global $product;
                               // session_start();                         
                              //  session_destroy ();
                                $exam=new revealPopUpClass();
                                $loop=count($exam->getProductTitle($product->id));
                                $i=0;
                                $symbol="DKK";  

                    
                                while($i<$loop) {
                                  
                                    echo '<div id="cover" class="col-12 col-1 col-m-1">';
                                    
                                    echo '<div class="myrow" id="row-'.$i.'">';
                                        
                                        echo '<div class="centerBlock">';
                                    
                                        echo '<a att-id="link-1-'.$i.'" href="'.$exam->getProductUrl($product->id)[$i].'" target=_blank><div class="hover02 column"></a>';
                                    
                                        echo '<div><a att-id="link-2-'.$i.'" href="'.$exam->getProductUrl($product->id)[$i].'" target=_blank><figure  attr-id="'.$i.'"></a>';
                                    
                                        echo "<a att-id='link-3-".$i."' href='".$exam->getProductUrl($product->id)[$i]."' target=_blank><img class='myimg' att-id='img-".$i."' src='". $exam->getProductImages($product->id)[$i]."'></img></a>" ;
                                        echo "</figure></div></div>";
                                        
                                        echo '<a att-id="link-4-'.$i.'" href="'.$exam->getProductUrl($product->id)[$i].'" target=_blank><myTag class="titleText" att-id="x-'.$i.'" >'.$exam->getProductTitle($product->id)[$i].'</myTag></a></div>';
                                        
                                        echo '<div class="separete-line">';
                                    
                                        echo '<div class="processPrice" style="display:none" id="prc-'.$i.'">';
                                                    
                                                        $real_price=explode($symbol,$exam->getPriceRegular($product->id)[$i]);
                                                        $regularPrice=strip_tags($real_price[0]);
                                                        $real_price_two=explode($symbol,$exam->getPriceSale($product->id)[$i]);
                                                        $salePrice=strip_tags($real_price_two[0]);
                                    
                                                        $last_convert=str_replace("&nbsp;", '', $salePrice);
                                                        if($last_convert!="0") {
                                                            echo $last_convert;
                                                            $salesClass="price-num-sale";
                                                        }
                                                        else {
                                                            echo str_replace("&nbsp;", '', $regularPrice);
                                                             $salesClass="";
                                                        }
                                                        
                                                  echo '</div>';        
                                    
                                    
                                            echo '<div class="price-tag">
                                                    Pris :   
                                                  </div>';
                                            if($salesClass=="price-num-sale") { 
                                                echo '<div class="'.$salesClass.'" att-id="p-'.$i.'">
                                                    '.$exam->getPriceRegular($product->id)[$i].'
                                                  </div>
                                                  
                                                  <div class="sale-price-tag">
                                                    Salg :
                                                  </div>
                                                  
                                                  <div class="sale-price-num" att-id="s-'.$i.'">
                                                    '.$exam->getPriceSale($product->id)[$i].'
                                                  </div>';
                                            }
                                            else {
                                                 echo '<div class="price-num" att-id="p-'.$i.'">
                                                    '.$exam->getPriceRegular($product->id)[$i].'
                                                  </div>';
                                            }
                                                
                                                
                                    //    echo $exam->removeUrl($exam->getCrossProductId($product->id)[$i])[$i];
                                     //  echo $exam->getProductUnderCat($product->id);
                       // echo $exam->get_product_category_by_id($exam->getCatName($exam->getCrossProductId($product->id)[$i]));
                                
                                   
                                        echo '</div>';
                                    
                                        echo '<div class="separete-line-2">';
                                    
                                     $calculation=$exam->getProductCalcType($exam->getCrossProductId($product->id)[$i]);
                                                //calculation option
                                            if($calculation=="fix"){
                                                    echo '<select class="option-select-fix" fix-door="xls-'.$i.'">
                                                            <option value="4">4 mm</option>
                                                            <option value="5">5 mm</option>
                                                            <option value="6" selected>6 mm</option>
                                                            <option value="10">10 mm</option>
                                                        </select>';
                                            echo '<input type="number" min=1 size="10" name="mynumber" class="quantity-box-fix" id="qnt-'.$i.'">';
                                            }
                                
                                            else if($calculation=="fug") {
                                                    echo '<select class="option-select-fug" fug-door="xls-'.$i.'">
                                                            <option value="2">2 mm</option>
                                                            <option value="3" selected>3 mm</option>
                                                            <option value="4">4 mm</option>
                                                        </select>';
             echo '<select class="fugColor" map-id="'.$i.'">';
                
                 $selected=$exam->getProductAttr($exam->getCrossProductId($product->id)[$i])["color"];                          
                 $args = array( 'post_type' => 'product', 'posts_per_page' => 10, 'product_cat' => 'fuge', 'orderby' => 'rand' );
                 $looppa = new WP_Query( $args );
              
                while ( $looppa->have_posts() ) : $looppa->the_post(); 
                    //global $product;
                        $myProduct = wc_get_product( $looppa->post->ID ); 
                        
                        $regular_price= explode("&nbsp;",strip_tags(wc_price( $myProduct->get_regular_price())));
                        
                        $sale_price= explode("&nbsp;",strip_tags(wc_price( $myProduct->get_sale_price())));
                        
                        $img_url=wp_get_attachment_image_src( get_post_thumbnail_id( $looppa->post->ID ), 'shop_thumbnail' );
                        
                        $cart_url=get_permalink(get_option( 'woocommerce_cart_page_id' )).'?add-to-cart='.$looppa->post->ID;
                        
                        $product_url=get_permalink( $looppa->post->ID );
                                                
                        $title=$looppa->post->post_title;       
                                                
                        $color=$exam->getProductAttr($looppa->post->ID)["color"];
                       
                        if($color==$selected) {
                            $selectedx="selected";
                        }
                        else {
                            $selectedx="";
                        }
                       
                    echo "<option title='".$title."' cart_url='".$cart_url."' img='".$img_url[0]."' sale_price='".$sale_price[0]."' regular_price='".$regular_price[0]."' product_url='".$product_url."'".$selectedx." autocomplete='off' >".$color."</option>";
            
               endwhile;        
      
           wp_reset_query();
                                            
                                                
      echo     '</select>'; 
                                           
                                             echo '<input type="number" min=1 size="10" name="mynumber" class="quantity-box-fug" id="qnt-'.$i.'">';
                                                        
                                                        $size=$exam->getProductAttr($product->id)["Størrelse (mm)"];
                                                        $size_attr = implode("", (array)$size);
                                                        $real_size=explode("x",$size_attr);
                                                        $tick=$exam->getProductAttr($product->id)["Tykkelse (mm)"];
                                                        $calcType=$exam->getProductCalcType($product->id);
                                                        if($calcType=="mosaik") {
                                                            echo '<span class="size-width">1.5</span>';
                                                            echo '<span class="size-height">1.5</span>';
                                                            echo '<span class="tick">'.$tick.'</span>';
                                                            echo '<span class="getCalctype">mosaik</span>';
                                                        }
                                                        else {
                                                            echo '<span class="size-width">'.$real_size[0].'</span>';
                                                            echo '<span class="size-height">'.$real_size[1].'</span>';
                                                            echo '<span class="tick">'.$tick.'</span>';
                                                            
                                                        }
                                                    
                                                    
                                            }
                                    
                                                //calculation option end
                                    
                                            //separete line and add to cart button
                                    echo '<div class="descripton-cart">Du kan bruge vores Fuge beregning til at se hvor meget fuge du behøver.</div>';
                                    echo '<button class="button-class" cartUrl="'.$exam->getAddToCartUrl($product->id)[$i].'" map-id="'.$i.'">Tilføj til kurv</button>';
                                        echo '</div>';
                                                                            
                                    //end line in while
                                        $i++;
                                  echo '</div>';
                                echo '</div>';
                                                              
                                }
                            echo '<div id="cover" class="col-12 col-1 col-m-1">';
                                  
                                  
                                    echo '<div class="myBasket" style="visibility:hidden;">';
                                    echo '<img class="myImgBasket" src="'.plugins_url('css/basket.png', __FILE__).'"></img>';
                                    echo '<div class="separete-line-basket"></div>';
                                    echo '<div class="cartList"></div>';
                                    echo '<div class="summa"><span class="myCartList"></span></div>';
                                    echo '<div class="kurvButtonCover">';
                                        echo '<button class="continueButton">Blive ved</button>';
                                        echo '<button class="kurvButton">Kurv</button>';
                                    echo '</div>';
    
                            echo '</div></div>';
                            
                            ?>
                  
                 </div>
            </p>
            
            <a class="close-reveal-modal">&#215;</a>
            
		</div>
    

<?php    
  
    echo ob_get_clean();
}
add_action( 'woocommerce_add_to_cart', 'add_content_after_addtocart_button_func' );
    
    function add_content_after_addtocart_button_func() {
        $_SESSION['myKey'] = "loaded";
      
        
        
        
    }
    

include_once( plugin_dir_path( __FILE__ ) . 'includes/class-admin-reveal-popup.php');


add_action( 'woocommerce_single_product_summary', 'prepare');

function prepare() {
    
      global $product;
    
        $status = get_post_meta( $product->id, '_reveal_popup_status', true );
                
            if($status=="yes") {
                bootIt();
            }                    
                                    
}



/*
add_action( 'init', 'woocommerce_clear_cart_url' );
function woocommerce_clear_cart_url() {
  global $woocommerce;

        $woocommerce->cart->empty_cart();   
}


/**
 * Add a new dashboard widget.
 */
/*
function wpdocs_add_dashboard_widgets() {
    wp_add_dashboard_widget( 'dashboard_widget', 'Reveal Popup Status', 'dashboard_widget_function' );
}
add_action( 'wp_dashboard_setup', 'wpdocs_add_dashboard_widgets' );
 

function dashboard_widget_function( $post, $callback_args ) {
    esc_html_e( "Hello World, this is my first Dashboard Widget!", "textdomain" );

}
*/

add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
 
function woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
    
    
	ob_start();
	
	?>
	<span class="myCartList" href="" title="<?php _e('View your shopping cart', 'woothemes'); ?>"><?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?> - <?php echo $woocommerce->cart->get_cart_total(); ?></span>
	<?php
	
	$fragments['span.myCartList'] = ob_get_clean();
	
	return $fragments;
	
}
    include_once( plugin_dir_path( __FILE__ ) . 'includes/class-reveal-popup.php');
    function getCart() {
            
           $exam=new revealPopUpClass();
            global $woocommerce;
          
            $items = $woocommerce->cart->get_cart();
            
           foreach($items as $item => $values) { 
                $_product = $values['data']->post;
                echo "<div class='cartListCon'>";
                 
                    echo "<myTag class='titleTextCart'>".$_product->post_title.'</myTag></b>';
                    echo "<div class='coverQntPrc'><div class='qnt'> Quantity: ".$values['quantity']."</div>";
                    $price = get_post_meta($values['product_id'] , '_price', true);
                    echo "<div class='cartListPrice'>  Price: ".$price."</div></div>";
                   // echo "<div>". count($exam->removeUrl($values['product_id']))."</div>";
             
               echo "</div>";
            echo "<div class='cartLine'></div>";
        }            
         
  }

 
function my_ajax_request() {
 	global $woocommerce;
      
    if ( isset($_REQUEST) ) {
     
        $way = $_REQUEST['way'];
        
        if ( $way == 'item' ) {
          //  $fruit = $woocommerce->cart->get_cart_total(); 
            $way = getCart(); 
              // $fruit =$woocommerce->cart->cart_contents_count." items - ".$woocommerce->cart->get_cart_total();
             $items =$woocommerce->cart->cart_contents_count." items - ".$woocommerce->cart->get_cart_total();
      
        }
        $content = preg_replace('/<[^>]*>/', '', $way);
        echo $content."[sperate]".$items;
        
           
    }
     
   die();
}
 
add_action( 'wp_ajax_my_ajax_request', 'my_ajax_request' );

add_action( 'wp_ajax_nopriv_my_ajax_request', 'my_ajax_request' );

add_action('wp_head', 'myplugin_ajaxurl');

function myplugin_ajaxurl() {

   echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}

function calculator() {
    global $product;
        $exam=new revealPopUpClass();
        $calc=$exam->getProductCalcType($product->id);
        $_product = wc_get_product( $product->id );
         echo '<span class="currentPrice" style="display:none">'. $_product->get_price().'</span>';
            if($calc=="fug") {
                ob_start();
                ?>

                <script>
                    jQuery(document).ready(function($){ 
                        
                        $(".input-text.qty.text").change(function() {
                            $(".calcInfo").attr("style","display:none;");
                        });
                    
                        $("#qnt-square").keyup(function() {
                        
                            _calc_fug_calc();
                        });
                        
                         $("#qnt-width").keyup(function() {
                        
                            _calc_fug_calc();
                        });
                        
                        $("#qnt-height").keyup(function() {
                        
                            _calc_fug_calc();
                        });
                    
                        $("#qnt-tickness").keyup(function() {
                        
                            _calc_fug_calc();
                        });
                        
                        $("#qnt-mm").keyup(function() {
                        
                            _calc_fug_calc();
                        });
                        
                    
                    $("#qnt-width").change(function() {
                        _calc_fug_calc();
                    });
                    
                    $("#qnt-height").change(function() {
                        _calc_fug_calc();
                    });
                        
                     $("#qnt-tickness").change(function() {
                        _calc_fug_calc();
                    });
                        
                     $("#qnt-square").change(function() {
                        _calc_fug_calc();
                    });
                    
                     $("#qnt-mm").change(function() {
                        _calc_fug_calc();
                    });
                    
                    
                    _calc_fug_calc=function() {
                        
                        var _width=$("#qnt-width").val();
                        var _height=$("#qnt-height").val();
                        var _tickness=$("#qnt-tickness").val();
                        var _squaree=$("#qnt-square").val();
                        var _mm=$("#qnt-mm").val();
                        
                        if(parseInt(_width)>0 && parseInt(_height)>0 && parseFloat(_tickness)>0.1 && parseFloat(_mm)>0.1 && parseInt(_squaree)>0) {
                            var A=parseInt(_width);
                            var B=parseInt(_height);
                            var C=parseFloat(_tickness);
                            var _square=parseFloat(_squaree);
                            var _x=parseInt(_mm);
                            var up=A+B;
                            
                            var currentPrice=parseInt($(".currentPrice").html());
                            var down=A*B;
                          
                            var middle=up/down;
							
                            var calculation=middle*C*_x*1.6;
						
                            var last=calculation*_square;
                            var _calculation=Math.ceil(last/5);
                        
							$(".input-text.qty.text").val(_calculation);
                            
                            $(".calcQnt").html(_calculation);
                            var infoCalc=_calculation*currentPrice;
                            $(".calcTotal").html(infoCalc.toLocaleString());
                            $(".calcInfo").attr("style","display:true;");
                        }
                        else {
                            $(".calcInfo").attr("style","display:none;");
                        }
                    
                    }
                    });
                </script>
                <div class="calcCont">
                    <p></p>
                    <div class="calcText"><span style="color:white; font-weight: bold;">Auto Beregning</span></div>
                    <P>
                        <p></p>
                       
                        <div class="textCont">
                            <p>Bredde af Flisen:</p>
                            <p>Højden af Flisen:</p>
                              <div class="inputCont">
                                   <p><input type="number" min=1 size="10" name="mynumber" class="myField" id="qnt-width"></p>
                            <p><input type="number" min=1 size="10" name="mynumber" class="myField" id="qnt-height"></p>
                            </div>
                           
                        </div>
                        <div class="textCont">
                         <p>Tykkelse af Flisen:</p>
                            <p>Kvadratmeter af Flisen:</p>
                            <p>Fugebredde:</p>
                             <div class="inputCont">
                           
                            <p><input type="number" min=1 size="10" name="mynumber" class="myField" id="qnt-tickness"></p>
                            <p><input type="number" min=1 size="10" name="mynumber" class="myField" id="qnt-square"></p>
                            <p><input type="number" min=1 size="10" name="mynumber" class="myField" id="qnt-mm"></p>
                        </div>
                            <div class="calcInfo" style="display:none;">
                                Du behøver også <span class="calcQnt" style="color:red;">x</span> stykker, det koster i alt  : <span class="calcTotal" style="color:red;">y</span> :-
                        </div>
                        </div>
                        
                        <p></p>
                    </P>
                    
                </div>
                        
            <?php
			
            }
	if($calc=="fix") {
			 ob_start();
                ?>
				<div class="calcCont">
                    <p></p>
				<div class="calcText"><span style="color:white; font-weight: bold;">Auto Beregning</span></div>
                    <P>
                        <p></p>
                        <div class="textCont">
                            
                            <p>Firkant:</p>
                            <div class="inputCont">
                                <p><input type="number" min=1 size="10" name="mynumber" class="myField" id="qnt-square"></p>
                            </div>
                            
                        </div>
                        <div class="textCont">
                            <p>Tandspartel:</p>
						  <div class="inputCont">
                                <p><select name="mynumber" class="myField-select" id="qnt-mm">
                                    <option value="4">4 mm</option>
                                    <option value="5">5 mm</option>
                                    <option value="6">6 mm</option>
                                    <option value="10">10 mm</option>
                                    </select>
                                    </p>
                            </div>
                              <div class="calcInfo" style="display:none;">
                                Du behøver også  <span class="calcQnt" style="color:red;">x</span> stykker, det koster i alt  : <span class="calcTotal" style="color:red;">y</span> :-
                        </div>
                        </div>
                        <p></p>
					</P>
				</div>
			
			<script>
				jQuery(document).ready(function($){ 
                    
                    $(".input-text.qty.text").change(function() {
                            $(".calcInfo").attr("style","display:none;");
                        });
                    
                    $("#qnt-square").keyup(function() {
                         var _squaree=$("#qnt-square").val();
                        _calc_fix_calc();
                    });
                    
                     $("#qnt-square").change(function() {
                        _calc_fix_calc();
                    });
                    
                     $("#qnt-mm").change(function() {
                        _calc_fix_calc();
                    });
					
				_calc_fix_calc=function() {
			
                    var _squaree=$("#qnt-square").val();
                    var _mm=$("#qnt-mm").val();
                	if(parseInt(_squaree)>0 && parseFloat(_mm)>0.1) {
                        var currentPrice=parseInt($(".currentPrice").html());
						var _x=parseInt(_mm);
                		var _square=parseFloat(_squaree);
                		var calculation=_square*1.2*_x;
                		var _calculation=Math.ceil(calculation/20);
                		$(".input-text.qty.text").val(_calculation);
                        
                        $(".calcQnt").html(_calculation);
                            var infoCalc=_calculation*currentPrice;
                            $(".calcTotal").html(infoCalc.toLocaleString());
                            $(".calcInfo").attr("style","display:true;");
					}
                    else {
                         $(".calcInfo").attr("style","display:none;");
                    }
            }
		});
			</script>

		<?php
	}
    
}

 add_action( 'woocommerce_after_add_to_cart_button', 'calculator' , 5 );