TmsMediaBundle API: [GET] Media
===============================

Retrieve one notification

## General
|             | Values
|-------------|-------
| **Method**  | GET
| **Path**    | /media/{id}.{_format}
| **Formats** | json|xml|csv|jpeg|jpg|png|gif
| **Secured** | true

## HTTP Request parameters
with images formats (png, jpeg, jpg, gif) 
-------------------------------
| Name      | Optional | Default | Requirements | Description
|-----------|----------|---------|--------------|------------
| rotate    | true     | 0       | \d+          | rotation transformer angle
| resize    | true     | 0       | \d+          | resize transformer value
| scale     | true     | 0       | \d+          | scale transformer value
| grayscale | true     | 0       | 0/1          | grayscale transformer off/on
| width     | true     | 0       | \d+          | output image width
| height    | true     | 0       | \d+          | output image height
| maxwidth  | true     | 0       | \d+          | rescale maxwidth value
| maxheight | true     | 0       | \d+          | rescale maxheight value
| minwidth  | true     | 0       | \d+          | rescale minwidth value
| minheight | true     | 0       | \d+          | rescale minheight value

with documents formats (json, xml) 
---------------------------------------
no options

## HTTP Response codes
| Code | Description
|------|------------
| 200  | Ok
| 404  | Not found (wrong id)
| 500  | Server error

## HTTP Response content examples

### json
```curl
$ curl http://your_domain/api/media/reference.json
```

```json
[{
  "id": "7",
  "source": "[127.0.0.1] DocumentGeneratorManager",
  "reference": "201795xxxx-139100xxxx-45be243015b0afd4d7fefb36xxxxxxxx-xxxxx",
  "extension": "jpeg",
  "providerServiceName": "gaufrette.web_images_filesystem",
  "name": "anxxxxx-xxxxxxx-Fractalius-7-640x425.jpg",
  "description": "phpxxxFrF",
  "size": "57246",
  "mimeType": "image/jpeg",
  "enabled": "1",
  "createdAt": "2014-01-29T15:47:32+01:00",
  "metadata": { "width": "640", "height": "425" }
}]
```

### xml

```curl
$ curl http://your_domain/api/media/reference.xml
```

```xml
<entities>
<media id="7">
<source>[127.0.0.1] DocumentGeneratorManager</source>
<reference>
201795xxxx-139100xxxx-45be243015b0afd4d7fefb36xxxxxxxx-xxxxx
</reference>
<extension>jpeg</extension>
<providerservicename>gaufrette.web_images_filesystem</providerservicename>
<name>anxxxxxx-xxxxxxx-Fractalius-7-640x425.jpg</name>
<description>phpxxxFrF</description>
<size>57246</size>
<mimetype>image/jpeg</mimetype>
<enabled>1</enabled>
<createdat>2014-01-29T15:47:32+01:00</createdat>
<metadata>
<width>640</width>
<height>425</height>
</metadata>
</media>
</entities>
```