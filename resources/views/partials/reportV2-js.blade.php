<script >
    <?php

use Illuminate\Support\Facades\Auth;

    $user_type = Auth::user()->user_type;
    ?>
$(document).ready(function() {
    var type = '<?php echo($user_type); ?>';
    if(type=='11x101'){
        $("#all_report").trigger('click'); 
    }
    
});


</script>

