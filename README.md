# prooph/common

[![Build Status](https://travis-ci.org/prooph/common.svg?branch=master)](https://travis-ci.org/prooph/common)
[![Coverage Status](https://coveralls.io/repos/prooph/common/badge.svg?branch=master)](https://coveralls.io/r/prooph/common?branch=master)

Common classes shared between prooph components

## Shared Kernel

Prooph components work with [php-fig](http://www.php-fig.org/) standards and other de facto standards like [Container-Interop](https://github.com/container-interop/container-interop) whenever possible.
But they also share some prooph software specific classes. These common classes are included in this repository.

## Messaging

`Prooph\Common\Messaging` provides basic message implementations. These message classes are dispatched by
[prooph/service-bus](https://github.com/prooph/service-bus), persisted by [prooph/event-store](https://github.com/prooph/event-store) and applied to aggregate roots by [prooph/event-sourcing](https://github.com/prooph/event-sourcing).

### DomainMessage

`Prooph\Common\Messaging\DomainMessage` is the basic class for all prooph messages. It is declared abstract and
requires implementers to provide a `messageType` and implementations for `public function payload(): array` and `protected function setPayload(array $payload)`.

A class constructor is not defined for `Prooph\Common\Messaging\DomainMessage`. It is up to you how you want to instantiate your messages.
Just call `DomainMessage::init()` within your message constructors to initialize a message object with defaults.

Each `Prooph\Common\Messaging\DomainMessage`
- has a `Ramsey\Uuid uuid` to identify the message in logs or in event streams, allows for duplicate checks and so on,
- a `string messageName` which defaults to the FQCN of the message, used to reconstitute a message
- a `int version` defaults to 0, mainly required for domain events to track version of corresponding aggregate roots
- `array metadata` can contain scalar types or sub arrays, it is recommended to only use it as a hash table for scalar values
- `DateTimeImmutable createdAt` is set when `DomainMessage::init`  is invoked, implementers can override `protected $dateTimeFormat = \DateTime::ISO8601` to use another format when message is serialized/deserialized.

#### Payload

Since prooph/common 3.x `payload` is no longer part of the message object but instead methods are required to get/set the `payload`.
Payload is the data transported by the message. It should only consist of scalar types and sub arrays so that it can easily be `json_encode`d and `json_decode`d.
Implementers don't need to manage a payload property but `public function payload()` should return the message data.
The `protected function setPayload(array $payload)` method instead should reconstitute message data from given payload array.

Here is a simple example of a command:
```php
<?php

final class RegisterUser extends \Prooph\Common\Messaging\Command
{
    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var string
     */
    private $username;

    public function __construct(UserId $userId, $username)
    {
        $this->userId = $userId;
        $this->username = $username;
        //Initialize message properties: uuid, messageName, createdAt
        $this->init();
    }

    public function payload()
    {
        return [
            'user_id' => $this->userId->toString(),
            'username' => $this->username
        ];
    }


    protected function setPayload(array $payload)
    {
        //we skip assertions here for the sake of simplicity
        $this->userId = UserId::fromString((string)$payload['user_id']);
        $this->username = (string)$payload['username'];
    }
}
```

##### PayloadConstructable

A very simple way to handle payload data is to provide it as an array at construction time.
prooph/common ships with a `Prooph\Common\Messaging\PayloadConstructable` interface and a `Prooph\Common\Messaging\PayloadTrait`.
Use them if you don't want to worry about payload handling.

##### Custom message conversion

If your message contains value objects and you don't want to serialize/deserialize them by hand you can also use hydrators or third party
serializers. But you have to do a bit of work.

First your message should return the value objects as payload when `public function payload()` is invoked.
`protected function setPayload(array $payload)` on the other hand should assert that the given payload contains the same
value objects and use some magic to set them as message properties.

If all your messages now use such a custom payload logic you can implement your own `Prooph\Common\Messaging\MessageFactory`
and `Prooph\Common\Messaging\MessageConverter` using hydrators or your serializer of choice to convert the message payload
from/to array.
All prooph/components which deal with message conversion use these objects and allow you to inject your own implementations.
Please referrer to the documentation of the components for details.

## ActionEventEmitter

To add event-driven capabilities to prooph components `prooph/common` provides a basic implementation of an event emitter and
an default action event object. We call these events `action events` because we want to differentiate them from domain events.

A `domain event` describes something happened in the past.

An `action event` on the other side is an communication object
used to pass parameters from one listener to the next listener during an event-driven process. Furthermore an `action event`
can be stopped which in turn stops the running process.
All prooph components type hint the interfaces `Prooph\Common\Event\ActionEventEmitter`, `Prooph\Common\Event\ActionEventListener/ListenerAggregate`,
`Prooph\Common\Event\ActionEvent` and therefor allow the usage of custom implementations.
If you don't inject your own implementations all components fall back to the default implementations provided by `prooph/common`.

## Support

- Ask questions on [prooph-users](https://groups.google.com/forum/?hl=de#!forum/prooph) google group.
- File issues at [https://github.com/prooph/common/issues](https://github.com/prooph/common/issues).

## Contribute

Please feel free to fork and extend existing or add new features and send a pull request with your changes!
To establish a consistent code quality, please provide unit tests for all your changes and may adapt the documentation.


