HumHub Modules Generater
===============================

Installation
---------------------------

step-1. Install module in humhub/protected/modules
step-2. In humhub/protected/humhub/config/web.php

```
    'modules' =>[
    	....................
		'gii' => [
			'class'=>'yii\gii\Module',
			'generators' => [
					'crud' => [
							'class' => 'app\modules\generator\Generator',
							'templates' => ['tejrajs' => '@app/modules/generator/deafult']
					]
			],
			'allowedIPs'=>['127.0.0.1','*'],
		],
		....................
	],
```

step-3 Go to /index.php?r=gii 
