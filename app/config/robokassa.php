<?php

return [
    'isTest'        => env('ROBOKASSA_IS_TEST', false),
    'login'         => env('ROBOKASSA_MERCHANT_LOGIN'),
    'password1'     => env('ROBOKASSA_PASSWORD1'),
    'password2'     => env('ROBOKASSA_PASSWORD2'),
    'testPassword1' => env('ROBOKASSA_TEST_PASSWORD1'),
    'testPassword2' => env('ROBOKASSA_TEST_PASSWORD2'),
    'taxSystem'     => env('ROBOKASSA_TAX_SYSTEM', 'patent'),
];