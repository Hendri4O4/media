<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Media_lib
{
	protected $ci;

	/**
	 * Array of images sizes set by plugins/themes.
	 * @since 	1.4.0
	 * @var 	array
	 */
	private $_images_sizes = array();

	/**
	 * Initialize class.
	 * @access 	public
	 * @return 	void
	 */
	public function __construct()
	{
		$this->ci =& get_instance();

		$this->ci->load->language('media/csk_media');

		// We register themes images action.
		add_action('_set_images_sizes', array($this, '_set_images_sizes'));

		log_message('info', 'Media_lib Class Initialized');
	}

	// ------------------------------------------------------------------------

	/**
	 * Create a new media item.
	 * @access 	public
	 * @param 	array 	$data 	Array of data to insert.
	 * @return 	the new media item ID if found, else false.
	 */
	public function create(array $data = array())
	{
		// Make sure $data is provided.
		if (empty($data))
		{
			return false;
		}

		// Make sure the object subtype is always attachment.
		$data['subtype'] = 'attachment';

		// Proceed to creation.
		return $this->ci->kbcore->objects->create($data);
	}

	// ------------------------------------------------------------------------

	/**
	 * Retrieve a single media item by primary key.
	 *
	 * @since 	1.0.0
	 * @since 	1.3.3 	Updated so we can get the media by username.
	 * 
	 * @access 	public
	 * @param 	mixed 	$id 	The primary key value.
	 * @return 	object if found, else null
	 */
	public function get($id)
	{
		// By ID?
		if (is_numeric($id))
		{
			return $this->get_by('id', $id);
		}

		// By username?
		if (is_string($id))
		{
			return $this->get_by('username', $id);
		}

		// Otherwise let the "get_by" method do the rest;
		return $this->get_by($id);
	}

	// ------------------------------------------------------------------------

	/**
	 * Retrieve a single media item by arbitrary WHERE clause.
	 * @access 	public
	 * @param 	mixed 	$field 	Column name or associative array.
	 * @param 	mixed 	$match 	Comparison value.
	 * @return 	object if found, else null.
	 */
	public function get_by($field, $match = null)
	{
		// Make sure to add the "attachment subtype".
		$this->ci->db
			->where('entities.subtype', 'attachment')
			->order_by('entities.id', 'DESC');

		if (false !== ($media = $this->ci->kbcore->objects->get_by($field, $match)))
		{
			$media->thumbnail = (isset($media->media_meta['sizes']['thumbnail']))
				? $media->media_meta['file_url'].$media->media_meta['sizes']['thumbnail']['file_name']
				: $media->content;
				$media->file_url  = site_url('media/'.$media->username.$media->media_meta['file_ext']);
				$media->file_path = $media->media_meta['file_path'].$media->username.$media->media_meta['file_ext'];

			return $media;
		}

		return false;
	}

	// ------------------------------------------------------------------------

	/**
	 * Retrieve multiple media items by arbitrary WHERE clause.
	 * @access 	public
	 * @param 	mixed 	$field 	Column name or associative array.
	 * @param 	mixed 	$match 	Comparison value.
	 * @param 	int 	$limit 	Limit to use for getting records.
	 * @param 	int 	$offset Database offset.
	 * @return 	array o objects if found, else null.
	 */
	public function get_many($field = null, $match = null, $limit = 0, $offset = 0)
	{
		// Make sure to add the "attachment subtype".
		$this->ci->db
			->where('entities.subtype', 'attachment')
			->order_by('entities.id', 'DESC');

		$medias = $this->ci->kbcore->objects->get_many($field, $match, $limit, $offset);

		if (false !== $medias)
		{
			foreach ($medias as &$media)
			{
				// Add direct URL to the file.
				$media->file_url  = site_url('media/'.$media->username.$media->media_meta['file_ext']);

				// Added direct path to the file.
				$media->file_path = $media->media_meta['file_path'].$media->username.$media->media_meta['file_ext'];

				// Add thumbnail.
				$media->thumbnail = site_url('media/'.$media->username.$media->media_meta['file_ext']);
				if (isset($media->media_meta['sizes']['thumbnail'])) {
					$media->thumbnail = site_url('media/'.$media->media_meta['sizes']['thumbnail']['file_name']);
				}
			}
			return $medias;
		}

		return false;
	}

	// ------------------------------------------------------------------------

	/**
	 * Retrieve all media items.
	 * @access 	public
	 * @param 	int 	$limit 	Limit to use for getting records.
	 * @param 	int 	$offset Database offset.
	 * @return 	array o objects if found, else null.
	 */
	public function get_all($limit = 0, $offset = 0)
	{
		return $this->get_many(null, null, $limit, $offset);
	}

	// ------------------------------------------------------------------------

	/**
	 * Update a single media item by its primary key.
	 * @access 	public
	 * @param 	mixed 	$id 	The primary key value.
	 * @param 	array 	$data 	Array of data to update.
	 * @return 	boolean
	 */
	public function update($id, array $data = array())
	{
		return $this->ci->kbcore->objects->update($id, $data);
	}

	// ------------------------------------------------------------------------

	/**
	 * Update all or multiple media items by arbitrary WHERE clause.
	 * @access 	public
	 * @return 	boolean
	 */
	public function update_by()
	{
		// Collect arguments first and make sure there are any.
		$args = func_get_args();
		if (empty($args))
		{
			return false;
		}

		// Data to update is always the last element.
		$data = array_pop($args);
		if (empty($data))
		{
			return false;
		}

		// Prepare where clause.
		if ( ! empty($args))
		{
			(is_array($args[0])) && $args = $args[0];
			$args['subtype'] = 'attachment';
		}
		else
		{
			$args['subtype'] = 'attachment';
		}

		return $this->ci->kbcore->objects->update_by($args, $data);
	}

	/**
	 * Delete a single media item by its primary key.
	 *
	 * @since 	1.4.0 	Files are deleted if the media is successfully removed.
	 * 
	 * @access 	public
	 * @param 	mixed 	$id 	The primary key value.
	 * @return 	boolean
	 */
	public function delete($id)
	{
		// To handle calls from delete_by.
		if ($id instanceof KB_Object OR is_object($id))
		{
			$media = $id;
		}
		// We make sure the media exists first.
		elseif (false === ($media = $this->get($id)))
		{
			return false;
		}

		// Fallback to old fashion if "file_path" is not set.
		if ( ! isset($media->media_meta['file_path']))
		{
			return $this->ci->kbcore->objects->remove($id);
		}

		// Get the path then once removed from database we delete its files.
		$file_path = $media->media_meta['file_path'];
		if (false !== $this->ci->kbcore->objects->remove($id))
		{
			@array_map('unlink', glob($file_path.$media->username.'*.*'));
			return true;
		}

		return false;
	}

	/**
	 * Delete multiple or all media items by arbitrary WHER clause.
	 *
	 * @since 	1.0.0
	 * @since 	1.4.0 	It will simply use the "delete" method on each item.
	 * 
	 * @access 	public
	 * @param 	mixed 	$field 	Column name or associative array.
	 * @param 	mixed 	$match 	Comparison value.
	 * @return 	boolean
	 */
	public function delete_by($field = null, $match = null)
	{
		// See if items exist.
		$items = $this->get_many($field, $match);

		// Found any? Proceed to delete.
		if (false !== $items)
		{
			foreach ($items as $item)
			{
				// Could not be delete? Stop the script.
				if (true !== $this->delete($item))
				{
					return false;
				}
			}

			return true;
		}

		return false;
	}

	// ------------------------------------------------------------------------

	/**
	 * Count all objects.
	 * @access 	public
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 * @return 	int
	 */
	public function count($field = null, $match = null)
	{
		// Prepare where clause.
		if ( ! empty($field))
		{
			(is_array($field)) OR $field = array($field => $match);
			foreach ($field as $key => $val)
			{
				if (is_int($key) && is_array($val))
				{
					$this->ci->db->where($val);
				}
				elseif (is_array($val))
				{
					$this->ci->db->where_in($key, $val);
				}
				else
				{
					$this->ci->db->where($key, $val);
				}
			}
		}

		$rows = $this->ci->db
			->where('entities.type', 'object')
			->where('entities.subtype', 'attachment')
			->join('objects', 'objects.guid = entities.id')
			->get('entities');

		return $rows->num_rows();
	}

	// ------------------------------------------------------------------------
	// Themes images sizes methods.
	// ------------------------------------------------------------------------

	/**
	 * add_image_size
	 *
	 * Method for adding thumbnails sizes for the currently active theme.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	https://goo.gl/wGXHO9
	 * 
	 * @since 	1.0.0
	 * @since 	1.4.0 	Some names are reserved, so ignored.
	 *
	 * @access 	public
	 * @param 	string 	$name 		The name of the thumbnail.
	 * @param 	int 	$width 		The width of the thumbnail.
	 * @param 	int 	$height 	The height of the thumbnail.
	 * @param  	bool 	$crop 		Whether to crop the image.
	 * @return 	void
	 */
	public function add_image_size($name, $width = 0, $height = 0, $crop = false)
	{
		if ( ! in_array($name, array('thumbnail', 'medium', 'large')))
		{
			$this->_images_sizes[$name] = array(
				'width'  => (int) $width,
				'height' => (int) $height,
				'crop'   => (bool) $crop,
			);
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * _set_images_sizes
	 *
	 * Method for adding thumbnails sizes for the currently active theme.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	https://goo.gl/wGXHO9
	 * @since 	1.0.0
	 *
	 * @since 	1.4.0 	Updated so we can handle images sizes correctly.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function _set_images_sizes($sizes)
	{
		// No images sizes set? Noting to do.
		if (empty($this->_images_sizes))
		{
			return false;
		}

		// Prepare the option name.
		$option_name = 'theme_images_'.$this->ci->kbcore->options->item('theme');

		// Get the option from database.
		$option = $this->ci->kbcore->options->get($option_name);

		// Did we find the option?
		if (false !== $option)
		{
			// Did sizes change?
			if ($this->_images_sizes == $option->value)
			{
				return true;
			}

			// Update the option.
			return $option->update('value', $this->_images_sizes);
		}

		// Otherwise, we create the option.
		return $this->ci->kbcore->options->create(array(
			'name'     => $option_name,
			'value'    => $this->_images_sizes,
			'tab'      => 'media',
			'required' => 0,
		));
	}

}

// ------------------------------------------------------------------------

if ( ! function_exists('add_image_size'))
{
	/**
	 * add_image_size
	 *
	 * Function for adding thumbnails sizes for the currently active theme.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	https://goo.gl/wGXHO9
	 * @since 	1.0.0
	 *
	 * @access 	public
	 * @param 	string 	$name 		The name of the thumbnail.
	 * @param 	int 	$width 		The width of the thumbnail.
	 * @param 	int 	$height 	The height of the thumbnail.
	 * @param  	bool 	$crop 		Whether to crop the image.
	 * @return 	void
	 */
	function add_image_size($name, $width = 0, $height = 0, $crop = false)
	{
		return get_instance()->media->add_image_size($name, $width, $height, $crop);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('get_media'))
{
	/**
	 * get_media
	 *
	 * Retrieve a single media by its ID or username (file name).
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	https://goo.gl/wGXHO9
	 * @since 	1.4.0
	 *
	 * @param 	mixed 	$id 	It can be the ID or username.
	 * @return 	mixed 	KB_Object instance if found, else false.
	 */
	function get_media($id)
	{
		return ($id instanceof KB_Object) 
			? $id
			: get_instance()->media->get($id);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('get_attached_media_id'))
{
	/**
	 * get_attached_media_id
	 *
	 * Function for retrieving the attached media ID for the selected entity.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	https://goo.gl/wGXHO9
	 * @since 	1.4.0
	 *
	 * @param 	mixed 	The entity's ID or username.
	 * @return 	mixed 	KB_Object instance of found, else false;
	 */
	function get_attached_media_id($id)
	{
		// The passed ID is already an instance of once of these?
		if ($id instanceof KB_Object 
			OR $id instanceof KB_Group 
			OR $id instanceof KB_User)
		{
			return $id->attached_media_id;
		}

		// Make sure to find the entity.
		if (false !== ($ent = get_instance()->kbcore->entities->get($id)))
		{
			return $ent->attached_media_id;
		}

		// Sorry, nothing found.
		return false;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('get_attached_media'))
{
	/**
	 * get_attached_media
	 *
	 * Function for retrieving the attached media object for the
	 * selected entity.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	https://goo.gl/wGXHO9
	 * @since 	1.4.0
	 *
	 * @param 	mixed 	$id 	The entity's ID or username.
	 * @return 	mixed 	KB_Object instance if found, else false;
	 */
	function get_attached_media($id)
	{
		return (false !== ($media_id = get_attached_media_id($d)))
			? get_instance()->media->get($media_id)
			: false;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('has_attached_media'))
{
	/**
	 * has_attached_media
	 *
	 * function for checking whether the selected entity has an attached media.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	https://goo.gl/wGXHO9
	 * @since 	1.4.0
	 *
	 * @param 	mixed 	$id 	The entity's ID, username or object.
	 * @return 	bool
	 */
	function has_attached_media($id)
	{
		return (false !== get_attached_media_id($id));
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('get_media_src'))
{
	/**
	 * get_media_src
	 *
	 * Function for returning the URL of the selected media with optional size;
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	https://goo.gl/wGXHO9
	 * @since 	1.3.3
	 *
	 * @access 	public
	 * @param 	int 	$id 	The media ID.
	 * @param 	strong 	$size 	The requested size as set by the current theme.
	 * @return 	string
	 */
	function get_media_src($id, $size = null)
	{
		$src   = '';

		if (false !== ($media = get_media($id)))
		{
			$src = $media->content;

			if (null !== $size && isset($media->media_meta['sizes'][$size]['file_name']))
			{
				$src = $media->media_meta['file_url'];
				$src .= $media->media_meta['sizes'][$size]['file_name'];
			}
		}

		return $src;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('blank_media_src'))
{
	function blank_media_src()
	{
		return 'data:image/png;base64, iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=';
	}
}
