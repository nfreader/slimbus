<?php
return [
  'app_name'       => 'Statbus',
  'UA'             => getenv('UA') ?: null,
  'wide'           => false,
  'remote_log_src' => 'https://tgstation13.org/parsed-logs/',
  'github'         => 'tgstation/tgstation',
  'servers'        => [
    [
      'address'=>'game.tgstation13.org',
      'port'=>2337,
      'servername'=>'SS13: Server 1 (Basil)',
      'name'=>'Basil'
    ],
    [
      'address'=>'game.tgstation13.org',
      'port'=>1337,
      'servername'=>'SS13: Server 2 (Sybil)',
      'name'=>'Sybil'
    ],
    [
      'address'=>'game.tgstation13.org',
      'port'=>3337,
      'servername'=>'SS13: Server 3 (Terry)',
      'name'=>'Terry'
    ]
  ],
  'mode_icons' => [
    'Abduction'=>'street-view',
    'Ai Malfunction'=>'network-wired',
    'Arching Operation'=>'',
    'Assimilation'=>'syringe',
    'Blob'=>'cubes',
    'Changeling'=>'spider',
    'Clockwork Cult'=>'cog',
    'Clown Ops'=>'angry',
    'Cult'=>'book-dead text-danger',
    'Devil'=>'handshake',
    'Devil Agents'=>'handshake',
    'Double Agents'=>'user-ninja',
    'Everyone Is The Traitor And Also'=>'',
    'Extended'=>'running',
    'Extended Events'=>'',
    'Families'=>'',
    'Gang War'=>'',
    'Gang War No Security'=>'',
    'Hand Of God'=>'',
    'Infiltration'=>'user-tie',
    'Internal Affairs'=>'user-secret',
    'Jeffjeff'=>'',
    'Just Fuck My Shit Up'=>'',
    'Meteor'=>'sign-out-alt',
    'Monkey'=>'dizzy',
    'Nuclear Emergency'=>'bomb',
    'Overthrow'=>'frown-open',
    'Ragin\' Mages'=>'magic',
    'Revolution'=>'fist-raised',
    'Rod Madness'=>'slash',
    'Sandbox'=>'grin-stars',
    'Secret Extended'=>'bed',
    'Shadowling'=>'users',
    'Speedy_revolution'=>'',
    'Traitor'=>'skull-crossbones',
    'Traitor+brothers'=>'user-injured',
    'Traitor+changeling'=>'user-astronaut',
    'Very Ragin\' Bullshit Mages'=>'cloud-sun',
    'Vigilante Gang War'=>'',
    'Wizard'=>'hat-wizard'
  ]
];