<template>
  <div class="col-12 p-0">
    <div v-if="!deleted" class="row py-2">
      <div class="col-1">
        <input type="checkbox" class="form-control checkbox">
      </div>
      <div class="col-1">
        <img width="40" height="40" :src="user.photo_url" class="rounded-circle profile-photo mr-1">
      </div>
      <div class="col-2" v-text="user.name" />
      <div class="col-3" v-text="user.email" />
      <div class="col-2" v-text="user.username" />
      <div class="col-2" v-text="createdAt" />
      <div class="col-1 text-right">
        <a href="#" @click="edit" class="text-success font-weight-light"><font-awesome-icon :icon="['fas','edit']" /></a>
      </div>
    </div>
  </div>
</template>
<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import eventBus from '../../plugins/eventBus'
import moment from 'moment'

export default {
  name: 'UserItem',
  components: {
    FontAwesomeIcon
  },
  props: {
    user: {
      required: true,
      type: Object
    }
  },
  data () {
    return {
      deleted: false
    }
  },
  computed: {
    createdAt () {
      return moment(this.user.created_at).format('HH:mm DD/MM/YYYY')
    }
  },
  methods: {
    edit () {
      eventBus.$emit('edit-user', { id: this.user.id })
    },
    async deleteUser () {
      let confirmed = await confirm('Are you sure want to delete user ' + this.user.email)
      if (confirmed) {
        await this.$store.dispatch('users/deleteUser', { id: this.user.id })
        this.deleted = true
      }
    }
  }
}
</script>

<style scoped>
.checkbox{
  width: 25px;
}
</style>
