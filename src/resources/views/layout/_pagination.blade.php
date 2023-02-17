<div class="page-number">
    @lang("tgn-reports::reports.Page") <span class="pagenum"></span>
</div>

{{-- 
<script type="text/php">
    
    $font = $fontMetrics->get_font("sans-serif", "normal");
    $size = 9;
    $margin=50;
    $pageText = "@lang("tgn-reports::reports.Page") " . $PAGE_NUM . " / " . $PAGE_COUNT;
    $textWidth = $fontMetrics->getTextWidth($pageText, $font, $size);
    $y = $pdf->get_height() + $size - $margin;
    $x = $pdf->get_width() - $textWidth  - $margin;
    
    $pdf->text($x, $y, $pageText, $font, $size);
</script> --}}
    
