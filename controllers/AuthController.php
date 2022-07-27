<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\SingupForm;
use app\models\User;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class AuthController extends Controller
{
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionSignup()
    {
        $model = new SingupForm();

        if (Yii::$app->request->isPost)
        {
            $model->load(Yii::$app->request->post());
            if ($model->signup())
            {
                $user = User::findByUsername($model->username);
                Yii::$app->user->login($user);
                return $this->redirect(['site/index']);
            }
        }
        return $this->render('signup', ['model' => $model]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionTest()
    {
        $user = User::findOne(1);
        Yii::$app->user->login($user);

    }
}