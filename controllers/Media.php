<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Media extends KB_Controller {

	public function __construct()
	{
		parent::__construct();
		if ( ! class_exists('Media_lib', false))
		{
			$this->load->library('media/Media_lib', null, 'media');
		}
	}

	/**
	 * Method for displaying a media item (experimental).
	 * @access 	public
	 * @param 	mixed 	$media 	The media ID or username.
	 * @return 	void
	 */
	public function index($file = null)
	{
		// If nothing provided, nothing to show.
		if (null === $file)
		{
			die();
		}

		// We remove any extension and hold the size just in case.
		$file = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
		$size  = null;

		// Is the size provided?
		if (false !== strpos($file, '-'))
		{
			list($file, $size) = explode('-', $file);
		}

		// we prepare and empty 1x1 PNG in case of failure.
		$content = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=');
		$content_type = 'png';

		// Get the media from database.
		$media = $this->media->get($file);

		// Found? proceed.
		if (false !== $media 
			&& isset($media->file_path) 
			&& is_file($media->file_path))
		{
			$file_path = $media->file_path;

			if (null !== $size) {
				$details = $media->media_meta;
				$thumb = $details['file_path'].$media->username.'-'.$size.$details['file_ext'];
				if (false !== is_file($thumb)) {
					$file_path = $thumb;
				}
			}

			$content = file_get_contents($file_path);
			$content_type = $media->media_meta['file_mime'];
		}

		// Set the output content and display it.
		$this->output
			->set_content_type($content_type)
			->set_output($content);
	}

}
