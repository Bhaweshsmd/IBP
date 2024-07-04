<?php

declare(strict_types=1);

return [
    /*
     * ------------------------------------------------------------------------
     * Default Firebase project
     * ------------------------------------------------------------------------
     */
    'default' => env('FIREBASE_PROJECT', 'app'),

    /*
     * ------------------------------------------------------------------------
     * Firebase project configurations
     * ------------------------------------------------------------------------
     */
    'projects' => [
        'app' => [
            /*
             * ------------------------------------------------------------------------
             * Credentials / Service Account
             * ------------------------------------------------------------------------
             *
             * In order to access a Firebase project and its related services using a
             * server SDK, requests must be authenticated. For server-to-server
             * communication this is done with a Service Account.
             *
             * If you don't already have generated a Service Account, you can do so by
             * following the instructions from the official documentation pages at
             *
             * https://firebase.google.com/docs/admin/setup#initialize_the_sdk
             *
             * Once you have downloaded the Service Account JSON file, you can use it
             * to configure the package.
             *
             * If you don't provide credentials, the Firebase Admin SDK will try to
             * auto-discover them
             *
             * - by checking the environment variable FIREBASE_CREDENTIALS
             * - by checking the environment variable GOOGLE_APPLICATION_CREDENTIALS
             * - by trying to find Google's well known file
             * - by checking if the application is running on GCE/GCP
             *
             * If no credentials file can be found, an exception will be thrown the
             * first time you try to access a component of the Firebase Admin SDK.
             *
             */
            'credentials' => [
                    
                    // "type"=>"service_account",
                    // "project_id"=> "ibp-app-eec45",
                    // "private_key_id"=> "b911651ac206bda4b14366a5f0f93e6d8ca21e51",
                    // "private_key"=> "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC+stRYN4vTdudG\n1FVYdXLvHjbh0gXDHCAVdcEuptocD3igW1nrpEkzixpAW2AUrD0vxNQkq1ZeAYUQ\nMnaYlpPpxdesBpLug+wlTF+u/EZBK+M7zsQRZTiKEn7wRbJ7ADklRzwLHqKLqYqV\nkjaNiThli8vQtPMWoXDTzv8WSkcRpJNYT+72NshtZJLZpyyqsW0br4v1mKW8ChEZ\n5TnBvKNQEvNNIgJEq95X7LuYqZpetvEH8MfmGT5+5kOmRDdFtxdRoWMwatPasPP1\nAScRcMgHrYz/SxXs9/0Svt8L97V/gqfYZ11YbAWlkKMZeQVhfag5possPug8cMja\nLFdFd1rFAgMBAAECggEAG1Wi3mpH3q3ihOKWwTFEokXgdAoYzVjQuILCiB9ekDsf\nfUza3M4VuA+uWZ8/bHcr5hb+mar9nEf3fNTe7wxWc2HqgjwG/61lQgtqg7ihkWHr\nFn2S2TN7IT95dpNm+/6SMme9kZJ4ljBdLwdgTnVm4hdhxa7KUWoetB2trtJC0aJX\nlqcpJlUdwKZN3dnGIDLNGK0t2Se2iVGckHJjlt/q2XANP+pj2iGB/PRtyH7CnOCX\neD3LQ4G1dy6rvNNNHa7p6q1WygJ3a41Y/Uqo6dr1qihfh6uR5rG/qLj/icZ8yfiX\ndVUT5462FK61IAzLIocM/whjxPHX0GMfqz7pi6oN2QKBgQD9JGXGwyLCJ4p8rgeq\n5MiaAKqu2NzDBm2iejo4vacPWfc0OT8q9mgk56beK9hE9YDxZBk1BIUT5Ez2ljia\nP1M0n/NsswKTNM2r4RAGzwQeWlSeT+d0aYzon2FesBCXhhw9+4VRWEI+2zP3eRVV\neOzcbsZD9rvv4uy6k/WWh5whawKBgQDA2fbqhB3jH/iKJfbuICQWphpS1E/CojQ5\nzw872DkRHhbElVcaHK8zZ0TBOM6h/1Vh/rfry16ePBZOOJEzlu5Vbwr1lME3fbLC\nXWvcY6y3fYwee6LPuX6jBG8ZReIIbt7hY8NT+0uv6nFVDikQc5juEpBo2v4uMTZx\nL9oTkf4QjwKBgQC9GbyBz3GesiUM4IBP1BpamNboSI4ZjirGLiJiEqLCoAU/2Ofs\nMyg3MWmBHCWx1efd61W9OkQjSO+JYUylRVrlu/r/H3Zz+wUNOdJcE7dS6U2++ZfA\nabzeZXk6X7H1TzS3xLWhv5m1FNDsNGKQeAYQ4Rtw19cq60zGUBMswN8MQwKBgQCt\nSe42crsMYKkLMev9s0HaXC0hKof1I518KJPOuY8l03yv7mWTIB1KOkwst6ftJyuI\n38JSja6azvFYHjpTOhc1C4+0dpNcEoXzYtDN+36ybozew0fcEhk50H0oH4RpAX8i\n1mso5pDvJOHrhMrfbpAHtQSwQ7/MsAn0kQQLO88o/wKBgDAl7nHSYrFzsPgSe+re\n1WKufX09P6pecY5bsUhEoovlGegD2OWbEvDVEVMIckw94eA8m2YDSLM/AhhgtsFx\nmiGqj6vY84UVysf4eus9cnXh5Qguy8WJqevnmH8JX1VpVLy0tzlMx+yIa+fnYKmF\nzN9gUBtjpO4ACToLe0HxU8PN\n-----END PRIVATE KEY-----\n",
                    // "client_email"=> "firebase-adminsdk-rc6h0@ibp-app-eec45.iam.gserviceaccount.com",
                    // "client_id"=> "101033661852299759360",
                    // "auth_uri"=> "https://accounts.google.com/o/oauth2/auth",
                    // "token_uri"=> "https://oauth2.googleapis.com/token",
                    // "auth_provider_x509_cert_url"=> "https://www.googleapis.com/oauth2/v1/certs",
                    // "client_x509_cert_url"=> "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-rc6h0%40ibp-app-eec45.iam.gserviceaccount.com",
                    // "universe_domain"=> "googleapis.com",
                
                'file' => env('FIREBASE_CREDENTIALS', env('GOOGLE_APPLICATION_CREDENTIALS')),

                /*
                 * If you want to prevent the auto discovery of credentials, set the
                 * following parameter to false. If you disable it, you must
                 * provide a credentials file.
                 */
                'auto_discovery' => true,
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Auth Component
             * ------------------------------------------------------------------------
             */

            'auth' => [
                'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Realtime Database
             * ------------------------------------------------------------------------
             */

            'database' => [
                /*
                 * In most of the cases the project ID defined in the credentials file
                 * determines the URL of your project's Realtime Database. If the
                 * connection to the Realtime Database fails, you can override
                 * its URL with the value you see at
                 *
                 * https://console.firebase.google.com/u/1/project/_/database
                 *
                 * Please make sure that you use a full URL like, for example,
                 * https://my-project-id.firebaseio.com
                 */
                'url' => env('FIREBASE_DATABASE_URL'),

                /*
                 * As a best practice, a service should have access to only the resources it needs.
                 * To get more fine-grained control over the resources a Firebase app instance can access,
                 * use a unique identifier in your Security Rules to represent your service.
                 *
                 * https://firebase.google.com/docs/database/admin/start#authenticate-with-limited-privileges
                 */
                // 'auth_variable_override' => [
                //     'uid' => 'my-service-worker'
                // ],
            ],

            'dynamic_links' => [
                /*
                 * Dynamic links can be built with any URL prefix registered on
                 *
                 * https://console.firebase.google.com/u/1/project/_/durablelinks/links/
                 *
                 * You can define one of those domains as the default for new Dynamic
                 * Links created within your project.
                 *
                 * The value must be a valid domain, for example,
                 * https://example.page.link
                 */
                'default_domain' => env('FIREBASE_DYNAMIC_LINKS_DEFAULT_DOMAIN'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Cloud Storage
             * ------------------------------------------------------------------------
             */

            'storage' => [
                /*
                 * Your project's default storage bucket usually uses the project ID
                 * as its name. If you have multiple storage buckets and want to
                 * use another one as the default for your application, you can
                 * override it here.
                 */

                'default_bucket' => env('FIREBASE_STORAGE_DEFAULT_BUCKET'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Caching
             * ------------------------------------------------------------------------
             *
             * The Firebase Admin SDK can cache some data returned from the Firebase
             * API, for example Google's public keys used to verify ID tokens.
             *
             */

            'cache_store' => env('FIREBASE_CACHE_STORE', 'file'),

            /*
             * ------------------------------------------------------------------------
             * Logging
             * ------------------------------------------------------------------------
             *
             * Enable logging of HTTP interaction for insights and/or debugging.
             *
             * Log channels are defined in config/logging.php
             *
             * Successful HTTP messages are logged with the log level 'info'.
             * Failed HTTP messages are logged with the the log level 'notice'.
             *
             * Note: Using the same channel for simple and debug logs will result in
             * two entries per request and response.
             */

            'logging' => [
                'http_log_channel' => env('FIREBASE_HTTP_LOG_CHANNEL'),
                'http_debug_log_channel' => env('FIREBASE_HTTP_DEBUG_LOG_CHANNEL'),
            ],

            /*
             * ------------------------------------------------------------------------
             * HTTP Client Options
             * ------------------------------------------------------------------------
             *
             * Behavior of the HTTP Client performing the API requests
             */
            'http_client_options' => [
                /*
                 * Use a proxy that all API requests should be passed through.
                 * (default: none)
                 */
                'proxy' => env('FIREBASE_HTTP_CLIENT_PROXY'),

                /*
                 * Set the maximum amount of seconds (float) that can pass before
                 * a request is considered timed out
                 * (default: indefinitely)
                 */
                'timeout' => env('FIREBASE_HTTP_CLIENT_TIMEOUT'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Debug (deprecated)
             * ------------------------------------------------------------------------
             *
             * Enable debugging of HTTP requests made directly from the SDK.
             */
            'debug' => env('FIREBASE_ENABLE_DEBUG', false),
        ],
    ],
];
