<?php
namespace Functional;

/**
 * Function composition
 *
 * @param callable $f1
 * @param callable $f2
 * @return callable
 */
function compose(callable $f1, callable $f2): callable {
  return static function (...$args) use ($f1, $f2) {
    return call_user_func($f1, call_user_func_array($f2, $args)); 
  };
}

/**
 * Partial application of a php callable.
 *
 * @param callable $f1
 *
 * @return callable
 */
function partial(callable $f, ...$args): callable {
  return static function(...$rest) use ($f, $args) {
    return call_user_func_array($f, array_merge($args, $rest));
  };
}
