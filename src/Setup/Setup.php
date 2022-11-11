<?php
namespace Tests\Setup;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverWait;
use PHPUnit\Framework\TestCase;
use Tests\Utils\UtilFunctions;


class Setup extends TestCase
{
    protected WebDriver $webDriver;
    protected WebDriverWait $webDriverWait;
    protected string $baseUrl;
    protected $util;

    protected function setUp() :void
    {
        ini_set('max_execution_time', 300);
        $capabilities = DesiredCapabilities::chrome();
        $chromeOptions = new ChromeOptions();
        $capabilities->setCapability(ChromeOptions::CAPABILITY_W3C, $chromeOptions);
        $this->webDriver = RemoteWebDriver::create('http://localhost:4444/wd/hub', $capabilities);
        $this->util = new UtilFunctions($this->webDriver);
        $this->webDriverWait = $this->webDriver->wait(10);
        $this->webDriver->manage()->window()->maximize();
        $this->webDriver->manage()->timeouts()->implicitlyWait(10);
        $this->baseUrl = 'https://alliantcu.homestory.co/';
    }

    protected function tearDown() :void
    {
         $this->webDriver->quit();
    }
}