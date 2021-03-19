<?php

namespace Plugin\SamplePlugin;

use Eccube\Common\EccubeNav;

class Nav implements EccubeNav
{
    /**
     * @return array
     */
    public static function getNav()
    {
        return [
            'setting' => [
                'children' => [
                    'shop' => [
                        'children' => [
                            'sample_plugin' => [
                                'name' => 'サンプルプラグイン',
                                'url' => 'sample_plugin_admin_config',
                            ]
                        ],
                    ],
                ],
            ],
        ];
    }
}
