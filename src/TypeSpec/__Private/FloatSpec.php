<?hh // strict
/*
 *  Copyright (c) 2016, Fred Emmott
 *  Copyright (c) 2017-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace Facebook\TypeSpec\__Private;

use type Facebook\TypeAssert\{IncorrectTypeException, TypeCoercionException};
use type Facebook\TypeSpec\TypeSpec;

final class FloatSpec extends TypeSpec<float> {
  <<__Override>>
  public function coerceType(mixed $value): float {
    if ($value is float) {
      return $value;
    }

    if ($value is int) {
      return (float)$value;
    }

    if ($value instanceof \Stringish) {
      /* HH_FIXME[4281] Stringish is going */
      $str = (string)$value;
      if ($str === '') {
        throw
          TypeCoercionException::withValue($this->getTrace(), 'float', $value);
      }
      if (\ctype_digit($value)) {
        return (float)$str;
      }
      if (\preg_match("/^(\\d*\\.)?\\d+([eE]\\d+)?$/", $str) === 1) {
        return (float)$str;
      }
    }
    throw TypeCoercionException::withValue($this->getTrace(), 'float', $value);
  }

  <<__Override>>
  public function assertType(mixed $value): float {
    if ($value is float) {
      return $value;
    }
    throw IncorrectTypeException::withValue($this->getTrace(), 'float', $value);
  }
}
