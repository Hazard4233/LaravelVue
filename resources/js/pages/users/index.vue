<template>
  <div class="row">
    <div class="col-8">
      <transition name="fade" mode="out-in">
        <router-view />
      </transition>
    </div>
    <div class="col-4 mt-6">
      <div class="card rounded-card">
        <div class="card-body p-0 pt-3">
          <userForm />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import userForm from './user-form'

export default {
  middleware: 'auth',
  components: {
    userForm
  },

  computed: {
  }
}
</script>

<style>
.mt-6{
  margin-top: 4rem;
}
</style>
