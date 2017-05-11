<?php
namespace yii2x\ui\menu;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii2x\ui\menu\models\Menu;
/**
 * Class Menu
 * Theme menu widget.
 */
// \app\components\accessAdminMenu
class MenuWidget extends \yii\widgets\Menu
{
    /**
     * @inheritdoc
     */
    public $alias = '';
    public $activeParents = true;
    public $menuTitle = null;
    /**
     * @inheritdoc
     */
    
    
    public function init()
    {
        if(!empty($this->alias)){
            $this->items = self::getItems($this->alias);
        }     
    }
    
    public static function getItems($alias){
        $model = Menu::find()->where(['alias' => $alias])->one();
        if(!empty($model->config) && is_array($model->config['children'])){
            return self::normalizeVisibility($model->config['children']);
        }               
        return [];
    }
    
    protected function renderItem($item)
    {


        
        $out = '';

            $out .= Html::beginTag ('a', $options = ['href' => $item['url']] );
            if(!empty($item['iconCls'])){
                $out .= Html::tag('i', null, ['class' => $item['iconCls']]);
            }
            $out .= Html::tag('span', $item['label']);
            if(!empty($item['items']) && is_array($item['items'])){
                $out .= Html::tag('i', null, ['class' => 'fa fa-angle-left pull-right']);
            }
            $out .= Html::endTag('a');            
        return $out;

    }
    /**
     * Recursively renders the menu items (without the container tag).
     * @param array $items the menu items to be rendered recursively
     * @return string the rendering result
     */
    protected function renderItems($items)
    {
        
       // print_r($items);exit;
        
        $n = count($items);
        $lines = [];
        
        if(!empty($this->menuTitle)){
            $lines[] = '<li class="header">'.$this->menuTitle.'</li>';
            $this->menuTitle = null;
        }
        foreach ($items as $i => $item) {
            $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
            $tag = ArrayHelper::remove($options, 'tag', 'li');
            $class = [];
            if ($item['active']) {
                $class[] = $this->activeCssClass;
            }
            if ($i === 0 && $this->firstItemCssClass !== null) {
                $class[] = $this->firstItemCssClass;
            }
            if ($i === $n - 1 && $this->lastItemCssClass !== null) {
                $class[] = $this->lastItemCssClass;
            }
            if (!empty($item['items'])) {
                $class[] = 'treeview';
            }
            
            
            if (!empty($class)) {
                if (empty($options['class'])) {
                    $options['class'] = implode(' ', $class);
                } else {
                    $options['class'] .= ' ' . implode(' ', $class);
                }
            }
            $menu = $this->renderItem($item);
            if (!empty($item['items'])) {
                
                $menu .= Html::beginTag('ul', ['class' => 'treeview-menu', "style"=> ($item['active'] ? 'display: block' : '') ]);
                $menu .= $this->renderItems($item['items']);
                $menu .= Html::endTag('ul');

                if(empty($options['class'])){
                    $options['class'] = '';
                }
            }
            $lines[] = Html::tag($tag, $menu, $options);
        }
        return implode("\n", $lines);
    }
    /**
     * @inheritdoc
     */
    
    public static function normalizeVisibility($items){
        if(is_array($items)){
            foreach ($items as $i => $item) {

                if(empty($item['scheme']) || $item['scheme'] == 'rel'){
                    $item['scheme'] = false;    
                }
                else if($item['scheme'] == 'abs'){
                    $item['scheme'] = true;    
                }     
                else if($item['scheme'] == 'protrel'){
                    $item['scheme'] = "";    
                }   

                if (empty($item['url'])) {
                    $item['url'] = '#';
                }  
                else{
                    $item['url'] = Url::to([$item['url']], $item['scheme']);
                }
                
                if (isset($item['visibility']) && $item['visibility'] != 'on') {
                    if($item['visibility'] == 'off'){
                        unset($items[$i]);
                        continue;
                    }     

                    if($item['visibility'] == 'guest' && Yii::$app->user->isGuest == false){
                        unset($items[$i]);
                        continue;
                    }  
                    
                    if($item['visibility'] == 'auth' && Yii::$app->user->isGuest == true){
                        unset($items[$i]);
                        continue;
                    }                      
                }
                
                if (isset($item['children'])  && is_array($item['children'])) {
                    $items[$i]['items'] = self::normalizeVisibility($item['children']);
                }
                
            }
        }
        return $items;
    }
    
    protected function normalizeItems($items, &$active)
    {
        
        if(is_array($items)){
            foreach ($items as $i => $item) {
                
                if (!isset($item['label'])) {
                    $item['label'] = '';
                }
                $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
                $items[$i]['label'] = $encodeLabel ? Html::encode($item['label']) : $item['label'];
                $items[$i]['icon'] = isset($item['icon']) ? $item['icon'] : '';
                $hasActiveChild = false;
                if (isset($item['items'])  && is_array($item['items'])) {
                    $items[$i]['items'] = $this->normalizeItems($item['items'], $hasActiveChild);
                    if (empty($items[$i]['items']) && $this->hideEmptyItems) {
                        unset($items[$i]['items']);
                        if (!isset($item['url'])) {
                            unset($items[$i]);
                            continue;
                        }
                    }
                }
                if (!isset($item['active'])) {
                    if ($this->activeParents && $hasActiveChild || $this->activateItems && $this->isItemActive($item)) {
                        $active = $items[$i]['active'] = true;
                    } else {
                        $items[$i]['active'] = false;
                    }
                } elseif ($item['active']) {
                    $active = true;
                }
            } 
            return array_values($items);
        }

        return $items;
    }
    /**
     * Checks whether a menu item is active.
     * This is done by checking if [[route]] and [[params]] match that specified in the `url` option of the menu item.
     * When the `url` option of a menu item is specified in terms of an array, its first element is treated
     * as the route for the item and the rest of the elements are the associated parameters.
     * Only when its route and parameters match [[route]] and [[params]], respectively, will a menu item
     * be considered active.
     * @param array $item the menu item to be checked
     * @return boolean whether the menu item is active
     */
    protected function isItemActive($item)
    {
     
        if (isset($item['url'])) {
            return (ltrim($item['url'], '/') == Yii::$app->controller->route);
        }
        return false;
    }
}

