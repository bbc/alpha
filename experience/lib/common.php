<?php

/* Author: Mo McRoberts <mo.mcroberts@bbc.co.uk>
 *
 * Copyright (c) 2017 BBC
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

/* Common initialisation: we use UTF-8 throughout, and UTC until we know
 * otherwise
 */

$profile_start = microtime(true);
umask(022);
error_reporting(E_ALL|E_STRICT|E_RECOVERABLE_ERROR);
ini_set('display_errors', 'On');
if(function_exists('set_magic_quotes_runtime'))
{
	@set_magic_quotes_runtime(0);
}
ini_set('session.auto_start', 0);
ini_set('default_charset', 'UTF-8');
if(function_exists('mb_regex_encoding')) mb_regex_encoding('UTF-8');
if(function_exists('mb_internal_encoding')) mb_internal_encoding('UTF-8');
date_default_timezone_set('UTC');
putenv('TZ=UTC');
ini_set('date.timezone', 'UTC');

/* Load the deployment configuration and defaults */

require_once(dirname(__FILE__) . '/../config/config.php');
require_once(dirname(__FILE__) . '/../config/defaults.php');

/* _e(str) - returns an HTML-safe version of a UTF-8 string */
function _e($str)
{
	return str_replace('&apos;', '&#39;', str_replace('&quot;', '&#34;', htmlspecialchars(strval($str), ENT_QUOTES, 'UTF-8')));
}

/* e(str) - emits an HTML-safe version of a UTF-8 string */
function e($str)
{
	echo str_replace('&apos;', '&#39;', str_replace('&quot;', '&#34;', htmlspecialchars(strval($str), ENT_QUOTES, 'UTF-8')));
}

function img($name)
{
    return STATIC_URI . '/img/' . $name;
}

function css($name)
{
    return STATIC_URI . '/css/' . $name;
}

function webfont($name)
{
    return STATIC_URI . '/fonts/' . $name;
}

function js($name)
{
    return STATIC_URI . '/js/' . $name;
}

function emit($templateName)
{
    /* Any variables (including globals) which ought to be available to
     * templates must be declared here.
     */
    global $components, $page, $profile_start;
    
	require(dirname(__FILE__) . '/../templates/' . THEME . '/' . $templateName . '.phtml');
}

define('CM_SSI', 0);
define('CM_ESI', 1);

class Component
{
	public $mode;
	public $root;
	public $key;
	
    public function get($params = null)
    {
        global $components;
        
        if($params === null)
        {
            $params = array();
        }
        $params = array_merge($params, $components->params);
        $path = $this->root . $this->key;
        if($this->mode == CM_SSI && substr($path, 0, 1) == '/' && strpos($this->key, '/') === false)
        {
            /* If we're including via SSIs, and serving within the same
             * virtual host, we almost certainly want to add a trailing
             * slash to a component name if it doesn't include one - that's
             * because SSIs don't play nicely with internal redirects into
             * directories.
             */
            $path .= '/';
        }
        if(!isset($components->hit[$this->key]))
        {
            $params['first'] = 1;
            $components->hit[$this->key] = true;
        }
        if(count($params))
        {
            $path .= '?' . http_build_query($params);
        }
		switch($this->mode)
		{
			case CM_SSI:
				return '<!--#include virtual="' . _e($path) . '" -->';
			case CM_ESI:
				return '<esi:include src="' . _e($path) . '" />';	
		}
    }
    
	public function __toString()
	{
        return $this->get();
	}
}

class Components implements ArrayAccess
{
	public $root = '/components/';
	public $mode = CM_SSI;
	public $params = array();
    public $hit = array();
    
	public function offsetGet($key)
	{
		$c = new Component();
		$c->root = $this->root;
		$c->key = $key;
		$c->mode = $this->mode;
		return $c;
	}
	
	public function offsetExists($key)
	{
		return true;
	}
	
	public function offsetSet($key, $value)
	{
	}
	
	public function offsetUnset($key)
	{
	}
}

class Page
{
    public $classes = array('alpha');
    public $title = '';
    public $lang = '';
    public $tz = 'UTC';
    public $body = null;
}

$components = new Components();
$page = new Page();

