<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Credit Claim</title>
    
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
                            <h3>Invoice #<?php echo $purchase->getID(); ?></h3>
                            <p></p>
                        </div>
                    </div>
                    <div class="pl-heading-row">
                        <div class="pl-elment third">
                            <h5>Detail</h5>
                            <p>
                                <span>Order Date:</span> <?php echo $purchase->getCreatedAt(); ?><br>
                            </p>
                        </div>
                        
                        <div class="pl-elment third">
                            <h5>Bill To</h5>
                            <?php if ($purchase->getVendor()) { ?>
                            <p>
                                <?php echo $purchase->getVendor()->getName(); ?><br>
                                <?php echo $purchase->getVendor()->getPhone(); ?>
                                <?php echo $purchase->getVendor()->getBillingAddress(); ?><br>
                                <?php echo $purchase->getVendor()->getBillingCity(); ?>
                                <?php echo $purchase->getVendor()->getBillingProvinceAbbr(); ?><br>
                                <?php echo $purchase->getVendor()->getBillingPostal(); ?><br>
                            </p>
                            <?php } ?>
                        </div>
                        
                        <div class="pl-elment third">
                            <h5>Ship To</h5>
                            <?php if ($purchase->getVendor()) { ?>
                            <p>
                                <?php echo $purchase->getVendor()->getName(); ?><br>
                                <?php echo $purchase->getVendor()->getShippingAddress(); ?><br>
                                <?php echo $purchase->getVendor()->getShippingCity(); ?>
                                <?php echo $purchase->getVendor()->getShippingProvinceAbbr(); ?><br>
                                <?php echo $purchase->getVendor()->getShippingPostal(); ?>
                            </p>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <table id="picklist">
                        <thead>
                            <tr>
                                <th class="medium">Barcode</tthd>
                                <th>Name</th>
                                <th class="small">Category</th>
                                <th class="xsmall">Unit</th>
                                <th class="small">Cost</th>
                                <th class="xsmall">Ordered</th>
                                <th class="xsmall">Received</th>
                                <th class="small">Credits</th>
                                <th class="medium">Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($items)) { ?>
                                <?php foreach($items as $item) { ?>
                                <?php $_credits = ($item->getQty() - $item->getReceived()) * $item->getCost(); ?>
                                <tr>
                                    <td><?php echo ($item->getProduct()->getBarcode()) ? $item->getProduct()->getBarcode() : '-'; ?></td>
                                    <td><?php echo $item->getProduct()->getName(); ?></td>
                                    <td><?php echo $item->getProduct()->getCategory()->getName(); ?></td>
                                    <td><?php echo $item->getProduct()->getUnit(); ?></td>
                                    <td>$<?php echo $item->getCost(); ?></td>
                                    <td><?php echo $item->getQty(); ?></td>
                                    <td><?php echo $item->getReceived(); ?></td>
                                    <td>$<?php echo $_credits; ?></td>
                                    <td><input type="text" class="xsmall"></td>
                                </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="9">No Record</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
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