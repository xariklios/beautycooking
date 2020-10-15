<?php
/**
 * Soapery Framework: theme variables storage
 *
 * @package	soapery
 * @since	soapery 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('soapery_storage_get')) {
	function soapery_storage_get($var_name, $default='') {
		global $SOAPERY_STORAGE;
		return isset($SOAPERY_STORAGE[$var_name]) ? $SOAPERY_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('soapery_storage_set')) {
	function soapery_storage_set($var_name, $value) {
		global $SOAPERY_STORAGE;
		$SOAPERY_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('soapery_storage_empty')) {
	function soapery_storage_empty($var_name, $key='', $key2='') {
		global $SOAPERY_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($SOAPERY_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($SOAPERY_STORAGE[$var_name][$key]);
		else
			return empty($SOAPERY_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('soapery_storage_isset')) {
	function soapery_storage_isset($var_name, $key='', $key2='') {
		global $SOAPERY_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($SOAPERY_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($SOAPERY_STORAGE[$var_name][$key]);
		else
			return isset($SOAPERY_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('soapery_storage_inc')) {
	function soapery_storage_inc($var_name, $value=1) {
		global $SOAPERY_STORAGE;
		if (empty($SOAPERY_STORAGE[$var_name])) $SOAPERY_STORAGE[$var_name] = 0;
		$SOAPERY_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('soapery_storage_concat')) {
	function soapery_storage_concat($var_name, $value) {
		global $SOAPERY_STORAGE;
		if (empty($SOAPERY_STORAGE[$var_name])) $SOAPERY_STORAGE[$var_name] = '';
		$SOAPERY_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('soapery_storage_get_array')) {
	function soapery_storage_get_array($var_name, $key, $key2='', $default='') {
		global $SOAPERY_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($SOAPERY_STORAGE[$var_name][$key]) ? $SOAPERY_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($SOAPERY_STORAGE[$var_name][$key][$key2]) ? $SOAPERY_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('soapery_storage_set_array')) {
	function soapery_storage_set_array($var_name, $key, $value) {
		global $SOAPERY_STORAGE;
		if (!isset($SOAPERY_STORAGE[$var_name])) $SOAPERY_STORAGE[$var_name] = array();
		if ($key==='')
			$SOAPERY_STORAGE[$var_name][] = $value;
		else
			$SOAPERY_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('soapery_storage_set_array2')) {
	function soapery_storage_set_array2($var_name, $key, $key2, $value) {
		global $SOAPERY_STORAGE;
		if (!isset($SOAPERY_STORAGE[$var_name])) $SOAPERY_STORAGE[$var_name] = array();
		if (!isset($SOAPERY_STORAGE[$var_name][$key])) $SOAPERY_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$SOAPERY_STORAGE[$var_name][$key][] = $value;
		else
			$SOAPERY_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Add array element after the key
if (!function_exists('soapery_storage_set_array_after')) {
	function soapery_storage_set_array_after($var_name, $after, $key, $value='') {
		global $SOAPERY_STORAGE;
		if (!isset($SOAPERY_STORAGE[$var_name])) $SOAPERY_STORAGE[$var_name] = array();
		if (is_array($key))
			soapery_array_insert_after($SOAPERY_STORAGE[$var_name], $after, $key);
		else
			soapery_array_insert_after($SOAPERY_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('soapery_storage_set_array_before')) {
	function soapery_storage_set_array_before($var_name, $before, $key, $value='') {
		global $SOAPERY_STORAGE;
		if (!isset($SOAPERY_STORAGE[$var_name])) $SOAPERY_STORAGE[$var_name] = array();
		if (is_array($key))
			soapery_array_insert_before($SOAPERY_STORAGE[$var_name], $before, $key);
		else
			soapery_array_insert_before($SOAPERY_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('soapery_storage_push_array')) {
	function soapery_storage_push_array($var_name, $key, $value) {
		global $SOAPERY_STORAGE;
		if (!isset($SOAPERY_STORAGE[$var_name])) $SOAPERY_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($SOAPERY_STORAGE[$var_name], $value);
		else {
			if (!isset($SOAPERY_STORAGE[$var_name][$key])) $SOAPERY_STORAGE[$var_name][$key] = array();
			array_push($SOAPERY_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('soapery_storage_pop_array')) {
	function soapery_storage_pop_array($var_name, $key='', $defa='') {
		global $SOAPERY_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($SOAPERY_STORAGE[$var_name]) && is_array($SOAPERY_STORAGE[$var_name]) && count($SOAPERY_STORAGE[$var_name]) > 0) 
				$rez = array_pop($SOAPERY_STORAGE[$var_name]);
		} else {
			if (isset($SOAPERY_STORAGE[$var_name][$key]) && is_array($SOAPERY_STORAGE[$var_name][$key]) && count($SOAPERY_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($SOAPERY_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('soapery_storage_inc_array')) {
	function soapery_storage_inc_array($var_name, $key, $value=1) {
		global $SOAPERY_STORAGE;
		if (!isset($SOAPERY_STORAGE[$var_name])) $SOAPERY_STORAGE[$var_name] = array();
		if (empty($SOAPERY_STORAGE[$var_name][$key])) $SOAPERY_STORAGE[$var_name][$key] = 0;
		$SOAPERY_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('soapery_storage_concat_array')) {
	function soapery_storage_concat_array($var_name, $key, $value) {
		global $SOAPERY_STORAGE;
		if (!isset($SOAPERY_STORAGE[$var_name])) $SOAPERY_STORAGE[$var_name] = array();
		if (empty($SOAPERY_STORAGE[$var_name][$key])) $SOAPERY_STORAGE[$var_name][$key] = '';
		$SOAPERY_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('soapery_storage_call_obj_method')) {
	function soapery_storage_call_obj_method($var_name, $method, $param=null) {
		global $SOAPERY_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($SOAPERY_STORAGE[$var_name]) ? $SOAPERY_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($SOAPERY_STORAGE[$var_name]) ? $SOAPERY_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('soapery_storage_get_obj_property')) {
	function soapery_storage_get_obj_property($var_name, $prop, $default='') {
		global $SOAPERY_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($SOAPERY_STORAGE[$var_name]->$prop) ? $SOAPERY_STORAGE[$var_name]->$prop : $default;
	}
}

// Merge two-dim array element
if (!function_exists('soapery_storage_merge_array')) {
    function soapery_storage_merge_array($var_name, $key, $arr) {
        global $SOAPERY_STORAGE;
        if (!isset($SOAPERY_STORAGE[$var_name])) $SOAPERY_STORAGE[$var_name] = array();
        if (!isset($SOAPERY_STORAGE[$var_name][$key])) $SOAPERY_STORAGE[$var_name][$key] = array();
        $SOAPERY_STORAGE[$var_name][$key] = array_merge($SOAPERY_STORAGE[$var_name][$key], $arr);
    }
}
?>