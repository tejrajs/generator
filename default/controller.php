<?php
/**
 * This is the template for generating a controller class within a module.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\module\Generator */

echo "<?php\n";
?>

namespace <?= $generator->getControllerNamespace() ?>;

/**
 * Description of <?=$generator->moduleClass?>Controller
 *
 * @author <?=$generator->author?>
 */

class <?=ucwords($generator->moduleID)?>Controller extends \humhub\components\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
