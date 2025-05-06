<?php

return [

    'renew' => [
        'default_method' => 'stripe',
        'bonus_enabled' => true
    ],

    'taxes' => [
        'id' => null,
        'name' => 'VAT',
        'description' => 'Peru VAT 18%', 
        'jurisdiction' => 'PE',
        'percentage' => 18.0,
        'exclusive' => true,
        'enabled' => false,
    ],

    'retry_attempts' => 3,
    'grace_period_days' => 5,

    'currency' => [
        'USD' => [
            'code' => 'USD',
            'name' => 'United States',
        ],
        'EUR' => [
            'code' => 'EUR',
            'name' => 'Eurozone',
        ],
    ],

    'period' => [
        'trial' => [
            'interval' => 30,
            'unit' => 'days',
            'name' => 'trial',
        ],
        'daily' => [
            'interval' => 1,
            'unit' => 'days',
            'name' => 'daily',
        ],
        'weekly' => [
            'interval' => 1,
            'unit' => 'weeks',
            'name' => 'weekly',
        ],
        'biweekly' => [
            'interval' => 2,
            'unit' => 'weeks',
            'name' => 'biweekly',
        ],
        'monthly' => [
            'interval' => 1,
            'unit' => 'months',
            'name' => 'monthly',
        ],
        'quarterly' => [
            'interval' => 3,
            'unit' => 'months',
            'name' => 'quarterly',
        ],
        'semiannual' => [
            'interval' => 6,
            'unit' => 'months',
            'name' => 'semiannual',
        ],
        'annual' => [
            'interval' => 1,
            'unit' => 'years',
            'name' => 'annual',
        ],
    ],

    'methods' => [
        'stripe' => [
            'key' => 'stripe',
            'name' => 'Credit Card (Stripe)',
            'icon' => 'mdi-credit-card-outline',
            'enable' => true
        ],
        'paypal' => [
            'key' => 'paypal',
            'name' => 'PayPal',
            'icon' => 'mdi-contactless-payment',
            'enable' => false
        ],
        'p2p' => [
            'key' => 'p2p',
            'name' => 'Peer 2 Peer',
            'icon' => 'mdi-cash-register',
            'enable' => true
        ],
        'bank_transfer' => [
            'key' => 'bank_transfer',
            'name' => 'Bank Transfer',
            'icon' => 'mdi-bank-transfer',
            'enable' => false
        ],
        'wallet' => [
            'key' => 'wallet',
            'name' => 'Wallet',
            'icon' => 'mdi-wallet-outline',
            'enable' => false
        ],
        'crypto' => [
            'key' => 'crypto',
            'name' => 'Cryptocurrency',
            'icon' => 'mdi-currency-btc',
            'enable' => false
        ],
    ],


    'status' => [
        'pending' => [
            'name' => 'pending',
            'message' => 'The payment has been initiated but not yet confirmed.',
        ],
        'processing' => [
            'name' => 'processing',
            'message' => 'The payment is currently being processed.',
        ],
        'successful' => [
            'name' => 'successful',
            'message' => 'The payment was completed successfully.',
        ],
        'failed' => [
            'name' => 'failed',
            'message' => 'The payment failed due to an error (insufficient funds, network issue, etc.).',
        ],
        'cancelled' => [
            'name' => 'cancelled',
            'message' => 'The payment was canceled before processing.',
        ],
        'refunded' => [
            'name' => 'refunded',
            'message' => 'The payment was fully refunded to the user.',
        ],
        'partially_refunded' => [
            'name' => 'partially_refunded',
            'message' => 'Only a portion of the payment was refunded.',
        ],
        'disputed' => [
            'name' => 'disputed',
            'message' => 'The payment is under dispute (e.g., suspected fraud).',
        ],
        'chargeback' => [
            'name' => 'chargeback',
            'message' => 'The payment was reversed by the bank or payment processor.',
        ],
        'expired' => [
            'name' => 'expired',
            'message' => 'The payment was not completed before the expiration deadline.',
        ],
        'on_hold' => [
            'name' => 'on_hold',
            'message' => 'The payment is temporarily on hold for manual review or fraud prevention.',
        ],
    ],

];
