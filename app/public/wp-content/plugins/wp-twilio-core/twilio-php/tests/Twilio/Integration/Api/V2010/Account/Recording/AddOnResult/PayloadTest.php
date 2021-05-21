<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Tests\Integration\Api\V2010\Account\Recording\AddOnResult;

use Twilio\Exceptions\DeserializeException;
use Twilio\Exceptions\TwilioException;
use Twilio\Http\Response;
use Twilio\Tests\HolodeckTestCase;
use Twilio\Tests\Request;

class PayloadTest extends HolodeckTestCase {
    public function testFetchRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->api->v2010->accounts("ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->recordings("REXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->addOnResults("XRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->payloads("XHXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://api.twilio.com/2010-04-01/Accounts/ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Recordings/REXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/AddOnResults/XRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Payloads/XHXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX.json'
        ));
    }

    public function testFetchResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "sid": "XHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "reference_sid": "REaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "add_on_sid": "XBaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "add_on_configuration_sid": "XEaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "add_on_result_sid": "XRaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "label": "XHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "content_type": "application/json",
                "date_created": "Wed, 01 Sep 2010 15:15:41 +0000",
                "date_updated": "Wed, 01 Sep 2010 15:15:41 +0000",
                "subresource_uris": {
                    "data": "/2010-04-01/Accounts/ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Recordings/REaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/AddOnResults/XRaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Payloads/XHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Data.json"
                }
            }
            '
        ));

        $actual = $this->twilio->api->v2010->accounts("ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->recordings("REXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->addOnResults("XRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->payloads("XHXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();

        $this->assertNotNull($actual);
    }

    public function testReadRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->api->v2010->accounts("ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->recordings("REXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->addOnResults("XRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->payloads->read();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://api.twilio.com/2010-04-01/Accounts/ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Recordings/REXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/AddOnResults/XRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Payloads.json'
        ));
    }

    public function testReadFullResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "end": 0,
                "first_page_uri": "/2010-04-01/Accounts/ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Recordings/REaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/AddOnResults/XRaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Payloads.json?PageSize=50&Page=0",
                "next_page_uri": null,
                "page": 0,
                "page_size": 50,
                "previous_page_uri": null,
                "payloads": [
                    {
                        "sid": "XHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "reference_sid": "REaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "add_on_sid": "XBaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "add_on_configuration_sid": "XEaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "add_on_result_sid": "XRaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "label": "XHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "content_type": "application/json",
                        "date_created": "Wed, 01 Sep 2010 15:15:41 +0000",
                        "date_updated": "Wed, 01 Sep 2010 15:15:41 +0000",
                        "subresource_uris": {
                            "data": "/2010-04-01/Accounts/ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Recordings/REaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/AddOnResults/XRaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Payloads/XHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Data.json"
                        }
                    }
                ],
                "start": 0,
                "uri": "/2010-04-01/Accounts/ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Recordings/REaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/AddOnResults/XRaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Payloads.json?PageSize=50&Page=0"
            }
            '
        ));

        $actual = $this->twilio->api->v2010->accounts("ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->recordings("REXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->addOnResults("XRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->payloads->read();

        $this->assertGreaterThan(0, \count($actual));
    }

    public function testReadEmptyResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "end": 0,
                "first_page_uri": "/2010-04-01/Accounts/ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Recordings/REaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/AddOnResults/XRaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Payloads.json?PageSize=50&Page=0",
                "next_page_uri": null,
                "page": 0,
                "page_size": 50,
                "previous_page_uri": null,
                "payloads": [],
                "start": 0,
                "uri": "/2010-04-01/Accounts/ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Recordings/REaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/AddOnResults/XRaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Payloads.json?PageSize=50&Page=0"
            }
            '
        ));

        $actual = $this->twilio->api->v2010->accounts("ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->recordings("REXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->addOnResults("XRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->payloads->read();

        $this->assertNotNull($actual);
    }

    public function testDeleteRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->api->v2010->accounts("ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->recordings("REXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->addOnResults("XRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->payloads("XHXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->delete();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'delete',
            'https://api.twilio.com/2010-04-01/Accounts/ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Recordings/REXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/AddOnResults/XRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Payloads/XHXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX.json'
        ));
    }

    public function testDeleteResponse() {
        $this->holodeck->mock(new Response(
            204,
            null
        ));

        $actual = $this->twilio->api->v2010->accounts("ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->recordings("REXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->addOnResults("XRXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->payloads("XHXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->delete();

        $this->assertTrue($actual);
    }
}