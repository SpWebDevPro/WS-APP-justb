<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Messaging\V1\Session;

use Twilio\Options;
use Twilio\Values;

/**
 * PLEASE NOTE that this class contains preview products that are subject to change. Use them with caution. If you currently do not have developer preview access, please contact help@twilio.com.
 */
abstract class ParticipantOptions {
    /**
     * @param string $identity A unique string identifier for the session
     *                         participant as Chat User.
     * @param string $userAddress The address of the participant's device.
     * @param string $attributes An optional string metadata field you can use to
     *                           store any data you wish.
     * @param string $twilioAddress The address of the Twilio phone number that the
     *                              participant is in contact with.
     * @param \DateTime $dateCreated The date that this resource was created.
     * @param \DateTime $dateUpdated The date that this resource was last updated.
     * @return CreateParticipantOptions Options builder
     */
    public static function create($identity = Values::NONE, $userAddress = Values::NONE, $attributes = Values::NONE, $twilioAddress = Values::NONE, $dateCreated = Values::NONE, $dateUpdated = Values::NONE) {
        return new CreateParticipantOptions($identity, $userAddress, $attributes, $twilioAddress, $dateCreated, $dateUpdated);
    }

    /**
     * @param string $attributes An optional string metadata field you can use to
     *                           store any data you wish.
     * @param \DateTime $dateCreated The date that this resource was created.
     * @param \DateTime $dateUpdated The date that this resource was last updated.
     * @return UpdateParticipantOptions Options builder
     */
    public static function update($attributes = Values::NONE, $dateCreated = Values::NONE, $dateUpdated = Values::NONE) {
        return new UpdateParticipantOptions($attributes, $dateCreated, $dateUpdated);
    }
}

class CreateParticipantOptions extends Options {
    /**
     * @param string $identity A unique string identifier for the session
     *                         participant as Chat User.
     * @param string $userAddress The address of the participant's device.
     * @param string $attributes An optional string metadata field you can use to
     *                           store any data you wish.
     * @param string $twilioAddress The address of the Twilio phone number that the
     *                              participant is in contact with.
     * @param \DateTime $dateCreated The date that this resource was created.
     * @param \DateTime $dateUpdated The date that this resource was last updated.
     */
    public function __construct($identity = Values::NONE, $userAddress = Values::NONE, $attributes = Values::NONE, $twilioAddress = Values::NONE, $dateCreated = Values::NONE, $dateUpdated = Values::NONE) {
        $this->options['identity'] = $identity;
        $this->options['userAddress'] = $userAddress;
        $this->options['attributes'] = $attributes;
        $this->options['twilioAddress'] = $twilioAddress;
        $this->options['dateCreated'] = $dateCreated;
        $this->options['dateUpdated'] = $dateUpdated;
    }

    /**
     * A unique string identifier for the session participant as [Chat User](https://www.twilio.com/docs/api/chat/rest/users). This parameter is non-null if (and only if) the participant is using the Programmable Chat SDK to communicate.
     * 
     * @param string $identity A unique string identifier for the session
     *                         participant as Chat User.
     * @return $this Fluent Builder
     */
    public function setIdentity($identity) {
        $this->options['identity'] = $identity;
        return $this;
    }

    /**
     * The address of the participant's device, e.g. a phone number or Messenger ID. Together with the Twilio Address, this determines a participant uniquely. This field (with twilio_address) is only null when the participant is interacting from a Chat endpoint (see the 'identity' field).
     * 
     * @param string $userAddress The address of the participant's device.
     * @return $this Fluent Builder
     */
    public function setUserAddress($userAddress) {
        $this->options['userAddress'] = $userAddress;
        return $this;
    }

    /**
     * An optional string metadata field you can use to store any data you wish. The string value must contain structurally valid JSON if specified.  **Note** that if the attributes are not set "{}" will be returned.
     * 
     * @param string $attributes An optional string metadata field you can use to
     *                           store any data you wish.
     * @return $this Fluent Builder
     */
    public function setAttributes($attributes) {
        $this->options['attributes'] = $attributes;
        return $this;
    }

    /**
     * The address of the Twilio phone number (or WhatsApp number, or Messenger Page ID) that the participant is in contact with. This field, together with user_address, is only null when the participant is interacting from a Chat endpoint (see the 'identity' field).
     * 
     * @param string $twilioAddress The address of the Twilio phone number that the
     *                              participant is in contact with.
     * @return $this Fluent Builder
     */
    public function setTwilioAddress($twilioAddress) {
        $this->options['twilioAddress'] = $twilioAddress;
        return $this;
    }

    /**
     * The date that this resource was created. This is most often useful when bulk-importing messages from another system, as the provided value will be trusted and displayed on SDK clients.
     * 
     * @param \DateTime $dateCreated The date that this resource was created.
     * @return $this Fluent Builder
     */
    public function setDateCreated($dateCreated) {
        $this->options['dateCreated'] = $dateCreated;
        return $this;
    }

    /**
     * The date that this resource was last updated. This is most often provided when bulk-importing messages from another system, as the provided value will be trusted and displayed on SDK clients.
     * 
     * @param \DateTime $dateUpdated The date that this resource was last updated.
     * @return $this Fluent Builder
     */
    public function setDateUpdated($dateUpdated) {
        $this->options['dateUpdated'] = $dateUpdated;
        return $this;
    }

    /**
     * Provide a friendly representation
     * 
     * @return string Machine friendly representation
     */
    public function __toString() {
        $options = array();
        foreach ($this->options as $key => $value) {
            if ($value != Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.Messaging.V1.CreateParticipantOptions ' . implode(' ', $options) . ']';
    }
}

class UpdateParticipantOptions extends Options {
    /**
     * @param string $attributes An optional string metadata field you can use to
     *                           store any data you wish.
     * @param \DateTime $dateCreated The date that this resource was created.
     * @param \DateTime $dateUpdated The date that this resource was last updated.
     */
    public function __construct($attributes = Values::NONE, $dateCreated = Values::NONE, $dateUpdated = Values::NONE) {
        $this->options['attributes'] = $attributes;
        $this->options['dateCreated'] = $dateCreated;
        $this->options['dateUpdated'] = $dateUpdated;
    }

    /**
     * An optional string metadata field you can use to store any data you wish. The string value must contain structurally valid JSON if specified.  **Note** that if the attributes are not set "{}" will be returned.
     * 
     * @param string $attributes An optional string metadata field you can use to
     *                           store any data you wish.
     * @return $this Fluent Builder
     */
    public function setAttributes($attributes) {
        $this->options['attributes'] = $attributes;
        return $this;
    }

    /**
     * The date that this resource was created. This is useful when bulk-importing messages from another system, as the provided value will be trusted and displayed on SDK clients.
     * 
     * @param \DateTime $dateCreated The date that this resource was created.
     * @return $this Fluent Builder
     */
    public function setDateCreated($dateCreated) {
        $this->options['dateCreated'] = $dateCreated;
        return $this;
    }

    /**
     * The date that this resource was last updated.
     * 
     * @param \DateTime $dateUpdated The date that this resource was last updated.
     * @return $this Fluent Builder
     */
    public function setDateUpdated($dateUpdated) {
        $this->options['dateUpdated'] = $dateUpdated;
        return $this;
    }

    /**
     * Provide a friendly representation
     * 
     * @return string Machine friendly representation
     */
    public function __toString() {
        $options = array();
        foreach ($this->options as $key => $value) {
            if ($value != Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.Messaging.V1.UpdateParticipantOptions ' . implode(' ', $options) . ']';
    }
}