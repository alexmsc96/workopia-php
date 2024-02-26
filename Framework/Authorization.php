<?php

namespace Framework;

use Framework\Session;

/**
 * Class Authorization
 * 
 * This class is responsible for handling authorization-related operations.
 */

class Authorization
{
  /**
   * Check if current logged in user owns a resource
   * 
   * @param int $resourceId
   * 
   * @return bool
   */

  public static function isOwner($resourceId)
  {
    $sessionUser = Session::get('user');

    if ($sessionUser !== null && isset($sessionUser['id'])) {
      $sessionUserId = (int) $sessionUser['id'];
      return $sessionUserId === $resourceId;
    }

    return false;
  }
}