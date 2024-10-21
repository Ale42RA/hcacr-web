<?php

function tower_manager_register_rest_api() {
    register_rest_route('tower-manager/v1', '/update', [
        'methods' => 'POST',
        'callback' => 'tower_manager_update_data',
    ]);
}

function tower_manager_update_data(WP_REST_Request $request) {
    try {
        DB_Handler::insert_data();
        return new WP_REST_Response('Data updated successfully', 200);
    } catch (Exception $e) {
        return new WP_REST_Response($e->getMessage(), 500);
    }
}


//TODO ALL THIS IS NOT WORKING 