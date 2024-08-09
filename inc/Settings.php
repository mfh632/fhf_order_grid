<?php


namespace FHF\OrderGrid;
use WC_Order_Query;

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
            [$this, 'settings_page']
        );
        add_submenu_page(
                'fhf-order-grid',
            'Display Orders',
            'Dispaly Orders',
            'manage_options',
            'fhf-order-grid-display',
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

        $orders = wc_get_orders($args);

        return $orders;
    }

    public function settings_page(){

        ?>
        <div class="wrap">
            <h2>OrderGrid Settings</h2>
            <p>Under Development</p>
        </div>
        <?php
    }
    public function fhf_display_order(){
        $limit = $_GET['limit'] ?? 20;

        $orders = $this->get_order_list(['limit' => $limit]);


        ?>
        <div class="container-fluid">
            <h2 class="mb-4 mt-4 head">Order List</h2>
            <div class="d-flex justify-content-between">
                <form>
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label for="limit" class="col-form-label">Limit</label>
                        </div>
                        <div class="col-auto">
                            <input type="number" id="limit" name="limit" class="form-control" min="1" value="<?php echo $limit;?>">
                            <input type="hidden" name="page" class="form-control" value="fhf-order-grid-display" />
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary ">Submit</button>
                        </div>

                    </div>

                </form>
                <div>
                    <button type="button" id="exportToExcel" class="btn btn-primary ">Export</button>
                </div>
            </div>
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
                            <td colspan="7">Not found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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