<?php

namespace WBS_WP_SMS_TO_BULK;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Class Wbs_sms_to_option
 * 
 * This plugin is a fork from https://wordpress.org/plugins/wp-sms/ developed by VeronaLabs
 * @author mostafa.s1990, kashani, mehrshaddarzi, alifallahrn, panicoschr10
 * @copyright  2020 VeronaLabs
 * @license    GPLv3
 * @license uri: http://www.gnu.org/licenses/gpl.html
 * 
 */
#[\AllowDynamicProperties]
class WBS_SMS_TO_Option {

	/**
	 * Get the whole Plugin Options
	 *
	 * @param string $setting_name
	 * @param bool $pro
	 *
	 * @return mixed|void
	 */
	public static function getOptions( $pro = false, $setting_name = '' ) {
		if ( ! $setting_name ) {
			if ( $pro ) {
				global $wpsmstobulk_pro_option;

				return $wpsmstobulk_pro_option;
			}

			global $wpsmstobulk_option;

			return $wpsmstobulk_option;
		}

		return get_option( $setting_name );
	}


	/**
	 * Get the only Option that we want
	 *
	 * @param $option_name
	 * @param string $setting_name
	 * @param bool $pro
	 *
	 * @return string
	 */
	public static function getOption( $option_name, $pro = false, $setting_name = '' ) {
		if ( ! $setting_name ) {
			if ( $pro ) {
				global $wpsmstobulk_pro_option;

				return isset( $wpsmstobulk_pro_option[ $option_name ] ) ? $wpsmstobulk_pro_option[ $option_name ] : '';
			}

			global $wpsmstobulk_option;

			return isset( $wpsmstobulk_option[ $option_name ] ) ? $wpsmstobulk_option[ $option_name ] : '';
		}
		$options = self::getOptions( $setting_name );

		return isset( $options[ $option_name ] ) ? $options[ $option_name ] : '';

	}

	/**
	 * Add an option
	 *
	 * @param $option_name
	 * @param $option_value
	 */
	public static function addOption( $option_name, $option_value ) {
		add_option( $option_name, $option_value );
	}

	/**
	 * Update Option
	 *
	 * @param $key
	 * @param $value
	 * @param bool $pro
	 */
	public static function updateOption( $key, $value, $pro = false ) {
		if ( $pro ) {
			$setting_name = 'sms_pp_settings';
		} else {
			$setting_name = 'wpsmstobulk_settings';
		}

		$options         = self::getOptions( $pro );
		$options[ $key ] = $value;

		update_option( $setting_name, $options );
	}

}