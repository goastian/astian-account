<template>
   <!-- Button trigger modal -->
<button type="button" :class="['btn', 'btn-primary', styles]" @click="sendEvent1(id)" data-bs-toggle="modal" :data-bs-target="('#').concat(target)">
   <slot name="button"></slot>
</button>

<!-- Modal -->
<div :class="['modal', 'fade', width]" :id="target" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
         <slot name="head"></slot>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <slot name="body"></slot>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="sendEvent2(id)">Aceptar</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</template>
<script>
export default {

    emits:['isClicked', 'isAccepted'],

     props:{
        target:{
            type:[String, Array],
            required:true
        }, 
        
        width:{
            type:String,
            default: "modal-lg"
        },
        styles:{
          type:[String, Array],
          default: 'btn-sm'
        }
     },
 
     methods: {
        sendEvent1(id){
            this.$emit('isClicked', id)
        },

        sendEvent2(id){
            this.$emit('isAccepted', id)
        }
     },


}
</script>
<style lang="">
    
</style>