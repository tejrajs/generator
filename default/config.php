<?php
echo "<?php\n";
?>
//use humhub\widgets\TopMenu;

return [
    'id' => 'mail',
    'class' => '<?=$generator->moduleClass?>',
    'namespace' => '<?=$generator->moduleNamespace()?>',
    'events' => [
        //['class' => TopMenu::className(), 'event' => TopMenu::EVENT_INIT, 'callback' => ['humhub\modules\mail\Events', 'onTopMenuInit']],
    ],
];
?>