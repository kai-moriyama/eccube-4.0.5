<?php

namespace Customize;

use Eccube\Common\EccubeNav;

class CustomizeNav implements EccubeNav
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
                            'donut_edit' => [
                                'name' => 'テストドーナツ',
                                'url' => 'admin_donut_list',
                            ]
                        ],
                    ],
                ],
            ],
        ];
    }
}