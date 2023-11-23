<?php

namespace App\Helpers;

class AuthHelper
{
  public function isEmail(string $email): bool
  {
    return (filter_var($email, FILTER_VALIDATE_EMAIL));
  }

  public function confirmPassword(string $password, string $confirmPassword): bool
  {
    return $password == $confirmPassword;
  }

  public function passwordMinimumLength(string $password, int $length): bool
  {
    return strlen($password) >= $length;
  }
}
