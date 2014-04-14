<?php
if (function_exists('event_base_new')) {
    echo 'LibEventLoop';
} else if (class_exists('libev\EventLoop')) {
    echo 'LibEvLoop';
} else if (class_exists('EventBase')) {
    echo 'ExtEventLoop';
}
