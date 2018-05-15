<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Media module - Admin: list media.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Modules\Views
 * @author 		Kader Bouyakoub <bkader@mail.com>
 * @link 		https://goo.gl/wGXHO9
 * @copyright 	Copyright (c) 2018, Kader Bouyakoub (https://goo.gl/wGXHO9)
 * @since 		Version 1.0.0
 * @version 	1.4.0
 */
// Upload form.
echo form_open_multipart('ajax/media/upload', 'id="media-dropzone"');
?>
<div tabindex="-1" class="row attachments clearfix">
<?php if ($media): ?>
<?php foreach ($media as $m): ?>
	<div class="col-6 col-sm-4 col-md-3 col-lg-2 attachment" id="media-<?php echo $m->id; ?>" data-id="<?php echo $m->id; ?>" data-nonce="<?php echo $m->delete_nonce; ?>" tabindex="-1">
		<div class="attachment-inner">
		<?php
		echo html_tag('a', array(
			'href' => admin_url('content/media?item='.$m->id),
			'class' => 'media-view',
		), html_tag('img', array(
			'alt'      => $m->name,
			'src'      => blank_media_src(),
			'data-src' => $m->thumbnail,
			'class'    => 'attachment-thumbnail',
		)));
		?>
		</div>
	</div>
<?php endforeach; ?>
<?php else: ?>
<p class="dz-message"><?php _e('CSK_MEDIA_DROP'); ?></p>
<?php endif; ?>
</div>
<?php
echo form_close();
echo $pagination;
?>

<div id="media-modal-container">
	<?php if ($item !== null): ?>
	<div class="modal fade" role="dialog" id="media-modal" tabindex="-1">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header clearfix">
					<h3 class="modal-title"><?php _e('CSK_MEDIA_DETAILS'); ?></h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12 col-md-7"><div class="attachment-preview">
						<?php
						echo html_tag('img', array(
							'src'         => $item->file_url,
							'alt'         => $item->name,
							'data-action' => 'zoom',
						));
						?>
						</div></div>
						<div class="col-sm-12 col-md-5">
							<strong><?php _e('CSK_MEDIA_FILE_NAME'); ?></strong>: <span class="txof"><?php echo $item->details['file_name']; ?></span><br />
							<strong><?php _e('CSK_MEDIA_FILE_TYPE'); ?></strong>: <span class="txof"><?php echo $item->details['file_mime']; ?></span><br />
							<strong><?php _e('CSK_MEDIA_CREATED_AT'); ?></strong>: <span class="txof"><?php echo $item->created_at; ?></span><br />
							<strong><?php _e('CSK_MEDIA_FILE_SIZE'); ?></strong>: <span class="txof"><?php echo $item->file_size; ?></span><br />
							<strong><?php _e('CSK_MEDIA_DIMENSIONS'); ?></strong>: <span class="txof"><?php echo $item->details['width']; ?> x <?php echo $item->details['height']; ?></span>
							<hr />
							<?php 
							echo form_open('ajax/media/update/'.$item->id, 'role="form" class="media-update" data-id="'.$item->id.'"');
							echo form_nonce('media-update_'.$item->id);
							?>
								<div class="form-group">
									<?php
									echo form_label(line('CSK_MEDIA_URL'), 'url'),
									form_input('url', site_url('media/'.$item->username), array(
										'id'       => 'url',
										'class'    => 'form-control-plaintext',
										'rel'      => 'tooltip',
										'title'    => line('CSK_MEDIA_CLIPBOARD'),
										'readonly' => 'readonly',
										'onclick' => 'window.prompt(\''.line('CSK_MEDIA_CLIPBOARD').'\', \''.$item->file_url.'\')',
									));
									?>
								</div>
								<div class="form-group">
									<label for="title"><?php _e('CSK_MEDIA_TITLE'); ?></label>
									<input class="form-control" type="text" name="name" id="name" value="<?php echo $item->name; ?>" placeholder="<?php _e('CSK_MEDIA_TITLE'); ?>">
								</div>
								<div class="form-group">
									<label for="description"><?php _e('CSK_MEDIA_DESCRIPTION'); ?></label>
									<textarea class="form-control" type="text" name="description" id="description" placeholder="<?php _e('CSK_MEDIA_DESCRIPTION'); ?>"><?php echo $item->description; ?></textarea>
								</div>
								<?php

								// Submit button.
								echo submit_button(null, 'primary small icon:paper-plane nowrap');

								// Delete button.
								echo html_tag('button', array(
									'type'          => 'button',
									'data-endpoint' => nonce_ajax_url("media/delete/{$item->id}", "media-delete_{$item->id}"),
									'class'         => 'btn btn-danger btn-sm btn-icon media-delete pull-right',
									'data-id'       => $item->id,
									'tabindex'      => '-1',
								), fa_icon('trash-o').line('CSK_BTN_DELETE'));

								// Form closing tag.
								echo form_close();

								?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>

<script type="text/x-handlebars-template" id="attachment-template">
<div class="col-6 col-sm-4 col-md-3 col-lg-2 attachment" id="media-{{id}}" data-id="{{id}}" data-nonce="{{delete_nonce}}" tabindex="-1">
	<div class="attachment-inner">
		<a class="media-view" href="<?php echo admin_url('media?item={{id}}'); ?>" data-endpoint="<?php echo ajax_url('media/details/{{id}}'); ?>"><img src="{{{thumbnail}}}" alt="{{name}}"></a>
	</div>
</div>
</script>

<script type="text/x-handlebars-template" id="media-modal-template">
<div class="modal fade" role="dialog" id="media-modal" tabindex="-1">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header clearfix">
				<h3 class="modal-title"><?php _e('CSK_MEDIA_DETAILS'); ?></h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12 col-md-7"><div class="attachment-preview">
					<?php
					echo html_tag('img', array(
						'src'         => '{{content}}',
						'alt'         => '{{name}}',
						'data-action' => 'zoom',
					));
					?>
					</div>
					</div>
					<div class="col-sm-12 col-md-5">
						<strong><?php _e('CSK_MEDIA_FILE_NAME'); ?></strong>: <span class="txof">{{details.file_name}}</span><br />
						<strong><?php _e('CSK_MEDIA_FILE_TYPE'); ?></strong>: <span class="txof">{{details.file_mime}}</span><br />
						<strong><?php _e('CSK_MEDIA_CREATED_AT'); ?></strong>: <span class="txof">{{created_at}}</span><br />
						<strong><?php _e('CSK_MEDIA_FILE_SIZE'); ?></strong>: <span class="txof">{{file_size}}</span><br />
						<strong><?php _e('CSK_MEDIA_DIMENSIONS'); ?></strong>: <span class="txof">{{details.width}} x {{details.height}}</span>
						<hr />
						<?php echo form_open('ajax/media/update/{{id}}', 'role="form" class="media-update" data-id="{{id}}"'); ?>
						{{{form_nonce}}}
							<div class="form-group">
								<label><?php _e('CSK_MEDIA_URL'); ?></label>
								<p class="well well-sm txof" rel="tooltip" title="<?php _e('CSK_MEDIA_CLIPBOARD'); ?>" onclick="window.prompt('<?php _e('CSK_MEDIA_CLIPBOARD'); ?>', '{{{content}}}');"><?php echo site_url('media/{{username}}'); ?></p>
							</div>
							<div class="form-group">
								<label for="title"><?php _e('CSK_MEDIA_TITLE'); ?></label>
								<input class="form-control" type="text" name="name" id="name" value="{{name}}" placeholder="<?php _e('CSK_MEDIA_TITLE'); ?>">
							</div>
							<div class="form-group">
								<label for="description"><?php _e('CSK_MEDIA_DESCRIPTION'); ?></label>
								<textarea class="form-control" type="text" name="description" id="description" placeholder="<?php _e('CSK_MEDIA_DESCRIPTION'); ?>">{{{description}}}</textarea>
							</div>
							<?php echo submit_button(null, 'primary small icon:paper-plane nowrap'); ?>
							{{{delete_btn}}}
							<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</script>
