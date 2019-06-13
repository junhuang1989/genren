<?php

   swoole_event_add($fd,function(){
      echo '创建一个php文件';
   });
