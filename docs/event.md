# Event

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
