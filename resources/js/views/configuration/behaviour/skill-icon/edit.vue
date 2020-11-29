<template>
  <div>
    <div class="page-titles">
      <div class="row">
        <div class="col-12 col-sm-6">
          <h3 class="text-themecolor">
            {{ trans('behaviour.edit_skill_icon') }}
          </h3>
        </div>
        <div class="col-12 col-sm-6">
          <div class="action-buttons pull-right">
            <button
              class="btn btn-info btn-sm"
              @click="$router.push('/configuration/behaviour/skill/icon')"
            >
              <i class="fas fa-list" /> <span class="d-none d-sm-inline">{{ trans('behaviour.skill_icon') }}</span>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="card card-form">
        <div class="card-body p-t-20">
          <skill-icon-form :id="id" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    import skillIconForm from './form';

    export default {
        components : { skillIconForm },
        data() {
            return {
                id:this.$route.params.id
            }
        },
        mounted(){
            if(!helper.hasPermission('access-configuration')){
                helper.notAccessibleMsg();
                this.$router.push('/dashboard');
            }
        }
    }
</script>
