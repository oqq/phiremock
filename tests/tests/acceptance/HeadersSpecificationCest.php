<?php

use Mcustiel\Phiremock\Domain\Request;
use Mcustiel\Phiremock\Domain\Response;
use Mcustiel\Phiremock\Domain\Expectation;
use Mcustiel\Phiremock\Domain\Condition;

class HeadersSpecificationCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->sendDELETE('/__phiremock/expectation');
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function createSpecificationWithOneHeaderInResponseTest(AcceptanceTester $I)
    {
        $I->wantTo('create an specification with one header in response');
        $request = new Request();
        $request->setUrl(new Condition('isEqualTo', '/the/request/url'));
        $response = new Response();
        $response->setHeaders(['Location' => '/potato.php']);
        $specification = new Expectation();
        $specification->setRequest($request)->setResponse($response);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/__phiremock/expectation', $specification);

        $I->sendGET('/__phiremock/expectation');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseEquals(
            '[{"scenarioName":null,"scenarioStateIs":null,"newScenarioState":null,'
            . '"request":{"method":null,"url":{"isEqualTo":"\/the\/request\/url"},"body":null,"headers":null},'
            . '"response":{"statusCode":200,"body":null,"headers":{"Location":"\/potato.php"},"delayMillis":null}}]'
        );
    }

    public function createSpecificationWithMoreThanOneHeaderInResponseTest(AcceptanceTester $I)
    {
        $I->wantTo('create an specification with several headers in response');
        $request = new Request();
        $request->setUrl(new Condition('isEqualTo', '/the/request/url'));
        $response = new Response();
        $response->setHeaders([
            'Location' => '/potato.php',
            'Cache-Control' => 'private, max-age=0, no-cache',
            'Pragma' => 'no-cache'
        ]);
        $specification = new Expectation();
        $specification->setRequest($request)->setResponse($response);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/__phiremock/expectation', $specification);

        $I->sendGET('/__phiremock/expectation');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseEquals(
            '[{"scenarioName":null,"scenarioStateIs":null,"newScenarioState":null,'
            . '"request":{"method":null,"url":{"isEqualTo":"\/the\/request\/url"},"body":null,"headers":null},'
            . '"response":{"statusCode":200,"body":null,"headers":{'
            . '"Location":"\/potato.php","Cache-Control":"private, max-age=0, no-cache",'
            . '"Pragma":"no-cache"},"delayMillis":null}}]'
        );
    }

    public function createSpecificationWithEmptyHeadersTest(AcceptanceTester $I)
    {
        $I->wantTo('create a specification with no headers in response');
        $request = new Request();
        $request->setUrl(new Condition('isEqualTo', '/the/request/url'));
        $response = new Response();
        $specification = new Expectation();
        $specification->setRequest($request)->setResponse($response);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/__phiremock/expectation', $specification);

        $I->sendGET('/__phiremock/expectation');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseEquals(
            '[{"scenarioName":null,"scenarioStateIs":null,"newScenarioState":null,'
            . '"request":{"method":null,"url":{"isEqualTo":"\/the\/request\/url"},"body":null,"headers":null},'
            . '"response":{"statusCode":200,"body":null,"headers":null,"delayMillis":null}}]'
        );
    }

    public function failOnEmptyHeadersInspecificationTest(AcceptanceTester $I)
    {
        $I->wantTo('fail when creating a specification with invalid headers');
        $request = new Request();
        $request->setUrl(new Condition('isEqualTo', '/the/request/url'));
        $response = (new Response())->setHeaders('potato');
        $specification = new Expectation();
        $specification->setRequest($request)->setResponse($response);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/__phiremock/expectation', $specification);
        $I->seeResponseCodeIs(500);
    }
}
