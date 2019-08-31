<?php

namespace snuzi\YiiHelpers\api\auth;

use Yii;
use yii\filters\auth\QueryParamAuth;
use Exception;

class MyQueryParamAuth extends QueryParamAuth {

    public $tokenParam = 'access_token';

    /** @var string user class name */
    public $userModel = '\app\models\User';

    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        $accessToken = $request->get($this->tokenParam);

        if (is_string($accessToken)) {

            if (!class_exists($this->userModel)) {
                throw new Exception(
                    sprintf(
                        'Class "%s" does not exist. Please set a valid user model.',
                        $this->userModel
                    )
                );
            }

            $identity = $this->userModel::findOne([
                'auth_key' => $accessToken,
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
        if ($accessToken !== null) {
            $this->handleFailure($response);
        }

        return null;
    }
}