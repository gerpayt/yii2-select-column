<?php

namespace gerpayt\yii2_select_column;

use common\assets\FrameAsset;
use Yii;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


/**
 * ToggleColumn Allows to modify the value of column on the fly. This type of column is good when you wish to modify
 * the value of a displayed model that has two states: yes-no, true-false, 1-0, etc...
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\grid
 */
class SelectColumn extends DataColumn
{
    public $url = '';
    public $selectList = [];
    public $classSuffix = '';
    public $classPrefix = 'select';
    public $options = [];

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->registerClientScript();
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $attribute = $this->attribute;
        $name = Html::getInputName($model, $attribute);
        $value = $model->$attribute;

        $options = ArrayHelper::merge([
            'class' => $this->classPrefix.'-'.$attribute,
            'data-id' => $key
        ], $this->options);

        return Html::dropDownList($name, $value, $model::$enum_category, $options);
    }

    /**
     * Registers the required scripts for the toggle column to work properly
     */
    protected function registerClientScript()
    {
        $attribute = $this->attribute;

        $view = $this->grid->getView();

        BootstrapSelectAsset::register($view);

        $grid = $this->grid->id;
        $selector = ".{$this->classPrefix}-{$attribute}";
        $csrf = Yii::$app->request->csrfToken;

        $view->registerJs("
    (function () {
        var previous;
        $('{$selector}').on('focus', function () {
            previous = this.value;
        }).change(function(){
            var obj = $(this),
                id = obj.data('id'),
                name = obj.attr('name'),
                value = obj.val(),
                data = {_csrf: '{$csrf}'};

            data[name] = value;

            $.ajax({
                url : '{$this->url}?id='+id,
                // TODO merge url
                method : 'POST',
                data : data,
                beforeSend: function(){
                    obj.prop('disabled',true);
                    obj.selectpicker('refresh');
                },
                success: function(data){
                    console.log(data);
                },
                error: function(){
                    obj.val(previous);
                },
                complete: function(){
                    obj.prop('disabled',false);
                    obj.selectpicker('refresh');
                }
            });
        });
    })();
        ", $view::POS_END);
        $view->registerJs("$('{$selector}').selectpicker();", $view::POS_END);

    }
}
