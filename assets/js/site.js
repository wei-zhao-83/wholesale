// Tag
(function($) {
    // Tag Adding
    $('.add-tag').fancybox({ maxWidth: 495, maxHeight: 495 });
    // Product History
    $('.view-history').fancybox({ minWidth: 1030, maxHeight: 595 });
    
    // Tag autoSuggest
    var tags = $("#tags"),
        _prefill     = tags.data('prefill') || '', // get prefill data
        _neverSubmit = tags.data('never-submit') || true,
        _settings    = {
            asHtmlID:    'tags',
            preFill:     '',
            neverSubmit: true
        };
    
    $.extend(_settings, {preFill: _prefill, neverSubmit: _neverSubmit});
    
    tags.autoSuggest("/wholesale/admin/tag/ajax_search", _settings);
}(jQuery));

// Form
(function($) {
    var siteForm = {
        config: {
            addRowBtn:    '.btn-add',
            removeRowBtn: '.btn-remove',
            checkAllBtn:  '.check-all'
        },
        
        init: function(config) {
            $.extend(this.config, config)
            
            this.bindEvents();
        },
        
        bindEvents: function() {
            var self = this,
                config = this.config;
            
            $(config.addRowBtn).on('click', function(e) {
                var _tbl = $(this).closest('table'),
                    _last_row = _tbl.find('tr').eq(-1),
                    _random = Math.ceil(Math.random()*99999);
                
                _last_row.clone(true).appendTo(_tbl)
                       .find('.btn-remove').css('display', 'inline-block').end()
                       .find('input').each(function() {
                            $(this).attr('name', $(this).attr('name').replace(/\[\d+\]/, '[' + _random + ']'));
                        });
                
                e.preventDefault();
            });
            
            $(config.removeRowBtn).on('click', function(e) {
                $(this).closest('tr').remove();
                e.preventDefault();
            });
            
            $(config.checkAllBtn).click(function () {
                $(this).parents('ul').find(':checkbox').attr('checked', this.checked);
            });
        }
    }
    
    siteForm.init();
}(jQuery));

