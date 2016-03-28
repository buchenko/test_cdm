<?php

class MovieController extends Controller
{

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id, true),
        ));
    }


    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id, true);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Movie'])) {
            if (!empty($_POST['Movie']['rating'])) {
                $model->rating = $_POST['Movie']['rating'];
            }
            $model->attributes = $_POST['Movie'];
            if ( $model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
//        if (!isset($_GET['ajax'])) {
//            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
//        }
        $this->redirect(array('index'));
    }

    /**
     * Manages all models.
     */
    public function search($release)
    {
        $model = new Movie();
        $model->unsetAttributes();  // clear any default values



        $maxPages = Yii::app()->params['totalPages'];
        $maxPages = max(1, min($maxPages, 1000));

        $pages = new CPagination();
        $pages->pageSize = Yii::app()->params['pageSize']; // default 20
        $pages->itemCount = $maxPages * $pages->pageSize;

        $this->render('index', array(
            'model' => $model,
            'release' => $release,
            'pages' => $pages,
        ));
    }

    public function actionIndex()
    {

        $this->search(false);
    }

    public function actionRelease()
    {

        $this->search(true);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Movie the loaded model
     * @throws CHttpException
     */
    public function loadModel($id, $create = false)
    {
        $model = Movie::model()->findByPk($id);
        if ($model === null) {
            if ($create) {
                // попытаемся скачать по апи и создать аналог в БД
                $model = new Movie;
                $model->createFromTheMovieDB($id);
                if (!$model->save()) {
                    throw new CHttpException(500, 'Невозможно сохранить запись в БД.');
                }
            }/* else {
                throw new CHttpException(404, 'Запрашиваемая страница не существует.');
            }*/
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Movie $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'movie-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
