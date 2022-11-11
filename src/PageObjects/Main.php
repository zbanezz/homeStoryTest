<?php
namespace Tests\PageObjects;
use Facebook\WebDriver\WebDriverBy;

class Main
{


    public WebDriverBy $locationTitleButton;
    public WebDriverBy $cityStateOrZipInput;
    public WebDriverBy $nextButton;
    public WebDriverBy $cityTextValue;
    public WebDriverBy $locationTitleButtonAnswered;
    public WebDriverBy $locationFormText;
    public WebDriverBy $locationTitleButtonInnerTextGNP;  // GNP = geolocation is not present
    public WebDriverBy $placeholderText; // geolocation is not present
    public WebDriverBy $searchResultContainer;
    public WebDriverBy $nextButtonDisabled;
    public WebDriverBy $formContainer;
    public WebDriverBy $userSelectionValueLa;


    public function __invoke() {
        $this->locationTitleButton = WebDriverBy::cssSelector
        (".tile__tile___H11aM.buyingPreferences__card___WHL3W.buyingPreferences__notAnswered___Nr2Xy");
        $this->locationTitleButtonAnswered = WebDriverBy::cssSelector
        (".tile__tile___H11aM.buyingPreferences__card___WHL3W.buyingPreferences__answered___kUxyI");
        $this->cityStateOrZipInput = WebDriverBy::name('purchaseLocation_input');
        $this->nextButton = WebDriverBy::className('questions__next___J1v3h');
        $this->cityTextValue = WebDriverBy::xpath('//div[contains(text(), "Belgrade,00")]');
        $this->locationFormText = WebDriverBy::xpath('//p[contains(text(), "Roughly, where are you looking?")]');
        $this->locationTitleButtonInnerTextGNP = WebDriverBy::xpath('//div[contains(text(), "Purchase Location")]');
        $this->placeholderText = WebDriverBy::xpath('//input[@placeholder="City, State, or Zip"]');
        $this->searchResultContainer = WebDriverBy::className('typeahead__wrapper___jyQ8S');
        $this->nextButtonDisabled = WebDriverBy::xpath('(//button[@disabled])[1]');
        $this->formContainer = WebDriverBy::className('questions__container___GKfuL');
        $this->userSelectionValueLa = WebDriverBy::xpath("(//div[contains(text(), 'Los Angeles, CA')])[1]");



        return $this;
    }

}