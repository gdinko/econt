<?php

/*
 * You can place your custom package configuration in here.
 */
return [

    /**
     * Configure econt endpoint (test|production)
     */
    'env' => env('ECONT_ENV', 'test'),

    /**
     * Set Econt API username
     */
    'user' => env('ECONT_API_USER', 'iasp-dev'),

    /**
     * Set Econt API password
     */
    'pass' => env('ECONT_API_PASS', 'iasp-dev'),

    /**
     * Default Econt test base url
     */
    'test-base-url' => rtrim(env('ECONT_API_TEST_BASE_URI', 'https://demo.econt.com/ee/services/'), '/'),

    /**
     * Default Econt production base url
     */
    'production-base-url' => rtrim(env('ECONT_API_PRODUCTION_BASE_URI', 'https://ee.econt.com/services/'), '/'),

    /**
     * Set Request timeout
     */
    'timeout' => env('ECONT_API_TIMEOUT', 5),
];
