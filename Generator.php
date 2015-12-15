<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\generator;

use yii\gii\CodeFile;
use yii\helpers\Html;
use Yii;
use yii\helpers\StringHelper;
use humhub\libs\ZipFile;

/**
 * This generator will generate the skeleton code needed by a module.
 *
 * @property string $controllerNamespace The controller namespace of the module. This property is read-only.
 * @property boolean $modulePath The directory that contains the module class. This property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Generator extends \yii\gii\Generator
{
    public $moduleClass;
    public $moduleID;
	public $moduleDsc;
	public $author;
	public $outputPath = "@app/runtime/tmp-module";
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'HumHub Module Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator helps you to generate the skeleton code needed by a HumHub module.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['moduleID', 'moduleClass'], 'filter', 'filter' => 'trim'],
            [['moduleID', 'moduleClass'], 'required'],
        	[['moduleDsc', 'author'], 'safe'],
            [['moduleID'], 'match', 'pattern' => '/^[\w\\-]+$/', 'message' => 'Only word characters and dashes are allowed.'],
            [['moduleClass'], 'match', 'pattern' => '/^[\w\\\\]*$/', 'message' => 'Only word characters and backslashes are allowed.'],
            [['moduleClass'], 'validateModuleClass'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'moduleID' => 'Module ID',
            'moduleClass' => 'Module Class',
        	'moduleDsc' => 'Module Description',
        	'author' => 'Author'
        ];
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return [
            'moduleID' => 'This refers to the ID of the module, e.g., <code>admin</code>.',
            'moduleClass' => 'This is the fully qualified class name of the module, e.g., <code>humbhub\modules\admin\Module</code>.',
        	'moduleDsc' => 'Module Description.',
        	'author' => 'Module Author, e.g., Jone Done &lt;jonedone@gmail.com&gt;.'
        ];
    }

    /**
     * @inheritdoc
     */
    public function successMessage()
    {
        if (Yii::$app->hasModule($this->moduleID)) {
            $link = Html::a('try it now', Yii::$app->getUrlManager()->createUrl($this->moduleID), ['target' => '_blank']);

            return "The module has been generated successfully. You may $link.";
        }

        $output = <<<EOD
<p>The module has been generated successfully.</p>
<p>To access the module, you need to add this to your application configuration:</p>
EOD;
        $code = <<<EOD
<?php
    ......
    'modules' => [
        '{$this->moduleID}' => [
            'class' => '{$this->moduleClass}',
        ],
    ],
    ......
EOD;

        return $output . '<pre>' . highlight_string($code, true) . '</pre>';
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        return ['assets.php','config.php','events.php','module_j.php','module.php', 'controller.php', 'view.php','index.html'];
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $files = [];
        $modulePath = $this->getOutputPath().DIRECTORY_SEPARATOR.strtolower($this->moduleID); //$this->getModulePath();
        $files[] = new CodeFile(
        		$modulePath . '/assets/index.html',
        		$this->render("index.html")
        );
        $files[] = new CodeFile(
            $modulePath . '/Assets.php',
            $this->render("assets.php")
        );
        $files[] = new CodeFile(
        		$modulePath . '/config.php',
        		$this->render("config.php")
        );
        $files[] = new CodeFile(
        		$modulePath . '/Events.php',
        		$this->render("events.php")
        );
        $files[] = new CodeFile(
        		$modulePath . '/module.json',
        		$this->render("module_j.php")
        );
        $files[] = new CodeFile(
        		$modulePath . '/Module.php',
        		$this->render("module.php")
        );
        $files[] = new CodeFile(
            $modulePath . '/controllers/'.ucwords($this->moduleID).'Controller.php',
            $this->render("controller.php")
        );
        $files[] = new CodeFile(
            $modulePath . '/views/'.strtolower($this->moduleID).'/index.php',
            $this->render("view.php")
        );
		
        return $files;
    }
    /**
     * @return boolean the directory that contains the module class
     */
    public function getOutputPath()
    {
    	return Yii::getAlias($this->outputPath);
    }
    /**
     * Validates [[moduleClass]] to make sure it is a fully qualified class name.
     */
    public function validateModuleClass()
    {
        if (strpos($this->moduleClass, '\\') === false || Yii::getAlias('@' . str_replace('\\', '/', $this->moduleClass), false) === false) {
            $this->addError('moduleClass', 'Module class must be properly namespaced.');
        }
        if (empty($this->moduleClass) || substr_compare($this->moduleClass, '\\', -1, 1) === 0) {
            $this->addError('moduleClass', 'Module class name must not be empty. Please enter a fully qualified class name. e.g. "app\\modules\\admin\\Module".');
        }
    }

    /**
     * @return boolean the directory that contains the module class
     */
    public function getModulePath()
    {
        return Yii::getAlias('@' . str_replace('\\', '/', substr($this->moduleClass, 0, strrpos($this->moduleClass, '\\'))));
    }

    /**
     * @return string the controller namespace of the module.
     */
    public function getControllerNamespace()
    {
        return substr($this->moduleClass, 0, strrpos($this->moduleClass, '\\')) . '\controllers';
    }
    public function moduleNamespace(){
    	$className = $this->moduleClass;
    	$pos = strrpos($className, '\\');
    	return ltrim(substr($className, 0, $pos), '\\');
    }
}
