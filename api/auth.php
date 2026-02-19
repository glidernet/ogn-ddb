<?php

/**
 * Resolve the authenticated user ID from either:
 *   1. Bearer token in Authorization header
 *   2. Active session (Cookie-based web requests)
 *
 * Returns integer user ID on success, or sends 401 and exits.
 */
function api_authenticate($dbh)
{
    // 1. Bearer token
    $auth_header = '';
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $auth_header = $_SERVER['HTTP_AUTHORIZATION'];
    } elseif (function_exists('apache_request_headers')) {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $auth_header = $headers['Authorization'];
        }
    }

    if (preg_match('/^Bearer\s+(\S+)$/i', $auth_header, $m)) {
        $token = $m[1];
        $hash  = hash('sha256', $token);
        $req   = $dbh->prepare('SELECT usr_id FROM users WHERE usr_token_hash = :th');
        $req->bindParam(':th', $hash);
        $req->execute();
        if ($req->rowCount() === 1) {
            $row = $req->fetch(PDO::FETCH_ASSOC);
            return (int)$row['usr_id'];
        }
        api_error(401, 'Invalid or expired token');
    }

    // 2. Session fallback
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['login']) && isset($_SESSION['user'])) {
        return (int)$_SESSION['user'];
    }

    api_error(401, 'Authentication required');
}

/**
 * Send a JSON error response and exit.
 */
function api_error($status, $message)
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode(array('error' => $message));
    exit;
}

/**
 * Send a JSON success response and exit.
 */
function api_response($status, $data)
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
