<template>
  <div v-show="showModal">
    <transition name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-dialog file-upload" role="document">
            <div class="modal-content">
              <div class="modal-body">
                <div class="form-group row">
                  <label for="inputFile" class="col-3 col-form-label">Avatar</label>
                  <div class="col-9">
                    <input id="inputFile" type="file" name="photo" class="form-control input-file">
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-rounded-border btn-secondary" @click="closeModal">
                  Close
                </button>
                <button type="button" class="btn btn-sm btn-rounded-border btn-primary" @click="uploadPhoto">
                  Save changes
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import eventBus from '../../plugins/eventBus'
import * as axios from 'axios'

export default {
  name: 'FileUploadModal',
  data () {
    return {
      showModal: false,
      user_id: null
    }
  },
  mounted () {
    eventBus.$on('show-modal', ({ userId }) => {
      this.showModal = true
      this.user_id = userId
    })
  },
  methods: {
    closeModal () {
      this.showModal = false
    },
    async uploadPhoto () {
      let formData = new FormData()
      let imageFile = document.querySelector('#inputFile')
      formData.append('image', imageFile.files[0])

      const {data} = await axios.post('/api/users/' + this.user_id + '/upload', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      this.showModal = false
      await this.$store.dispatch('users/fetchUsers')
      await this.$store.dispatch('auth/fetchUser')
    }
  }
}
</script>

<style scoped>
.modal-dialog.file-upload {
  margin: 0;
  margin-bottom: 20px;
}
.modal-footer {
  padding: 0.5rem;
}
.input-file{
  padding-top: 3px;
}
</style>
