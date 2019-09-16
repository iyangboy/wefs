<?php

function test_helper() {
    return 'OK';
}

// 路由名称转换
function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}
