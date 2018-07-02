<?php
namespace Functional;

use function Functional\compose;

require_once("./Monad.php");

/**
> $r = Result::Ok(5);
> $r->isOk();
true
> $v = $r->unwrap() or return $r; 


*/
class Result implements Monad {
  
  private $data;
  private $msg;

  private function __construct() { }


  public static function Ok($data): Result {
    $r = new static;
    $r->data = $data;
    return $r; 
  }

  public static function Error($msg): Result {
    $r = new static;
    $r->msg = $msg;
    return $r; 
  }
  
  public static function pure($data): Result {
    return static::Ok($data);
  }

  public function isOk(): bool {
    return $this->msg === null;
  }
  
  public function unwrap() {
    return $this->data;
  }
  
  public function unwrapOr($backup) {
    if (static::isOk($this)) {
      return $this->data;
    }
    if (is_callable($backup)) {
      return call_user_func($backup);
    } 
    return $backup;
  }

  public function getMsg() {
    return $this->msg;
  }
  
  /**
   * Monadic bind
   *
   * @param callable $func of type :: a -> Result a
   * @return Maybe
   */
  public function bind(callable $func): Result {
    if (static::isOk($this)) {
      return call_user_func($func, $this->data);
    } 
    return $this;
  }
}
