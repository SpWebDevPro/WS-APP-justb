<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Tests\Integration\Conversations\V1;

use Twilio\Exceptions\DeserializeException;
use Twilio\Exceptions\TwilioException;
use Twilio\Http\Response;
use Twilio\Tests\HolodeckTestCase;
use Twilio\Tests\Request;

class WebhookTest extends HolodeckTestCase {
    public function testFetchRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->conversations->v1->webhooks()->fetch();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://conversations.twilio.com/v1/Conversations/Webhooks'
        ));
    }

    public function testFetchResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "pre_webhook_url": "https://example.com/pre",
                "post_webhook_url": "https://example.com/post",
                "method": "GET",
                "filters": [
                    "onMessageSend",
                    "onConversationUpdated"
                ],
                "target": "webhook",
                "url": "https://conversations.twilio.com/v1/Conversations/Webhooks"
            }
            '
        ));

        $actual = $this->twilio->conversations->v1->webhooks()->fetch();

        $this->assertNotNull($actual);
    }

    public function testUpdateRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->conversations->v1->webhooks()->update();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'post',
            'https://conversations.twilio.com/v1/Conversations/Webhooks'
        ));
    }

    public function testUpdateResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "pre_webhook_url": "https://example.com/pre",
                "post_webhook_url": "http://example.com/post",
                "method": "GET",
                "filters": [
                    "onConversationUpdated"
                ],
                "target": "webhook",
                "url": "https://conversations.twilio.com/v1/Conversations/Webhooks"
            }
            '
        ));

        $actual = $this->twilio->conversations->v1->webhooks()->update();

        $this->assertNotNull($actual);
    }
}