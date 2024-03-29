<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Tests\Integration\Api\V2010\Account\Call;

use Twilio\Exceptions\DeserializeException;
use Twilio\Exceptions\TwilioException;
use Twilio\Http\Response;
use Twilio\Tests\HolodeckTestCase;
use Twilio\Tests\Request;

class PaymentTest extends HolodeckTestCase {
    public function testCreateRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->api->v2010->accounts("ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->calls("CAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->payments->create("idempotency_key", "https://example.com");
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $values = array('IdempotencyKey' => "idempotency_key", 'StatusCallback' => "https://example.com", );

        $this->assertRequest(new Request(
            'post',
            'https://api.twilio.com/2010-04-01/Accounts/ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Calls/CAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Payments.json',
            null,
            $values
        ));
    }

    public function testStartPaymentSessionSuccessResponse() {
        $this->holodeck->mock(new Response(
            201,
            '
            {
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "call_sid": "CAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "date_created": "Wed, 18 Dec 2019 20:02:01 +0000",
                "date_updated": "Wed, 18 Dec 2019 20:02:01 +0000",
                "sid": "PKaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "uri": "/2010-04-01/Accounts/ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Calls/CAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Payments/PKaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa.json"
            }
            '
        ));

        $actual = $this->twilio->api->v2010->accounts("ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->calls("CAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->payments->create("idempotency_key", "https://example.com");

        $this->assertNotNull($actual);
    }

    public function testUpdateRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->api->v2010->accounts("ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->calls("CAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->payments("PKXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->update("idempotency_key", "https://example.com");
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $values = array('IdempotencyKey' => "idempotency_key", 'StatusCallback' => "https://example.com", );

        $this->assertRequest(new Request(
            'post',
            'https://api.twilio.com/2010-04-01/Accounts/ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Calls/CAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Payments/PKXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX.json',
            null,
            $values
        ));
    }

    public function testCollectCreditCardNumberResponse() {
        $this->holodeck->mock(new Response(
            202,
            '
            {
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "call_sid": "CAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "date_created": "Wed, 18 Dec 2019 20:02:01 +0000",
                "date_updated": "Wed, 18 Dec 2019 20:02:01 +0000",
                "sid": "PKaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "uri": "/2010-04-01/Accounts/ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Calls/CAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Payments/PKaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa.json"
            }
            '
        ));

        $actual = $this->twilio->api->v2010->accounts("ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->calls("CAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->payments("PKXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->update("idempotency_key", "https://example.com");

        $this->assertNotNull($actual);
    }

    public function testCollectCreditCardExpiryDateResponse() {
        $this->holodeck->mock(new Response(
            202,
            '
            {
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "call_sid": "CAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "date_created": "Wed, 18 Dec 2019 20:02:01 +0000",
                "date_updated": "Wed, 18 Dec 2019 20:02:01 +0000",
                "sid": "PKaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "uri": "/2010-04-01/Accounts/ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Calls/CAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Payments/PKaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa.json"
            }
            '
        ));

        $actual = $this->twilio->api->v2010->accounts("ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->calls("CAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->payments("PKXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->update("idempotency_key", "https://example.com");

        $this->assertNotNull($actual);
    }

    public function testCompletePaymentResponse() {
        $this->holodeck->mock(new Response(
            202,
            '
            {
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "call_sid": "CAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "date_created": "Wed, 18 Dec 2019 20:02:01 +0000",
                "date_updated": "Wed, 18 Dec 2019 20:02:01 +0000",
                "sid": "PKaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "uri": "/2010-04-01/Accounts/ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Calls/CAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Payments/PKaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa.json"
            }
            '
        ));

        $actual = $this->twilio->api->v2010->accounts("ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->calls("CAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->payments("PKXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->update("idempotency_key", "https://example.com");

        $this->assertNotNull($actual);
    }
}