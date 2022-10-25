<?php

function livepeer_portl_create_stream($stream_name = null, $recording = false){
    
    $livepeer_wp_options = get_option('livepeer_wp_options');

    $channel_name = sanitize_title($stream_name);
    
    $livepeer_url = 'https://livepeer.studio/api/stream';

    $shop_name = $channel_name;

    if (!$shop_name) {
    
        return [
    
            'status' => false,
    
            'code' => 'has_no_channel_created'
    
        ];
    
    }

    $body = [
    
        "name" => sanitize_title($shop_name),
    
        "profiles" => [
            [
    
                "name" => "720p",
    
                "bitrate" => 2000000,
    
                "fps" => 30,
    
                "width" => 1280,
    
                "height" => 720,
            ],
            [
    
                "name" => "480p",
    
                "bitrate" => 1000000,
    
                "fps" => 30,
    
                "width" => 854,
    
                "height" => 480,
            ],
            [
                "name" => "360p",
    
                "bitrate" => 500000,
    
                "fps" => 30,
    
                "width" => 640,
    
                "height" => 360,
            ],
        ],
    ];

    if( $recording ){

        $body['record'] = true;

    }

    $api_response = wp_remote_post($livepeer_url, [

        "body" => wp_json_encode($body),
        
        "headers" => [
        
            "Content-Type" => "application/json",
        
            "Authorization" => "Bearer " . $livepeer_wp_options['LIVEPEER_API_TOKEN'],
        
        ],

    ]);


    $api_body = wp_remote_retrieve_body($api_response);

    if (empty($api_body)) {

        return [
        
            "status" => false,
        
            "code" => "error_creating_data_api"
        
        ];

    }
    $update_success = update_option('_stream_config', json_decode($api_body));
    //$update_success = update_user_meta($user_id, "_stream_cofig", json_decode($api_body) );


    if (!$update_success) {

        return [
        
            "status" => false,
        
            "code" => "error_creating_data"
        
        ];
    }
    
    flush_rewrite_rules(false);
    
    return json_decode($api_body);
}

function livepeer_portl_verify_stream($stream_id){
    
    $livepeer_wp_options = get_option('livepeer_wp_options');
    
    $api_response = wp_remote_get('https://livepeer.studio/api/stream/' . $stream_id, [
    
        "headers" => [
    
            "Content-Type" => "application/json",
    
            "Authorization" => "Bearer " . $livepeer_wp_options['LIVEPEER_API_TOKEN'],
    
        ],
    
    ]);

    $status_code = wp_remote_retrieve_response_code($api_response);
    
    return $status_code;
}

function livepeer_portl_created_stream_status_reponse($code, $response){

    if (!empty($code['code']) && $code['code'] === 'has_no_channel_created') {
        
        $response->set_status(403);
        
        $response->set_data([
        
            "status" => false,
        
            "code" => "has_no_channel_created",
        
            "message" => "Has no channel created",
        
        ]);
        
        return $response;
    }

    if (!empty($code['code']) && $code['code'] === 'error_creating_data_api') {
        
        $response->set_status(400);
        
        $response->set_data([
        
            "status" => false,
        
            "code" => "error_creating_data_api",
        
            "message" =>
        
                "Error when creating streming data could not be created",
        
        ]);
        
        return $response;
    }

    if (!empty($code['code']) && $code['code'] === 'error_creating_data_api') {

        
        $response->set_status(400);
        
        $response->set_data([
        
            "status" => false,
        
            "code" => "error_creating_data",
        
            "message" =>
        
                "Error when creating streming data could not be created",
        
        ]);

        return $response;

    }
}

function livepeer_portl_get_or_create_stream($stream_name = null, $recording = false) {

    $user_id = get_current_user_id();

    //$user_meta = get_user_meta($user_id, "_stream_cofig", true);
    $global_stream_config = get_option('_stream_config');

    if (empty($global_stream_config)) {

        $stream_created = livepeer_portl_create_stream($stream_name, $recording);
        
        return $stream_created;
    }

    $stream_id = $global_stream_config->id;

    $status_code = livepeer_portl_verify_stream($stream_id);

    if ($status_code === 200) {
        
        $stream_created = livepeer_portl_set_recording_stream_status($stream_id, $recording);
        //$global_stream_config = livepeer_portl_update_stream($stream_id, $stream_name, $recording);

        return $stream_created;

    } else {

        $stream_created = livepeer_portl_create_stream($stream_name);
        
        return $stream_created;
    }
}

function livepeer_portl_set_recording_stream_status($stream_id, $recording = false){

    $livepeer_wp_options = get_option('livepeer_wp_options');
    
    $response = wp_remote_request( 'https://livepeer.studio/api/stream/' . $stream_id .'/record',
    
        array(
    
            "headers" => [
    
                "Content-Type" => "application/json",
    
                "Authorization" => "Bearer " . $livepeer_wp_options['LIVEPEER_API_TOKEN'],
    
            ],
    
            'method' => 'PATCH',
    
            'body' => wp_json_encode(array('record' => $recording))
    
        )
    
    );
    
    $status_code = wp_remote_retrieve_response_code($response);

    return $status_code;
}

function livepeer_portl_update_stream($stream_id, $stream_name, $recording){

    $livepeer_wp_options = get_option('livepeer_wp_options');
    
    $packet = array(

        "headers" => [

            "Content-Type" => "application/json",

            "Authorization" => "Bearer " . $livepeer_wp_options['LIVEPEER_API_TOKEN'],

        ],

        'method' => 'PATCH',

        'body' => wp_json_encode(array(
            'record' => $recording ? true : false
        ))

    );

    $response = wp_remote_request( 'https://livepeer.studio/api/stream/'.$stream_id,
    
        $packet
    
    );
    
    $status_code = wp_remote_retrieve_response_code($response);

    return $status_code;
}

function livepeer_portl_get_recording_stream_status(){

    $user_id = get_current_user_id();
    
    $livepeer_wp_options = get_option('livepeer_wp_options');
    
    //$user_meta = get_user_meta($user_id, "_stream_cofig", true);
    $global_stream_config = get_option("_stream_config");

    $stream_id = $global_stream_config->id;
    
    $api_response = wp_remote_get('https://livepeer.studio/api/stream/' . $stream_id, [
    
        "headers" => [
    
            "Content-Type" => "application/json",
    
            "Authorization" => "Bearer " . $livepeer_wp_options['LIVEPEER_API_TOKEN'],
    
        ],
    
    ]);

    $response_body = wp_remote_retrieve_body($api_response);

    $response_body = json_decode($response_body);

    return $response_body->record;
}

function livepeer_portl_delete_stream(){

    $livepeer_wp_options = get_option('livepeer_wp_options');

    $global_stream_config = get_option("_stream_config");

    $stream_id = $global_stream_config->id;
    
    $packet = array(

        "headers" => [

            "Authorization" => "Bearer " . $livepeer_wp_options['LIVEPEER_API_TOKEN'],

        ],

        'method' => 'DELETE',


    );

    $response = wp_remote_request( 'https://livepeer.studio/api/stream/'.$stream_id,
    
        $packet
    
    );
    
    $status_code = wp_remote_retrieve_response_code($response);

    if( $status_code == '204' ){

        delete_option('_stream_config');

        return $status_code;
    }

    return $status_code;
}