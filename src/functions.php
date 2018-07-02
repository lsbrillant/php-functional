<?php
namespace Functional;

function compose(callable $f1, callable $f2): callable {
  return static function (...$args) use ($f1, $f2) {
    return call_user_func($f1, call_user_func_array($f2, $args)); 
  };
}

function partial(callable $f, ...$args) {
  return static function(...$rest) use ($f, $args) {
    return call_user_func_array($f, array_merge($args, $rest));
  };
}
