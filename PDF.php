<?php
    session_start();
    if(!isset($_SESSION['nombre'])){
        header("Location: login.php");
        exit;
    } else {
        require_once('api/TCPDF-main/tcpdf.php');
        include_once 'includes/Usuario.php';

        $logoPath = 'img/ico.png';

        class CustomPDF extends TCPDF {
            protected $logoPath;

            public function __construct($logoPath) {
                parent::__construct();
                $this->logoPath = $logoPath;
            }

            // Sobrescribe el método Header
            public function Header() {
                // Posición del logo
                $this->Image($this->logoPath, 10, 10, 30, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
                
                // Establece la fuente para el título de la cabecera
                $this->SetFont('helvetica', 'B', 14);
                
                // Título de la cabecera
                $this->Cell(0, 25, 'Ticket ID: '.$_GET['id'], 0, 1, 'C', 0, '', 0, false, 'T', 'M');
                
                // Añade espacio después del título
                $this->Ln(5);
            }
        }

        if(isset($_GET['id'])) {
            $conn = new Usuario();
            $datos = $conn->ticket_pdf($_SESSION['id'], $_GET['id']);
            
            // Verifica si se obtuvieron los datos correctamente
            if($datos === false) {
                echo "Error: No se pudieron obtener los detalles del ticket.";
                exit;
            }
            
            $id_ticket = $_GET['id'];
            
            // Crea una instancia de CustomPDF
            $pdf = new CustomPDF($logoPath);

            // Configura la información del documento y otros ajustes
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Domo-Sapiens');
            $pdf->SetTitle("Ticket ID: $id_ticket");
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // Añade una página
            $pdf->AddPage();

            // Establece la fuente
            $pdf->SetFont('helvetica', '', 12);

            // Añade contenido
            $pdf->Ln(5);
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 10, 'Nombre del Cliente: ' . $datos['nombre_usu'], 0, 1, 'L');
            $pdf->Cell(0, 10, 'Fecha de Compra: ' . $datos['fecha_compra'], 0, 1, 'L');
            $pdf->Cell(0, 10, 'Dirección de entrega: ' . $datos['direccion_ciudad_cp'], 0, 1, 'L');
            $pdf->Cell(0, 10, 'Metodo de pago: ' . $datos['metodo_pago'], 0, 1, 'L');

            $pdf->Ln(5);

            // Añade la tabla de productos
            $pdf->writeHTML($datos['tabla_productos'], true, false, true, false, '');

            $pdf->Ln(10);

            // Añade otros detalles
            $pdf->Cell(0, 10, '¡Esperemos que te disfrutes tus articulos!', 0, 1, 'L');
            $pdf->Cell(0, 10, 'Atentamente,', 0, 1, 'L');
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 10, 'DomosaPiens', 0, 1, 'L');

            $pdf->Ln(5);

            $pdf->Write(0, 'Este es un ticket de caracter oficial. Cualquier problema con los articulos o envio, este documento servira en caso de reclamacion.', '', 0, 'C', true, 0, false, false, 0);

            // Cierra y genera el PDF
            $pdf->Output("Ticket_$id_ticket.pdf", 'I');
        } else {
            echo 'Ha ocurrido algo que no debía, serás redirigido a tu panel.';
        }
    }
?>