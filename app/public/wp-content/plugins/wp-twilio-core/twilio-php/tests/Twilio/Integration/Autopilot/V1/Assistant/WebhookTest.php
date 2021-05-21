<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Tests\Integration\Autopilot\V1\Assistant;

use Twilio\Exceptions\DeserializeException;
use Twilio\Exceptions\TwilioException;
use Twilio\Http\Response;
use Twilio\Tests\HolodeckTestCase;
use Twilio\Tests\Request;

class WebhookTest extends HolodeckTestCase {
    public function testFetchRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->autopilot->v1->assistants("UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                        ->webhooks("UMXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://autopilot.twilio.com/v1/Assistants/UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Webhooks/UMXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        ));
    }

    public function testFetchResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "url": "https://autopilot.twilio.com/v1/Assistants/UAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Webhooks/UMaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "sid": "UMaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "date_updated": "2015-07-30T20:00:00Z",
                "assistant_sid": "UAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "date_created": "2015-07-30T20:00:00Z",
                "unique_name": "unique_name",
                "events": "ondialogueend",
                "webhook_url": "https://example.com/url",
                "webhook_method": "POST"
            }
            '
        ));

        $actual = $this->twilio->autopilot->v1->assistants("UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                              ->webhooks("UMXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();

        $this->assertNotNull($actual);
    }

    public function testReadRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->autopilot->v1->assistants("UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                        ->webhooks->read();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://autopilot.twilio.com/v1/Assistants/UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Webhooks'
        ));
    }

    public function testReadEmptyResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "meta": {
                    "key": "webhooks",
                    "page_size": 50,
                    "next_page_url": null,
                    "url": "https://autopilot.twilio.com/v1/Assistants/UAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Webhooks?PageSize=50&Page=0",
                    "page": 0,
                    "first_page_url": "https://autopilot.twilio.com/v1/Assistants/UAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Webhooks?PageSize=50&Page=0",
                    "previous_page_url": null
                },
                "webhooks": []
            }
            '
        ));

        $actual = $this->twilio->autopilot->v1->assistants("UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                              ->webhooks->read();

        $this->assertNotNull($actual);
    }

    public function testReadFullResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "meta": {
                    "key": "webhooks",
                    "page_size": 50,
                    "next_page_url": null,
                    "url": "https://autopilot.twilio.com/v1/Assistants/UAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Webhooks?PageSize=50&Page=0",
                    "page": 0,
                    "first_page_url": "https://autopilot.twilio.com/v1/Assistants/UAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Webhooks?PageSize=50&Page=0",
                    "previous_page_url": null
                },
                "webhooks": [
                    {
                        "url": "https://autopilot.twilio.com/v1/Assistants/UAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Webhooks/UMaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "sid": "UMaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "date_updated": "2015-07-30T20:00:00Z",
                        "assistant_sid": "UAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "date_created": "2015-07-30T20:00:00Z",
                        "unique_name": "unique_name",
                        "events": "ondialogueend",
                        "webhook_url": "https://example.com/url",
                        "webhook_method": "POST"
                    }
                ]
            }
            '
        ));

        $actual = $this->twilio->autopilot->v1->assistants("UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                              ->webhooks->read();

        $this->assertGreaterThan(0, \count($actual));
    }

    public function testCreateRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->autopilot->v1->assistants("UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                        ->webhooks->create("unique_name", "events", "https://example.com");
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $values = array(
            'UniqueName' => "unique_name",
            'Events' => "events",
            'WebhookUrl' => "https://example.com",
        );

        $this->assertRequest(new Request(
            'post',
            'https://autopilot.twilio.com/v1/Assistants/UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Webhooks',
            null,
            $values
        ));
    }

    public function testCreateResponse() {
        $this->holodeck->mock(new Response(
            201,
            '
            {
                "url": "https://autopilot.twilio.com/v1/Assistants/UAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Webhooks/UMaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "sid": "UMaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "date_updated": "2015-07-30T20:00:00Z",
                "assistant_sid": "UAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "date_created": "2015-07-30T20:00:00Z",
                "unique_name": "unique_name",
                "events": "ondialogueend",
                "webhook_url": "https://example.com/url",
                "webhook_method": "POST"
            }
            '
        ));

        $actual = $this->twilio->autopilot->v1->assistants("UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                              ->webhooks->create("unique_name", "events", "https://example.com");

        $this->assertNotNull($actual);
    }

    public function testUpdateRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->autopilot->v1->assistants("UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                        ->webhooks("UMXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->update();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'post',
            'https://autopilot.twilio.com/v1/Assistants/UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Webhooks/UMXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        ));
    }

    public function testUpdateResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "url": "https://autopilot.twilio.com/v1/Assistants/UAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Webhooks/UMaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "sid": "UMaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "date_updated": "2015-07-30T20:00:00Z",
                "assistant_sid": "UAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "date_created": "2015-07-30T20:00:00Z",
                "unique_name": "unique_name",
                "events": "ondialogueend",
                "webhook_url": "https://example.com/url",
                "webhook_method": "POST"
            }
            '
        ));

        $actual = $this->twilio->autopilot->v1->assistants("UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                              ->webhooks("UMXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->update();

        $this->assertNotNull($actual);
    }

    public function testDeleteRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->autopilot->v1->assistants("UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                        ->webhooks("UMXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->delete();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'delete',
            'https://autopilot.twilio.com/v1/Assistants/UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Webhooks/UMXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        ));
    }

    public function testDeleteResponse() {
        $this->holodeck->mock(new Response(
            204,
            null
        ));

        $actual = $this->twilio->autopilot->v1->assistants("UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                              ->webhooks("UMXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->delete();

        $this->assertTrue($actual);
    }
}