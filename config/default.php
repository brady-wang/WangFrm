<?php

return [
    "http-server"=>[
        'host'=>'0.0.0.0',
        'port'=>9501,
        'setting'=>[
            'reactor_num'   => 2,     // reactor thread num
            'worker_num'    => 1,     // worker process num
            'backlog'       => 128,   // listen backlog
            'max_request'   => 50,
            'dispatch_mode' => 1,
        ],
    ],
    'tcp'=>[
        'host'=>'0.0.0.0',
        'port'=>9502,
        'setting'=>[
            'worker_number'=>1
        ],
    ],
    'reload_dirs'=>[APP_PATH,CONFIG_PATH,BIN_PATH,FRAME_WORK_PATH,ROOT_PATH],
];