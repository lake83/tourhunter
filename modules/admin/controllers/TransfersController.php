<?php

namespace app\modules\admin\controllers;

/**
 * TransfersController implements the CRUD actions for Transfers model.
 */
class TransfersController extends AdminController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => $this->actionsPath.'Index',
                'search' => $this->modelPath.'TransfersSearch'
            ],
            'delete' => [
                'class' => $this->actionsPath.'Delete',
                'model' => $this->modelPath.'Transfers'
            ]
        ];
    }
}