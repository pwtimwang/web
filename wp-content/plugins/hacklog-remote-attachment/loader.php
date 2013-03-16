<?php
/*
 Plugin Name: Hacklog Remote Attachment
 Plugin URI: http://ihacklog.com/?p=5001
 Description: WordPress 远程附件上传插件.Remote attachment support for WordPress.Support Multisite.
 Version: 1.2.8
 Author: <a href="http://ihacklog.com/">荒野无灯</a>
 Author URI: http://ihacklog.com/
 */

/**
 * $Id: loader.php 571281 2012-07-12 14:54:00Z ihacklog $
 * $Revision: 571281 $
 * $Date: 2012-07-12 14:54:00 +0000 (Thu, 12 Jul 2012) $
 * @package Hacklog Remote Attachment
 * @encoding UTF-8
 * @author 荒野无灯 <HuangYeWuDeng>
 * @link http://ihacklog.com
 * @copyright Copyright (C) 2011 荒野无灯
 * @license http://www.gnu.org/licenses/
 */

/*
 Copyright 2011  荒野无灯

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

define('HACKLOG_RA_LOADER', __FILE__);
require plugin_dir_path(__FILE__) . '/includes/hacklogra.class.php';
new hacklogra();

