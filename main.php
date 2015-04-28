<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * Plugin Name: Quijaua - Editais
 * Description: Gerenciamento de editais
 * Version: 0.0.1
 * Author: Eduardo Alencar de Oliveira
 * License: GPL3
 * Depends: Meta Box
 */
if ( ! defined( 'QUIJAUAEDITAIS_URL' ) ) {
    define( 'QUIJAUAEDITAIS_URL', plugin_dir_url( __FILE__ ) );
}

define( 'QUIJAUAEDITAIS_JS_URL', trailingslashit( QUIJAUAEDITAIS_URL . 'assets/scripts' ) );
define( 'QUIJAUAEDITAIS_CSS_URL', trailingslashit( QUIJAUAEDITAIS_URL . 'assets/styles' ) );


function quijauaeditais_cpts() {

    $labels = array(
        'name'                  =>   __( 'Editais', '' ),
        'singular_name'         =>   __( 'Edital', '' ),
        'add_new_item'          =>   __( 'Adicionar edital', '' ),
        'all_items'             =>   __( 'Todos editais', '' ),
        'edit_item'             =>   __( 'Editar edital', '' ),
        'new_item'              =>   __( 'Novo edital', '' ),
        'view_item'             =>   __( 'Ver edital', '' ),
        'not_found'             =>   __( 'Nenhum edital cadastrada', '' ),
        'not_found_in_trash'    =>   __( 'Nenhum edital na lixeira', '' )
    );

    $supports = array('title');

    $args = array(
        'label'         =>   __( 'Editais', '' ),
        'labels'        =>   $labels,
        'description'   =>   __( 'Editais', '' ),
        'public'        =>   true,
        'show_in_menu'  =>   true,
        'menu_icon'     =>   'dashicons-media-text',
        'has_archive'   =>   true,
        'rewrite'       =>   'editais',
        'supports'      =>   $supports
    );

    register_post_type( 'quijauaeditais_edt', $args );

}

function quijauaeditais_metaboxes() {

    $prefix = 'edt_';

    $meta_boxes[] = array(
        'id'       => 'edital-details',
        'title'    => 'Detalhes do edital',
        'pages'    => array( 'quijauaeditais_edt'),
        'context'  => 'normal',
        'priority' => 'high',

        'fields' => array(

            array(
                'name'  => 'Orgão/Instituição/Organização',
                'desc'  => '',
                'id'    => $prefix . 'organization',
                'type'  => 'text',
                'std'   => '',
                'class' => 'custom-class',
                'clone' => false,
            ),

            array(
                'name'  => 'Periodo de inscrição',
                'desc'  => '',
                'id'    => $prefix . 'period',
                'type'  => 'text',
                'std'   => '',
                'class' => 'custom-class',
                'clone' => false,
            ),

            array(
                'name'  => 'Periodo de inscrição - inicial',
                'desc'  => '',
                'id'    => $prefix . 'period_start',
                'type'  => 'text',
                'std'   => '',
                'class' => 'custom-class',
                'clone' => false,
            ),

            array(
                'name'  => 'Periodo de inscrição - final',
                'desc'  => '',
                'id'    => $prefix . 'period_finish',
                'type'  => 'text',
                'std'   => '',
                'class' => 'custom-class',
                'clone' => false,
            ),

            array(
                'name'  => 'Link para informações',
                'desc'  => 'http://www.exemplo.com.br',
                'id'    => $prefix . 'link',
                'type'  => 'url',
                'std'   => '',
                'class' => 'custom-class',
                'clone' => false,
            ),
        )
    );
    return $meta_boxes;
}

function quijauaeditais_shortcode() {
    ob_start();
?>
    <h1>EDITAIS E OPORTUNIDADES</h1>
    <ul>
    <?php

    $args = array( 'posts_per_page' => 5, 'post_type'=> 'quijauaeditais_edt', 'post_status' => 'publish');

    $editais_posts = get_posts($args);

    foreach($editais_posts as $edital_post)
    {

        setup_postdata($edital_post);
        $externalLink = get_post_meta($more_post->ID, 'edt_link', true);
        $tooltipMarkup = '<p>ÓRGÃO/INSTITUIÇÃO/ORGANIZAÇÃO: ' . get_post_meta($edital_post->ID, 'edt_organization', true);
        $tooltipMarkup .= '<br />PERIODO INSCRIÇÃO: ' . get_post_meta($edital_post->ID, 'edt_period', true);
        $tooltipMarkup .= '<br />LINK PARA INFORMAÇÕES: <a href="' . $externalLink. '">'.$externalLink.'<a/>';
        $tooltipMarkup .= '</p>';
        echo '<li class="tooltip" title="'.htmlentities($tooltipMarkup).'"><a href="'.get_post_meta($edital_post->ID, 'edt_link', true).'">'.$edital_post->post_title.'</a></li>';
    }
    wp_reset_postdata();

    ?>
    </ul>
    <a href="?page_id=<page_id>">Ver mais</a>

    <div id="more-editals-list" style="display: none;">
        <?php
            $args = array( 'offset' => 5, 'post_type'=> 'quijauaeditais_edt', 'post_status' => 'publish');
            $more_posts = get_posts($args);
        foreach($more_posts as $more_post)
        {
            setup_postdata($more_post);
            $markup = '<div class="more-edital-item"><p>ÓRGÃO/INSTITUIÇÃO/ORGANIZAÇÃO: ' . get_post_meta($more_post->ID, 'edt_organization', true);
            $markup .= '<br />PERIODO INSCRIÇÃO: ' . get_post_meta($more_post->ID, 'edt_period', true);
            $markup .= '<br />LINK PARA INFORMAÇÕES: <a href="' . get_post_meta($more_post->ID, 'edt_link', true). '">'.get_post_meta($more_post->ID, 'edt_link', true).'<a/>';
            $markup .= '</p></div>';
            echo $markup;
        }
        wp_reset_postdata();


        ?>
    </div>
    <form method="post" id="frm-edital">
        <input type="text" name="title" id="title" data-rule-required="true" data-msg-required="Campo NOME DO EDITAL é obrigatório" placeholder="NOME DO EDITAL" />
        <br />
        <input type="text" name="edt_organization" id="edt_organization" data-rule-required="true" data-msg-required="Campo ÓRGÃO/INSTITUIÇÃO/ORGANIZAÇÃO é obrigatório" placeholder="ÓRGÃO/INSTITUIÇÃO/ORGANIZAÇÃO" />
        <input type="text" name="edt_period" id="edt_period" data-rule-required="true" data-msg-required="Campo PERIODO DA INSCRIÇÃO é obrigatório" placeholder="PERIODO DA INSCRIÇÃO" />
        <input type="text" name="edt_link" id="edt_link" data-rule-required="true" data-msg-required="Campo LINK PARA INFORMAÇÕES é obrigatório" data-rule-url="true" data-msg-url="Digite uma URL válida" placeholder="LINK PARA INFORMAÇÕES" />
        <br />
        <input type="submit" value="ENVIAR" id="btn-send-frm-edital" />
    </form>

<?php
    return ob_get_clean();
}

function quijauaeditais_change_default_title() {
    $screen = get_current_screen();

    if  ( 'quijauaeditais_edt' === $screen->post_type ) {
        $title = 'NOME DO EDITAL';
    }
    return $title;
}

function quijauaeditais_scripts() {
    global $post;
    if ( has_shortcode( $post->post_content, 'editais' ) ) {
        wp_enqueue_style('quijauaedital-main', QUIJAUAEDITAIS_CSS_URL . 'main.css');
        wp_enqueue_style('quijauaedital-sweetalert-css', QUIJAUAEDITAIS_CSS_URL . 'sweet-alert.css');
        wp_enqueue_script('quijauaedital-plugins', QUIJAUAEDITAIS_JS_URL . 'plugins.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('quijauaedital-sweetalert', QUIJAUAEDITAIS_JS_URL . 'sweetalert/lib/sweet-alert.min.js', array(), '1.0.0', true);
        wp_enqueue_script('quijauaedital-tooltipster', QUIJAUAEDITAIS_JS_URL . 'tooltipster/js/jquery.tooltipster.min.js', array(), '1.0.0', true);

        wp_enqueue_script('quijauaedital-main', QUIJAUAEDITAIS_JS_URL . 'main.js', array('jquery'), '1.0.0', true);


        wp_localize_script('quijauaedital-main', 'quijauaeditais_ajax',
            array(
                'ajax_url' => admin_url('admin-ajax.php')
            )
        );
    }
}

function quijauaeditais_save_edital_callback() {

    // Create post object
    $edital_post = array(
        'post_title'    => sanitize_text_field($_POST['title']),
        'post_status'   => 'draft',
        'post_type'     => 'quijauaeditais_edt',
    );

    $edital_post_id = wp_insert_post( $edital_post );

    if( $edital_post_id ) { 

        $period = $_POST['edt_period'];
        $edt_period_start = implode("-",array_reverse(explode("/",substr($period, 0, strpos($period,'-') -1))));
        $edt_period_finish = implode("-",array_reverse(explode("/",substr($period, strpos($period,'-') + 1 ))));

        add_post_meta( $edital_post_id, 'edt_organization', sanitize_text_field($_POST['edt_organization']) );
        add_post_meta( $edital_post_id, 'edt_period', $period );
        add_post_meta( $edital_post_id, 'edt_period_start', $edt_period_start );
        add_post_meta( $edital_post_id, 'edt_period_finish', $edt_period_finish );
        add_post_meta( $edital_post_id, 'edt_link', $_POST['edt_link'] );

        $result = array(
            'status' => 1
        );
        echo json_encode($result);
        wp_die();

    }

    $result = array(
        'status' => 0
    );
    echo json_encode($result);
    wp_die();

}

function quijauaeditais_init() {
    quijauaeditais_cpts();

}

// Actions
add_action( 'init', 'quijauaeditais_init' );
add_shortcode( 'editais', 'quijauaeditais_shortcode' );
add_action( 'wp_enqueue_scripts', 'quijauaeditais_scripts' );
add_action( 'wp_ajax_quijauaeditais_save_edital', 'quijauaeditais_save_edital_callback' );
add_action( 'wp_ajax_nopriv_quijauaeditais_save_edital', 'quijauaeditais_save_edital_callback' );

// Filters
add_filter( 'rwmb_meta_boxes', 'quijauaeditais_metaboxes' );
add_filter( 'enter_title_here', 'quijauaeditais_change_default_title' );
