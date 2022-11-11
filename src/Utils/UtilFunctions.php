<?php
namespace Tests\Utils;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverElement;
use Facebook\WebDriver\WebDriverSelect;

class UtilFunctions
{


    public function fillInput(WebDriverElement $element, $value)
    {
        $element->clear();
        $element->sendKeys($value);
    }



    public function select(WebDriverElement $element, string $selectValue) {

        $select = new WebDriverSelect($element);
        $select->selectByVisibleText($selectValue);

    }

    public function getGeoLocation($ip) {
        $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
        return $details;

    }

    public function getCurrentIp() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,'https://api.ipify.org?format=json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($ch));
        curl_close($ch);
        return $response->ip;
    }


    public function checkIfCityExistsInUS(string $city, string $state)
    {
        $ch = curl_init();
        $where = urlencode('{"name": "'.$city.'"}');
        curl_setopt($ch, CURLOPT_URL, 'https://parseapi.back4app.com/classes/'.$state.'?keys=name&where=' . $where);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-Parse-Application-Id: YV6GTTBZe2seEMboA5c44F9eXledturUyBFmQwkD', // This is the fake app's application id
            'X-Parse-Master-Key: WCx4AtqgKzDpQllBdBqeBqlpEzlr5EhfRWSbeI0n' // This is the fake app's readonly master key
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = json_decode(curl_exec($ch));
        curl_close($ch);
        if (count($data->results) > 0) {
            return true;
        }
        return  false;
    }





}