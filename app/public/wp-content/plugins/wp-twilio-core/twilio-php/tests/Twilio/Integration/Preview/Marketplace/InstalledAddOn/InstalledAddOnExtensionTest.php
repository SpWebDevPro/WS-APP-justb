<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Tests\Integration\Preview\Marketplace\InstalledAddOn;

use Twilio\Exceptions\DeserializeException;
use Twilio\Exceptions\TwilioException;
use Twilio\Http\Response;
use Twilio\Serialize;
use Twilio\Tests\HolodeckTestCase;
use Twilio\Tests\Request;

class InstalledAddOnExtensionTest extends HolodeckTestCase {
    public function testFetchRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->preview->marketplace->installedAddOns("XEXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                               ->extensions("XFXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://preview.twilio.com/marketplace/InstalledAddOns/XEXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Extensions/XFXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        ));
    }

    public function testFetchResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "sid": "XFaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "installed_add_on_sid": "XEaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "friendly_name": "Incoming Voice Call",
                "product_name": "Programmable Voice",
                "unique_name": "voice-incoming",
                "enabled": true,
                "url": "https://preview.twilio.com/marketplace/InstalledAddOns/XEaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Extensions/XFaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
            }
            '
        ));

        $actual = $this->twilio->preview->marketplace->installedAddOns("XEXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                                     ->extensions("XFXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();

        $this->assertNotNull($actual);
    }

    public function testUpdateRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->preview->marketplace->installedAddOns("XEXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                               ->extensions("XFXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->update(True);
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $values = array('Enabled' => Serialize::booleanToString(True), );

        $this->assertRequest(new Request(
            'post',
            'https://preview.twilio.com/marketplace/InstalledAddOns/XEXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Extensions/XFXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
            null,
            $values
        ));
    }

    public function testUpdateResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "sid": "XFaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "installed_add_on_sid": "XEaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "friendly_name": "Incoming Voice Call",
                "product_name": "Programmable Voice",
                "unique_name": "voice-incoming",
                "enabled": false,
                "url": "https://preview.twilio.com/marketplace/InstalledAddOns/XEaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Extensions/XFaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
            }
            '
        ));

        $actual = $this->twilio->preview->marketplace->installedAddOns("XEXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                                     ->extensions("XFXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->update(True);

        $this->assertNotNull($actual);
    }

    public function testReadRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->preview->marketplace->installedAddOns("XEXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                               ->extensions->read();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://preview.twilio.com/marketplace/InstalledAddOns/XEXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/Extensions'
        ));
    }

    public function testReadFullResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "extensions": [
                    {
                        "sid": "XFaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "installed_add_on_sid": "XEaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "friendly_name": "Incoming Voice Call",
                        "product_name": "Programmable Voice",
                        "unique_name": "voice-incoming",
                        "enabled": true,
                        "url": "https://preview.twilio.com/marketplace/InstalledAddOns/XEaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Extensions/XFaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
                    }
                ],
                "meta": {
                    "page": 0,
                    "page_size": 50,
                    "first_page_url": "https://preview.twilio.com/marketplace/InstalledAddOns/XEaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Extensions?PageSize=50&Page=0",
                    "previous_page_url": null,
                    "url": "https://preview.twilio.com/marketplace/InstalledAddOns/XEaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Extensions?PageSize=50&Page=0",
                    "next_page_url": null,
                    "key": "extensions"
                }
            }
            '
        ));

        $actual = $this->twilio->preview->marketplace->installedAddOns("XEXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                                     ->extensions->read();

        $this->assertGreaterThan(0, \count($actual));
    }

    public function testReadEmptyResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "extensions": [],
                "meta": {
                    "page": 0,
                    "page_size": 50,
                    "first_page_url": "https://preview.twilio.com/marketplace/InstalledAddOns/XEaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Extensions?PageSize=50&Page=0",
                    "previous_page_url": null,
                    "url": "https://preview.twilio.com/marketplace/InstalledAddOns/XEaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Extensions?PageSize=50&Page=0",
                    "next_page_url": null,
                    "key": "extensions"
                }
            }
            '
        ));

        $actual = $this->twilio->preview->marketplace->installedAddOns("XEXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                                     ->extensions->read();

        $this->assertNotNull($actual);
    }
}