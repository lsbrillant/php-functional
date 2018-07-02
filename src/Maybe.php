<?php
namespace Functional;

use function Functional\compose;

require_once("./Monad.php");

function None() {
  return Maybe::None();
}

function Just($data) {
  return Maybe::Just($data);
}

/**
 * A Maybe type for PHP!
 * =====================
 * 
 *
 * # Usage:
 *
 * ```php
 *  Maybe::None()
 *  Maybe::Just($student)
 *
 *  $maybeStudent->display_name()->unwrapOr("no name"); 
 *  if ($var = $thing->unwrap()) {
 *    // do something with $var;
 *  }
 * ```
 */
class Maybe implements Monad {

  private $data;

  private function __construct($data) {
    $this->data = $data; 
  }

  /**
   * @param mixed $data The data you want in the just.
   * @return Maybe
   */
  public static function Just($data): Maybe {
    assert($data !== null);
    return new static($data); 
  }

  /**
   * @return Maybe
   */
  public static function None(): Maybe {
    return new static(null);
  }

  public static function fromVar($data): Maybe {
    return new static($data);
  }

  public static function isJust($maybe): bool {
    return $maybe->data !== null;
  }

  public function unwrap() {
    return $this->data;
  }
  
  public function unwrapOr($backup) {
    if (static::isJust($this)) {
      return $this->data;
    } 
    if (is_callable($backup)) {
      return call_user_func($backup);
    } 
    return $backup;
  }

  public static function pure($data): Maybe {
    return static::Just($data);
  }
  
  /**
   * Monadic bind
   *
   * @param callable $func of type :: a -> Maybe a
   * @return Maybe
   */
  public function bind(callable $func): Maybe {
    if (static::isJust($this)) {
      return call_user_func($func, $this->data);
    } 
    return $this;
  }


  public function __call($name, $args) {
    return $this->bind(function($data) use ($name, $args) { 
      return static::fromVar(call_user_func_array([$data, $name], $args));
    });
  }
}
