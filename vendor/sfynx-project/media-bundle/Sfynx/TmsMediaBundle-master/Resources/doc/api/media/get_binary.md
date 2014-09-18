TmsMediaBundle API: [GET] Media
===============================

Retrieve one notification

## General
|             | Values
|-------------|-------
| **Method**  | GET
| **Path**    | /media/{id}/{_format}.bin
| **Formats** | json|xml|csv|jpeg|jpg|png|gif
| **Secured** | true

## HTTP Request parameters

[see parameters along {_format}](../media/get_media.md)

## HTTP Response codes
| Code | Description
|------|------------
| 200  | Ok
| 404  | Not found (wrong id)
| 500  | Server error

## HTTP Response content examples

### Binary
```curl
$ curl http://your_domain/api/media/reference/png.bin?rotate=106
```

```
8950 4e47 0d0a 1a0a 0000 000d 4948 4452
0000 024a 0000 02df 0806 0000 009e 88ed
. . .
7065 673a 7361 6d70 6c69 6e67 2d66 6163
746f 7200 3278 322c 3178 312c 3178 3149
faa6 b400 0000 0049 454e 44ae 4260 82

```
