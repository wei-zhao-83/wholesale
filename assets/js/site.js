// Tag
;(function($) {
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

// Sortable
(function($) { $('#search-products, #picklist').stupidtable(); })(jQuery);
// Dateepicker
(function($) {
    if ($.fn.datepicker) {
        $('.datepicker').datepicker({'dateFormat': 'yy-mm-dd'}); }
    }
)(jQuery);

// Dashboard
(function($) {
    var current_view = $('body').data('view');
    
    if (current_view === 'report' || current_view == 'dashboard') {
        var sales_by_date = $("#sales-flowchart").data('sales-by-date') || [];
        var result = [];
        
        $.each(sales_by_date, function(idx, val) {
            var timestamp = parseInt(idx);
            var amount = parseFloat(val);
            
            result.push([timestamp, amount]);
        });
        
        for (var i = 0; i < result.length; ++i) {
			result[i][0] += 60 * 60 * 1000;
		}
        
        var options = {
            xaxis: {
                mode: 'time',
                minTickSize: [1, 'day'],
            },
            yaxis: {
                tickFormatter: function(value, axis) {
					return '$' + value.toFixed(axis.tickDecimals);
				}
            },
            selection: {
                mode: "x"
            },
            points: {
                show: true
            },
            lines: {
                show: true,
                fill: true
            }
        };
        
        $.plot("#sales-flowchart", [result], options);
    }
}(jQuery));

// Form
;(function($) {
    var siteForm = {
        config: {
            addRowBtn:    '.btn-add',
            removeRowBtn: '.btn-remove',
            checkAllBtn:  '.check-all',
            
            // Handlebar templates
            prodTemplate:     '#product-search-template',
            returnTemplate:     '#return-search-template',
            purchaseTemplate: '#purchase-search-template',
            
            // Products ajax search
            prodSearchForm:   '#product-ajax-search',
            prodSearchBtn:    '#product-ajax-search-btn',
            returnSearchBtn:    '#return-ajax-search-btn',
            
            // Products list table
            prodTable:        '#search-products',
            
            //prodSearchTrigger: '#product-search-trigger',
            
            // Sale table
            defaultDiscount: '#default-discount',
            discountField:   '.field-discount',
            saleType:        '#sale-type',
            
            qtyField: '.field-qty',
            
            picklistField: '.picklist-field',
            recievedField: '.recieved-field',
            
            maxQty:  '.max-qty',
            
            notification: '#notification'
        },
        
        init: function(config) {
            $.extend(this.config, config)
            
            this.bindEvents();
        },
        
        bindEvents: function() {
            var self = this,
                config = this.config;
            
            $('th ' + config.addRowBtn).on('click', function(e) {
                var _tbl = $(this).closest('table'),
                    _last_row = _tbl.find('tr').eq(-1),
                    _random = Math.ceil(Math.random()*99999);
                
                _last_row.clone(true).appendTo(_tbl)
                       .find('.btn-remove').css('display', 'inline-block').end()
                       .find('input, select').each(function() {
                            $(this).attr('name', $(this).attr('name').replace(/\[\d+\]/, '[' + _random + ']'));
                        });
                
                e.preventDefault();
            });
            
            $(config.picklistField + ', ' + config.recievedField).on('dblclick', function(e) {
                var qty = $(this).closest('tr').data('qty');
                
                $(this).val(qty);
                
                e.preventDefault();
            });
            
            $('body').on('click', config.removeRowBtn, function(e) {
                $(this).closest('tr').remove();
                e.preventDefault();
            });
            
            // Picklist
            $(config.maxQty).on('click', function(e) {
                var $max = $(this),
                    $table = $max.parents('table'),
                    fieldType = $max.data('field');
                
                $table.find('tr').each(function() {
                    var $tr = $(this),
                        qty = $tr.data('qty');
                        
                    $tr.find('.' + fieldType + '-field').val(qty);
                });
                
                e.preventDefault();
            });
            
            $(config.checkAllBtn).on('click', function() {
                $(this).parents('ul').find(':checkbox').attr('checked', this.checked);
            });
            
            $('body').on('keydown', config.qtyField, function(e) {
                var qty = $(this).val();
                
                if (e.which == 38) { // up
                    qty++;
                } else if (e.which == 40 && qty > 0) { // down
                    qty--;
                }
                
                $(this).val(qty);
                e.preventDefault();
            });
            
            // Search the product
            $(config.prodSearchBtn).on('click', function(e) {
                var result = self.prodSearch.call(this),
                    type = $(this).data('type');
                
                result.done(function(data) {
                    if (data) {
                        self.renderProdRows(data, type);
                    }
                });
                
                e.preventDefault();
            });
            
            $(config.returnSearchBtn).on('click', function(e) {
                var result = self.prodSearch.call(this),
                    type = $(this).data('type');
                
                result.done(function(data) {
                    if (data) {
                        self.renderProdRows(data, type);
                    }
                });
                
                e.preventDefault();
            });
            
            //$(config.prodSearchTrigger).on('change', function(e) {
            //    var $this = $(this),
            //        type = $this.data('type');
            //    
            //    $.ajax({
            //        type:     siteForm.METHODS.POST,
            //        url:      $this.data('url'),
            //        data:     $.param({search: {vendor: $this.val()}}),
            //        dataType: 'json'
            //    }).done(function(data) {
            //        if (data) {
            //            self.renderProdRows(data, type);
            //        }
            //    });
            //    
            //    e.preventDefault();
            //});
            
            $(config.defaultDiscount).on('change', function(e) {
                // Update discount
                self.updateDiscount();
                
                // Show notification
                $(config.notification).html('Discount updated').delay(3000).queue(function() {
                    $(this).empty();
                    $.dequeue(this);
                });
                
                e.preventDefault();
            });
            
            $(config.saleType).on('change', function(e) {
                var selectedType = $(this).val().replace(/_/g, '-');
                
                self.updateCurrentPrice(selectedType);
                
                self.updateDiscount();
                
                // Show notification
                $(config.notification).html('Price and discount updated').delay(3000).queue(function() {
                    $(this).empty();
                    $.dequeue(this);
                 });
                
                e.preventDefault();
            });
        },
        
        updateCurrentPrice: function(type) {
            $(this.config.prodTable).find('tr').each(function() {
                var $prodRow = $(this);
                
                if ($prodRow.data('id') != undefined) {
                    // update the current price
                    $prodRow.find('.field-price').html($prodRow.data(type));
                    // updsate the current price in data
                    $prodRow.data('current-price', $prodRow.data(type));
                }
            });
        },
        
        updateDiscount: function() {
            var self = this,
                discount = this.getDefaultDiscount();
                
            $(this.config.prodTable).find('tr').each(function() {
                var $prodRow = $(this);
                
                if ($prodRow.data('id') != undefined) {
                    $prodRow.data('discount', ($prodRow.data('current-price') * discount).toFixed(2));
                    $prodRow.find(self.config.discountField).val(($prodRow.data('current-price') * discount).toFixed(2));
                }
            });
        },
        
        getDefaultDiscount: function() {
            return $(this.config.defaultDiscount).val() || 0;
        },
        
        getSaleType: function() {
            return $(this.config.saleType).val();
        },
        
        getPriceByType: function(product, type) {
            var self = this;
            
            if (type === 'sale') {
                return product[self.getSaleType()] || 0;
            }
            
            if (type === 'purchase') {
                return product.cost;
            }
        },
        
        renderProdRows: function(prods, type) {
            var self = this,
                config = this.config,
                current_ids = this.getCurrentProdIDs();
                products = [],
                template = this.loadProdTemplate(type);
            
            $.each(prods, function() {
                if ($.inArray(this.id, current_ids) < 0) {
                    // Update the discount and price
                    // if current product discount is true, calculate the discount and reasign to itself.
                    if (!this.no_discount && type === 'sale') {
                        this.discount = (self.getDefaultDiscount() * this.cost).toFixed(2);
                    }
                    // todo set price and discount
                    this.price = self.getPriceByType(this, type);
                    
                    products.push(this);
                }
            });
            
            $(config.prodTable + ' > tbody').find('tr').removeClass('highlight').end().prepend(template({ products: products }));
        },
        
        loadProdTemplate: function(type) {            
            if (type === 'sale') {
                source = $(this.config.prodTemplate).html();
            }
            
            if (type === 'return') {
                source = $(this.config.returnTemplate).html();
            }
            
            if (type === 'purchase') {
                source = $(this.config.purchaseTemplate).html();
            }
            
            return Handlebars.compile(source);
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

