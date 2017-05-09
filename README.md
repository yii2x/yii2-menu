YII2 Menu
=========
YII2 Menu

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yii2x/yii2-menu "@dev"
```

or add

```
"yii2x/yii2-menu": "@dev"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :


Application Config:
-------------------

```php
    
    [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                ...

                ['class' => 'yii\rest\UrlRule', 'controller' => ['menuapi']],

                ...
            ]
        ],
    ],
    'controllerMap' => [
        'menuapi' => [
            'class' => 'yii2x\ui\menu\controllers\MenuController'
        ]          
    ],

```

Admin Menu Manager
----------

```
                <div class="row">
                    <div class="col-sm-4">
                    <?= \yii2x\ui\ext\Component::widget([
                        "id" => 'menu-grid',
                        "params" => [
                            "xtype" => "menugridpanel"                            
                        ]
                    ]); ?>
                    </div>
                    <div class="col-sm-8">
                    <?= \yii2x\ui\ext\Component::widget([
                        "id" => 'menu-tree-grid',
                        "params" => [
                            "xtype" => "menutreegrid",                            
                        ]
                    ]); ?>
                    </div>
                </div>    
```


Menu Widget:
-------
```
    <?= \yii2x\ui\menu\MenuWidget::widget(
        [
            'alias' => 'MY_MENU',
            //'menuTitle' => 'MAIN NAVIGATION',
            'options' => [
              //  "class"=>"sidebar-menu"
            ]
        ]
    ); ?>  
```

Load Menu Items
---------------




