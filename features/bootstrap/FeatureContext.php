<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->bearerToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIzIiwianRpIjoiNWJjY2RhNTc1OTkxMGQyYTM5MTBjMjJhYjY3N2QwNWJmNDY2ZTRhYWJlZDgyNTdjNzQyNTE0MmIyMmNkM2JjNzdmNzYzYmNhODQ2YTcyOTYiLCJpYXQiOjE1OTU0NDg2NzIsIm5iZiI6MTU5NTQ0ODY3MiwiZXhwIjoxNjI2OTg0NjcyLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.DVllx6frQPxCjvt0wAobIVE-GNcoWabC6hMeJfrzVApRaMN0PbHqPYCy-B9WsOiSXYTzClFK6Oe4G89EELKVNwuMbuzwnUIjXGXO5p2hrV3TjjW7vOha5GpnvMawI8qw_S06IZcCsFmJ1FWT_rKiiUkRxfKzpT0l8EJegBIOAA4o18qMsNb1z9eliKaPOAhrFopCxI39YJPcILiakKRvxRRa-QTvJoM3w49kIjXef974ZU3P5VJ2eI3BZg63q0HRk5Nvxkt34DIGs-xzkOuIx4VkyjugOAlkF7N_Vked2K6_MZc86avzx_Nv9czA6aDQXSL87VlJO1yrsWYLZ9b4HKqtT7jyxBtKxYfFAIIE1ixQqSs1XO7aavMtnSBFU08KbTWSDUqnEu3fxfGCKOGDawa27NbOOE9RPcsEzpalJ09teBWBz6e-tKqTiQHEKELz1hD2lTPUv1gIr6-3p-GrSXgurn5rv9zyOcY5IgkwGwwzHGyoMYsbwgedzxJTFwKOz-lv6BXmWJNoFg2sQ9mpjFz9FFu9TwXCaoi7-BfjiP49NTE38Zk3N6lax9GtL-dWlqKXkLbqf9swiUIi-IJZ69jc_OeBHxR_TKupartKV7TFuYOIsx8W4ULJlEsHzWEf7Nq39X-bGOZ5MMIzTELSnsbnsuee78uHiVrxnoTm0G8";
    }

    /**
     * @Given I have the payload:
     */
    public function iHaveThePayload(PyStringNode $string)
    {
        $this->payload = $string;
    }

    /**
     * @When /^I request "(GET|PUT|POST|DELETE|PATCH) ([^"]*)"$/
     */
    public function iRequest($httpMethod, $argument1)
    {
        $client = new GuzzleHttp\Client();
        $this->response = $client->request(
            $httpMethod,
            'http://community-poll.loc' . $argument1,
            [
                'body' => $this->payload,
                'headers' => [
                    "Authorization" => "Bearer {$this->bearerToken}",
                    "Content-Type" => "application/json",
                ],
            ]
        );
        $this->responseBody = $this->response->getBody(true);
    }

    /**
     * @Then /^I get a response$/
     */
    public function iGetAResponse()
    {
        if (empty($this->responseBody)) {
            throw new Exception('Did not get a response from the API');
        }
    }

    /**
     * @Given /^the response is JSON$/
     */
    public function theResponseIsJson()
    {
        $data = json_decode($this->responseBody);

        if (empty($data)) {
            throw new Exception("Response was not JSON\n" . $this->responseBody);
        }
    }

    /**
     * @Then the response contains :arg1 records
     */
    public function theResponseContainsRecords($arg1)
    {
        $data = json_decode($this->responseBody);
        $count = count($data);
        return $count == $arg1;
    }

    /**
     * @Then the response contains a question
     */
    public function theResponseContainsAQuestion()
    {
        $data = json_decode($this->responseBody);

        $question = $data[0];
        if(!property_exists($question, 'question')) {
            throw new Exception('This is not a question');
        }
    }

    /**
     * @Then the question contains a title of :arg1
     * @throws Exception
     */
    public function theQuestionContainsATitleOf($arg1)
    {
        $data = json_decode($this->responseBody);
        if($data->title == $arg1) {

        } else {
            throw new Exception('The title does not match.');
        }
    }
}
