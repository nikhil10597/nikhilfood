<?php

return [
  'gcm' => [
      'priority' => 'normal',
      'dry_run' => false,
      'apiKey' => 'AIzaSyBrqgphfoyn7d9Zm--BVjlngu4V3_zDtU0',
  ],
  'fcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'AAAACF93GTg:APA91bFpIsW-ihVBFWYivwpbdfkzH_CJFOhPxZedspeLWYXNmvx6VkLuNwqbzUFAg53cowPsikBlfxBZ_tdWoh2Zn4nR53BxC5cNicQ_ncNhiy7KV--2gQqUdnOrasl_TRCLyVvtnLuM',
  ],
  'apn' => [
      'certificate' => app_path().'/apns/user/orderarounddist.pem',
      'passPhrase' => env('IOS_PUSH_PASS', 'appoets123$'),
      'passFile' => app_path().'/apns/provider/deliverarounddist.pem',
      'dry_run' => true
  ]
];