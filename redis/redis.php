<?php
namespace redis;

class Redis
{
    private static $redis = null;
    function __construct($host='127.0.0.1',$auth='',$port='6379'){
        if(null == self::$redis){
            $newRedis = new \Redis();
            $newRedis_result = $newRedis->connect($host,$port);
            if($auth){$newRedis->auth($auth);}
            self::$redis = $newRedis;
        }
    }
    public function __clone(){trigger_error('Clone is not allow!',E_USER_ERROR);}

    // 设置缓存
    public function set($key,$value,$expire=''){
        //对数组/对象数据进行处理
        $value  =  $this->disposeAdd($value);
        if(is_int($expire) && $expire) {
            $result = self::$redis->setex($key, $expire, $value);
        }else{
            $result = self::$redis->set($key, $value);
        }
        return $result;
    }

    // 获取缓存
    public function get($key){
        return $this->disposeReturn(self::$redis->get($key));
    }

    // 删除缓存
    public function rm($key) {
        return self::$redis->delete($key);
    }

    // 清除缓存
    public function clear() {
        return self::$redis->flushDB();
    }

    // 左，入列
    public function lpush($key,$value){
        return self::$redis->lpush($key,$this->disposeAdd($value));
    }

    // 右，入列
    public function rpush($key,$value){
        return self::$redis->rpush($key,$this->disposeAdd($value));
    }

    // 左，出列，删除
    public function lpop($key){
        return $this->disposeReturn(self::$redis->lpop($key));
    }

    // 右，出列，删除
    public function rpop($key){
        return $this->disposeReturn(self::$redis->rpop($key));
    }

    // 队列长度
    public function llen($key){
        return self::$redis->llen($key);
    }

    // 返回指定位置的元素
    public function lindex($key,$number){
        return $this->disposeReturn(self::$redis->lindex($key,$number));
    }

    // 修改队列中指定位置的value
    public function lset($key,$index,$mark){
        return self::$redis->lset($key,$index,$mark);
    }

    // 删除队列中左起指定数量的字符 （按照值）
    public function lrem($key,$mark,$num=0){
        return self::$redis->lrem($key,$this->disposeAdd($mark),$num);
    }

    // 删除队列中指定位置的值(下标)
    public function delIndexList($key,$index,$mark){
        self::$redis->multi();
        self::$redis->lset($key,$index,$mark);
        self::$redis->lrem($key,$mark,0);
        return current(self::$redis->exec());
    }

    // 返回队列数据，默认全部数据
    public function lrange($key,$start='0',$end='-1'){
        return self::$redis->lrange($key,$start,$end);
    }

    // 开启事务处理
    public function multi(){
        return self::$redis->multi();
    }

    // 执行
    public function exec(){
        return self::$redis->exec();
    }

    // 丢弃事务
    public function discard(){
        return self::$redis->discard();
    }

    // 处理添加元素
    private function disposeAdd($value){
        return (is_object($value) || is_array($value)) ? json_encode($value) : $value;
    }

    // 处理返回元素
    private function disposeReturn($value){
        $jsonData  = json_decode( $value, true );
        return ($jsonData === NULL) ? $value : $jsonData;
    }

    /* hash */

    /**
     * 保存数据到hash表
     * @param  [type] $name  [hash表名]
     * @param  [type] $key   [key]
     * @param  [type] $value [value]
     * @return [type]        [bool]
     */
    public function hset($name,$key,$value){
        return self::$redis->hset($name,$key,$this->disposeAdd($value));
    }

    /**
     * 获取hash表指定key的值
     * @param  [type] $name [hash表名]
     * @param  [type] $key  [key]
     * @return [type]       []
     */
    public function hget($name,$key){
        return $this->disposeReturn(self::$redis->hget($name,$key));
    }

    // 返回hash表中的指定key是否存在,true or false
    public function hexists($name,$key){
        return self::$redis->hexists($name,$key);
    }

    // 删除hash表中指定key的元素,true or false
    public function hdel($name,$key){
        return self::$redis->hdel($name,$key);
    }

    // 返回hash表元素个数
    public function hlen($name){
        return self::$redis->hlen($name);
    }

    // 增加一个元素，但不能重复
    public function hsetnx($name,$key,$value){
        return self::$redis->hsetnx($name,$key,$value);
    }

    // 保存多个元素到hash表 $redis->hmset(‘hash1′,array(‘key3′=>’v3′,’key4′=>’v4′));
    public function hmset($name,$array){
        return self::$redis->hmset($name,$array);
    }

    // 获取多个元素,返回相应的值 array(‘v3′,’v4′)
    public function hmget($name,$array){
        return self::$redis->hmget($name,$array);
    }

    // 返回hash表中的所有key,//返回array(‘key1′,’key2′,’key3′,’key4′,’key5′)
    public function hkeys($name){
        return self::$redis->hkeys($name);
    }

    // 返回hash表中的所有value,//返回array(‘v1′,’v2′,’v3′,’v4′,13)
    public function hvals($name){
        return self::$redis->hvals($name);
    }

    // 返回整个hash表元素,返回array(‘key1′=>’v1′,’key2′=>’v2′,’key3′=>’v3′,’key4′=>’v4′,’key5′=>13)
    public function hgetall($name){
        return self::$redis->hgetall($name);
    }
}
