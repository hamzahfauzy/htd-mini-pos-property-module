<?php

$item = isset($_POST['item']) ? $_POST['item'] : false;
$transactionType = isset($_POST['transaction_type']) ? $_POST['transaction_type'] : false;
$issueDate = isset($_POST['issue_date']) ? $_POST['issue_date'] : false;
$dueDate = isset($_POST['due_date']) ? $_POST['due_date'] : false;
$instalment = isset($_POST['instalment']) ? $_POST['instalment'] : false;
$amountOfInstalment = isset($_POST['amount_of instalment']) ? $_POST['amount_of instalment'] : false;
$customer = isset($_POST['customer']) ? $_POST['customer'] : false;
$paytotal = isset($_POST['paytotal']) ? $_POST['paytotal'] : false;
$sales = isset($_POST['sales']) ? $_POST['sales'] : false;

$validationStatus = true;
$message = "";

// required validation
if(!$transactionType)
{
    $message = 'Jenis Pembayaran tidak boleh kosong';
    $validationStatus = false;
}
else if($transactionType != 'Cash' && !$dueDate)
{
    $message = 'Tanggal jatuh tempo tidak boleh kosong';
    $validationStatus = false;
}
else if($transactionType == 'Kredit' && !$instalment)
{
    $message = 'Jumlah cicilan tidak boleh kosong';
    $validationStatus = false;
}
else if($transactionType == 'Kredit' && !$amountOfInstalment)
{
    $message = 'Nominal cicilan tidak boleh kosong';
    $validationStatus = false;
}
else if(!$customer)
{
    $message = 'Customer tidak boleh kosong';
    $validationStatus = false;
}
else if(!$issueDate)
{
    $message = 'Tanggal tidak boleh kosong';
    $validationStatus = false;
}
else if(!$paytotal)
{
    $message = 'Nominal Pembayaran tidak boleh kosong';
    $validationStatus = false;
}

if(!$validationStatus)
{
    http_response_code(401);
    echo json_encode([
        'message' => $message,
        'data'    => [],
        'status'  => 'fail'
    ]);
    die();
}

$conn = conn();
$db   = new Database($conn);

if($sales)
{
    $sales = $db->single('users', ['id' => $sales]);
}

$inv_code = strtotime('now');
$customer = $db->single('customers', ['id' => $customer]);
$product = $db->single('products', ['id' => $item]);
$product_price = $db->single('product_prices', ['product_id' => $item]);
$remaining = $product_price->base_price - $paytotal;

$insert_data = [
    'created_by'  => auth()->user->id,
    'total'    => $product_price->base_price,
    'remaining_payment' => $remaining,
    'status'   => $remaining == 0 ? 'finish' : 'on going',
    'code' => $inv_code,
    'notes' => $transactionType,
    'issue_date' => $issueDate,
    'due_date' => $dueDate,
    'metadata' => json_encode($_POST),
    'customer_id' => $customer->id
];

if(is_object($sales))
{
    $insert_data['sales_id'] = $sales->id;

    if(app('fee_sales') && app('fee_sales') > 0)
    {
        $fee_sales = app('fee_sales_type') == 'fixed' ? app('fee_sales') : ($product_price->base_price * app('fee_sales')/100); 
        $db->insert('balance_mutations',[
            'user_id' => $sales->id,
            'amount'   => $fee_sales,
            'record_type' => 'IN',
            'description' => 'Fee Sales '.$inv_code,
        ]);
    }
}

$invoice = $db->insert('invoices',$insert_data);
$db->insert('invoice_items',[
    'invoice_id'     => $invoice->id,
    'product_id'     => $item,
    'price'          => $product_price->base_price,
    'qty'            => 1,
    'subtotal'       => $product_price->base_price,
    'status'         => $remaining == 0 ? 'pay' : 'order',
]);

$db->insert('product_stocks',[
    'product_id' => $item,
    'qty'        => -1,
]);

$db->update('property_items', [
    'record_status' => $transactionType == 'Booking' ? 'booked' : ($transactionType == 'Kredit' ? 'kredit' : 'sold')
], [
    'product_id' => $item
]);

$transaction = null;

$transaction = $db->insert('transactions', [
    'invoice_id' => $invoice->id,
    'amount' => $paytotal,
    'amount_total' => $product_price->base_price,
    'amount_return' => 0,
    'payment_type' => 'cash',
    'created_by'  => auth()->user->id,
]);

echo json_encode([
    'status'   => 'success',
    'message'  => 'order success',
    'data'     => $invoice
]);
die();