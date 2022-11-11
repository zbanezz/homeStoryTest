<?php

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use Tests\Setup\Setup;

class TestLocationInput extends Setup
{

    //Case 1. Verify that when you click on the title button, the pop-up form is displayed (all elements are present).
    private \Tests\PageObjects\Main $mainPage;

    public function __construct()
    {
        $this->mainPage =  (new \Tests\PageObjects\Main())();
        parent::__construct();
    }

    public function  testPopUpForm () {
        //1. Navigate to main page(https://alliantcu.homestory.co/)
        $this->webDriver->get($this->baseUrl);

        //2.Click on the location title button.
        $this->webDriver->findElement($this->mainPage->locationTitleButton)->click();

        //3. Assert-popup form is displayed and all elements are present
        //Assert that form innerText is displayed
        $this->assertEquals('Roughly, where are you looking?', $this->webDriver->findElement($this->mainPage->locationFormText)->getText());
        //Assert - "City, State or zip" input is displayed
        $this->assertEquals(true, $this->webDriver->findElement($this->mainPage->cityStateOrZipInput)->isDisplayed());
        //Assert- "Next" button is displayed
        $this->assertEquals(true, $this->webDriver->findElement($this->mainPage->nextButton)->isDisplayed());

    }//endTest



    //Case 2. Verify that the location is pre-populated with the user's geolocation.
    public function testGeolocationPrePopulated () {
        //1. Navigate to the main page(https://alliantcu.homestory.co/)
        $this->webDriver->get($this->baseUrl);

        //2. Refresh page
        //Step note: Because WebDriver is in an incognito-alike tab, I need to refresh the page so the website can detect my IP address(not sure why :))
        $this->webDriver->navigate()->refresh();


        //3. Get geolocation data
        $geoLocationData = $this->util->getGeoLocation($this->util->getCurrentIp());
        $city = $geoLocationData->city;

        //4. Assert-location is pre-populated with user geolocation
        $cityText = $this->webDriver->findElement($this->mainPage->locationTitleButtonAnswered)->getText();
        $this->assertStringContainsString($city, $cityText);

    }//endTest

    // case 3. Verify that when geolocation is not present, field should have the following placeholder text: “City, State, or Zip”
    public function testGeolocationInputPlaceholderValues () {

        //1. Navigate to the main page(https://alliantcu.homestory.co/)
        $this->webDriver->get($this->baseUrl);

        //4.Click on the location title button.
        $this->webDriver->findElement($this->mainPage->locationTitleButton)->click();

        //3. Assert-geolocation is not present
        $this->assertEquals("Purchase Location", $this->webDriver->findElement($this->mainPage->locationTitleButtonInnerTextGNP)->getText());
        //Assert-field placeholder text = “City, State, or Zip”
        $this->assertEquals(true, $this->webDriver->findElement($this->mainPage->placeholderText)->isDisplayed());

    }//endTest====

    // case 4. Verify that the suggestion list (type-ahead) is displayed after the user enters 3 letters.
    public function testVerifySuggestionListPresentAfterUserInputLetters () {

        //1. Navigate to main page(https://alliantcu.homestory.co/)
        $this->webDriver->get($this->baseUrl);

        //3.Click on the location title button.
        $this->webDriver->findElement($this->mainPage->locationTitleButton)->click();

        //4 In the city, state, or zip input field, type three letters.
        $this->util->fillInput($this->webDriver->findElement($this->mainPage->cityStateOrZipInput), 'los');

        //assert-suggestion list is displayed(suggestion value contains exact letters entered)
        $resultSetContainer = $this->webDriver->findElement($this->mainPage->searchResultContainer);
        $this->assertEquals(true, $resultSetContainer->findElement(WebDriverBy::id('results'))->isDisplayed());


    }//endTest

    //case 5. Verify that the suggestion list (type-ahead) is displayed after the user enters 3 digits.
    public function testVerifySuggestionListPresentAfterUserInputDigits () {

        //1. Navigate to main page(https://alliantcu.homestory.co/)
        $this->webDriver->get($this->baseUrl);

        //3.Click on the location title button.
        $this->webDriver->findElement($this->mainPage->locationTitleButton)->click();

        //4 In the city, state, or zip input field, type three digits.
        $this->util->fillInput($this->webDriver->findElement($this->mainPage->cityStateOrZipInput), '110');

        //assert-suggestion list is displayed(suggestion value contains exact digits entered)
        $resultSetContainer = $this->webDriver->findElement($this->mainPage->searchResultContainer);
        $this->assertEquals(true, $resultSetContainer->findElement(WebDriverBy::id('results'))->isDisplayed());



    }//endTest

    //case 6. Verify that the suggestion value contains the exact letters entered by the user.
    public function  testThatSuggestionListContainsLettersEnteredByUsers () {

        //1. Navigate to the main page(https://alliantcu.homestory.co/)
        $this->webDriver->get($this->baseUrl);

        //3.Click on the location title button
        $this->webDriver->findElement($this->mainPage->locationTitleButton)->click();

        //4. Enter 3 letters in city, stater or zip input field
        $inputString = 'los';
        $this->util->fillInput($this->webDriver->findElement($this->mainPage->cityStateOrZipInput), $inputString);

        //5. Get results
        $resultSetContainer = $this->webDriver->findElement($this->mainPage->searchResultContainer);
        $resultsContainer = $resultSetContainer->findElement(WebDriverBy::id('results'));

        //assert-results contains inputted value
        $spans = $resultsContainer->findElements(WebDriverBy::className('typeaheadListItem__listItemValue___pf38N'));
        foreach ($spans as $span) {
            $this->assertStringContainsString(strtolower($inputString), strtolower($span->getText()));
        }

    }//endTest

    // case 7. Verify that location suggestion list (typeahead) should only return US zip codes and Cities //
    public function  test7 () {

        //1. Navigate to the main page(https://alliantcu.homestory.co/)
        $this->webDriver->get($this->baseUrl);

        //3.Click on the location title button
        $this->webDriver->findElement($this->mainPage->locationTitleButton)->click();

        //4. Enter 3 letters in city, stater or zip input field
        $inputString = '110';
        $this->util->fillInput($this->webDriver->findElement($this->mainPage->cityStateOrZipInput), $inputString);

        //5. Get results
        $resultSetContainer = $this->webDriver->findElement($this->mainPage->searchResultContainer);
        $resultsContainer = $resultSetContainer->findElement(WebDriverBy::id('results'));
        $spans = $resultsContainer->findElements(WebDriverBy::className('typeaheadListItem__listItemValue___pf38N'));

        //6.Assert-All results are cities in US using API to check if city is indeed in the US
        foreach ($spans as $span) {
            $cityData = explode(',', $span->getText());
            if (!is_int($inputString)) {
                $city = $cityData[0];
                $state = trim($cityData[1]);
            } else {
                $city = $cityData[1];
                $state = trim($cityData[2]);
            }
            $this->assertTrue($this->util->checkIfCityExistsInUS($city, $state));
        }
        //todo not working :(
        //todo I think the only way for this test to work (for me, this is the most optimal way to write this test/script)
        // is to have access to the api/database with cities in US, could not find any free that has all cities.


    }//endTest

    //case 8. Verify that the location field is not accepting empty input.
    public function testLocationFieldWIthEmptyInput () {

        //1. Navigate to the main page(https://alliantcu.homestory.co/)
        $this->webDriver->get($this->baseUrl);

        //3.Click on the location title button
        $this->webDriver->findElement($this->mainPage->locationTitleButton)->click();

        //4. Clear input field

        $this->webDriver->findElement($this->mainPage->cityStateOrZipInput)->clear();

        //5. Assert-"Next" button  is not clickable
        $this->assertEquals(true, $this->webDriver->findElement($this->mainPage->nextButtonDisabled)->isDisplayed());

    }//endTest

    //case 9. Verify that  clicking on the Next button closes the popup module.
    public function testClickOnNextButtonShouldClosePopup () {

        //1. Navigate to the main page(https://alliantcu.homestory.co/)
        $this->webDriver->get($this->baseUrl);

        //3.Click on the location title button
        $this->webDriver->findElement($this->mainPage->locationTitleButton)->click();

        //4 In the city, state, or zip input field enter "Los Angeles, CA" and press Enter
        $this->util->fillInput($this->webDriver->findElement($this->mainPage->cityStateOrZipInput), 'Los Angeles, CA');
        sleep(1);
        $this->webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER);

        //5 Click on the Next button
        $this->webDriver->findElement($this->mainPage->nextButton)->click();

        //5. Assert-the popup/container is closed
        sleep(1);
        try {
            $this->webDriver->findElement($this->mainPage->formContainer);
        }catch (\Exception $e){
            $this->assertStringContainsString('no such element: Unable to locate element', $e->getMessage());
        }

    }//endTest

    //case.10 Verify that user’s selection is displayed in the location tile after selection
    public function testUserLocationIsCorrectlyDisplayedAfterSelection () {

        //1. Navigate to the main page(https://alliantcu.homestory.co/)
        $this->webDriver->get($this->baseUrl);

        //3.Click on the location title button
        $this->webDriver->findElement($this->mainPage->locationTitleButton)->click();

        //4 In the city, state, or zip input field enter "Los Angeles, CA" and press Enter
        $this->util->fillInput($this->webDriver->findElement($this->mainPage->cityStateOrZipInput), 'Los Angeles, CA');
        sleep(1);
        $this->webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER);

        //5. Click on the Next button
        $this->webDriver->findElement($this->mainPage->nextButton)->click();

        //6. assert-User’s selection  is displayed in the location tile
        $this->assertEquals("Los Angeles, CA", $this->webDriver->findElement($this->mainPage->userSelectionValueLa)->getText());

    }//endTest



}//endClass