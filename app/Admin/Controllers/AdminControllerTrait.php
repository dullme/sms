<?php
/**
 * Created by PhpStorm.
 * User: dullme
 * Date: 2018/11/14
 * Time: 9:17 AM
 */

namespace App\Admin\Controllers;


use Encore\Admin\Admin;

trait AdminControllerTrait
{

    public function loadVue()
    {
        Admin::script(<<<EOF
        const app = new Vue({
        el: '#app'
    });
EOF
        );
    }
}