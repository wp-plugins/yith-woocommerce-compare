<?php
/**
 * Woocommerce Compare page
 *
 * @author Your Inspiration Themes
 * @package YITH Woocommerce Compare
 * @version 1.0.2
 */

global $product;

// remove the style of woocommerce
if ( defined('WOOCOMMERCE_USE_CSS') && WOOCOMMERCE_USE_CSS ) wp_dequeue_style('woocommerce_frontend_styles');

$is_iframe = (bool)( isset( $_REQUEST['iframe'] ) && $_REQUEST['iframe'] );

wp_enqueue_script( 'jquery-fixedheadertable', YITH_WOOCOMPARE_URL . 'assets/js/jquery.dataTables.min.js', array('jquery'), '1.3', true );
wp_enqueue_script( 'jquery-fixedcolumns', YITH_WOOCOMPARE_URL . 'assets/js/FixedColumns.min.js', array('jquery', 'jquery-fixedheadertable'), '1.3', true );

$widths = array();
foreach( $products as $product ) $widths[] = '{ "sWidth": "205px", resizeable:true }';

?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if IE 9]>
<html id="ie9" class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if gt IE 9]>
<html class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if !IE]>
<html <?php language_attributes() ?>>
<![endif]-->

<!-- START HEAD -->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width" />
    <title><?php _e( 'Product Comparison', 'yit' ) ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" />
    <link rel="stylesheet" href="<?php echo $this->stylesheet_url() ?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo YITH_WOOCOMPARE_URL ?>assets/css/colorbox.css"/>
    <link rel="stylesheet" href="<?php echo YITH_WOOCOMPARE_URL ?>assets/css/jquery.dataTables.css"/>

    <?php wp_head() ?>
    
    <style type="text/css">
        body.loading {
            background: url("<?php echo YITH_WOOCOMPARE_URL ?>assets/images/colorbox/loading.gif") no-repeat scroll center center transparent;
        }
    </style>
</head>
<!-- END HEAD -->
<!-- START BODY -->
<body <?php body_class('woocommerce') ?>>

    <h1>
        <?php _e( 'Compare products', 'yit' ) ?>
        <?php if ( ! $is_iframe ) : ?><a class="close" href="#"><?php _e( 'Close window [X]', 'yit' ) ?></a><?php endif; ?>
    </h1>

    <table class="compare-list" cellpadding="0" cellspacing="0"<?php if ( empty( $products ) ) echo ' style="width:100%"' ?>>
        <thead>
        <tr>
            <th>&nbsp;</th>
            <?php foreach( $products as $i => $product ) : ?>
            <td></td>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>&nbsp;</th>
            <?php foreach( $products as $i => $product ) : ?>
                <td></td>
            <?php endforeach; ?>
        </tr>
        </tfoot>
        <tbody>

        <?php if ( empty( $products ) ) : ?>

            <tr class="no-products">
                <td><?php _e( 'No products added in the compare table.', 'yit' ) ?></td>
            </tr>

        <?php else : ?>
            <tr class="remove">
                <th>&nbsp;</th>
                <?php foreach( $products as $i => $product ) : $product_class = ( $i % 2 == 0 ? 'odd' : 'even' ) . ' product_' . $product->id ?>
                    <td class="<?php echo $product_class; ?>">
                        <a href="<?php echo add_query_arg( 'redirect', 'view', $this->remove_product_url( $product->id ) ) ?>" data-product_id="<?php echo $product->id; ?>"><?php _e( 'Remove', 'yit' ) ?> <span class="remove">x</span></a>
                    </td>
                <?php endforeach ?>
            </tr>

            <?php foreach ( $fields as $field => $name ) : ?>

                <tr class="<?php echo $field ?>">

                    <th>
                        <?php echo $name ?>
                        <?php if ( $field == 'image' ) echo '<div class="fixed-th"></div>'; ?>
                    </th>

                    <?php foreach( $products as $i => $product ) : $product_class = ( $i % 2 == 0 ? 'odd' : 'even' ) . ' product_' . $product->id ?>
                    <td class="<?php echo $product_class; ?>"><?php
                        switch( $field ) {

                            case 'image':
                                echo '<div class="image-wrap">' . wp_get_attachment_image( $product->fields[$field], 'yith-woocompare-image' ) . '</div>';
                                break;

                            case 'add-to-cart':
                                woocommerce_get_template( 'loop/add-to-cart.php' );
                                break;

                            default:
                                echo empty( $product->fields[$field] ) ? '&nbsp;' : $product->fields[$field];
                                break;
                        }
                        ?>
                    </td>
                    <?php endforeach ?>

                </tr>

            <?php endforeach; ?>

            <?php if ( $repeat_price == 'yes' && isset( $fields['price'] ) ) : ?>
                <tr class="price repeated">
                    <th><?php echo $fields['price'] ?></th>

                    <?php foreach( $products as $i => $product ) : $product_class = ( $i % 2 == 0 ? 'odd' : 'even' ) . ' product_' . $product->id ?>
                        <td class="<?php echo $product_class ?>"><?php echo $product->fields['price'] ?></td>
                    <?php endforeach; ?>

                </tr>
            <?php endif; ?>

            <?php if ( $repeat_add_to_cart == 'yes' && isset( $fields['add-to-cart'] ) ) : ?>
                <tr class="add-to-cart repeated">
                    <th><?php echo $fields['add-to-cart'] ?></th>

                    <?php foreach( $products as $i => $product ) : $product_class = ( $i % 2 == 0 ? 'odd' : 'even' ) . ' product_' . $product->id ?>
                        <td class="<?php echo $product_class ?>"><?php woocommerce_get_template( 'loop/add-to-cart.php' ); ?></td>
                    <?php endforeach; ?>

                </tr>
            <?php endif; ?>

        <?php endif; ?>

        </tbody>
    </table>

    <?php do_action('wp_print_footer_scripts'); ?>

    <script type="text/javascript">

        jQuery(document).ready(function($){
            <?php if ( $is_iframe ) : ?>$('a').attr('target', '_parent');<?php endif; ?>

            var oTable;
            $('body').on( 'yith_woocompare_render_table', function(){
                oTable = $('table.compare-list').dataTable( {
                    "sScrollX": "100%",
                    //"sScrollXInner": "150%",
                    "bScrollInfinite": true,
                    "bScrollCollapse": true,
                    "bPaginate": false,
                    "bSort": false,
                    "bInfo": false,
                    "bFilter": false,
                    "bAutoWidth": false
                } );
                new FixedColumns( oTable );
                $('<table class="compare-list" />').insertAfter( $('h1') ).hide();
            }).trigger('yith_woocompare_render_table');

            // add to cart
            var button_clicked;
            $(document).on('click', 'a.add_to_cart_button', function(){
                button_clicked = $(this);
                button_clicked.block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.ajax_loader_url + ') no-repeat center', backgroundSize: '16px 16px', opacity: 0.6}});
            });

            // remove add to cart button after added
            $('body').on('added_to_cart', function(){
                button_clicked.hide();
                <?php if ( $is_iframe ) : ?>$('a').attr('target', '_parent');<?php endif; ?>
            });

            // close window
            $(document).on( 'click', 'a.close', function(e){
                e.preventDefault();
                window.close();
            });

            $(window).on( 'yith_woocompare_product_removed', function(){
                oTable.fnDestroy(true);
                $('body').trigger('yith_woocompare_render_table');
            });

        });

    </script>

</body>
</html>