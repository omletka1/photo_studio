<?php

namespace components;

class UserIdentity
{
    public static function findIdentity($id)
    {
        $user = static::findOne([
            'id' => $id,
            'status' => self::STATUS_ACTIVE  // 🔥 Только активные пользователи
        ]);
        return $user ? new static($user) : null;
    }
}