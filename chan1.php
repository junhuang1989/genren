<?php
use Swoole\Coroutine as co;
$chan = new co\Channel(1);
co::create(function () use ($chan) {
    for($i = 0; $i < 10; $i++) {
        co::sleep(1.0);//为了演示channel的读写操作
        //往通道写操作，因为通道的长度为1，写入一次通道满了，后续的循环写入阻塞掉
        //并发生协程切换，挂起当前协程
        $chan->push(['rand' => rand(1000, 9999), 'index' => $i]);
        echo "$i\n";
    }
});
co::create(function () use ($chan) {
    while(1) {
    	//上面的协程切换后该协程执行，从通道读取数据，也是因为通道长度为1，
    	//读取完一条数据后，通道为空发生切换，又切换到是一个协程继续往通道写操作
        $data = $chan->pop();
        var_dump($data);
    }
});
//PHP低于5.4的版本，需要在你的PHP脚本结尾处加swoole_event_wait函数。
//使脚本开始进行事件轮询，高于5.4的版本不需要
swoole_event::wait();
//修改文件了
