<?php





return [
    'lab_price' => '₹350 / month',
    'lab_title' => 'OWASP 2026 AI Security Top 10 Lab Access',
    'billing_period' => '30 days',
    'access_days' => 30,
    'methods' => [
        'upi' => [
            'id' => 'upi',
            'name' => 'UPI / GPay / PhonePe / Paytm',
            'icon' => 'fa-mobile-screen-button',
            'details' => [
                'UPI ID' => 'secureworldz@okaxis',
                'Payee Name' => 'Secure Worldz Academy',
                'Instructions' => 'Scan or send payment to the UPI ID. Take a screenshot of the payment confirmation showing Transaction/UTR ID.'
            ]
        ],
        'bank' => [
            'id' => 'bank',
            'name' => 'Bank Transfer (IMPS / NEFT)',
            'icon' => 'fa-building-columns',
            'details' => [
                'Account Holder' => 'Secure Worldz Academy Lab',
                'Account Number' => '98765432101234',
                'IFSC Code' => 'HDFC0001234',
                'Bank Name' => 'HDFC Bank, Cyber City Branch',
                'Instructions' => 'Transfer the exact lab fee and upload the transfer receipt screenshot with UTR number visible.'
            ]
        ],
        'crypto' => [
            'id' => 'crypto',
            'name' => 'USDT / Crypto (TRC-20)',
            'icon' => 'fa-wallet',
            'details' => [
                'Network' => 'TRC-20 (Tron)',
                'Wallet Address' => 'TYDzmHqK9XnZb6Pq7x1V7g4K2mN8L3wJ5P',
                'Instructions' => 'Transfer USDT (TRC-20) and upload a screenshot of the transfer confirmation showing the TxHash.'
            ]
        ]
    ]
];
