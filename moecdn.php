<?php
/**
 * @package MoeCDN
 * @version 1.0
 */
/*
	Plugin Name: MoeNet Public CDN
	Plugin URI: http://http://cdn.moefont.com/
	Description: Static files CDN which WordPress needs in China.
	Author: MoeNet Inc.
	Version: 1.6
	Author URI: http://www.moenetwork.com
*/

class MoeCDN {
	protected static $options;
	protected static $buffer_count;
	
	const GRAVATAR = "gravatar.moefont.com";
	const GRAVATAR_SSL = "gravatar-ssl.moefont.com";
	const GOOGLE_FONTS = "cdn.moefont.com/fonts";
	const GOOGLE_AJAX = "cdn.moefont.com/ajax";
	const WORG = "cdn.moefont.com/worg";
	const WPCOM = "cdn.moefont.com/wpcom";
	
	public function __construct() {
		self::$options = get_option('moecdn_options');
		if (!is_array(self::$options))
			self::reset_options();
		self::$buffer_count = 0;
		self::init();
    }
    
    protected static function init() {
		//add_action('admin_init', array('MoeCDN', 'options_init'));
		//add_action('admin_menu', array('MoeCDN', 'options_menu'));
		//add_action('admin_notices', array('MoeCDN', 'options_notice'));
		
		add_action('init', array('MoeCDN', 'buffer_start'));
		add_action('shutdown', array('MoeCDN', 'buffer_end'));
    }
	
	// 缓冲替换输出
	public static function buffer_start() {
		foreach (self::$options as $key => $value) {
			if ($value) {
				ob_start(array('MoeCDN', 'replace_' . $key));
				self::$buffer_count++;
			}
		}
	}
	public static function buffer_end() {
		for ($i = 0; $i < self::$buffer_count; $i++)
			ob_end_flush();
	}
	
	// 替换函数
	protected static function replace_gravatar($avatar) {
		$avatar = str_replace(array("www.gravatar.com", "0.gravatar.com", "1.gravatar.com", "2.gravatar.com"), GRAVATAR, $avatar);
		$avatar = str_replace(array("secure.gravatar.com"), GRAVATAR_SSL, $avatar);
		return $avatar;
	}
	protected static function replace_googleapis($url) {
		$url = str_replace(array("fonts.googleapis.com"), GOOGLE_FONTS, $url);
		$url = str_replace(array("ajax.googleapis.com"), GOOGLE_AJAX, $url);
		return $url;
	}
	protected static function replace_worg($url) {
		$url = str_replace(array("s.w.org"), WORG, $url);
	}
	protected static function replace_wpcom($url) {
		$url = str_replace(array("s0.wp.com", "s1.wp.com"), WPCOM, $url);
	}
	
	// 设置页面
	/*protected static function get_options() {
		return self::$options;
	}*/
	protected static function reset_options() {
		self::$options = array(
			'gravatar' => true,
			'googleapis' => true,
			'worg' => true,
			'wpcom' => true
		);
		update_option('moecdn_options', $options);
	}
	protected static function save_options() {
		self::$options = array(
			'gravatar' => $_POST['gravatar'],
			'googleapis' => $_POST['googleapis'],
			'worg' => $_POST['worg'],
			'wpcom' => $_POST['wpcom']);
		update_option('moecdn_options', $options);
	}
	public static function options_notice() {
		settings_errors();
	}
	public static function options_init() {
		if (isset($_POST['save-options'])) {
			self::save_options();
			add_settings_error('moecdn_options', 'moecdn_options-updated', __('Settings saved.'), 'updated');
		} elseif (isset($_POST['reset-options'])) {
			self::reset_options();
			add_settings_error('moecdn_options', 'moecdn_options-reseted', __('Settings reseted.'), 'updated');
		}
	}
	public static function options_menu() {
		add_options_page('MoeCDN 设置', 'MoeCDN', 'manage_options', basename(__FILE__), array('MoeCDN', 'options_display'));
	}

	public static function options_display() {
		?>

		<style type="text/css">
			.clear { clear: both; }
		</style>
		
		<input name="save-options" type="submit" class="save" value="保存设置">
		<input name="reset-options" type="submit" class="reset" value="重置设置" onclick="return confirm('你确定要重置所有的设置吗？');">
		
		<div class="clear"></div>

		<?php
	}
}

$MoeCDN = new MoeCDN();
