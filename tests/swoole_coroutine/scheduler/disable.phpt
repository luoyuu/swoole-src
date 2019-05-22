--TEST--
swoole_coroutine/scheduler: disable
--SKIPIF--
<?php
require __DIR__ . '/../../include/skipif.inc';
skip_if_in_valgrind();
?>
--FILE--
<?php
require __DIR__ . '/../../include/bootstrap.php';
Co::set(['enable_preemptive_scheduler' => true]);
go(function () {
    Co::set(['enable_preemptive_scheduler' => false]);
});
$flag = true;
go(function () use (&$flag) {
    Co::set(['enable_preemptive_scheduler' => true]);
    $s = microtime(true);
    while ($flag) {
        continue;
    }
    Assert::greaterThan(microtime(true) - $s, 0.01);
});
$flag = false;
Swoole\Event::wait();
?>
--EXPECT--
