<template>
  <div>
    <div class="page-titles">
      <div class="row">
        <div class="col-12 col-sm-6">
          <h3 class="text-themecolor">
            {{ trans('behaviour.edit_employee_skill') }}
          </h3>
        </div>
        <div class="col-12 col-sm-6">
          <div class="action-buttons pull-right">
            <button
              class="btn btn-info btn-sm"
              @click="$router.push('/configuration/behaviour/skill/employee')"
            >
              <i class="fas fa-list" /> <span class="d-none d-sm-inline">{{ trans('behaviour.employee_skill') }}</span>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="card card-form">
        <div class="card-body p-t-20">
          <employee-skill-form :id="id" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    import employeeSkillForm from './form';

    export default {
        components : { employeeSkillForm },
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
