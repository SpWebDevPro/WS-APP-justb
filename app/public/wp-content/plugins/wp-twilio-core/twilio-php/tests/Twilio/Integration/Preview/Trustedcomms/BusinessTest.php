<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Tests\Integration\Preview\Trustedcomms;

use Twilio\Exceptions\DeserializeException;
use Twilio\Exceptions\TwilioException;
use Twilio\Http\Response;
use Twilio\Tests\HolodeckTestCase;
use Twilio\Tests\Request;

class BusinessTest extends HolodeckTestCase {
    public function testFetchRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->preview->trustedComms->businesses("BXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://preview.twilio.com/TrustedComms/Businesses/BXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        ));
    }

    public function testFetchResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "sid": "BXaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "url": "https://preview.twilio.com/TrustedComms/Businesses/BXaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "links": {
                    "insights": "https://preview.twilio.com/TrustedComms/Businesses/BXaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Insights"
                }
            }
            '
        ));

        $actual = $this->twilio->preview->trustedComms->businesses("BXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();

        $this->assertNotNull($actual);
    }
}