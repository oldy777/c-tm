(function() {
 
    CKEDITOR.plugins.add( 'span',
    {
        init: function( editor )
        {
            var pluginName = 'span';
 
            // регистрируем диалоговое окно
            CKEDITOR.dialog.add( pluginName, this.path + 'dialogs/' + pluginName + '.js' );
 
            // связываем диалоговое окно с командой pluginName
            // команда pluginName отдается при нажатии иконки на тулбаре
            editor.addCommand( pluginName, new CKEDITOR.dialogCommand( pluginName ) );
 
            // добавляем css для иконки в редакторе
            var basicCss =
                'background:url(' + CKEDITOR.getUrl( this.path + 'images/editor_icon.png' ) + ') no-repeat left center;' +
                'border:1px dotted #aaa;';
 
 
            // обрабатываем двойной клик в редакторе
            editor.on( 'doubleclick', function( evt )
            {
            var element = evt.data.element;

            // если <img> с атрибутом как название плагина, то откроем диалоговое окно
            if ( element.is( 'span' )  )
             evt.data.dialog = pluginName;
            } );
 
            // добавляем кнопку на тулбар
            if(editor.ui.addButton)
            {
                editor.ui.addButton( 'span',
                {
                    label: 'Высота',
                    command: pluginName,
                    icon: this.path + 'images/panel_icon.png'
                } );
            }
 
        }
 
    });
 
})();