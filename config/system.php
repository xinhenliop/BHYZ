<?php return array(
    'system' =>
        array(
            'name' => 'BH验证网',
            'description' => '一个开放的验证平台',
            'admin_url' => '/admin',
            'agent_url' => '/Agent',
            'time_zone' => '0',
            'language' => '0',
            'status' => true,
            'close_toast' => '系统已关闭！',
            'agent' => true,
            'agent_close_toast' => '代理中心已关闭！',
            'sql' => true,
            'sql_argv' => NULL,
            'ip_whitelist' => NULL,
            'ver' => '0.0.1',
        ),
    'sessions' =>
        array(
            'max' => 180000,
            'prefix' => 'bh_',
        ),
    'smtp' =>
        array(
            'host' => 'qq.smtp.com',
            'port' => 425,
            'username' => '',
            'password' => '',
            'SSL' => false,
            'email' => 'BH验证网',
            'status' => true,
        ),
    'invitation' =>
        array(
            'status' => false,
            'integral' => 1,
            'max' => 10,
            'proportion' => 0.1,
            'proportions' => 0.02,
            'system' => true,
        ),
    'template' =>
        array(
            'webs' => 'default',
            'language' => 'zh',
        ),
    'plugins' =>
        array(),
    'captcha' =>
        array(
            'web' => true,
            'fonts' => 'D3Parallelism.ttf',
            'count' => 4,
            'width' => 120,
            'height' => 40,
            'snowflake' => true,
            'line' => true,
            'curve' => true,
        ),
    'Times' =>
        array(
            0 =>
                array(
                    0 => '秒',
                    1 => 1,
                ),
            1 =>
                array(
                    0 => '分',
                    1 => 60,
                ),
            2 =>
                array(
                    0 => '时',
                    1 => 3600,
                ),
            3 =>
                array(
                    0 => '天',
                    1 => 86400,
                ),
            4 =>
                array(
                    0 => '周',
                    1 => 604800,
                ),
            5 =>
                array(
                    0 => '月',
                    1 => 2592000,
                ),
            6 =>
                array(
                    0 => '季',
                    1 => 7776000,
                ),
            7 =>
                array(
                    0 => '年',
                    1 => 31536000,
                ),
            8 =>
                array(
                    0 => '永久',
                    1 => 3153600000,
                ),
        ),
    'author' =>
        array(
            'user' => '用户',
            'agent' => '代理',
            'app' => '程序',
            'card' => '卡密',
            'type' => '分类',
            'system' => '设置',
            'log' => '日志',
            'plugin' => '插件',
            'tools' => '工具',
        ),
);
