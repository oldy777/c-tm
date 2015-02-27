/*
Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
    config.scayt_autoStartup = false;
  config.startupFocus = false;
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
        config.filebrowserBrowseUrl = '/editor/plugins/kcfinder/browse.php?type=files';
   config.filebrowserImageBrowseUrl = '/editor/plugins/kcfinder/browse.php?type=images';
   config.filebrowserFlashBrowseUrl = '/editor/plugins/kcfinder/browse.php?type=flash';
   config.filebrowserUploadUrl = '/editor/plugins/kcfinder/upload.php?type=files';
   config.filebrowserImageUploadUrl = '/editor/plugins/kcfinder/upload.php?type=images';
   config.filebrowserFlashUploadUrl = '/editor/plugins/kcfinder/upload.php?type=flash';
   
};
