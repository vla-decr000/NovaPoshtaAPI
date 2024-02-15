<?php

namespace NovaPost;

class NP
{

    /*
     *
     * Glory to Ukraine! :)
     *
     * All parameters for search are on ukrainian. (cities` and areas` names)
     * Response can be used on different language (as many as api allows)
     * */

    private string $api_key;
    /*
     * In this simple case of usage, this module can be used without api_key.
     * It is genuine for 15.02.24
     * Better to past api_key
     * */
    private string  $api_url = 'https://api.novaposhta.ua/v2.0/json/';


    public function __construct(string $api_key)
    {
        $this->api_key = $api_key;
    }


    //Common request method
    public function request(string $model, string $method, array $properities)
    {
        $data = array(
            'apiKey' => $this->api_key,
            'modelName' => $model,
            'calledMethod' => $method,
            "methodProperties" => $properities,
        );
        $post = json_encode($data, JSON_UNESCAPED_UNICODE);
        $url = $this->api_url;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); //xml is possible to. Do it ;)
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result, JSON_UNESCAPED_UNICODE);
        if($result['success'] == false) {
            return false;
        }
        return $result['data'];
    }

    // Getting all ukraine`s  areas
    public function getAreas()
    {
        return $this->request('Address', 'getSettlementAreas', ["Ref" => ""]);
    }


    // Getting all cities in Ukraine or city by name
    public function getCities(string $query = '')
    {
        $parametres = [
            "Ref" => "",
            "FindByString" => $query
        ];
        return $this->request('Address', 'getCities', $parametres);
    }


    // Getting cities by area + possibly by name
    public function getCitiesByArea(string $area, string $query = '')
    {
        /*
         * Nova Poshta`s api don`t have possibility to get cities by area.
         * So we should do it by sorting all cities
         * */

        $cities = $this->getCities($query); // all ukrainian cities

        if(!$cities || !count($cities) > 0) {
            return false;
        }
        $area_cities = [];
        foreach ($cities as $city) {
            if ($city['AreaDescription'] == $area) {
                $area_cities[] = $city;
            }
        }
       return $area_cities;
    }

    //Getting warehouses by city and possibly by warehouse`s name
    public function getWarehousesByCity(string $city_name, string $query = '')
    {
        $parametres = [
            "FindByString" => $query,
            "CityName" => $city_name,
        ];
        return $this->request('Address', 'getWarehouses', $parametres);
    }

}