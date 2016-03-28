<?php
// for test
// $apiKey = '3dcc0e56ad7f1708352e2567bf60fff0';

class TheMovieDB
{
    static private $_remoteHost = 'http://api.themoviedb.org/3/';
    static private $_configuration;
    static private $_apiKey;

    public static function setRemoteHost($value)
    {
        self::$_remoteHost = $value;
    }

    public static function test()
    {

        return 'test';
    }

    private static function getConfiguration()
    {
        // в идеале нужно сделать кеширование, но пока что так

        if (Yii::app()->user->isGuest) {
            Yii::app()->getRequest()->redirect(Yii::app()->user->loginUrl);
        }

        // set parameters
        if (!isset(self::$_apiKey)) {
            self::$_apiKey = Yii::app()->user->getState('api_key');
        }

        if (!isset(self::$_configuration)) {
            $request = new HTTP_Request2(
                self::$_remoteHost . 'configuration',
                HTTP_Request2::METHOD_GET
            );
            $url = $request->getUrl();
            $url->setQueryVariables(array(
                'package_name' => array('HTTP_Request2', 'Net_URL2'),
                'status' => 'Open'
            ));
            $url->setQueryVariable('api_key', self::$_apiKey);

            $response = $request->send();
            if ($response->getStatus() == 200) {
                $body = $response->getBody();
                self::$_configuration = json_decode($body, true);
            }
        }
    }

    public static function getImage($fileName, $size = null, $base_url = null)
    {

        self::getConfiguration();

        if (!isset($size)) {
            $size = "w500";
        }
        if (!isset($base_url)) {
            $base_url = self::$_configuration['images']['base_url'];
        }

        $request = new HTTP_Request2(
            $base_url . $size . '/' . $fileName,
            HTTP_Request2::METHOD_GET
        );
        $url = $request->getUrl();
        $url->setQueryVariables(array(
            'package_name' => array('HTTP_Request2', 'Net_URL2'),
            'status' => 'Open'
        ));
        $url->setQueryVariable('api_key', self::$_apiKey);

        $response = $request->send();
        $status = $response->getStatus();
        if ($status == 200) {
            $body = $response->getBody();
            $filePath = $_SERVER['DOCUMENT_ROOT'] . '/images/' . $fileName;
            $h = fopen($filePath, "w");
            $res = fwrite($h, $body);
            if ($res) {
                return true;
            } else {
                throw new CHttpException(500, 'неудачная попытка сохранения файла.');
            }
        } else {
            throw new CHttpException($status, 'неудачная попытка получения файла.');
        }
    }

    public static function getMovie($id)
    {

        self::getConfiguration();

        $res = array();
        $request = new HTTP_Request2(
            self::$_remoteHost . 'movie/' . $id,
            HTTP_Request2::METHOD_GET,
            array(
                "Accept: application/json"
            )
        );
        $url = $request->getUrl();
        $url->setQueryVariables(array(
            'package_name' => array('HTTP_Request2', 'Net_URL2'),
            'status' => 'Open'
        ));
        $url->setQueryVariable('api_key', self::$_apiKey);
        $url->setQueryVariable('language', 'ru');

        $response = $request->send();
        if ($response->getStatus() == 200) {
            $body = $response->getBody();
            $res = json_decode($body, true);
        }
        return $res;
    }

    public static function setRating($id, $rating)
    {

        self::getConfiguration();

        $request = new HTTP_Request2(self::$_remoteHost . 'movie/' . $id . '/rating');
        $request->setAdapter('HTTP_Request2_Adapter_Curl');
        $request->setMethod(HTTP_Request2::METHOD_POST)
            ->setHeader('Accept: application/json')
            ->setHeader('Content-Type: application/json')
            ->setBody("{\"value\": $rating}");
        $url = $request->getUrl();
        $url->setQueryVariables(array(
            'package_name' => array('HTTP_Request2', 'Net_URL2'),
            'status'       => 'Open'
        ));
        $url->setQueryVariable('api_key', self::$_apiKey);
        $url->setQueryVariable('guest_session_id', Yii::app()->user->getState('guest_session_id'));

        $response = $request->send();
//        $body = $response->getBody(); /* для получения текстового описания результата*/
        $status = $response->getStatus();
        if ($status == 200 || $status == 201) {
            return true;
        }
        return false;
    }

    public static function discoverMovie($currentPage, $release = false)
    {

        self::getConfiguration();

        $res = '';
        $request = new HTTP_Request2(
            self::$_remoteHost . 'discover/movie',
            HTTP_Request2::METHOD_GET,
            array(
                "Accept: application/json"
            )
        );
        $url = $request->getUrl();
        $url->setQueryVariables(array(
            'package_name' => array('HTTP_Request2', 'Net_URL2'),
            'status' => 'Open'
        ));
        $url->setQueryVariable('language', 'ru');
        $url->setQueryVariable('page', $currentPage);
        $url->setQueryVariable('api_key', self::$_apiKey);
        if ($release) {
            $url->setQueryVariable('sort_by', 'release_date.desc');
            $formatter = new CFormatter;
            $formatter->dateFormat = 'Y-m-d';
            $url->setQueryVariable('release_date.lte', date('Y-m-d'));
            $url->setQueryVariable('release_date.gte',
                date('Y-m-d', mktime(0, 0, 0, date("m") - 2, date("d"), date("Y"))));
        } else {
            $url->setQueryVariable('sort_by', 'popularity.desc');
        }


        $response = $request->send();
        if ($response->getStatus() == 200) {
            $body = $response->getBody();
            $res = json_decode($body, true);

        }
        return $res;
    }

    public static function authentication($apiKey)
    {

        $request = new HTTP_Request2(
            self::$_remoteHost . 'authentication/guest_session/new',
            HTTP_Request2::METHOD_GET,
            array(
                "Accept: application/json"
            )
        );
        $url = $request->getUrl();
        $url->setQueryVariables(array(
            'package_name' => array('HTTP_Request2', 'Net_URL2'),
            'status' => 'Open'
        ));
        $url->setQueryVariable('api_key', $apiKey);

        $response = $request->send();
        if ($response->getStatus() == 200) {
            $body = $response->getBody();
            $res = json_decode($body, true);
            return $res;
        }

        return false;
    }
}