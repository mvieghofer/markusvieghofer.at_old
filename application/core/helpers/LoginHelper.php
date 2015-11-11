<?php

class LoginHelper {

    public static $userLogin = 1;
    public static $adminLogin = 2;

    public static function loginCheck() {
        if (isset($_COOKIE[Config::$loginCookieName])) {
            return LoginHelper::$userLogin;
        } else if (isset($_COOKIE[Config::$adminLoginCookieName])) {
            return LoginHelper::$adminLogin;
        } else {
            return false;
        }
    }

    public static function adminLoginCheck() {
        return LoginHelper::loginCheck() === LoginHelper::$adminLogin;
    }

    public static function isLogin() {
        return isset($_POST['username']) && isset($_POST['password']);
    }

    public static function login($successRedirect, $errorRedirect, $isAdminLogin = false) {
        if (LoginHelper::isLogin()) {
            $remember = isset($_POST['remember']) ? $_POST['remember'] : false;
            LoginHelper::doLogin($successRedirect, $errorRedirect, $isAdminLogin, $_POST['username'], $_POST['password'], $remember);
        }
    }

    private static function doLogin($successRedirect, $errorRedirect, $isAdminLogin, $username, $password, $remember) {
        $users = User::where('username', '=', $username)->get();
        if ($users->isEmpty()) {
            header("Location: {$errorRedirect}");
        } else {
            $user = $users->first();
            if ($isAdminLogin && !$user->is_admin) {
                header("Location: {$errorRedirect}");
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['salt' => $user->salt]);
                if ($user->password === $hashedPassword) {
                    LoginHelper::setCookie($remember, $username, $isAdminLogin);
                    header("Location: {$successRedirect}");
                } else {
                    header("Location: {$errorRedirect}");
                }
            }
        }
    }

    private static function setCookie($remember, $username, $isAdminLogin) {
        $expirationDate = new DateTime();
        $expirationDate->add(new DateInterval("PT24H"));
        if ($remember) {
            $expirationDate->add(new DateInterval('P6D'));
        }

        $loginCookie = [];
        $loginCookie['token'] = md5(uniqid($username, true));
        $cookieName = Config::$loginCookieName;
        if ($isAdminLogin) {
            $cookieName = Config::$adminLoginCookieName;
        }
        setcookie($cookieName, json_encode($loginCookie), $expirationDate->getTimestamp());
    }
}
?>
