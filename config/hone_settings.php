<?php

return [
    
    // the location of the ADMIN section of the site
    'admin_subdirectory' => 'admin', 
    
    // the location of the MEMBER section of the site
    'member_subdirectory' => 'account', 
    
    // date format
    'date_format' => 'j M Y', 
    
    // time format
    'time_format' => 'H:ia', 
    
    // date time format
    'date_time_format' => 'd/m/Y H:ia', 
    
    // In minutes, how long should we cache models for?
    'cache_for' => 60*8,  // 8 hours, a working day
    
    // icons
    'positive_icon' => '<i class="fas fa-check text-success"></i>', 
    'negative_icon' => '<i class="fas fa-times text-danger"></i>', 
    
    // white list of mime types for file uploads
    // see: https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types
    'mime_types' => [
        'image/png', 
        'image/jpeg', 
        'application/pdf', 
    ], 
    
    // in MB, the maximum allowed file size
    'upload_max_filesize' => 2, 
    
    // menu quick select config 
    'menu_populator_quick_select' => [
        'Page' => [
            'namespace' => 'Purcell\Hone\Models', 
            'scopes' => ['published', 'get'], 
            'route_name' => 'frontend.page', 
            'route_key_name' => 'uri', 
            'label_column' => 'title', 
            'extras' => [
            ], 
        ], 
        'Custom' => [
            'extras' => [
                //'/page/one' => 'Page one'
            ]
        ], 
    ], 
    
];
