<?php
namespace App\Support;

class CacheKeys
{
    public static function exceptKeys(string $key)
    {
        return !in_array($key, ['cache.default', 'cache.expires']);
    }
    public static function user(string $user_id)
    {
        return "user:$user_id";
    }

    public static function userScopes(string $user_id)
    {
        return "user:$user_id:scopes";
    }

    public static function userScopesApiKey(string $user_id)
    {
        return "user:$user_id:scopes:api_key";
    }


    public static function userAdmin(string $user_id)
    {
        return "user:$user_id:admin";
    }

    public static function userGroups(string $user_id)
    {
        return "user:$user_id:groups";
    }

    public static function userAuth(string $user_id)
    {
        return "user:$user_id:auth";
    }

    public static function userScopeList(string $user_id)
    {
        return "user:$user_id:scopes:list";
    }

    public static function passportScopes()
    {
        return "passport:scopes";
    }

    public static function settings(string $key)
    {
        return "settings:$key";
    }

    public static function broadcast()
    {
        return "broadcast:channels";
    }
}
