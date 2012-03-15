/*
* CKEditor OnlineVideo Plugin - Plugin
* Publisher Host (c) Creative Commons 2008
* http://creativecommons.org/licenses/by-sa/3.0/
* Author: Michael James | http://www.mjjames.co.uk
* Modified by Bolzamo | http://bolzamo.ru/ (added russian language, rutube.ru video support)
* bolzamo`s build published at http://bolzamo.org.ru/download/CK_plugin_onlinevideo.zip
*/

// Register the related commands.
CKCommands.RegisterCommand('OnlineVideo', new CKDialogCommand(CKLang['DlgOnlineVideoTitle'], CKLang['DlgOnlineVideoTitle'], CKConfig.PluginsPath + 'OnlineVideo/OnlineVideo.html', 450, 350));

// Create the "OnlineVideo" toolbar button.
var oFindItem		= new CKToolbarButton( 'OnlineVideo', CKLang['OnlineVideoTip'] ) ;
oFindItem.IconPath	= CKConfig.PluginsPath + 'OnlineVideo/OnlineVideo.gif' ;

CKToolbarItems.RegisterItem( 'OnlineVideo', oFindItem ) ;			// 'OnlineVideo' is the name used in the Toolbar config.
