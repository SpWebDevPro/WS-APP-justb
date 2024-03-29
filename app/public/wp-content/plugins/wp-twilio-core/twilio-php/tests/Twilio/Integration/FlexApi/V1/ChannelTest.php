<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Tests\Integration\FlexApi\V1;

use Twilio\Exceptions\DeserializeException;
use Twilio\Exceptions\TwilioException;
use Twilio\Http\Response;
use Twilio\Tests\HolodeckTestCase;
use Twilio\Tests\Request;

class ChannelTest extends HolodeckTestCase {
    public function testReadRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->flexApi->v1->channel->read();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://flex-api.twilio.com/v1/Channels'
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
                    "first_page_url": "https://flex-api.twilio.com/v1/Channels?PageSize=50&Page=0",
                    "previous_page_url": null,
                    "url": "https://flex-api.twilio.com/v1/Channels?PageSize=50&Page=0",
                    "next_page_url": null,
                    "key": "flex_chat_channels"
                },
                "flex_chat_channels": [
                    {
                        "flex_flow_sid": "FOaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "sid": "CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "task_sid": "WTaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "user_sid": "USaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "date_created": "2016-08-01T22:10:40Z",
                        "date_updated": "2016-08-01T22:10:40Z",
                        "url": "https://flex-api.twilio.com/v1/Channels/CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
                    }
                ]
            }
            '
        ));

        $actual = $this->twilio->flexApi->v1->channel->read();

        $this->assertGreaterThan(0, \count($actual));
    }

    public function testReadEmptyResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "meta": {
                    "page": 0,
                    "page_size": 50,
                    "first_page_url": "https://flex-api.twilio.com/v1/Channels?PageSize=50&Page=0",
                    "previous_page_url": null,
                    "url": "https://flex-api.twilio.com/v1/Channels?PageSize=50&Page=0",
                    "next_page_url": null,
                    "key": "flex_chat_channels"
                },
                "flex_chat_channels": []
            }
            '
        ));

        $actual = $this->twilio->flexApi->v1->channel->read();

        $this->assertNotNull($actual);
    }

    public function testFetchRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->flexApi->v1->channel("CHXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://flex-api.twilio.com/v1/Channels/CHXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        ));
    }

    public function testFetchResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "flex_flow_sid": "FOaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "sid": "CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "task_sid": "WTaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "user_sid": "USaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "date_created": "2016-08-01T22:10:40Z",
                "date_updated": "2016-08-01T22:10:40Z",
                "url": "https://flex-api.twilio.com/v1/Channels/CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
            }
            '
        ));

        $actual = $this->twilio->flexApi->v1->channel("CHXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();

        $this->assertNotNull($actual);
    }

    public function testCreateRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->flexApi->v1->channel->create("FOXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX", "identity", "chat_user_friendly_name", "chat_friendly_name");
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $values = array(
            'FlexFlowSid' => "FOXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX",
            'Identity' => "identity",
            'ChatUserFriendlyName' => "chat_user_friendly_name",
            'ChatFriendlyName' => "chat_friendly_name",
        );

        $this->assertRequest(new Request(
            'post',
            'https://flex-api.twilio.com/v1/Channels',
            null,
            $values
        ));
    }

    public function testCreateResponse() {
        $this->holodeck->mock(new Response(
            201,
            '
            {
                "flex_flow_sid": "FOaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "sid": "CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "task_sid": "WTaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "user_sid": "USaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "date_created": "2016-08-01T22:10:40Z",
                "date_updated": "2016-08-01T22:10:40Z",
                "url": "https://flex-api.twilio.com/v1/Channels/CHaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
            }
            '
        ));

        $actual = $this->twilio->flexApi->v1->channel->create("FOXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX", "identity", "chat_user_friendly_name", "chat_friendly_name");

        $this->assertNotNull($actual);
    }

    public function testDeleteRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->flexApi->v1->channel("CHXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->delete();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'delete',
            'https://flex-api.twilio.com/v1/Channels/CHXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        ));
    }

    public function testDeleteResponse() {
        $this->holodeck->mock(new Response(
            204,
            null
        ));

        $actual = $this->twilio->flexApi->v1->channel("CHXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->delete();

        $this->assertTrue($actual);
    }
}