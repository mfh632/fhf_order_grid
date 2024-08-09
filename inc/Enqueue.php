<?php

namespace FHF\OrderGrid;

class Enqueue extends BaseComponent
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }


    public function enqueue_scripts(){

        wp_enqueue_style('bootstrap_min_css', FOGRID_PLUGIN_URL . '/css/bootstrap.min.css', [], 5.3);
        wp_enqueue_style('fog_style', FOGRID_PLUGIN_URL . '/css/style.css');

        wp_enqueue_script('jquery');
        wp_enqueue_script('bootstrap_bundle_min', FOGRID_PLUGIN_URL . '/js/bootstrap.bundle.min.js');
        wp_enqueue_script('tableToExcel', FOGRID_PLUGIN_URL . '/js/tableToExcel.js');
    }

}