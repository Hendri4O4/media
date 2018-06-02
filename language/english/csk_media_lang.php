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

$lang['CSK_MEDIA'] = 'Media';
$lang['CSK_MEDIA_LIBRARY'] = 'Library';

// Media page heading.
$lang['CSK_MEDIA_LIBRARY'] = 'Media Library';

// Drop zone tip.
$lang['CSK_MEDIA_DROP'] = 'Drop files here to upload.';

// Media details.
$lang['CSK_MEDIA_DETAILS']     = 'Attachment Details';
$lang['CSK_MEDIA_TITLE']       = 'Title';
$lang['CSK_MEDIA_DESCRIPTION'] = 'Description';
$lang['CSK_MEDIA_URL']         = 'URL';
$lang['CSK_MEDIA_DIMENSIONS']  = 'Dimensions';
$lang['CSK_MEDIA_FILE_NAME']   = 'File Name';
$lang['CSK_MEDIA_FILE_SIZE']   = 'File Size';
$lang['CSK_MEDIA_FILE_TYPE']   = 'File Type';
$lang['CSK_MEDIA_CREATED_AT']  = 'Uploaded On';

// Copy media link to clipboard.
$lang['CSK_MEDIA_CLIPBOARD'] = 'Copy to clipboard: Ctrl+C';

// Confirmation messages.
$lang['CSK_MEDIA_CONFIRM_DELETE']      = 'You are about to permanently delete this item from your site.<br />This action cannot be undone.<br />"Cancel" to stop, "OK" to delete.';
$lang['CSK_MEDIA_CONFIRM_DELETE_BULK'] = 'You are about to permanently delete these items from your site.<br />This action cannot be undone.<br />"Cancel" to stop, "OK" to delete.';

// Success messages.
$lang['CSK_MEDIA_SUCCESS_UPLOAD'] = 'Media successfully uploaded.';
$lang['CSK_MEDIA_SUCCESS_DELETE'] = 'Media item successfully deleted.';
$lang['CSK_MEDIA_SUCCESS_UPDATE'] = 'Media item successfully updated.';

// Error messages.
$lang['CSK_MEDIA_ERROR_UPLOAD']  = 'Unable to upload media.';
$lang['CSK_MEDIA_ERROR_DELETE']  = 'Unable to delete media item.';
$lang['CSK_MEDIA_ERROR_UPDATE']  = 'Unable to update media item.';
$lang['CSK_MEDIA_ERROR_MISSING'] = 'No media file found.';

// Media permissions.
$lang['CSK_MEDIA_ERROR_PERMISSION_DELETE'] = 'Only an admin or the owner of this item can delete it.';
$lang['CSK_MEDIA_ERROR_PERMISSION_UPDATE'] = 'Only an admin or the owner of this item can update it.';

// Add and selection action.
$lang['CSK_MEDIA_ADD']           = 'Add Media';
$lang['CSK_MEDIA_SELECT_BULK']   = 'Bulk Select';
$lang['CSK_MEDIA_SELECT_CANCEL'] = 'Cancel Selection';
$lang['CSK_MEDIA_SELECT_DELETE'] = 'Delete Selected';
