<?php
return [
      'defaults' => [
          'guard' => 'api',
          'passwords' => 'users123',
      ],

      'guards' => [
          'api' => [
              'driver' => 'passport',
              'provider' => 'users',
          ],
      ],

      'providers' => [
          'users' => [
              'driver' => 'eloquent',
              'model' => \App\Models\User::class
          ]
      ]
  ];
