<?
$CKEditor = new CKEditor();
$CKEditor->config['height'] = 200;
$CKEditor->config['toolbar'] = array(
    array('Source', '-', 'Templates'),
    array('Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord'),
    array('Undo', 'Redo', '-', 'Find', 'Replace', '-', 'SelectAll', 'RemoveFormat'),
    array('BidiLtr', 'BidiRtl'),
    array('Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript'),
    array('NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', 'Blockquote', 'CreateDiv'),
    array('JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'),
    array('Link', 'Unlink', 'Anchor'),
    array('Image', 'Flash', 'Youtube', 'Table', 'HorizontalRule', 'SpecialChar'),
    array('Format', 'FontSize'),
    array('TextColor', 'BGColor'),
    array('Maximize', 'ShowBlocks')
);
$CKEditor->editor($args['_field']['name'], $args['item'][$args['_field']['name']]);
?>