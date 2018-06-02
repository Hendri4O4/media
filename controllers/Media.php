<?php
/**
 * CodeIgniter Skeleton - Media Manager Module
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2018, Kader Bouyakoub <bkader[at]mail[dot]com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Modules
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright	Copyright (c) 2018, Kader Bouyakoub <bkader[at]mail[dot]com>
 * @license 	http://opensource.org/licenses/MIT	MIT License
 * @link 		https://goo.gl/bfs7kp
 * @since 		1.0.0
 */
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
