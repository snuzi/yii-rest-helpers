<?php

namespace snuzi\YiiHelpers\auth;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use Exception;

class MyHttpBearerAuth extends HttpBearerAuth {

    /** @var string user class name */
    public $userModel = '\app\models\User';

    /** @var string header for the authentication */
    public $header = 'X-Api-Key';

    /** @var string pattern for the authentication */
    public $pattern = "/^Bearer\\s+(.*?)$/";

    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
		$authHeader = $request->getHeaders()->get($this->header); 

		if ($authHeader !== null) {

            if (!class_exists($this->userModel)) {
                throw new Exception(
                    sprintf(
                        'Class "%s" does not exist. Please set a valid user model.',
                        $this->userModel
                    )
                );
            }

            $identity = $this->userModel::findOne([
                'auth_key' => $authHeader,
                'status' => $this->userModel::STATUS_ACTIVE
            ]);

            if ($identity == null) {
                $this->handleFailure($response);
                return;
            }

            Yii::$app->session->useCookies = false;
            Yii::$app->user->apiLogin($identity);
			
            if ($identity !== null) {
                return $identity;
            }
        }
		
        return null;
    }
}