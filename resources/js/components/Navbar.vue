<template>
  <nav v-if="user" class="navbar navbar-expand-lg shadow" style="background-color:#E91E63;border-radius:20px; margin: 4px; height: 44px; font-weight: 400">
    <div class="container-fluid m-0 p-0">
      <router-link :to="{ name: user ? 'home' : 'welcome' }" class="navbar-brand">
        <img src="/plany_fav_2.png" algin="left" style="padding:0px 10px;margin:auto;max-height:30px">
      </router-link>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false">
        <span class="navbar-toggler-icon" />
      </button>

      <div id="navbarToggler" class="collapse navbar-collapse">
        <ul class="navbar-nav">
          <li class="nav-item">
            <button type="button" class="btn btn-sm btn-outline-light mt-1 px-4" style="border-radius: 40px;">New <fa icon="plus" fixed-width /></button>
          </li>
          <li class="nav-item mx-1">
            <router-link :to="{name:'users.index'}" class="nav-link text-white" ><fa icon="list-ul" fixed-width /></router-link>
            <small  style="position: absolute;">Tasks</small>
          </li>
          <li class="nav-item mx-1">
            <router-link :to="{name:'users.index'}" class="nav-link text-white" ><fa icon="columns" fixed-width /></router-link>
            <small style="position: absolute;">Kanban</small>
          </li>
          <li class="nav-item mx-1">
            <router-link :to="{name:'users.index'}" class="nav-link text-white" ><fa icon="archive" fixed-width /></router-link>
            <small style="position: absolute;">Archive</small>
          </li>      
          <li class="nav-item mx-1">
            <router-link :to="{name:'users.index'}" class="nav-link text-white" ><fa icon="archive" fixed-width /></router-link>
              <small style="position: absolute;">Users</small>
            </router-link>
          </li>
        </ul>

        <ul class="navbar-nav ml-auto">
          <li class="nav-item mx-1">
            <router-link :to="{name:'users.index'}" class="nav-link text-white" ><fa icon="cubes" fixed-width /></router-link>
            <small style="position: absolute;">Products</small>
          </li>   
          <li class="nav-item mx-1">
            <router-link :to="{name:'users.index'}" class="nav-link text-white" ><fa icon="user" fixed-width /></router-link>
            <small style="position: absolute;">Contacts</small>
          </li>   
          <li class="nav-item mx-1">
            <router-link :to="{name:'users.index'}" class="nav-link text-white" ><fa icon="hammer" fixed-width /></router-link>
            <small style="position: absolute;">Stations</small>
          </li>   
          <!-- Authenticated -->
          <li v-if="user" class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white"
               href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
            >
              {{ loginDisplayName }}
              <img :src="user.photo_url" class="rounded-circle profile-photo mr-1" style="width:auto;max-height: 28px;">
              {{ user.companyname }}
            </a>
            <div class="dropdown-menu">
              <router-link :to="{ name: 'settings.profile' }" class="dropdown-item pl-3">
                <fa icon="cog" fixed-width />
                {{ $t('settings') }}
              </router-link>

              
              <router-link :to="{ name: 'users.index'}" class="dropdown-item pl-3" ><fa icon="user" fixed-width /> Profile Settings</router-link>
              <router-link :to="{ name: 'users.index'}" class="dropdown-item pl-3" ><fa icon="users" fixed-width /> Users</router-link>
              <div class="dropdown-divider" />
              <router-link :to="{ name: 'settings.profile' }" class="dropdown-item pl-3"><fa icon="cog" fixed-width /> {{ $t('settings') }}</router-link>
              <router-link :to="{ name: 'settings.profile' }" class="dropdown-item pl-3"><fa icon="file" fixed-width /> Library</router-link>
              <router-link :to="{ name: 'settings.profile' }" class="dropdown-item pl-3"><fa icon="tag" fixed-width /> Tags</router-link>
              <div class="dropdown-divider" />
              <router-link :to="{ name: 'users.index'}" class="dropdown-item pl-3" ><fa icon="credit-card" fixed-width /> Billing</router-link>
              <small style=" font-style:italic;">Expiration 23/12/2025</small>
              <div class="dropdown-divider" />
              <router-link :to="{ name: 'users.index'}" class="dropdown-item pl-3" ><fa icon="question-circle" fixed-width /> Knowledge base</router-link>
              <a href="#" class="dropdown-item pl-3" @click.prevent="logout">
                <fa icon="sign-out-alt" fixed-width />
                {{ $t('logout') }}
              </a>
            </div>
          </li>
          <!-- Guest -->
          <template v-else>
            <li v-if="isTenant" class="nav-item">
              <router-link :to="{ name: 'login' }" class="nav-link" active-class="active">
                {{ $t('login') }}
              </router-link>
            </li>
          </template>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { mapGetters } from 'vuex'
import LocaleDropdown from './LocaleDropdown'

export default {
  components: {
    LocaleDropdown
  },

  data: () => ({
    appName: window.config.appName
  }),

  computed: {
    isTenant () {
      return window.config.isTenant
    },
    loginDisplayName () {
      return this.user.username
    },
    ...mapGetters({
      user: 'auth/user'
    })
  },

  methods: {
    async logout () {
      // Log out the user.
      await this.$store.dispatch('auth/logout')

      // Redirect to login.
      this.$router.push({ name: 'login' })
    }
  }
}
</script>

<style scoped>
.profile-photo {
  width: 2rem;
  height: 2rem;
  margin: -.375rem 0;
}
</style>
