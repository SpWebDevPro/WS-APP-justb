<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Tests\Integration\Studio\V1;

use Twilio\Exceptions\DeserializeException;
use Twilio\Exceptions\TwilioException;
use Twilio\Http\Response;
use Twilio\Tests\HolodeckTestCase;
use Twilio\Tests\Request;

class FlowTest extends HolodeckTestCase {
    public function testReadRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->studio->v1->flows->read();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://studio.twilio.com/v1/Flows'
        ));
    }

    public function testReadEmptyResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "meta": {
                    "previous_page_url": null,
                    "next_page_url": null,
                    "url": "https://studio.twilio.com/v1/Flows?PageSize=50&Page=0",
                    "page": 0,
                    "first_page_url": "https://studio.twilio.com/v1/Flows?PageSize=50&Page=0",
                    "page_size": 50,
                    "key": "flows"
                },
                "flows": []
            }
            '
        ));

        $actual = $this->twilio->studio->v1->flows->read();

        $this->assertNotNull($actual);
    }

    public function testFetchRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->studio->v1->flows("FWXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://studio.twilio.com/v1/Flows/FWXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        ));
    }

    public function testFetchResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "sid": "FWaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "friendly_name": "Test Flow",
                "status": "published",
                "version": 1,
                "date_created": "2017-11-06T12:00:00Z",
                "date_updated": null,
                "url": "https://studio.twilio.com/v1/Flows/FWaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "links": {
                    "engagements": "https://studio.twilio.com/v1/Flows/FWaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Engagements",
                    "executions": "https://studio.twilio.com/v1/Flows/FWaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Executions"
                }
            }
            '
        ));

        $actual = $this->twilio->studio->v1->flows("FWXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();

        $this->assertNotNull($actual);
    }

    public function testDeleteRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->studio->v1->flows("FWXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->delete();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'delete',
            'https://studio.twilio.com/v1/Flows/FWXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        ));
    }

    public function testDeleteResponse() {
        $this->holodeck->mock(new Response(
            204,
            null
        ));

        $actual = $this->twilio->studio->v1->flows("FWXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->delete();

        $this->assertTrue($actual);
    }
}