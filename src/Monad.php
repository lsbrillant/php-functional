<?php
namespace Functional;

interface Monad {
  public static function pure($data);
  
  /**
   * monadic bind
   */
  public function bind(callable $func);
}
