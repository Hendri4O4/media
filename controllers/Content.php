<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Content extends Admin_Controller {

	/**
	 * Class constructor.
	 * @return 	void
	 */
	public function __construct()
	{
		// Call parent constructor.
		parent::__construct();

		// Make sure to load the library if not already loaded.
		if ( ! class_exists('Media_lib', false))
		{
			$this->load->library('media/media_lib', null, 'media');
		}

		add_filter('admin_head', array($this, '_admin_head'));

		// Add require assets files.
		$this
			->_dropzone()
			->_handlebars()
			->_zoom()
			->_jquery_validate();
		
		// Add needed assets.
		$this->theme
			->add('css', modules_url('media/assets/css/media'))
			->add('js', modules_url('media/assets/js/media'));
	}

	// ------------------------------------------------------------------------

	/**
	 * List site's uploaded media.
	 *
	 * @since 	1.0.0
	 * @since 	1.3.0 	Rewritten to make it possible to show single item with
	 *         			get parameter "item".
	 * @since 	1.4.0 	Added media thumbnail and delete nonce.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function index()
	{
		$this->prep_form(array(
			array( 	'field' => 'name',
					'label' => 'lang:title',
					'rules' => 'trim|required|min_length[3]|max_length[100]')
		), '.media-update');

		$this->load->library('pagination');

		$config['base_url']   = $config['first_link'] = admin_url('media');
		$config['total_rows'] = $this->media->count();
		$config['per_page']   = 36;

		$this->pagination->initialize($config);

		$this->data['pagination'] = $this->pagination->create_links();

		$limit = $config['per_page'];
		$offset = 0;
		if ($this->input->get('page'))
		{
			$offset = $config['per_page'] * (intval($this->input->get('page')) - 1);
		}

		$this->load->helper('number');

		$this->data['media'] = $this->media->get_all($limit, $offset);

		if ($this->data['media'])
		{
			foreach ($this->data['media'] as &$media)
			{
				$media->details = $media->media_meta;
				$media->created_at = date('Y/m/d H:i', $media->created_at);
				$media->file_size = byte_format($media->details['file_size'] * 1024, 2);
				$media->delete_nonce = create_nonce('media-delete_'.$media->id);
			}
		}

		// In case of viewing a single item.
		$item = null;
		$item_id = $this->input->get('item', true);
		if (null !== $item_id 
			&& false !== $db_item = $this->media->get($item_id))
		{
			$item = $db_item;
			$item->details = $item->media_meta;
			$item->created_at = date('Y/m/d H:i', $item->created_at);
			$item->file_size = byte_format($item->details['file_size'] * 1024, 2);
		}

		// Pass the item to view.
		$this->data['item'] = $item;

		// Set page title and load view.
		$this->theme
			->set_title(line('CSK_MEDIA_LIBRARY'))
			->render($this->data);
	}

	// ------------------------------------------------------------------------
	// Private methods.
	// ------------------------------------------------------------------------
	
	/**
	 * Method to add our confirmations alerts to DOM.
	 *
	 * @since 	1.3.3
	 * @since 	1.4.0 	Added nonce to header.
	 *
	 * @access 	public
	 * @param 	string
	 * @return 	string
	 */
	public function _admin_head($output)
	{
		// Lines and nonce.
		$lines = array(
			'delete'      => line('CSK_MEDIA_CONFIRM_DELETE'),
			'delete_bulk' => line('CSK_MEDIA_CONFIRM_DELETE_BULK'),
		);
		
		// Media object.
		$media = array(
			'uploadUrl'  => ajax_url('media/upload'),
			'previewUrl' => ajax_url('media/item'),
			'deleteUrl' => ajax_url('media/delete'),
		);
		
		// Nonce protection.
		$nonce = array(
			'name' => 'media_upload',
			'value' => create_nonce('media_upload')
		);
		
		$output .= '<script type="text/javascript">';
		$output .= 'csk.i18n = csk.i18n || {};';
		$output .= ' csk.i18n.media = '.json_encode($lines).';';
		$output .= ' csk.media = '.json_encode($media).';';
		$output .= ' csk.nonce = '.json_encode($nonce).';';
		$output .= '</script>';
		
		return $output;
	}

	// ------------------------------------------------------------------------

	/**
	 * _subhead
	 *
	 * Add some needed links to dashboard subhead section.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	https://goo.gl/wGXHO9
	 * @since 	2.0.0
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	protected function _subhead()
	{
		$this->data['page_icon'] = 'picture-o';
		$this->data['page_title'] = line('CSK_MEDIA_LIBRARY');

		add_action('admin_subhead', function() {

			// Upload media button.
			echo html_tag('button', array(
				'type'  => 'button',
				'role'  => 'button',
				'class' => 'ml-3 btn btn-success btn-sm btn-icon',
				'id'    => 'media-add',
			), fa_icon('plus-circle').line('CSK_MEDIA_ADD')),

			// Bulk selection buttons.
			html_tag('button', array(
				'type'     => 'button',
				'role'     => 'button',
				'class'    => 'ml-3 btn btn-default btn-sm media-select-bulk disabled',
				'disabled' => 'disabled',
			), line('CSK_MEDIA_SELECT_BULK')),
			html_tag('button', array(
				'type'     => 'button',
				'role'     => 'button',
				'class'    => 'ml-3 btn btn-default btn-sm media-select-cancel d-none',
			), line('CSK_MEDIA_SELECT_CANCEL')),
			html_tag('button', array(
				'type'     => 'button',
				'role'     => 'button',
				'class'    => 'ml-1 btn btn-danger btn-sm media-select-delete disabled d-none',
				'disabled' => 'disabled',
			), line('CSK_MEDIA_SELECT_DELETE'));
		});
	}

}
