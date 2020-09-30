<template>
  <div class="row">
    <div class="col-lg-8 m-auto">
      <div class="myform form ">
        <div class="logo mb-3">
          <div class="col-md-12 text-center">
            <h1>Welcome to</h1>
          </div>
          <div class="col-md-12 text-center">
            <h5>{{ domainName }}</h5>
          </div>
        </div>
        <form @submit.prevent="login" @keydown="form.onKeydown($event)">
          <div class="form-group">
            <label for="email">Email address</label>
            <input v-model="form.email" :class="{ 'is-invalid': form.errors.has('email') }" class="form-control" type="email" name="email">
            <has-error :form="form" field="email" />
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control" type="password" name="password">
            <has-error :form="form" field="password" />
          </div>
          <!-- Remember Me -->
          <div class="form-group">
            <checkbox v-model="remember" name="remember">
              {{ $t('remember_me') }}
            </checkbox>
          </div>
          <div class="col-md-12 text-center ">
            <button type="submit" class=" btn btn-block mainbtn">Log in</button>
          </div>
          <div class="col-md-12 ">
            <div class="login-or">
                <hr class="hr-or">
                <span class="span-or">or</span>
            </div>
          </div>
          <div class="col-md-12 mb-3">
            <p class="text-center">
              <a href="javascript:void();" class=" btn socialbtn btn btn-block btn-social btn-google"><img src="https://img.icons8.com/color/16/000000/google-logo.png" algin="left">
              </i> Sign in with Google
              </a>
            </p>
          </div>
          <div class="form-group">
            <p class="text-left">Don't an have account? <a href="/register" id="signup">Sign up</a></p>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<style>
</style>

<script>
import Form from 'vform'
import LoginWithGithub from '~/components/LoginWithGithub'

export default {
  middleware: 'guest',

  components: {
    LoginWithGithub
  },

  metaInfo () {
    return { title: this.$t('login') }
  },

  data: () => ({
    form: new Form({
      email: '',
      password: ''
    }),
    remember: false
  }),

  computed: {
    domainName () {
      return window.config.tenantDomain + '.' + window.config.centralDomain;
    }
  },

  methods: {
    async login () {
      // Submit the form.
      const { data } = await this.form.post('/api/login')

      // Save the token.
      this.$store.dispatch('auth/saveToken', {
        token: data.token,
        remember: this.remember
      })

      // Fetch the user.
      await this.$store.dispatch('auth/fetchUser')

      // Redirect home.
      this.$router.push({ name: 'home' })
    }
  }
}
</script>
