<?php

// require_once 'vendor/behat/mink-extension/src/Behat/MinkExtension/Context/MinkContext.php';

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\MinkExtension\Context\MinkContext;

class FeatureContext implements Context
{
    /**
     * @var MinkContext
     */
    private $minkContext;

    /**
     * FeatureContext constructor.
     * @param MinkContext $minkContext
     */
    public function __construct(MinkContext $minkContext)
    {
        $this->minkContext = $minkContext;
    }

    /**
     * @BeforeScenario
     */
    public function setUp()
    {
        // Placez ici le code de configuration de votre application avant chaque scenario
    }

    /**
     * @AfterStep
     */
    public function takeScreenshotAfterFailedStep(AfterStepScope $scope)
    {
        // Prendre une capture d'écran après chaque étape qui échoue
        if ($scope->getTestResult()->isFailed()) {
            $this->minkContext->takeScreenshot();
        }
    }

    /**
     * @Given je suis sur la page d'accueil
     */
    public function jeSuisSurLaPageDAccueil()
    {
        $this->minkContext->visit('/');
    }

    /**
     * @Then la variable de session :sessionName est initialisée avec :value
     */
    public function laVariableDeSessionEstInitialiseeAvec($sessionName, $value)
    {
        $this->assertSession()->arrayHasKey($sessionName, $_SESSION);
        $this->assertEquals($value, $_SESSION[$sessionName]);
    }

    /**
     * @When je saisis :message dans le champ :fieldName et que je clique sur :buttonName
     */
    public function jeSaisisDansLeChampEtQueJeCliqueSur($message, $fieldName, $buttonName)
    {
        $this->getSession()->getPage()->fillField($fieldName, $message);
        $this->getSession()->getPage()->pressButton($buttonName);
    }

    /**
     * @Then le message :message est ajouté au salon actuel dans la variable de session :sessionName
     */
    public function leMessageEstAjouteAuSalonActuelDansLaVariableDeSession($message, $sessionName)
    {
        $this->assertSession()->arrayHasKey('chatrooms', $_SESSION);
        $currentRoom = $_SESSION['chatroom'];
        $this->assertSession()->arrayHasKey($currentRoom, $_SESSION['chatrooms']);
        $this->assertContains($message, $_SESSION['chatrooms'][$currentRoom]);
    }

    /**
     * @Then le message :message est affiché sur la page dans le salon actuel
     */
    public function leMessageEstAfficheSurLaPageDansLeSalonActuel($message)
    {
        $this->assertSession()->pageTextContains($message);
    }

    /**
     * @Then la variable de session :sessionName est mise à jour avec le temps du dernier message envoyé
     */
    public function laVariableDeSessionEstMiseAJourAvecLeTempsDuDernierMessageEnvoye($sessionName)
    {
        $this->assertSession()->arrayHasKey($sessionName, $_SESSION);
        $this->assertNotNull($_SESSION[$sessionName]);
    }

    /**
     * @Then je vois le message d'erreur :errorMessage
     */
    public function jeVoisLeMessageDErreur($errorMessage)
    {
        $this->assertSession()->pageTextContains($errorMessage);
    }

    /**
     * @Given je suis connecté avec le compte :username
     */
    public function jeSuisConnecteAvecLeCompte($username)
    {
        $this->minkContext->visit('/login');
        $this->getSession()->getPage()->fillField('username', $username);
        $this->getSession()->getPage()->fillField('password', 'password');
        $this->getSession()->getPage()->pressButton('_submit');
    }

    /**
     * @When j'ouvre le salon :roomName
     */
    public function jOuvreLeSalon($roomName)
    {
        $this->minkContext->visit('/chat/' . $roomName);
    }

    /**
     * @Given le message :message a été envoyé il y a moins de :seconds secondes
     */
    public function leMessageAEteEnvoyeIlYAMoinsDeSecondes($message, $seconds)
    {
        $this->assertSession()->arrayHasKey('chatrooms', $_SESSION);
        $currentRoom = $_SESSION['chatroom'];
        $messages = $_SESSION['chatrooms'][$currentRoom];
        $this->assertContains($message, $messages);

        $lastMessage = end($messages);
        $this->assertLessThan($seconds, time() - strtotime($lastMessage['time']));
    }
}