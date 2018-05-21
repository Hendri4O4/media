<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! class_exists('Csk_media_module', false)):
/**
 * Csk_media_module
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Modules\Initialize
 * @author 		Kader Bouyakoub <bkader@mail.com>
 * @link 		https://goo.gl/wGXHO9
 * @copyright 	Copyright (c) 2018, Kader Bouyakoub (https://goo.gl/wGXHO9)
 * @since 		1.0.0
 * @version 	1.0.0
 */
class Csk_media_module {

	/**
	 * Array of default options to set/check in database.
	 * @var array
	 */
	protected static $_options = array(
		// Organize folder within year/month folders.
		'upload_year_month' => array(
			'value'      => true,
			'tab'        => 'media',
			'field_type' => 'dropdown',
			'options'    => array('true' => 'lang:CSK_YES', 'false' => 'lang:CSK_NO'),
			'required'   => true,
		),

		// Image thumbnail width.
		'image_thumbnail_w' => array(
			'value'      => 150,
			'tab'        => 'media',
			'field_type' => 'number',
			'required'   => true,
		),

		// Image thumbnail height.
		'image_thumbnail_h' => array(
			'value'      => 150,
			'tab'        => 'media',
			'field_type' => 'number',
			'required'   => true,
		),

		// Image medium width.
		'image_medium_w' => array(
			'value'      => 300,
			'tab'        => 'media',
			'field_type' => 'number',
			'required'   => true,
		),

		// Image medium height.
		'image_medium_h' => array(
			'value'      => 300,
			'tab'        => 'media',
			'field_type' => 'number',
			'required'   => true,
		),

		// Whether to crop thumbnails
		'image_thumbnail_crop' => array(
			'value'      => true,
			'tab'        => 'media',
			'field_type' => 'dropdown',
			'options'    => array('true' => 'lang:CSK_YES', 'false' => 'lang:CSK_NO'),
			'required'   => true,
		),

		// Image large width.
		'image_large_w' => array(
			'value'      => 1024,
			'tab'        => 'media',
			'field_type' => 'number',
			'required'   => true,
		),

		// Image large height.
		'image_large_h' => array(
			'value'      => 1024,
			'tab'        => 'media',
			'field_type' => 'number',
			'required'   => true,
		),
	);

	// ------------------------------------------------------------------------

	/**
	 * activate
	 *
	 * This method is triggered upon module's activation. It makes sure to add
	 * required configurations to "options" table.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	https://goo.gl/wGXHO9
	 * @since 	1.0.0
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public static function activate()
	{
		$CI =& get_instance();

		/**
		 * We loop through all default settings and insert them one by
		 * one only if they don't already exist.
		 */
		foreach (self::$_options as $name => $data)
		{
			if (false !== $CI->kbcore->options->get($name))
			{
				continue;
			}

			// Added the name if it's not already set.
			isset($data['name']) OR $data['name'] = $name;
			$CI->kbcore->options->create($data);
		}

		return true;
	}

	// ------------------------------------------------------------------------

	/**
	 * deactivate
	 *
	 * This method is trigger upon module's deactivation. It simply remove all
	 * options inserted when this module was activated.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	https://goo.gl/wGXHO9
	 * @since 	1.0.0
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	bool
	 */
	public static function deactivate()
	{
		$CI =& get_instance();
		return $CI->kbcore->options->delete_by('tab', 'media');
	}

	// ------------------------------------------------------------------------

	/**
	 * load
	 *
	 * This method is triggered when this file is loaded.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	https://goo.gl/wGXHO9
	 * @since 	1.0.0
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public static function autoloader()
	{
		if (class_exists('Media_lib', false)) {
			return;
		}

		get_instance()->load->library('media/media_lib', null, 'media');
	}

}

/**
 * Register activation action.
 * @since 	1.0.0
 */
add_action('module_activate_media', array('Csk_media_module', 'activate'));

/**
 * Register deactivation action.
 * @since 	1.0.0
 */
add_action('module_deactivate_media', array('Csk_media_module', 'deactivate'));

/**
 * Register action when this module is loaded.
 * @since 	1.0.0
 */
add_action('module_loaded_media', array('Csk_media_module', 'autoloader'));

endif; // End of the Csk_media_module class.
