<?php


namespace FHF\OrderGrid;

/**
 * Class Settings
 * @package FHF\OrderGrid
 */
class Settings extends BaseComponent
{

    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_admin_menu']);

    }

    public function add_admin_menu(){

        add_menu_page(
            'Order Settings',
            'Order List',
            'manage_options',
            'fhf-order-grid',
            [$this, 'fhf_display_order']
        );
    }

    protected function get_order($order_id){
        if (!$order_id){
            return;
        }
        $order = wc_get_order($order_id);
        return $order;
    }

    protected function get_order_list(array $args){

        $args = array_merge($args, [
            'orderby' => 'date',
            'order' => 'DESC',
        ]);
        $ids = [];

        if (isset($args['product_name']) && !empty($args['product_name'])){
            $per_page = $args['per_page'];
            global $wpdb;
            $t_orders = $wpdb->prefix . "wc_orders";
            $t_order_items = $wpdb->prefix . "woocommerce_order_items";

            $productName = $args['product_name'];

            $query  = "SELECT id FROM $t_orders AS o ";
            $query  .= "LEFT JOIN $t_order_items AS oi ON oi.order_id=o.id ";
            $query  .= "WHERE  oi.order_item_name LIKE '%". $productName . "%' ";
            $query  .= "ORDER BY  o.date_created_gmt DESC ";
            $query  .= "LIMIT  $per_page";

            $orderIds = $wpdb->get_results($query, 'ARRAY_A');

            if ($orderIds){
                foreach ($orderIds as $orderId){
                    $ids[] = $orderId['id'];
                }
                $args['id'] = $ids;
            } else {
                return [];
            }
        }

        $orders = wc_get_orders($args);

        return $orders;
    }

    public function fhf_display_order(){
        $per_page = $_GET['per_page'] ?? 20;
        $product_name = $_GET['product_name'] ?? "";
        $orders = [];
        if ($per_page && $product_name){
            $orders = $this->get_order_list(['per_page' => $per_page, 'product_name' => $product_name]);
        }
        ?>
        <div class="container-fluid">
            <h2 class="mb-4 mt-4 head">Order List</h2>
            <div class="d-flex justify-content-between">
                <form class="row g-3 align-items-center">
                    <div class="col-auto">
                        <div class="input-group">
                            <span class="input-group-text"><?= __('Per Page') ?></span>
                            <input type="number" id="per_page" name="per_page" class="form-control" min="1" value="<?php echo $per_page;?>" required>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="input-group">
                            <span class="input-group-text"><?= __('Product Name') ?></span>
                            <input type="text" id="product_name" name="product_name" class="form-control"  value="<?php echo $product_name;?>" required>
                        </div>
                    </div>
                    <input type="hidden" name="page" class="form-control" value="fhf-order-grid" />
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary "><?= __('Submit') ?></button>
                    </div>
                </form>
                <div>
                    <?php if (count($orders)): ?>
                    <button type="button" id="exportToExcel" class="btn btn-primary "><?= __('Export') ?></button>
                    <?php endif; ?>
                </div>
            </div>
            <?php if ($product_name && $per_page):?>
            <hr>
            <table class="table table-striped" id="fogOrderList">
                <thead>
                    <th>ID</th>
                    <th>Date of purchase</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Number</th>
                    <th>Payment Method</th>
                    <th>Amount</th>
                </thead>
                <tbody>
                    <?php if (count($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo $order->get_id(); ?></td>
                            <td><?php echo $order->get_date_created()->date('Y-m-d H:i:s'); ?></td>
                            <td>
                                <?php
                                    $customerBillingName = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
                                    echo $customerBillingName;
                                ?>
                            </td>
                            <td><?php echo $order->get_billing_email(); ?></td>
                            <td><?php echo $order->get_billing_phone(); ?></td>
                            <td><?php echo $order->get_payment_method_title(); ?></td>
                            <td><?php echo $order->get_total(); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Not found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

        <script>
            jQuery(document).ready(function ($) {
                $("#exportToExcel").click(function () {
                    let table = $("#fogOrderList");

                    TableToExcel.convert(table[0], {
                        name: `OrderList.xlsx`,
                        sheet: {
                            name: 'OrderList'
                        }
                    });
                });
            });
        </script>
        <?php
    }

}