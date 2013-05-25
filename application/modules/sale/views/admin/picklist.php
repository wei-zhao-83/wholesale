<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Pick List</title>
    
    <link rel="stylesheet" href="<?php echo site_url('assets/css/reset.css') ?>">
    <link rel="stylesheet" href="<?php echo site_url('assets/css/text.css') ?>">
    <link rel="stylesheet" href="<?php echo site_url('assets/css/site.css') ?>">
</head>

<body>
    <div id="content-wrapper">
        <div class="container">
            <section>
                <div id="white-bg-container">
                    <div class="pl-heading-row">
                        <div class="pl-elment third">
                            <p>
                                <strong><?php echo $company['name']; ?></strong><br>
                                <?php echo $company['phone']; ?><br>
                                <?php echo $company['address']; ?><br>
                                <?php echo $company['city']; ?> <?php echo $company['prov']; ?><br>
                                <?php echo $company['postal']; ?>
                            </p>
                        </div>
                        <div class="pl-elment third">
                            &nbsp;
                        </div>
                        <div class="pl-elment third">
                            <h3>Order #<?php echo $sale->getID(); ?></h3>
                            <p>
                                <?php echo $sale->getComment(); ?>
                            </p>
                        </div>
                    </div>
                    <div class="pl-heading-row">
                        <div class="pl-elment third">
                            <h5>Detail</h5>
                            <p>
                                <span>Order Date:</span> <?php echo $sale->getCreatedAt(); ?><br>
                                <span>Salesperson:</span> <?php echo $sale->getUser()->getUsername(); ?><br>
                                <span>Status:</span> <?php echo $sale->getStatus()->getName(); ?><br>
                                <span>Type:</span> <?php echo get_full_name($sale->getType()) ?><br>
                            </p>
                        </div>
                        
                        <div class="pl-elment third">
                            <h5>Customer</h5>
                            <p>
                                <?php echo $sale->getCustomer()->getName(); ?><br>
                                <?php echo $sale->getCustomer()->getPhone(); ?><br>
                                <?php echo $sale->getCustomer()->getEmail(); ?><br>
                                <?php echo $sale->getCustomer()->getFax(); ?>
                            </p>
                        </div>
                        
                        <div class="pl-elment third">
                            <h5>Ship To</h5>
                            <p>
                                <?php echo $sale->getCustomer()->getShippingAddress(); ?><br>
                                <?php echo $sale->getCustomer()->getShippingCity(); ?>
                                <?php echo $sale->getCustomer()->getShippingProvinceAbbr(); ?><br>
                                <?php echo $sale->getCustomer()->getShippingPostal(); ?><br>
                            </p>
                            <p>
                                <span>Ship Date:</span> <?php echo $sale->getShipDate(); ?><br>
                            </p>
                        </div>
                    </div>
                    
                    <form action="<?php echo site_url('admin/sale/picklist/' . $sale->getID()); ?>" method="post">
                    <fieldset>
                        <table id="picklist">
                            <thead>
                                <tr>
                                    <th class="medium">Barcode</tthd>
                                    <th>Name</th>
                                    <th class="xsmall">Location</th>
                                    <th class="small">Category</th>
                                    <th class="xxsmall">Unit</th>
                                    <th class="xsmall">BOH</th>
                                    <th class="xxsmall">Ordered</th>
                                    <th class="xsmall">Picked*</th>
                                    <th class="xsmall">Shipped*</th>
                                    <th class="small">Comment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter_qty = 0; ?>
                                <?php foreach($sale->getItems() as $item) { ?>
                                <?php $counter_qty += $item->getQty(); ?>
                                <tr data-qty="<?php echo $item->getQty(); ?>">
                                    <td><?php echo ($item->getProduct()->getBarcode()) ? $item->getProduct()->getBarcode() : '-'; ?></td>
                                    <td><?php echo $item->getProduct()->getName(); ?></td>
                                    <td><?php echo $item->getProduct()->getSection(); ?></td>
                                    <td><?php echo $item->getProduct()->getCategory()->getName(); ?></td>
                                    <td><?php echo $item->getProduct()->getUnit(); ?></td>
                                    <td><?php echo $item->getProduct()->getTotalQty() * $item->getProduct()->getQtyUnit(); ?></td>
                                    <td><?php echo $item->getQty(); ?></td>
                                    <td><input name="picked[<?php echo $item->getID(); ?>]" value="<?php echo $item->getPicked(); ?>" class="xxsmall picklist-field"></td>
                                    <td><input name="shipped[<?php echo $item->getID(); ?>]" value="<?php echo $item->getShipped(); ?>" class="xxsmall picklist-field"></td>
                                    <td><?php echo ($item->getComment()) ? $item->getComment() : '-'; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        
                        <p class="pick-list-summary quarter">
                            <span>Total Quantity to Pick:</span> <?php echo $counter_qty; ?><br>
                            <span>Total Items on list:</span> <?php echo $sale->getItems()->count(); ?>
                        </p>
                        
                        
                        </fieldset>
                        <div class="btn-box">
                            <ul>
                                <li><?php echo form_submit('picklist-update', '', 'class=\'btn-create\''); ?></li>
                            </ul>
                        </div>
                    </form>
                </div>
                <p></p>
                <p class="note">* "Double Click" the input field to copy the ordered qty</p>
            </section>
        </div>
    </div>
    
    <script src="<?php echo site_url('assets/js/jquery-1.9.1.js') ?>" type="text/javascript"></script>
    <script src="<?php echo site_url('assets/js/handlebars.js') ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo site_url('assets/js/jquery.autoSuggest.js') ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo site_url('assets/js/jquery.fancybox.js') ?>" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo site_url('assets/js/site.js') ?>" type="text/javascript"></script>
</body>
</html>