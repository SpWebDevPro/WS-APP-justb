<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Tests\Integration\Trunking\V1\Trunk;

use Twilio\Exceptions\DeserializeException;
use Twilio\Exceptions\TwilioException;
use Twilio\Http\Response;
use Twilio\Serialize;
use Twilio\Tests\HolodeckTestCase;
use Twilio\Tests\Request;

class OriginationUrlTest extends HolodeckTestCase {
    public function testFetchRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->trunking->v1->trunks("TRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                       ->originationUrls("OUXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://trunking.twilio.com/v1/Trunks/TRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/OriginationUrls/OUXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        ));
    }

    public function testFetchResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "weight": 1,
                "date_updated": "2018-05-07T20:20:46Z",
                "enabled": false,
                "friendly_name": "friendly_name",
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "priority": 1,
                "sip_url": "sip://sip-box.com:1234",
                "sid": "OUaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "date_created": "2018-05-07T20:20:46Z",
                "trunk_sid": "TRaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "url": "https://trunking.twilio.com/v1/Trunks/TRaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/OriginationUrls/OUaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
            }
            '
        ));

        $actual = $this->twilio->trunking->v1->trunks("TRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                             ->originationUrls("OUXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();

        $this->assertNotNull($actual);
    }

    public function testDeleteRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->trunking->v1->trunks("TRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                       ->originationUrls("OUXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->delete();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'delete',
            'https://trunking.twilio.com/v1/Trunks/TRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/OriginationUrls/OUXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        ));
    }

    public function testDeleteResponse() {
        $this->holodeck->mock(new Response(
            204,
            null
        ));

        $actual = $this->twilio->trunking->v1->trunks("TRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                             ->originationUrls("OUXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->delete();

        $this->assertTrue($actual);
    }

    public function testCreateRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->trunking->v1->trunks("TRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                       ->originationUrls->create(1, 1, True, "friendly_name", "https://example.com");
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $values = array(
            'Weight' => 1,
            'Priority' => 1,
            'Enabled' => Serialize::booleanToString(True),
            'FriendlyName' => "friendly_name",
            'SipUrl' => "https://example.com",
        );

        $this->assertRequest(new Request(
            'post',
            'https://trunking.twilio.com/v1/Trunks/TRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/OriginationUrls',
            null,
            $values
        ));
    }

    public function testCreateResponse() {
        $this->holodeck->mock(new Response(
            201,
            '
            {
                "weight": 1,
                "date_updated": "2018-05-07T20:50:58Z",
                "enabled": true,
                "friendly_name": "friendly_name",
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "priority": 1,
                "sip_url": "sip://sip-box.com:1234",
                "sid": "OUaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "date_created": "2018-05-07T20:50:58Z",
                "trunk_sid": "TRaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "url": "https://trunking.twilio.com/v1/Trunks/TRaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/OriginationUrls/OUaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
            }
            '
        ));

        $actual = $this->twilio->trunking->v1->trunks("TRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                             ->originationUrls->create(1, 1, True, "friendly_name", "https://example.com");

        $this->assertNotNull($actual);
    }

    public function testReadRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->trunking->v1->trunks("TRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                       ->originationUrls->read();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://trunking.twilio.com/v1/Trunks/TRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/OriginationUrls'
        ));
    }

    public function testReadFullResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "meta": {
                    "first_page_url": "https://trunking.twilio.com/v1/Trunks/TRaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/OriginationUrls?PageSize=50&Page=0",
                    "key": "origination_urls",
                    "next_page_url": null,
                    "page": 0,
                    "page_size": 50,
                    "previous_page_url": null,
                    "url": "https://trunking.twilio.com/v1/Trunks/TRaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/OriginationUrls?PageSize=50&Page=0"
                },
                "origination_urls": [
                    {
                        "weight": 1,
                        "date_updated": "2018-05-09T20:47:35Z",
                        "enabled": true,
                        "friendly_name": "friendly_name",
                        "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "priority": 1,
                        "sip_url": "sip://sip-box.com:1234",
                        "sid": "OUaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "date_created": "2018-05-09T20:47:35Z",
                        "trunk_sid": "TRaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "url": "https://trunking.twilio.com/v1/Trunks/TRaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/OriginationUrls/OUaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
                    }
                ]
            }
            '
        ));

        $actual = $this->twilio->trunking->v1->trunks("TRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                             ->originationUrls->read();

        $this->assertGreaterThan(0, \count($actual));
    }

    public function testReadEmptyResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "meta": {
                    "first_page_url": "https://trunking.twilio.com/v1/Trunks/TRaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/OriginationUrls?PageSize=50&Page=0",
                    "key": "origination_urls",
                    "next_page_url": null,
                    "page": 0,
                    "page_size": 50,
                    "previous_page_url": null,
                    "url": "https://trunking.twilio.com/v1/Trunks/TRaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/OriginationUrls?PageSize=50&Page=0"
                },
                "origination_urls": []
            }
            '
        ));

        $actual = $this->twilio->trunking->v1->trunks("TRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                             ->originationUrls->read();

        $this->assertNotNull($actual);
    }

    public function testUpdateRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->trunking->v1->trunks("TRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                       ->originationUrls("OUXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->update();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'post',
            'https://trunking.twilio.com/v1/Trunks/TRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/OriginationUrls/OUXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        ));
    }

    public function testUpdateResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "weight": 2,
                "date_updated": "2018-05-07T20:50:58Z",
                "enabled": false,
                "friendly_name": "updated_name",
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "priority": 2,
                "sip_url": "sip://sip-updated.com:4321",
                "sid": "OUaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "date_created": "2018-05-07T20:50:58Z",
                "trunk_sid": "TRaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "url": "https://trunking.twilio.com/v1/Trunks/TRaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/OriginationUrls/OUaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
            }
            '
        ));

        $actual = $this->twilio->trunking->v1->trunks("TRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                             ->originationUrls("OUXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->update();

        $this->assertNotNull($actual);
    }
}