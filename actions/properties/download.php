<?php

use PhpOffice\PhpWord\TemplateProcessor;

$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');

$invoice = $db->single('invoices', [
    'id' => $_GET['invoice']
]);

$invoice_item = $db->single('invoice_items', [
    'invoice_id' => $invoice->id
]);

$customer = $db->single('customers', [
    'id' => $invoice->customer_id
]);

$transaction = $db->single('transactions', [
    'invoice_id' => $invoice->id
]);

$product = $db->single('products', [
    'id' => $invoice_item->product_id
]);

$property_item = $db->single('property_items', [
    'product_id' => $product->id
]);

$property = $db->single('properties', [
    'id' => $property_item->property_id
]);

$property_item->property = $property;

$product->property_item = $property_item;

$invoice_item->product = $product;

$invoice->item = $invoice_item;
$invoice->metadata = json_decode($invoice->metadata);
$invoice->customer = $customer;
$invoice->transaction = $transaction;

// echo json_encode($invoice); die;

// Baca template Word
$templateProcessor = new TemplateProcessor(config('file_template'));

$pattern = [
    '{day}' => hari_ini(),
    '{date}' => terbilang(date('d')),
    '{month}' => bulan_indo(date('m')),
    '{year}' => terbilang(date('Y')),
    '{customer_name}' => $invoice->customer->name,
    '{customer_address}' => $invoice->customer->address,
    '{customer_code}' => $invoice->customer->code,
    '{customer_phone}' => $invoice->customer->phone,
    '{property_address}' => $invoice->item->product->property_item->property->address,
    '{property_item_size}' => $invoice->item->product->property_item->property_size,
    '{property_item_base_price}' => 'Rp. '.number_format($invoice->total, 0,',','.'),
    '{property_item_base_price_text}' => terbilang($invoice->total) . ' rupiah',
    '{transaction_first_amount}' => 'Rp. '.number_format($invoice->transaction->amount, 0,',','.'),
    '{transaction_first_amount_text}' => terbilang($invoice->transaction->amount) . ' rupiah',
    '{invoice_instalment}' => $invoice->metadata->instalment,
    '{invoice_amount_of_instalment}' => $invoice->metadata->amount_of_instalment ? 'Rp. '.number_format($invoice->metadata->amount_of_instalment,0,',','.'): '',
    '{invoice_amount_of_instalment_text}' => $invoice->metadata->amount_of_instalment ? terbilang($invoice->metadata->amount_of_instalment)  . ' rupiah' : "",
    '{invoice_due_date}' => date('d-m-Y', strtotime($invoice->due_date)),
];

foreach($pattern as $key => $value)
{
    // Ganti teks (misalnya: mengganti {name} dengan "John Doe")
    $templateProcessor->setValue($key, $value);
}

// Simpan perubahan ke file baru
$file = 'downloads/'.$invoice->code.'.docx';
if(file_exists($file))
{
    unlink($file);
}

$templateProcessor->saveAs($file);

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.basename($file));
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file));
ob_clean();
flush();
readfile($file);
exit;