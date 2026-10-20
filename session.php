<?php
if (session_status() == PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.use_trans_sid', 0);

    session_name('SVU_SESSID');
    session_set_cookie_params(array(
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off',
        'httponly' => true,
        'samesite' => 'Lax'
    ));
    session_start();
}

$session_timeout = 1800;
$session_refresh = 900;

function user_agent_hash()
{
    $agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    return hash('sha256', $agent);
}

function login_user($user)
{
    session_regenerate_id(true);
    $_SESSION['user_id']       = $user['id'];
    $_SESSION['username']      = $user['username'];
    $_SESSION['agent']         = user_agent_hash();
    $_SESSION['last_activity'] = time();
    $_SESSION['created']       = time();
}

if (isset($_SESSION['user_id'])) {
    $expired = isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > $session_timeout;
    $wrong_agent = !isset($_SESSION['agent']) || !hash_equals($_SESSION['agent'], user_agent_hash());

    if ($expired || $wrong_agent) {
        session_unset();
        session_regenerate_id(true);
        $_SESSION['error'] = $expired ? "Session expired, please login again" : "Please login again";
    } else {
        $_SESSION['last_activity'] = time();
        if (!isset($_SESSION['created']) || time() - $_SESSION['created'] > $session_refresh) {
            session_regenerate_id(true);
            $_SESSION['created'] = time();
        }
    }
}
