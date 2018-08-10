<?php
/**
 * Creates new user
 *
 * @param array $user_data          User data contains the following fields:
 *                                      - name
 *                                      - email
 *                                      - password_hash
 *
 * @return string                   Returns ID of created user
 *
 * @throws \UserExistsException     Throws exception if user with this email already exists
 *
 */
function create_user(array $user_data)
{
    $redis = getRedis();

    $id = sha1($user_data['email']. $user_data['password_hash']);

    if ($redis->get($id) !== false) {
        throw new \UserExistsException('User with this email already exists');
    }

    $redis->set($id, json_encode($user_data));

    return $id;
}

/**
 * Finds user by combination of email and password hash
 *
 * @param string $email
 * @param string $password_hash
 *
 * @return string|null                   Returns ID of user or null if user not found
 */
function authorize_user($email, $password_hash)
{
    $redis = getRedis();
    $id = sha1($email. $password_hash);
    $user_data = json_decode($redis->get($id), true);

    return !isset($user_data['email']) ? null : $id;
}

/**
 * @return Redis
 */
function getRedis()
{
    $redis = new Redis();
    $redis->connect('localhost');
    $redis->select(0);

    return $redis;
}