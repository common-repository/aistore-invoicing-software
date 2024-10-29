<?php



function aistore_books_html_pdf( $html,$filename ){
    
global $dompdf;
$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');
// $dompdf->Cell(0,10,'Display Image');
// $dompdf->Image('http://invoicingsoftware.blogentry.in/wp-content/uploads/documents/1/Screenshot-2022-03-01-at-16-29-17-Detalhes-QR-Code.png',10,30,30,30);
// Render the HTML as PDF
$dompdf->render();
 
    $output = $dompdf->output();
    
    $file= __DIR__.'/documents/'.$filename;
    
    file_put_contents($file, $output);
    
    return $file;


//return "foo and bar";
}