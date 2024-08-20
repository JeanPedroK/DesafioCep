<template>
    <div class="modal fade" id="modalAlerta" tabindex="-1" role="dialog"
        aria-labelledby="modalAlertaTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAlertaLongTitle">Alerta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ msgCentro }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default{
    props: {
    msgCentro: {
      type: String,
      required: true
    }
  },
    data(){
        
    }
}



</script>