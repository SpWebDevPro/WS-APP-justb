<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Tests\Integration\Messaging\V1\Service;

use Twilio\Exceptions\DeserializeException;
use Twilio\Exceptions\TwilioException;
use Twilio\Http\Response;
use Twilio\Tests\HolodeckTestCase;
use Twilio\Tests\Request;

class PhoneNumberTest extends HolodeckTestCase {
    public function testCreateRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->messaging->v1->services("MGXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                        ->phoneNumbers->create("PNXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX");
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $values = array('PhoneNumberSid' => "PNXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX", );

        $this->assertRequest(new Request(
            'post',
            'https://messaging.twilio.com/v1/Services/MGXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/PhoneNumbers',
            null,
            $values
        ));
    }

    public function testCreateResponse() {
        $this->holodeck->mock(new Response(
            201,
            '
            {
                "sid": "PNaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "service_sid": "MGaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "date_created": "2015-07-30T20:12:31Z",
                "date_updated": "2015-07-30T20:12:33Z",
                "phone_number": "+987654321",
                "country_code": "US",
                "capabilities": [],
                "url": "https://messaging.twilio.com/v1/Services/MGaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/PhoneNumbers/PNaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
            }
            '
        ));

        $actual = $this->twilio->messaging->v1->services("MGXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                              ->phoneNumbers->create("PNXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX");

        $this->assertNotNull($actual);
    }

    public function testCreateWithCapabilitiesResponse() {
        $this->holodeck->mock(new Response(
            201,
            '
            {
                "sid": "PNaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "service_sid": "MGaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "date_created": "2015-07-30T20:12:31Z",
                "date_updated": "2015-07-30T20:12:33Z",
                "phone_number": "+987654321",
                "country_code": "US",
                "capabilities": [
                    "MMS",
                    "SMS",
                    "Voice"
                ],
                "url": "https://messaging.twilio.com/v1/Services/MGaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/PhoneNumbers/PNaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
            }
            '
        ));

        $actual = $this->twilio->messaging->v1->services("MGXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                              ->phoneNumbers->create("PNXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX");

        $this->assertNotNull($actual);
    }

    public function testDeleteRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->messaging->v1->services("MGXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                        ->phoneNumbers("PNXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->delete();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'delete',
            'https://messaging.twilio.com/v1/Services/MGXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/PhoneNumbers/PNXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        ));
    }

    public function testDeleteResponse() {
        $this->holodeck->mock(new Response(
            204,
            null
        ));

        $actual = $this->twilio->messaging->v1->services("MGXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                              ->phoneNumbers("PNXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->delete();

        $this->assertTrue($actual);
    }

    public function testReadRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->messaging->v1->services("MGXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                        ->phoneNumbers->read();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://messaging.twilio.com/v1/Services/MGXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/PhoneNumbers'
        ));
    }

    public function testReadFullResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "meta": {
                    "page": 0,
                    "page_size": 50,
                    "first_page_url": "https://messaging.twilio.com/v1/Services/MGaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/PhoneNumbers?PageSize=50&Page=0",
                    "previous_page_url": null,
                    "next_page_url": null,
                    "key": "phone_numbers",
                    "url": "https://messaging.twilio.com/v1/Services/MGaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/PhoneNumbers?PageSize=50&Page=0"
                },
                "phone_numbers": [
                    {
                        "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "service_sid": "MGaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "sid": "PNaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "date_created": "2015-07-30T20:12:31Z",
                        "date_updated": "2015-07-30T20:12:33Z",
                        "phone_number": "+987654321",
                        "country_code": "US",
                        "capabilities": [],
                        "url": "https://messaging.twilio.com/v1/Services/MGaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/PhoneNumbers/PNaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
                    }
                ]
            }
            '
        ));

        $actual = $this->twilio->messaging->v1->services("MGXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                              ->phoneNumbers->read();

        $this->assertGreaterThan(0, \count($actual));
    }

    public function testFetchRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->messaging->v1->services("MGXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                        ->phoneNumbers("PNXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://messaging.twilio.com/v1/Services/MGXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/PhoneNumbers/PNXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        ));
    }

    public function testFetchResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "sid": "SCaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "service_sid": "MGaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "date_created": "2015-07-30T20:12:31Z",
                "date_updated": "2015-07-30T20:12:33Z",
                "phone_number": "12345",
                "country_code": "US",
                "capabilities": [],
                "url": "https://messaging.twilio.com/v1/Services/MGaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/PhoneNumbers/SCaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
            }
            '
        ));

        $actual = $this->twilio->messaging->v1->services("MGXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                              ->phoneNumbers("PNXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();

        $this->assertNotNull($actual);
    }
}