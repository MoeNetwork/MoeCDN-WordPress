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
	
	public function __construct() {
		self::$options = get_option('moecdn_options');
		if (!is_array(self::$options))
			self::reset_options();
		self::init();
    }
    
	protected static function init() {
		add_action('admin_init', array('MoeCDN', 'options_init'));
		add_action('admin_menu', array('MoeCDN', 'options_menu'));
		add_action('admin_notices', array('MoeCDN', 'options_notice'));
		
		add_action('init', array('MoeCDN', 'buffer_start'));
		add_action('shutdown', array('MoeCDN', 'buffer_end'));
	}
	
	// 缓冲替换输出
	public static function buffer_start() {
		ob_start(array('MoeCDN', 'replace'));
	}
	public static function buffer_end() {
		ob_end_flush();
	}
	protected static function replace($buffer) {
		$buffer = str_replace(array("www.gravatar.com", "0.gravatar.com", "1.gravatar.com", "2.gravatar.com"), "gravatar.moefont.com", $buffer);
		$buffer = str_replace(array("secure.gravatar.com"), "gravatar-ssl.moefont.com", $buffer);
		
		$buffer = str_replace(array("fonts.googleapis.com"), "cdn.moefont.com/fonts", $buffer);
		$buffer = str_replace(array("ajax.googleapis.com"), "cdn.moefont.com/ajax", $buffer);
		
		$buffer = str_replace(array("\\/\\/s.w.org"), "\\/\\/cdn.moefont.com\\/worg", $buffer);
		
		$buffer = str_replace(array("s0.wp.com", "s1.wp.com"), "cdn.moefont.com/wpcom", $buffer);
		
		return $buffer;
	}
	
	// 设置页面
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

