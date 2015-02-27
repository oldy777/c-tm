(function() {
 
 
    CKEDITOR.dialog.add( 'span', function( editor )
    {
        var _dialog, _insertMode, _element, pluginName = 'span';
 
        return {
            // название диалогового окна
            title : 'Высота',
 
            // минимальная ширина
            minWidth : 400,
 
            // минимальная высота
            minHeight : 200,
 
            // элементы
            contents : [
 
                {
                    // вкладка "tab1"
                    id : 'tab1',
                    label : 'Label',
                    title : 'Выберите высоту',
                    expand : true,
                    padding : 0,
                    elements :
                    [
                        {
                                type : 'select',
                                id : 'span_h',
                                label : 'Высота',
                                width: '100%',
                                items : 
                                [
                                        [ '<none>', '' ],
                                        [ '540', 'h_540' ],
                                        [ '960', 'h_960' ],
                                        [ '1460', 'h_1460' ],
                                        [ '2200', 'h_2200' ]
                                ],
                                commit : function( element  )
                                {
                                        var val = this.getValue()
                                        if(val)
                                            element.setAttribute('class','m_box_s '+this.getValue());
                                        else
                                            element.setAttribute('class','');
                                        
                                }
                        }
                    ]
                }
            ],
 
            // в окне будут 2 кнопки - Ok и Cancel
            buttons : [
                CKEDITOR.dialog.okButton, CKEDITOR.dialog.cancelButton
            ],
 
            // обработчик нажатия на кнопку Ok
            onOk : function() {
 
                var dialog = this,
                    span = _element;
// 
//                // если вставляем элемент первый раз, то добавим его в редактор
                if ( _insertMode )
                    editor.insertElement( span );
 
                // вынимаем контент из заполненной формы в атрибуты <img>
                // для каждого из элемента формы происходит то, что написано в его функции commit
                this.commitContent( span );
            },
 
            // срабатывает при открытии диалогового окна
            onShow : function() {
 
                // получаем элемент, который выбрали
                var sel = editor.getSelection(),
                    element = sel.getStartElement();

                _dialog = this;
 
                // если не <img> или нет атрибута 'myplugin', то создаем новый элемент
                if ( !element || element.getName() != 'span' || !element.getAttribute( pluginName ) )
                {
                    var text = sel.getSelectedText();
                    element = editor.document.createElement( 'span' );
                    element.setText( text);
 
                    // вставляем элемент первый раз
                    _insertMode = true;
                }
                else
                    // редактируем существующий элемент
                    _insertMode = false;
 
                _element = element;
 
                // заносим контент из атрибутов в форму
                this.setupContent( _element );
            }
 
        };
 
    });
})();