// Tag
(function($) {
    // Tag Adding
    $('.add-tag').fancybox({ maxWidth: 495, maxHeight: 395 });
    // Product History
    $('.view-history').fancybox({ minWidth: 1030, maxHeight: 595 });
    
    // Tag autoSuggest
    var $tags = $("#tags"),
        _prefill     = $tags.data('prefill') || '', // get prefill data
        _neverSubmit = $tags.data('never-submit') || true,
        _url = $tags.data('url'),
        _settings    = {
            asHtmlID:    'tags',
            preFill:     '',
            neverSubmit: true
        };
    
    $.extend(_settings, {preFill: _prefill, neverSubmit: _neverSubmit});
    
    $tags.autoSuggest(_url, _settings);
}(jQuery));

// Form
(function($) {
    var siteForm = {
        config: {
            addRowBtn:    '.btn-add',
            removeRowBtn: '.btn-remove',
            checkAllBtn:  '.check-all',
            
            prodTemplate: '#product-search-template',
            prodSearchForm: '#product-ajax-search',
            prodSearchBtn: '#product-ajax-search-btn',
            prodTable: '#search-products'
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
            
            $('body').on('click', config.removeRowBtn, function(e) {
                $(this).closest('tr').remove();
                 e.preventDefault();
            });
            
            $(config.checkAllBtn).on('click', function() {
                $(this).parents('ul').find(':checkbox').attr('checked', this.checked);
            });
            
            $(config.prodSearchBtn).on('click', function(e) {
                var result = self.prodSearch.call(this);
                
                result.done(function(data) {
                    if (data) {
                        self.renderProdRows(data);
                    }
                });
                
                e.preventDefault();
            });
        },
        
        renderProdRows: function(prods) {
            var config = this.config,
                source   = $(config.prodTemplate).html(),
                template = Handlebars.compile(source),
                _currentIds = this.getCurrentProdIDs();
                _products = [];            
            
            $.each(prods, function() {                
                if ($.inArray(this.id, _currentIds) < 0) {
                    _products.push(this);
                }
            });
            
            $(config.prodTable + ' > tbody').find('tr').removeClass('highlight').end().prepend(template({ products: _products }));
        },
        
        getCurrentProdIDs: function() {
            var config = this.config,
                ids = [];
            
            $(config.prodTable).find('tr').each(function() {
                var _id = $(this).data('id');
                
                if (_id) {
                    ids.push(_id);
                }
            });
            
            return ids;
        },
        
        prodSearch: function() {
            var $this = $(this);
            
            return $.ajax({
                type:     siteForm.METHODS.POST,
                url:      $this.data('url'),
                data:     $(siteForm.config.prodSearchForm + ' :input').serialize(),
                dataType: 'json'
            });
        }
    }
    
    siteForm.METHODS = {
        POST:   'post',
        GET:    'get',
        DELETE: 'delete'
    }
    
    siteForm.init();
})(jQuery);

