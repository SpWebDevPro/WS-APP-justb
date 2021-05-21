<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Tests\Integration\Verify\V2\Service\RateLimit;

use Twilio\Exceptions\DeserializeException;
use Twilio\Exceptions\TwilioException;
use Twilio\Http\Response;
use Twilio\Tests\HolodeckTestCase;
use Twilio\Tests\Request;

class BucketTest extends HolodeckTestCase {
    public function testCreateRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->verify->v2->services("VAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->rateLimits("RKXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->buckets->create(1, 1);
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $values = array('Max' => 1, 'Interval' => 1, );

        $this->assertRequest(new Request(
            'post',
            'https://verify.twilio.com/v2/Services/VAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/RateLimits/RKXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Buckets',
            null,
            $values
        ));
    }

    public function testCreateBucketResponse() {
        $this->holodeck->mock(new Response(
            201,
            '
            {
                "sid": "BLaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "rate_limit_sid": "RKaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "service_sid": "VAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "max": 5,
                "interval": 60,
                "date_created": "2015-07-30T20:00:00Z",
                "date_updated": "2015-07-30T20:00:00Z",
                "url": "https://verify.twilio.com/v2/Services/VAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/RateLimits/RKaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Buckets/BLaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
            }
            '
        ));

        $actual = $this->twilio->verify->v2->services("VAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->rateLimits("RKXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->buckets->create(1, 1);

        $this->assertNotNull($actual);
    }

    public function testUpdateRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->verify->v2->services("VAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->rateLimits("RKXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->buckets("BLXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->update();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'post',
            'https://verify.twilio.com/v2/Services/VAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/RateLimits/RKXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Buckets/BLXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        ));
    }

    public function testUpdateBucketResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "sid": "BLaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "rate_limit_sid": "RKaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "service_sid": "VAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "max": 5,
                "interval": 60,
                "date_created": "2015-07-30T20:00:00Z",
                "date_updated": "2015-07-30T20:00:00Z",
                "url": "https://verify.twilio.com/v2/Services/VAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/RateLimits/RKaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Buckets/BLaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
            }
            '
        ));

        $actual = $this->twilio->verify->v2->services("VAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->rateLimits("RKXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->buckets("BLXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->update();

        $this->assertNotNull($actual);
    }

    public function testFetchRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->verify->v2->services("VAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->rateLimits("RKXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->buckets("BLXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://verify.twilio.com/v2/Services/VAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/RateLimits/RKXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Buckets/BLXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        ));
    }

    public function testFetchBucketResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "sid": "BLaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "rate_limit_sid": "RKaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "service_sid": "VAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "max": 5,
                "interval": 60,
                "date_created": "2015-07-30T20:00:00Z",
                "date_updated": "2015-07-30T20:00:00Z",
                "url": "https://verify.twilio.com/v2/Services/VAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/RateLimits/RKaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Buckets/BLaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
            }
            '
        ));

        $actual = $this->twilio->verify->v2->services("VAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->rateLimits("RKXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->buckets("BLXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();

        $this->assertNotNull($actual);
    }

    public function testReadRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->verify->v2->services("VAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->rateLimits("RKXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->buckets->read();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://verify.twilio.com/v2/Services/VAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/RateLimits/RKXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Buckets'
        ));
    }

    public function testReadEmptyResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "buckets": [],
                "meta": {
                    "page": 0,
                    "page_size": 50,
                    "first_page_url": "https://verify.twilio.com/v2/Services/VAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/RateLimits/RKaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Buckets?PageSize=50&Page=0",
                    "previous_page_url": null,
                    "url": "https://verify.twilio.com/v2/Services/VAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/RateLimits/RKaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Buckets?PageSize=50&Page=0",
                    "next_page_url": null,
                    "key": "buckets"
                }
            }
            '
        ));

        $actual = $this->twilio->verify->v2->services("VAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->rateLimits("RKXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->buckets->read();

        $this->assertNotNull($actual);
    }

    public function testReadFullResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "buckets": [
                    {
                        "sid": "BLaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "rate_limit_sid": "RKaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "service_sid": "VAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "max": 5,
                        "interval": 60,
                        "date_created": "2015-07-30T20:00:00Z",
                        "date_updated": "2015-07-30T20:00:00Z",
                        "url": "https://verify.twilio.com/v2/Services/VAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/RateLimits/RKaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Buckets/BLaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
                    }
                ],
                "meta": {
                    "page": 0,
                    "page_size": 50,
                    "first_page_url": "https://verify.twilio.com/v2/Services/VAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/RateLimits/RKaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Buckets?PageSize=50&Page=0",
                    "previous_page_url": null,
                    "url": "https://verify.twilio.com/v2/Services/VAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/RateLimits/RKaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Buckets?PageSize=50&Page=0",
                    "next_page_url": null,
                    "key": "buckets"
                }
            }
            '
        ));

        $actual = $this->twilio->verify->v2->services("VAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->rateLimits("RKXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->buckets->read();

        $this->assertGreaterThan(0, \count($actual));
    }

    public function testDeleteRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->verify->v2->services("VAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->rateLimits("RKXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->buckets("BLXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->delete();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'delete',
            'https://verify.twilio.com/v2/Services/VAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/RateLimits/RKXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Buckets/BLXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        ));
    }

    public function testDeleteResponse() {
        $this->holodeck->mock(new Response(
            204,
            null
        ));

        $actual = $this->twilio->verify->v2->services("VAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->rateLimits("RKXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->buckets("BLXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->delete();

        $this->assertTrue($actual);
    }
}