<template>
  <form
    @submit.prevent="proceed"
    @keydown="skillForm.errors.clear($event.target.name)"
  >
    <div class="row">
      <div class="col-12 col-sm-6">
        <div class="form-group">
          <label for="">{{ trans('behaviour.skill_name') }}</label>
          <input
            v-model="skillForm.name"
            class="form-control"
            type="text"
            name="name"
            :placeholder="trans('behaviour.skill_name')"
          >
          <show-error
            :form-name="skillForm"
            prop-name="name"
          />
        </div>
      </div>
      <div class="col-12 col-sm-6">
        <div class="form-group">
          <label for="">{{ trans('behaviour.skill_points') }}</label>
          <input
            v-model="skillForm.points"
            class="form-control"
            type="number"
            name="points"
            :placeholder="trans('behaviour.skill_points')"
          >
          <show-error
            :form-name="skillForm"
            prop-name="points"
          />
        </div>
      </div>
      <div class="col-12 col-sm-6">
        <div class="form-group">
          <label for="">{{ trans('behaviour.skill_positive') }}</label>
          <v-switch
            v-model="skillForm.positive"
            :label="trans('behaviour.skill_positive')"
            color="success"
          />
          <show-error
            :form-name="skillForm"
            prop-name="positive"
          />
        </div>
      </div>
    </div>
    <div class="card-footer text-right">
      <router-link
        v-show="id"
        to="/configuration/behaviour/skill"
        class="btn btn-danger waves-effect waves-light "
      >
        {{ trans('general.cancel') }}
      </router-link>
      <button
        v-if="!id"
        type="button"
        class="btn btn-danger waves-effect waves-light "
        @click="$emit('cancel')"
      >
        {{ trans('general.cancel') }}
      </button>
      <button
        type="submit"
        class="btn btn-info waves-effect waves-light"
      >
        <span v-if="id">{{ trans('general.update') }}</span>
        <span v-else>{{ trans('general.save') }}</span>
      </button>
    </div>
  </form>
</template>


<script>
    export default {
        props: ['id'],
        data() {
            return {
                skillForm: new Form({
                    positive : 1,
                    name : '',
                    points : 1,
                    skill_icon_id : '',
                    batch_id : '',
                })
            };
        },
        mounted() {
            if(this.id)
                this.get();
        },
        methods: {
            proceed(){
                if(this.id)
                    this.update();
                else
                    this.store();
            },
            store(){
                let loader = this.$loading.show();
                this.skillForm.post('/api/behaviour/skill')
                    .then(response => {
                        toastr.success(response.message);
                        this.$emit('completed');
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            },
            get(){
                let loader = this.$loading.show();
                axios.get('/api/behaviour/skill/'+this.id)
                    .then(response => {
                        this.skillForm.name = response.name;
                        this.skillForm.points = response.points;
                        this.skillForm.positive = response.positive;
                        this.skillForm.skill_icon_id = response.skill_icon_id;
                        this.skillForm.batch_id = response.batch_id;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                        this.$router.push('/configuration/behaviour/skill');
                    });
            },
            update(){
                let loader = this.$loading.show();
                this.skillForm.patch('/api/behaviour/skill/'+this.id)
                    .then(response => {
                        toastr.success(response.message);
                        loader.hide();
                        this.$router.push('/configuration/behaviour/skill');
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            }
        }
    }
</script>
