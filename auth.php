<?php
/**
 * Log in a particular user
 */
function login($username) {
	$_SESSION['username'] = $username;
}

/**
 * Log out the current user
 */
function logout() {
	unset($_SESSION['username']);
}

/**
 * Return whether there a user is logged in
 */
function is_logged_in() {
	return isset($_SESSION['username']);
}

/**
 * Return whether the logged-in user is an admin
 */
function is_admin() {
	return is_logged_in() and (logged_in_user() === 'admin');
}

/**
 * Get the current logged-in username
 */
function logged_in_user() {
	return $_SESSION['username'];
}

/**
 * Redirect if not logged in
 */
function require_login() {
	if(!is_logged_in()) {
		header('Location: index.php');
		exit;
	}
}