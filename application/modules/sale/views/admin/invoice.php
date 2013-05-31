<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Invoice</title>
    
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
                            <h3>Invoice #<?php echo $sale->getID(); ?></h3>
                            <p>
                                <span>HST #</span> <?php echo $hst; ?><br>
                                <span>Tax</span> <?php echo $tax * 100; ?>%
                            </p>
                        </div>
                    </div>
                    <div class="pl-heading-row">
                        <div class="pl-elment third">
                            <h5>Detail</h5>
                            <p>
                                <span>Order Date:</span> <?php echo $sale->getCreatedAt(); ?><br>
                                <span>Ship Date:</span> <?php echo $sale->getShipDate(); ?><br><br>
                                <span>Salesperson:</span> <?php echo $sale->getUser()->getUsername(); ?><br>
                                <span>Type:</span> <?php echo get_full_name($sale->getType()) ?><br>
                                <span>Payment:</span> <?php echo ucfirst($sale->getPayment()); ?><br>
                            </p>
                        </div>
                        
                        <div class="pl-elment third">
                            <h5>Bill To</h5>
                            <p>
                                <?php echo $sale->getCustomer()->getName(); ?><br>
                                <?php echo $sale->getCustomer()->getBillingAddress(); ?><br>
                                <?php echo $sale->getCustomer()->getBillingCity(); ?>
                                <?php echo $sale->getCustomer()->getBillingProvinceAbbr(); ?><br>
                                <?php echo $sale->getCustomer()->getBillingPostal(); ?><br>
                                <?php echo $sale->getCustomer()->getPhone(); ?>
                            </p>
                        </div>
                        
                        <div class="pl-elment third">
                            <h5>Ship To</h5>
                            <p>
                                <?php echo $sale->getCustomer()->getName(); ?><br>
                                <?php echo $sale->getCustomer()->getShippingAddress(); ?><br>
                                <?php echo $sale->getCustomer()->getShippingCity(); ?>
                                <?php echo $sale->getCustomer()->getShippingProvinceAbbr(); ?><br>
                                <?php echo $sale->getCustomer()->getShippingPostal(); ?>
                            </p>
                        </div>
                    </div>
                    
                    <table id="picklist">
                        <thead>
                            <tr>
                                <th class="medium">Barcode</tthd>
                                <th>Name</th>
                                <th class="small">Category</th>
                                <th class="xsmall">Unit</th>
                                <th class="small">Unit Price</th>
                                <th class="small">Discount</th>
                                <th class="xsmall">Qty</th>
                                <th class="small">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($sale->getItems() as $item) { ?>
                            <tr">
                                <td><?php echo ($item->getProduct()->getBarcode()) ? $item->getProduct()->getBarcode() : '-'; ?></td>
                                <td><?php echo $item->getProduct()->getName(); ?></td>
                                <td><?php echo $item->getProduct()->getCategory()->getName(); ?></td>
                                <td><?php echo $item->getProduct()->getUnit(); ?></td>
                                <td>$<?php echo $item->getSalePrice(); ?></td>
                                <td><?php echo ($item->getDiscount() != '0.00') ? '-$' . $item->getDiscount() : '-'; ?></td>
                                <td><?php echo $item->getPicked(); ?></td>
                                <td>$<?php echo $item->getSaleAmount(); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7" class="text-rgt"><strong>Sub Total</strong></td>
                                <td>$<?php echo $summary['sub_total'] ?></td>
                            </tr>
                            <tr>
                                <td colspan="7" class="text-rgt"><strong>Tax</strong></td>
                                <td>$<?php echo $summary['tax'] ?></td>
                            </tr>
                            <tr>
                                <td colspan="7" class="text-rgt"><strong>Total</strong></td>
                                <td>$<?php echo $summary['total'] ?></td>
                            </tr>
                            <!--<tr>
                                <td colspan="7" class="text-rgt"><strong>Total Due</strong></td>
                                <td>$<?php echo $summary['total_due'] ?></td>
                            </tr>-->
                            <tr>
                                <td colspan="7" class="text-rgt"><strong>Total Discount</strong></td>
                                <td>($<?php echo $summary['discount'] ?>)</td>
                            </tr>
                        </tfoot>
                    </table>
                    <h3 class="text-center">Thank You</h3>
                </div>
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