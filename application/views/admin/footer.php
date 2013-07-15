            </div>
            <br clear="both">
        </div><!-- content wrapper end -->    
        
        <footer id="footer"></footer>
        
        <script src="<?php echo site_url('assets/js/jquery-1.9.1.js') ?>" type="text/javascript"></script>
        <script src="<?php echo site_url('assets/js/jquery-ui-1.10.3.custom.min.js') ?>" type="text/javascript"></script>
        <script src="<?php echo site_url('assets/js/handlebars.js') ?>" type="text/javascript" charset="utf-8"></script>
        <script src="<?php echo site_url('assets/js/jquery.autoSuggest.js') ?>" type="text/javascript" charset="utf-8"></script>
        <script src="<?php echo site_url('assets/js/jquery.fancybox.js') ?>" type="text/javascript" charset="utf-8"></script>
        <script src="<?php echo site_url('assets/js/flot-master/jquery.flot.js') ?>" type="text/javascript"></script>
        <script src="<?php echo site_url('assets/js/flot-master/jquery.flot.time.js') ?>" type="text/javascript"></script>
        <script src="<?php echo site_url('assets/js/stupidtable.min.js') ?>" type="text/javascript"></script>
        <!--<script src="<?php echo site_url('assets/js/flot-master/jquery.flot.selection.js') ?>" type="text/javascript"></script>-->
        <script src="<?php echo site_url('assets/js/site.js') ?>" type="text/javascript"></script>
        
        <script id="product-search-template" type="text/x-handlebars-template">
            {{#products}}
            <tr class="highlight" data-id="{{id}}" data-current-price="{{price}}" data-cash-and-carry="{{cash_and_carry}}" data-full-service="{{full_service}}" data-standard-service="{{standard_service}}">
                <td><a target="_blank" href="/admin/product/edit/{{id}}">{{name}}</a></td>
                <td>{{barcode}}</td>
                <td>{{category}}</td>
                <td><input type="text" autocomplete="off" name="products[{{id}}][qty]" value="0" class="xxxsmall field-qty"></td>
                <td>{{qty}}</td>
                <td class="field-price">{{price}}</td>
                <td>
                    {{#if discount }}
                    <input type="text" name="products[{{id}}][discount]" autocomplete="off" value="{{discount}}" class="xxxsmall field-discount">
                    {{/if}}
                </td>
                <td><input type="text" name="products[{{id}}][comment]" value="" class="small"></td>
                <td><a href="#" class="btn-remove show-inline"></a></td>
            </tr>
            {{/products}}
        </script>
        
        <script id="purchase-search-template" type="text/x-handlebars-template">
            {{#products}}
            <tr class="highlight" data-id="{{id}}">
                <td><a target="_blank" href="/admin/product/edit/{{id}}">{{name}}</a></td>
                <td>{{barcode}}</td>
                <td>{{category}}</td>
                <td><input type="text" autocomplete="off" name="products[{{id}}][qty]" value="1" class="xxsmall field-qty"></td>
                <td>{{qty}}[{{unit}}]</td>
                <td class="field-price">{{price}}</td>
                <td><input type="text" name="products[{{id}}][comment]" value="" class="small"></td>
                <td><a href="#" class="btn-remove show-inline"></a></td>
            </tr>
            {{/products}}
        </script>
    </body>
</html>