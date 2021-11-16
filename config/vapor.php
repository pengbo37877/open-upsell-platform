<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Redirect "www" To The Root Domain
    |--------------------------------------------------------------------------
    |
    | When this option is enabled, Vapor will redirect requests to the "www"
    | subdomain to the application's root domain. When this option is not
    | enabled, Vapor redirects your root domain to the "www" subdomain.
    |
    */

    'redirect_to_root' => true,

    /*
    |--------------------------------------------------------------------------
    | Redirect robots.txt
    |--------------------------------------------------------------------------
    |
    | When this option is enabled, Vapor will redirect requests for your
    | application's "robots.txt" file to the location of the S3 asset
    | bucket or CloudFront's asset URL instead of serving directly.
    |
    */

    'redirect_robots_txt' => true,

    /*
    |--------------------------------------------------------------------------
    | Servable Assets
    |--------------------------------------------------------------------------
    |
    | Here you can configure list of public assets that should be servable
    | from your application's domain instead of only being servable via
    | the public S3 "asset" bucket or the AWS CloudFront CDN network.
    |
    */

    'serve_assets' => [
        'images/upsell-logo.svg',
        'images/how-1.png',
        'images/how-2.png',
        'images/how-3.png',
        'images/how-4.png',
        'images/how-5.png',
        'images/how-6.png',
        'images/smart-step1.png',
        'images/smart-step2-1.png',
        'images/smart-step2-2.png',
        'images/smart-step2-3.png',
        'images/smart-step3.png',
        'images/smart-step3-1.png',
        'images/smart-step4.png',
        'robots.txt',
        'favicon.ico',
        'js/app.js',
        'js/upsellrock.js',
        'css/app.css',
        'vendor/vapor-ui/app.css',
        'vendor/vapor-ui/app.js'
    ],

];
