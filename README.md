# phpredis
**封装php的redis,简洁版**

*使用方法*    
    

```javascript
<?php
namespace XXXXX;    
use redis\redis;

function demo(){

  $redis = new Redis('127.0.0.1',12345,6379);
  $redis -> set('demo',123,10);
  echo $redis -> get('demo');
}
```
