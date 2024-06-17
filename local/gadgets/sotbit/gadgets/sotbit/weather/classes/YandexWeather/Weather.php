<?php
namespace Yandex\Weather;
use Exception;

class Weather
{
    protected $_version = '2';
    protected $_response = null;
    protected $_token = '';
    protected $_lat = '';
    protected $_lon = '';
    protected $_lang = 'ru_RU';

    public function __construct($token, $lat, $lon, $version = null)
    {
        if (!empty($version)) {
            $this->_version = (string)$version;
        }

        $this->_lat = $lat;
        $this->_lon = $lon;
        $this->_token = $token;
        $this->_lang = LANGUAGE_ID ?: $this->_lang;
    }

    public function load()
    {
        $apiUrl = 'https://api.weather.yandex.ru/v'.$this->_version.'/informers?lat='.$this->_lat.'&lon='.$this->_lon;
        $headers = [
            'X-Yandex-API-Key:' . $this->_token
        ];

        $response = $this->request($apiUrl, $headers);
        $data = json_decode($response['data'], true);
        if ($response['code'] == 200) {

            if (!is_null($data)) {
                $this->_response = $data;
            } else {
                $msg = sprintf('Bad response: %s', var_export($data, true));
                throw new Exception(trim($msg));
            }
        } else {
            $msg = strip_tags($data['message']);
            throw new Exception(trim($msg));
        }

        return $this;
    }

    public function getResponse() {
        return $this->_response;
    }

    protected function request($url, $headers = null) {
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        curl_setopt($curl, CURLOPT_HTTPGET, 1);
        
        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        $data = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return [
            'code' => $code,
            'data' => $data
        ];
    }
}