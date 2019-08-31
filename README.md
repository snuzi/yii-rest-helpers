# yii-rest-helpers

Helpers for my Yii2 projects

### Usage

In rest controller put the following code:

```
use snuzi\YiiHelpers\auth\MyQueryParamAuth;
use snuzi\YiiHelpers\auth\MyHttpBearerAuth;
```

And in behaviors function:

```
public function behaviors()
{
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
			'authMethods' => [
				[
                    'class' => MyQueryParamAuth::className()
                ],
				[
                    'class' => MyHttpBearerAuth::className()
                ],
			]
        ];

        .....

        return $behaviors;
}
```