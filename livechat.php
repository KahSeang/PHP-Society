<?php
function generateTawkToScript($propertyId) {
    return <<<HTML
<!-- Tawk.to live chat script -->
<script type="text/javascript">
    var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
    (function(){
        var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/$propertyId/default';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>
HTML;
}

// Example usage:
$propertyId = 'your_tawkto_property_id';
echo generateTawkToScript($propertyId);
?>
