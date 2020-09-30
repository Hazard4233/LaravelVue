<template>
  <div class="row user-list">
    <div class="col-12">

      <div class="row mb-2 mx-2">
        <div class="col-9">
          <h2>Users</h2>
        </div>
        <div class="col-3 text-right">
          <button class="btn btn-rounded-border" @click="newUser">
            New <FontAwesomeIcon icon="plus"></FontAwesomeIcon>
          </button>
        </div>
      </div>

      <div class="card rounded-card">
        <div class="card-body">
          <template v-for="(user,i) in users">
            <user-item :key="'user-item-'+i" :user="user" />
            <hr class="separator" v-if="i< users.length -1" :key="'user-hr-'+i"/>
          </template>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import UserItem from '../../components/users/UserItem'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import eventBus from '../../plugins/eventBus'

export default {
  name: 'List',
  components: {
    UserItem,
    FontAwesomeIcon
  },
  computed: {
    users () {
      return this.$store.state.users.users
    }
  },
  created () {
    this.$store.dispatch('users/fetchUsers')
  },
  methods: {
    newUser () {
      eventBus.$emit('create-user')
    }
  }
}
</script>

<style scoped>
.user-list{
  padding:10px;
}
.btn-rounded-border{
  padding: 6px 18px;
 }
.separator{
  margin: 5px 0;
}
</style>
