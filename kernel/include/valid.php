<?php

// url validator
function is_url($str)
{
  return (preg_match('~^(http|https|ftp):\/\/[a-z0-9\/:_\-_\.\?\$,~\=#&%\+]+$~i', $str) > 0);
}

// email validator
function is_email($str)
{
  return (preg_match('~^[_a-zA-Z\d\-\.]+@([_a-zA-Z\d\-]+(\.[_a-zA-Z\d\-]+)*)$~', $str) > 0);
}

// alpha numeric validator
function is_alphanum($str)
{
  return (preg_match('~^[a-zA-Z -пЂ-џ\d]+$~', $str) > 0);
}

// phone number
function is_phone($str)
{
  return (preg_match('~^(\+\d *)?(\(\d+\) *)?(\d+(-\d*)*)$~', $str) > 0);
}

?>