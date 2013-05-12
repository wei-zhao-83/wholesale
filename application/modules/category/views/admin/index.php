<section id="content">
    <div id="content-head">
        <h2>Category</h2>

        <ul>
            <li><a class="action-list active" href="<?php echo base_url('admin/category'); ?>"></a></li>
            <li><a class="action-create" href="<?php echo base_url('admin/category/create'); ?>"></a></li>
        </ul>
    </div>
    
    <div id="white-bg-container">
        <?php echo form_open('admin/category', array('class' => 'search')); ?>
            <ul class="filter-fields-list">
                <li>
                    <?php echo form_input('name', set_value('name'), 'class=\'small\' placeholder=\'Name\''); ?>
                </li>
                
                <li>
                    <?php echo form_input('slug', set_value('slug'), 'class=\'small\' placeholder=\'Slug\''); ?>
                </li>
                
                <li>
                    <select name="active">
                        <option value="">Active</option>
                        <option value="1" <?php echo set_select('active"', '1'); ?>>Yes</option>
                        <option value="0" <?php echo set_select('active', '0'); ?>>No</option>
                    </select>
                </li>
            </ul>
            <ul class="filter-fields-list">
                <li>
                    <input name="tags" class="large" id="tags" data-url="<?php echo site_url('admin/tag/ajax_search/'); ?>" data-never-submit="false" data-prefill="<?php echo $this->input->post('as_values_tags'); ?>" >
                </li>
            </ul>
            
            <?php echo form_submit('filter', '', 'class=\'btn-filter\''); ?>
        <?php echo form_close(); ?>
        
        <?php $this->load->view('admin/message'); ?>
        
        <table>
            <tr>
                <th class="medium">Name</th>
                <th class="medium">Slug</th>
                <th class="small">Active</th>
                <th class="xxsmall">Order</th>
                <th class="xxsmall"></th>
            </tr>
            <?php foreach ($categories as $category): ?>
            <tr>
                <td><?php echo $category->getName(); ?></td>
                <td><?php echo $category->getSlug(); ?></td>
                <td><?php echo $category->getActive()?'Yes':'No'; ?></td>
                <td><?php echo $category->getArrange(); ?></td>
                <td>
                    <a class="btn-edit" href="category/edit/<?php echo $category->getId(); ?>"></a>
                    <a class="btn-delete" href="category/delete/<?php echo $category->getId(); ?>"></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
    </div>
</section>