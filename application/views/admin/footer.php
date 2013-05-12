            </div>
            <br clear="both">
        </div><!-- content wrapper end -->    
        
        <footer id="footer"></footer>
        
        <script src="<?php echo site_url('assets/js/jquery-1.9.1.js') ?>" type="text/javascript"></script>
        <script src="<?php echo site_url('assets/js/handlebars.js') ?>" type="text/javascript" charset="utf-8"></script>
        <script src="<?php echo site_url('assets/js/jquery.autoSuggest.js') ?>" type="text/javascript" charset="utf-8"></script>
        <script src="<?php echo site_url('assets/js/jquery.fancybox.js') ?>" type="text/javascript" charset="utf-8"></script>
        <script src="<?php echo site_url('assets/js/site.js') ?>" type="text/javascript"></script>
        
        <script id="product-search-template" type="text/x-handlebars-template">
            {{#products}}
            <tr class="highlight" data-id="{{id}}">
                <td>{{name}}</td>
                <td>{{barcode}}</td>
                <td>{{category}}</td>
                <td><input type="text" name="products[{{id}}][qty]" value="1" class="xxsmall item-update-field"></td>
                <td>{{qty}}[{{unit}}]</td>
                <td>{{cost}}</td>
                <td><input type="text" name="products[{{id}}][discount]" value="0.00" class="xxsmall item-update-field"></td>
                <td><input type="text" name="products[{{id}}][comment]" value="" class="small"></td>
                <td><a href="#" class="btn-remove show-inline"></a></td>
            </tr>
            {{/products}}
        </script>
    </body>
</html>