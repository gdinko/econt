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
     * Default Econt test endpoint
     */
    'test-endpoint' => rtrim(env('ECONT_API_TEST_ENDPOINT', 'https://demo.econt.com/ee/services/'), '/'),

    /**
     * Default Econt production endpoint
     */
    'production-endpoint' => rtrim(env('ECONT_API_PRODUCTION_ENDPOINT', 'https://ee.econt.com/services/'), '/'),

    /**
     * Set Request timeout
     */
    'timeout' => env('ECONT_API_TIMEOUT', 5),
];
