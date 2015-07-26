<?php

namespace gerpayt\yii2_select_column;

use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * ToggleColumnAsset
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\grid
 */
class BootstrapSelectAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap-select/dist';

    public $css = ['css/bootstrap-select.css'];
    public $js = [
        'js/bootstrap-select.js',
        'js/i18n/defaults-zh_CN.js'
    ];

    public $depends = [
        '\yii\web\YiiAsset'
    ];

} 
