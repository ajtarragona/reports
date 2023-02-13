
{{-- <div class="page-number">
    @lang("Page") <span  class="pagenum"></span>
</div> --}}

<script type="text/php">
    $font = $fontMetrics->get_font("sans-serif", "normal");
    $size = 9;
    $pageText = "@lang("Page") " . $PAGE_NUM . " de " . $PAGE_COUNT;
    $y = 750;
    $x = 515;
    $pdf->text($x, $y, $pageText, $font, $size);
</script>