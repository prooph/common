# Messaging

`Prooph\Common\Messaging` provides basic message implementations. These message classes are dispatched by
[prooph/service-bus](https://github.com/prooph/service-bus), persisted by [prooph/event-store](https://github.com/prooph/event-store) and applied to aggregate roots by [prooph/event-sourcing](https://github.com/prooph/event-sourcing).

## DomainMessage

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

### Payload

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

    public function payload(): array
    {
        return [
            'user_id' => $this->userId->toString(),
            'username' => $this->username
        ];
    }


    protected function setPayload(array $payload): void
    {
        //we skip assertions here for the sake of simplicity
        $this->userId = UserId::fromString((string)$payload['user_id']);
        $this->username = (string)$payload['username'];
    }
}
```

### PayloadConstructable

A very simple way to handle payload data is to provide it as an array at construction time.
prooph/common ships with a `Prooph\Common\Messaging\PayloadConstructable` interface and a `Prooph\Common\Messaging\PayloadTrait`.
Use them if you don't want to worry about payload handling.

### Metadata

Message metadata is an array of key => value pairs, where the key is a string and the value has to be a scalar type.
Note that you should NOT use metadata keys beginning with an underscore "_", because this is used internally.

## Custom Messages

If you prefer to work with your own message implementations and want to use third party serializers everything you need to
do is to implement the interface `Prooph\Common\Messaging\Message` and inject custom implementations of
`Prooph\Common\Messaging\MessageFactory` and `Prooph\Common\Messaging\MessageConverter` into prooph components which deal
with message conversion from/to plain PHP arrays. Please refer the docs of the components for details.

## Migration from Prooph\Common v3

The method `Prooph\Common\Messaging\Message::version(): int` has been removed in v4. If you rely on the version-method
you have to implement it yourself and store the version in the metadata, f.e.

```
    public function version(): int
    {
        return $this->metadata['version'];
    }
    
    public function withVersion(int $version): Message
    {
        $self = clone $this;
        $self->setVersion($version);
        return $self;
    }
    
    protected function setVersion(int $version): void
    {
        $this->metadata['version'] = $version;
    }
```
