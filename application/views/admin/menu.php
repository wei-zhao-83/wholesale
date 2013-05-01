<nav id="nav">
    <ul id="sidebar-menu">
        <li>
        	<a class="<?php if($this->router->fetch_module() == 'user' || $this->router->fetch_module() == 'role' || $this->router->fetch_module() == 'permission') { ?>active<?php } ?>" href="<?php echo base_url('admin/user'); ?>">
                <span class="icon-user"></span>
                Account
            </a>
            <ul>
            	<li><a class="<?php if($this->router->fetch_module() == 'user') { ?>active<?php } ?>" href="<?php echo base_url('admin/user'); ?>">User</a></li>
            	<li><a class="<?php if($this->router->fetch_module() == 'role') { ?>active<?php } ?>" href="<?php echo base_url('admin/role'); ?>">Role</a></li>
                <li><a class="<?php if($this->router->fetch_module() == 'permission') { ?>active<?php } ?>" href="<?php echo base_url('admin/permission'); ?>">Permission</a></li>
            </ul>
        </li>
        <li>
            <a class="<?php if($this->router->fetch_module() == 'vendor' || $this->router->fetch_module() == 'customer') { ?>active<?php } ?>" href="<?php echo base_url('admin/vendor'); ?>"><span class="icon-vendor"></span>Dealer</a>
            <ul>
                <li><a class="<?php if($this->router->fetch_module() == 'vendor') { ?>active<?php } ?>" href="<?php echo base_url('admin/vendor'); ?>">Vendor</a></li>
                <li><a class="<?php if($this->router->fetch_module() == 'customer') { ?>active<?php } ?>" href="<?php echo base_url('admin/customer'); ?>">Customer</a></li>
            </ul>
        </li>
        <li><a class="<?php if($this->router->fetch_module() == 'product') { ?>active<?php } ?>" href="<?php echo base_url('admin/product'); ?>"><span class="icon-product"></span>Product</a></li>
        <li><a class="<?php if($this->router->fetch_module() == 'category') { ?>active<?php } ?>" href="<?php echo base_url('admin/category'); ?>"><span class="icon-category"></span>Category</a></li>
        <li>
            <a class="<?php if($this->router->fetch_module() == 'sale' || $this->router->fetch_module() == 'purchase' || $this->router->fetch_module() == 'quote') { ?>active<?php } ?>" href="<?php echo base_url('admin/sale'); ?>"><span class="icon-transaction"></span>Transaction</a>
            <ul>
                <li><a  class="<?php if($this->router->fetch_module() == 'sale') { ?>active<?php } ?>" href="<?php echo base_url('admin/sale'); ?>">Sale</a></li>
				<li><a  class="<?php if($this->router->fetch_module() == 'purchase') { ?>active<?php } ?>" href="<?php echo base_url('admin/purchase'); ?>">Purchase</a></li>
				<li><a  class="<?php if($this->router->fetch_module() == 'quote') { ?>active<?php } ?>" href="<?php echo base_url('admin/quote'); ?>">Quote</a></li>
                <li><a  class="<?php if($this->router->fetch_module() == 'returns') { ?>active<?php } ?>" href="<?php echo base_url('admin/returns'); ?>">Return</a></li>
                <li><a class="<?php if($this->router->fetch_module() == 'transaction_status') { ?>active<?php } ?>" href="<?php echo base_url('admin/transaction_status'); ?>">status</a></li>
            </ul>
        </li>
        <li><a class="<?php if($this->router->fetch_module() == 'tag') { ?>active<?php } ?>" href="<?php echo base_url('admin/tag'); ?>"><span class="icon-tag"></span>Tag</a></li>
        <li>
            <a class="<?php if($this->router->fetch_module() == 'module') { ?>active<?php } ?>" href="<?php echo base_url('admin/module'); ?>"><span class="icon-module"></span>Module</a>
        <li>
            <a class="<?php if($this->router->fetch_module() == 'setting') { ?>active<?php } ?>" href="#"><span class="icon-maintenance"></span>Maintenance</a>
            <ul>
                <li><a class="<?php if($this->router->fetch_module() == 'setting') { ?>active<?php } ?>" href="<?php echo base_url('admin/setting'); ?>">setting</a></li>
            </ul>
        </li>
    </ul>
</nav>