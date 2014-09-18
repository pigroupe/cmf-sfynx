This will result to a :

- *204 No Content HTTP Status Code* : if a correct reference is passed
- *404 Not Found HTTP Status Code* : if an invalid reference (i.e a reference which does not exist neither in the database or in the filesystem)

**Parameters description**

- *reference* : The unique reference of the media

**Example of usage**

```curl
curl -X DELETE http://your_domain/api/media/reference
```