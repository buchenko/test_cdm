<?php

/**
 * This is the model class for table "movie".
 *
 * The followings are the available columns in table 'movie':
 * @property integer $id
 * @property string $title
 * @property string $original_title
 * @property string $release_date
 * @property string $runtime
 * @property string $overview
 * @property string $genres
 * @property string $poster_path
 */
class Movie extends CActiveRecord
{
    public $rating;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'movie';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title', 'required', 'on' => 'insert,update'),
            array('title, original_title, release_date, runtime, overview, genres', 'safe'),
            array(
                'poster_path',
                'file',
                'types' => 'jpg, gif, png',
                'safe' => false,
                'maxSize' => 1048576,
                'allowEmpty' => true,
                'on' => 'update'
            ),
        );
    }

    protected function beforeSave()
    {
        if (!parent::beforeSave()) {
            return false;
        }
        if ($this->scenario == 'update' && ($poster = CUploadedFile::getInstance($this, 'poster_path'))) {
            $this->deleteImg(); // старый документ удалим, потому что загружаем новый

            $this->poster_path = $poster;
            $this->poster_path->saveAs(
                Yii::getPathOfAlias('webroot.images') . DIRECTORY_SEPARATOR . $this->poster_path);
        }
        return true;
    }

    protected function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }
        $this->deleteImg(); // удалили модель? удаляем и файл
        return true;
    }

    public function deleteImg()
    {
        $posterPath = Yii::getPathOfAlias('webroot.images') . DIRECTORY_SEPARATOR .
            $this->poster_path;
        if (is_file($posterPath)) {
            unlink($posterPath);
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => 'Наименование',
            'original_title' => 'Оригинальное наименование',
            'release_date' => 'Дата выхода',
            'runtime' => 'Продолжительность',
            'overview' => 'Описание',
            'genres' => 'Жанры',
            'poster_path' => 'Постер',
            'rating' => 'Установить рейтинг',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search($currentPage = 0, $release = false)
    {
        $query = TheMovieDB::discoverMovie($currentPage, $release);
        $rawData = $query['results'];
        $total_pages = min($query['total_pages'], 5);
//        $total_results = $query['total_results'];
        $total_results = $total_pages * 20;
        $dataProvider = new CArrayDataProvider($rawData, array(
//            'sort' => array(
//                'attributes' => array(
//                    'id',
//                    'title',
//                ),
//            ),
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));
        // $dataProvider->setTotalItemCount(80);
        return $dataProvider;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Movie the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getGenres($genres)
    {

        $result = "";
        foreach ($genres as $genre) {
            $result .= $genre['name'] . '; ';
        }

        return $result;
    }

    public function createFromTheMovieDB($id)
    {

        $attributesTheMovieDB = TheMovieDB::getMovie($id);
        $this->attributes = $attributesTheMovieDB;
        $this->id = $id;
        // жанры запишем строкой
        if (is_array($attributesTheMovieDB['genres'])) {
            $this->genres = $this->getGenres($attributesTheMovieDB['genres']);
        }
        if (isset($attributesTheMovieDB['poster_path'])) {
            $this->poster_path = ltrim($attributesTheMovieDB['poster_path'], '/');
            TheMovieDB::getImage($this->poster_path);
        }
    }

    public function setRatingMovie()
    {
        return TheMovieDB::setRating($this->id, $this->rating);
    }
}
