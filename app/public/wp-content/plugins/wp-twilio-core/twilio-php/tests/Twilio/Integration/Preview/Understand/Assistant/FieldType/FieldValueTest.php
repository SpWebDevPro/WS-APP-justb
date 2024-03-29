<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Tests\Integration\Preview\Understand\Assistant\FieldType;

use Twilio\Exceptions\DeserializeException;
use Twilio\Exceptions\TwilioException;
use Twilio\Http\Response;
use Twilio\Tests\HolodeckTestCase;
use Twilio\Tests\Request;

class FieldValueTest extends HolodeckTestCase {
    public function testFetchRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->preview->understand->assistants("UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                              ->fieldTypes("UBXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                              ->fieldValues("UCXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://preview.twilio.com/understand/Assistants/UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/FieldTypes/UBXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/FieldValues/UCXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        ));
    }

    public function testFetchResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "url": "https://preview.twilio.com/understand/Assistants/UAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/FieldTypes/UBaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/FieldValues/UCaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "field_type_sid": "UBaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "language": "language",
                "assistant_sid": "UAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "value": "value",
                "date_updated": "2015-07-30T20:00:00Z",
                "date_created": "2015-07-30T20:00:00Z",
                "sid": "UCaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "synonym_of": null
            }
            '
        ));

        $actual = $this->twilio->preview->understand->assistants("UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                                    ->fieldTypes("UBXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                                    ->fieldValues("UCXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();

        $this->assertNotNull($actual);
    }

    public function testReadRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->preview->understand->assistants("UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                              ->fieldTypes("UBXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                              ->fieldValues->read();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://preview.twilio.com/understand/Assistants/UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/FieldTypes/UBXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/FieldValues'
        ));
    }

    public function testReadEmptyResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "field_values": [],
                "meta": {
                    "first_page_url": "https://preview.twilio.com/understand/Assistants/UAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/FieldTypes/UBaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/FieldValues?Language=language&PageSize=50&Page=0",
                    "page_size": 50,
                    "previous_page_url": null,
                    "key": "field_values",
                    "page": 0,
                    "next_page_url": null,
                    "url": "https://preview.twilio.com/understand/Assistants/UAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/FieldTypes/UBaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/FieldValues?Language=language&PageSize=50&Page=0"
                }
            }
            '
        ));

        $actual = $this->twilio->preview->understand->assistants("UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                                    ->fieldTypes("UBXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                                    ->fieldValues->read();

        $this->assertNotNull($actual);
    }

    public function testReadFullResponse() {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "field_values": [
                    {
                        "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "url": "https://preview.twilio.com/understand/Assistants/UAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/FieldTypes/UBaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/FieldValues/UCaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "field_type_sid": "UBaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "language": "language",
                        "assistant_sid": "UAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "value": "value",
                        "date_updated": "2015-07-30T20:00:00Z",
                        "date_created": "2015-07-30T20:00:00Z",
                        "sid": "UCaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "synonym_of": "UCbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb"
                    }
                ],
                "meta": {
                    "first_page_url": "https://preview.twilio.com/understand/Assistants/UAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/FieldTypes/UBaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/FieldValues?Language=language&PageSize=50&Page=0",
                    "page_size": 50,
                    "previous_page_url": null,
                    "key": "field_values",
                    "page": 0,
                    "next_page_url": null,
                    "url": "https://preview.twilio.com/understand/Assistants/UAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/FieldTypes/UBaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/FieldValues?Language=language&PageSize=50&Page=0"
                }
            }
            '
        ));

        $actual = $this->twilio->preview->understand->assistants("UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                                    ->fieldTypes("UBXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                                    ->fieldValues->read();

        $this->assertGreaterThan(0, \count($actual));
    }

    public function testCreateRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->preview->understand->assistants("UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                              ->fieldTypes("UBXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                              ->fieldValues->create("language", "value");
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $values = array('Language' => "language", 'Value' => "value", );

        $this->assertRequest(new Request(
            'post',
            'https://preview.twilio.com/understand/Assistants/UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/FieldTypes/UBXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/FieldValues',
            null,
            $values
        ));
    }

    public function testCreateResponse() {
        $this->holodeck->mock(new Response(
            201,
            '
            {
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "url": "https://preview.twilio.com/understand/Assistants/UAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/FieldTypes/UBaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/FieldValues/UCaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "field_type_sid": "UBaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "language": "language",
                "assistant_sid": "UAaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "value": "value",
                "date_updated": "2015-07-30T20:00:00Z",
                "date_created": "2015-07-30T20:00:00Z",
                "sid": "UCaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "synonym_of": "UCbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb"
            }
            '
        ));

        $actual = $this->twilio->preview->understand->assistants("UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                                    ->fieldTypes("UBXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                                    ->fieldValues->create("language", "value");

        $this->assertNotNull($actual);
    }

    public function testDeleteRequest() {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->preview->understand->assistants("UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                              ->fieldTypes("UBXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                              ->fieldValues("UCXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->delete();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'delete',
            'https://preview.twilio.com/understand/Assistants/UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/FieldTypes/UBXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/FieldValues/UCXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        ));
    }

    public function testDeleteResponse() {
        $this->holodeck->mock(new Response(
            204,
            null
        ));

        $actual = $this->twilio->preview->understand->assistants("UAXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                                    ->fieldTypes("UBXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                                    ->fieldValues("UCXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->delete();

        $this->assertTrue($actual);
    }
}