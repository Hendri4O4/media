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

class Reports extends Reports_Controller {

	public function index()
	{
		// Load pagination library and set configuration.
		$this->load->library('pagination');
		$config['base_url'] = $config['first_ul'] = admin_url('reports/media');
		$config['per_page'] = $this->config->item('per_page');

		// Count total rows.
		$config['total_rows'] = $this->kbcore->activities->count('module', 'media');

		// Initialize pagination.
		$this->pagination->initialize($config);

		// Create pagination links.
		$this->data['pagination'] = $this->pagination->create_links();

		// Prepare the offset and limit users to get reports.
		$limit  = $config['per_page'];
		$offset = (isset($get['page'])) ? $config['per_page'] * ($get['page'] - 1) : 0;

		// Retrieve reports.
		$reports = $this->kbcore->activities->get_many('module', 'media', $limit, $offset);

		$this->theme->render();
	}
}