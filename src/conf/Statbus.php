<?php
return [
  'app_name'       => getenv('APP') ?: 'Statbus',
  'UA'             => getenv('UA') ?: null,
  'remote_log_src' => getenv('REMOTE_LOGS') ?: null,
  'github'         => getenv('GITHUB') ?: null,
  'auth'           => [
    'remote_auth'  => getenv('REMOTE_AUTH') ?: false,
    'oauth_start'  => 'oauth_create_session.php',
    'token_url'    => 'oauth.php',
    'auth_session' => 'oauth_get_session_info.php'
  ],
  'perm_flags'     => [
    'BUILDMODE'   => (1<<0),
    'ADMIN'       => (1<<1),
    'BAN'         => (1<<2),
    'FUN'         => (1<<3),
    'SERVER'      => (1<<4),
    'DEBUG'       => (1<<5),
    'POSSESS'     => (1<<6),
    'PERMISSIONS' => (1<<7),
    'STEALTH'     => (1<<8),
    'POLL'        => (1<<9),
    'VAREDIT'     => (1<<10),
    'SOUNDS'      => (1<<11),
    'SPAWN'       => (1<<12),
    'AUTOLOGIN'   => (1<<13),
    'DBRANKS'     => (1<<14)
  ],
  'ranks' => [
    'Coder' => [
      'backColor' => '#31b626',
      'foreColor' => '#FFF',
      'icon'      => 'code'
    ],
    'Codermin' => [
      'backColor' => '#31b626',
      'foreColor' => '#FFF',
      'icon'      => 'code'
    ],
    'Debugger' => [
      'backColor' => '#31b626',
      'foreColor' => '#FFF',
      'icon'      => 'spider'
    ],
    'TrialAdmin' => [
      'backColor' => '#d05995',
      'foreColor' => '#FFF',
      'icon'      => 'gavel'
    ],
    'GameAdmin' => [
      'backColor' => '#9b59b6',
      'foreColor' => '#FFF',
      'icon'      => 'asterisk'
    ],
    'Barista' => [
      'backColor' => '#6b4711',
      'foreColor' => '#FFF',
      'icon'      => 'coffee'
    ],
    'AdminTrainer' => [
      'backColor' => '#9570c0',
      'foreColor' => '#FFF',
      'icon'      => 'asterisk'
    ],
    'GameMaster' => [
      'backColor' => '#d9b733',
      'foreColor' => '#000',
      'icon'      => 'dungeon'
    ],
    'HeadAdmin' => [
      'backColor' => '#ffa500',
      'foreColor' => '#000',
      'icon'      => 'star'
    ],
    'HeadCoder' => [
      'backColor' => '#31b626',
      'foreColor' => '#FFF',
      'icon'      => 'star'
    ],
    'Host' => [
      'backColor' => '#A00',
      'foreColor' => '#FFF',
      'icon'      => 'server'
    ],
  ],
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