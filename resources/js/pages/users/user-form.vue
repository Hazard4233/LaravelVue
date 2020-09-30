<template>
  <div class="row form-holder">
    <div v-if="form.id" class="col-12 p-0">
      <FileUploadModal />
      <div class="row">
        <div class="col-5">
          <button class="btn btn-primary btn-sm my-3 btn-rounded-border">
            Change Password
          </button>
        </div>
        <div class="col-7">
          <div class="row">
            <div class="col-8 text-right">
              <button class="btn btn-sm" @click="showModal">
                change avatar
                <FontAwesomeIcon icon="plus-circle" />
              </button>
              <br>
              <button class="btn btn-sm" @click="removeAvatar">
                remove avatar
                <FontAwesomeIcon icon="minus-circle" />
              </button>
            </div>
            <div class="col">
              <img width="40" height="40" :src="photoUrl" class="rounded-circle profile-photo mr-1">
            </div>
          </div>
        </div>
      </div>
    </div>
    <form class="col-12 p-0">
      <div class="form-group">
        <label for="inputName">Full name</label>
        <input id="inputName" v-model="form.name" :class="{ 'is-invalid': errors.name }" class="form-control" type="text" name="name">
        <div v-if="errors.name" class="help-block invalid-feedback" v-html="errors.name" />
      </div>
      <div class="form-group">
        <label for="inputEmail1">Email</label>
        <input id="inputEmail1" v-model="form.email" :class="{ 'is-invalid': errors.email }" class="form-control" type="text" name="email">
        <div v-if="errors.email" class="help-block invalid-feedback" v-html="errors.email" />
      </div>
      <div class="form-group">
        <label for="inputUsername">Username</label>
        <input id="inputUsername" v-model="form.username" :class="{ 'is-invalid': errors.username }" class="form-control" type="text" name="username">
        <div v-if="errors.username" class="help-block invalid-feedback" v-html="errors.username" />
      </div>
      <div class="form-group">
        <label for="inputUserLevel">User level</label>
        <select id="inputUserLevel" name="user_level" class="form-control">
          <option v-for="level in userLevels" :value="level.id" v-text="level.title" />
        </select>
        <div v-if="errors.user_level" class="help-block invalid-feedback" v-html="errors.user_level" />
      </div>


      <div class="row my-4">
        <div class="col-12">
          <a href="javascript:void();" class="btn btn-google-connect btn btn-block btn-social">
            <img src="/images/google-icon.png" algin="left">
            <span>Link to Google account</span>
          </a>
        </div>
      </div>

      <div class="form-group">
        <div class="row">
          <div v-if="form.id!=null" class="col-6 text-left">
            <button type="button" :disabled="form.busy" class="btn btn-danger btn-rounded-border btn-action" @click="deleteUser">
              {{ $t('delete') }}
            </button>
          </div>
          <div class="col text-right">
            <template v-if="form.id==null">
              <button type="button" :disabled="form.busy" class="btn btn-primary btn-rounded-border btn-action" @click="save">
                {{ $t('save') }}
              </button>
            </template>
            <template v-else>
              <button type="button" :disabled="form.busy" class="btn btn-primary btn-rounded-border btn-action" @click="update">
                {{ $t('save') }}
              </button>
            </template>
          </div>
        </div>
      </div>
      <div v-if="userData.id" class="row">
        <div class="col-auto">
          <small>Date created: {{dateCreated}}</small>
        </div>
        <div class="col-auto">
          <small>Date updated: {{dateUpdated}}</small>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import Form from 'vform'
import eventBus from '../../plugins/eventBus'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import FileUploadModal from '../../components/users/FileUploadModal'
import * as axios from 'axios'
import moment from 'moment'

export default {
  name: 'UserForm',
  middleware: 'auth',

  components: {
    FontAwesomeIcon,
    FileUploadModal
  },

  metaInfo () {
    return { title: this.$t('register') }
  },

  data: () => ({
    form: null,
    errors: {},
    domain: { edit: false },
    mustVerifyEmail: false,
    showFileModal: false,
    userData: {}
  }),

  computed: {
    userLevels () {
      return [
        { id: 1, title: 'Admin' },
        { id: 2, title: 'User' }
      ]
    },
    dateCreated () {
      if (this.userData.id) {
        return moment(this.userData.created_at).format('DD/MM/YYYY')
      }
      return ''
    },
    dateUpdated () {
      if (this.userData.id) {
        return moment(this.userData.updated_at).format('DD/MM/YYYY')
      }
      return ''
    },
    photoUrl () {
      let photoUrl
      this.$store.state.users.users.map((user) => {
        if (user.id === this.form.id) {
          photoUrl = user.photo_url
        }
      })
      return photoUrl
    }
  },

  created () {
    this.resetForm()
  },

  async mounted () {
    eventBus.$on('edit-user', async ({ id }) => {
      this.userData = await this.$store.dispatch('users/fetchUser', id)
      // eslint-disable-next-line camelcase
      let { name, email, username, user_level, photo_url } = this.userData
      let user = { id, name, email, username, user_level, photo_url }
      this.form = Object.assign(this.form, user)
    })

    eventBus.$on('create-user', async () => {
      this.resetForm()
    })

    eventBus.$on('errors-user', ({ errors }) => {
      console.log(errors)
      for (let key in errors) {
        if (errors.hasOwnProperty(key)) {
          this.errors[key] = errors[key].join(',')
        }
      }
    })
  },

  methods: {
    save () {
      this.$store.dispatch('users/storeUser', this.form)
    },
    update () {
      this.$store.dispatch('users/updateUser', this.form)
    },
    async deleteUser () {
      let response = await confirm('Are you sure want to delete ' + this.form.email + ' ?')
      if (response) {
        await this.$store.dispatch('users/deleteUser', { id: this.form.id })
        this.resetForm()
        await this.$store.dispatch('users/fetchUsers')
      }
    },
    resetForm () {
      this.form = new Form({
        id: null,
        name: '',
        email: '',
        user_level: 1
      })
      this.errors = []
    },
    showModal () {
      eventBus.$emit('show-modal', { userId: this.form.id })
    },
    async removeAvatar () {
      let response = await confirm('are you sure want to remove this avatar?')
      if (response) {
        await axios.delete('/api/users/' + this.form.id + '/upload')
        await this.$store.dispatch('users/fetchUsers')
      }
    }
  }

}
</script>

<style scoped>
.form-holder{
  margin: 0 10px;
  padding: 10px;
}
.btn-action{
  padding: 6px 30px;
}
.btn-google-connect {
  padding: 0;
  text-align: left;
  border: 1px solid #4285f4;
  background: #4285f4;
  color:#fff;
}
.btn-google-connect > img{
  width: 50px;
}
.btn-google-connect > span{
  padding-left: 20px;
}
</style>
