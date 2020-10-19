/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */
CKEDITOR.editorConfig = function(config) {
    // Define changes to default configuration here. For example:
    // config.language = 'fr';
    // config.uiColor = '#AADC6E';
    // Remove some buttons, provided by the standard plugins, which we don't
    // need to have in the Standard(s) toolbar.
    config.removeButtons = 'Underline,Subscript,Superscript';
    config.Element = 'div'
    // Se the most common block elements.
    config.format_tags = 'p;h1;h2;h3;pre;div';
    config.extraAllowedContent = '*(*);*{*}';


    // Make dialogs simpler.
    config.removeDialogTabs = 'link:advanced';
    config.allowedContent = true;
    config.enterMode = CKEDITOR.ENTER_BR;
};
//
//CKEDITOR.editorConfig = function( config ) {
//	// Define changes to default configuration here.
//	// For the complete reference:
//	// http://docs.ckeditor.com/#!/api/CKEDITOR.config
//
//	// The toolbar groups arrangement, optimized for two toolbar rows.
//	config.toolbarGroups = [
//		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
//		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
//		{ name: 'links' },
//		{ name: 'insert' },
//		{ name: 'forms' },
//		{ name: 'tools' },
//		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
//		{ name: 'others' },
//		'/',
//		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
//		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks',  'bidi' ] },
//		{ name: 'styles' },
//		{ name: 'colors' },
//		{ name: 'about' }
//	];
//
//	// Remove some buttons, provided by the standard plugins, which we don't
//	// need to have in the Standard(s) toolbar.
//	config.removeButtons = 'Underline,Subscript,Superscript';
//
//	// Se the most common block elements.
//	config.format_tags = 'p;h1;h2;h3;pre;div';
//
//	// Make dialogs simpler.
//	config.removeDialogTabs = 'link:advanced';
//        config.allowedContent = true;
//};
