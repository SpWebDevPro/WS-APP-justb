<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Tests\Integration\Preview\Hostednumbers\AuthorizationDocument;

use Twilio\Exceptions\DeserializeException;
use Twilio\Exceptions\TwilioException;
use Twilio\Http\Response;
use Twilio\Tests\HolodeckTestCase;
use Twilio\Tests\Request;

class DependentHostedNumberOrderTest extends HolodeckTestCase {
    public function testReadRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->preview->hostedNumbers->authorizationDocuments("PXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                                 ->dependentHostedNumberOrders->read();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://preview.twilio.com/HostedNumbers/AuthorizationDocuments/PXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/DependentHostedNumberOrders'
        ));
    }

    public function testReadEmptyResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "meta": {
                    "first_page_url": "https://preview.twilio.com/HostedNumbers/AuthorizationDocuments/PXaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/DependentHostedNumberOrders?Status=completed&FriendlyName=example&PhoneNumber=%2B19193608000&UniqueName=something123&IncomingPhoneNumberSid=PNaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa&PageSize=50&Page=0",
                    "key": "items",
                    "next_page_url": null,
                    "page": 0,
                    "page_size": 50,
                    "previous_page_url": null,
                    "url": "https://preview.twilio.com/HostedNumbers/AuthorizationDocuments/PXaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/DependentHostedNumberOrders?Status=completed&FriendlyName=example&PhoneNumber=%2B19193608000&UniqueName=something123&IncomingPhoneNumberSid=PNaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa&PageSize=50&Page=0"
                },
                "items": []
            }
            '
        ));

        $actual = $this->twilio->preview->hostedNumbers->authorizationDocuments("PXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                                       ->dependentHostedNumberOrders->read();

        $this->assertNotNull($actual);
    }

    public function testReadFullResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "meta": {
                    "first_page_url": "https://preview.twilio.com/HostedNumbers/AuthorizationDocuments/PXaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/DependentHostedNumberOrders?PageSize=50&Page=0",
                    "key": "items",
                    "next_page_url": null,
                    "page": 0,
                    "page_size": 50,
                    "previous_page_url": null,
                    "url": "https://preview.twilio.com/HostedNumbers/AuthorizationDocuments/PXaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/DependentHostedNumberOrders?PageSize=50&Page=0"
                },
                "items": [
                    {
                        "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "address_sid": "AD11111111111111111111111111111111",
                        "call_delay": 15,
                        "capabilities": {
                            "sms": true,
                            "voice": false
                        },
                        "cc_emails": [
                            "aaa@twilio.com",
                            "bbb@twilio.com"
                        ],
                        "date_created": "2017-03-28T20:06:39Z",
                        "date_updated": "2017-03-28T20:06:39Z",
                        "email": "test@twilio.com",
                        "extension": "1234",
                        "friendly_name": "friendly_name",
                        "incoming_phone_number_sid": "PN11111111111111111111111111111111",
                        "phone_number": "+14153608311",
                        "sid": "HRaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "signing_document_sid": "PX11111111111111111111111111111111",
                        "status": "received",
                        "failure_reason": "",
                        "unique_name": "foobar",
                        "verification_attempts": 0,
                        "verification_call_sids": [
                            "CAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                            "CAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaab"
                        ],
                        "verification_code": "8794",
                        "verification_document_sid": null,
                        "verification_type": "phone-call"
                    }
                ]
            }
            '
        ));

        $actual = $this->twilio->preview->hostedNumbers->authorizationDocuments("PXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                                       ->dependentHostedNumberOrders->read();

        $this->assertGreaterThan(0, \count($actual));
    }
}