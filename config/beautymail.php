<?php

return [

    // These CSS rules will be applied after the regular template CSS

    'css' => [
        '.confirm-button a { font-size: 16px; }',
        'h1 { font-size: 22px; margin-top: 5px; text-align: center; padding: 0 15px; }',
        'h2 { font-size: 20px; text-align:center; color: #000000 }',
        '.img-container {
            margin: 0 auto;
            position: relative;
            max-width: 525px;
            height: 310px;
            padding-bottom: 20px;
        }',
        '@media (max-width: 570px) {
            .img-container {
                height: 185px !important;
            }
        }',
        '.img-container img {  max-height: 100%;
            max-width: 100%;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
            border: 2px solid rgb(44,44,44);
        }',
        'p { font-size: 16px; text-align: center; padding: 0 20px; }',
        '.bottom-notice { background-color: #999999; padding: 25px 0; margin: 0; }',
        '.bottom-notice p { color: #EEEEEE; font-size: 12px; }',
        'hr { padding: 0; margin: 0; }',
        '.devicewidthinner { margin: 0 auto; width: 100%; }',
        '.unsubscribe { color: #EEEEEE; }'
    ],

    'colors' => [
        'highlight' => '#fffade',
        'button'    => '#993fff',
    ],

    'view' => [
        'senderName'  => 'Codery',
        'reminder'    => null,
        'unsubscribe' => null,
        'address'     => null,
        'logo'        => [
            'path'   => '%PUBLIC%/images/logo.svg',
            'width'  => '160',
            'height' => '65',
        ],
        'twitter'  => null,
        'facebook' => null,
        'flickr'   => null,
    ],

];
