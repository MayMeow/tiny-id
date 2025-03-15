# Tiny ID

Shortener for UID.

You can select base2 to base62. If you want only use numbers and small letters select base36.

## Example

```php

$encoded = \Maymeow\TinyID\UuidShortener::encode($uid):
$uid = \Maymeow\TinyID\UuidShortener::decode($encoded):

## change base
$encoded = \Maymeow\TinyID\UuidShortener::encode($uid, 36):
$uid = \Maymeow\TinyID\UuidShortener::decode($encoded, 36):
```
