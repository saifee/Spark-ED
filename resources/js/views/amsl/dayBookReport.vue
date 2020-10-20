<template>
  <div>
    <div class="page-titles">
      <h3 class="text-themecolor">
        {{ trans('general.report') }}
      </h3>
    </div>
    <div class="container-fluid container-body">
      <div class="row">
        <div class="col-12 col-sm-3">
          <div class="card card-box">
            <div class="card-body">
              <h4 class="card-title">
                {{ trans('amsl.cash') }}
              </h4>
              <v-btn
                :to="{name:'dayBookShow',params:{type:'cash'}}"
                x-small
                color="#009efb"
                dark
                depressed
              >
                {{ trans('general.go_to_link', {link: trans('amsl.cash')}) }}
              </v-btn>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-3">
          <div class="card card-box">
            <div class="card-body">
              <h4 class="card-title">
                {{ trans('amsl.bank') }}
              </h4>
              <v-btn
                :to="{name:'dayBookShow',params:{type:'bank'}}"
                x-small
                color="#009efb"
                dark
                depressed
              >
                {{ trans('general.go_to_link', {link: trans('amsl.bank')}) }}
              </v-btn>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
	export default {

	}
</script>
