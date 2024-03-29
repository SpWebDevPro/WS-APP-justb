<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Tests\Integration\Video\V1\Room\Participant;

use Twilio\Exceptions\DeserializeException;
use Twilio\Exceptions\TwilioException;
use Twilio\Http\Response;
use Twilio\Tests\HolodeckTestCase;
use Twilio\Tests\Request;

class SubscribeRulesTest extends HolodeckTestCase {
    public function testFetchRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->video->v1->rooms("RMXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                    ->participants("PAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                    ->subscribeRules->fetch();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://video.twilio.com/v1/Rooms/RMXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Participants/PAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/SubscribeRules'
        ));
    }

    public function testReadEmptyResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "participant_sid": "PAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "room_sid": "RMaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "date_created": null,
                "date_updated": null,
                "rules": [
                    {
                        "type": "include",
                        "all": true,
                        "publisher": null,
                        "track": null,
                        "kind": null,
                        "priority": null
                    }
                ]
            }
            '
        ));

        $actual = $this->twilio->video->v1->rooms("RMXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                          ->participants("PAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                          ->subscribeRules->fetch();

        $this->assertNotNull($actual);
    }

    public function testUpdateRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->video->v1->rooms("RMXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                    ->participants("PAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                    ->subscribeRules->update();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'post',
            'https://video.twilio.com/v1/Rooms/RMXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Participants/PAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/SubscribeRules'
        ));
    }

    public function testUpdateFiltersResponse() {
        $this->holodeck->mock(new Response(
            202,
            '
            {
                "participant_sid": "PAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "room_sid": "RMaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "date_created": null,
                "date_updated": null,
                "rules": [
                    {
                        "type": "exclude",
                        "all": true,
                        "publisher": null,
                        "track": null,
                        "kind": null,
                        "priority": null
                    }
                ]
            }
            '
        ));

        $actual = $this->twilio->video->v1->rooms("RMXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                          ->participants("PAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                          ->subscribeRules->update();

        $this->assertNotNull($actual);
    }
}