<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Tests\Integration\Serverless\V1\Service\TwilioFunction;

use Twilio\Exceptions\DeserializeException;
use Twilio\Exceptions\TwilioException;
use Twilio\Http\Response;
use Twilio\Tests\HolodeckTestCase;
use Twilio\Tests\Request;

class FunctionVersionTest extends HolodeckTestCase {
    public function testReadRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->serverless->v1->services("ZSXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                         ->functions("ZHXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                         ->functionVersions->read();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://serverless.twilio.com/v1/Services/ZSXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Functions/ZHXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Versions'
        ));
    }

    public function testReadEmptyResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "function_versions": [],
                "meta": {
                    "first_page_url": "https://serverless.twilio.com/v1/Services/ZS00000000000000000000000000000000/Functions/ZH00000000000000000000000000000000/Versions?PageSize=50&Page=0",
                    "key": "function_versions",
                    "next_page_url": null,
                    "page": 0,
                    "page_size": 50,
                    "previous_page_url": null,
                    "url": "https://serverless.twilio.com/v1/Services/ZS00000000000000000000000000000000/Functions/ZH00000000000000000000000000000000/Versions?PageSize=50&Page=0"
                }
            }
            '
        ));

        $actual = $this->twilio->serverless->v1->services("ZSXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                               ->functions("ZHXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                               ->functionVersions->read();

        $this->assertNotNull($actual);
    }

    public function testFetchRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->serverless->v1->services("ZSXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                         ->functions("ZHXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                         ->functionVersions("ZNXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://serverless.twilio.com/v1/Services/ZSXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Functions/ZHXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Versions/ZNXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        ));
    }

    public function testFetchResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "sid": "ZN00000000000000000000000000000000",
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "service_sid": "ZS00000000000000000000000000000000",
                "function_sid": "ZH00000000000000000000000000000000",
                "path": "test-path",
                "visibility": "public",
                "date_created": "2018-11-10T20:00:00Z",
                "url": "https://serverless.twilio.com/v1/Services/ZS00000000000000000000000000000000/Functions/ZH00000000000000000000000000000000/Versions/ZN00000000000000000000000000000000"
            }
            '
        ));

        $actual = $this->twilio->serverless->v1->services("ZSXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                               ->functions("ZHXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                               ->functionVersions("ZNXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();

        $this->assertNotNull($actual);
    }
}