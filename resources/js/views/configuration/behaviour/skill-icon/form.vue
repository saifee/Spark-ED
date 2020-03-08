<template>
    <form @submit.prevent="proceed" @keydown="skillIconForm.errors.clear($event.target.name)">
        <div class="row">
            <div class="col-12 col-sm-6">
                <div class="form-group">
                    <label for="">{{trans('behaviour.skill_icon_name')}}</label>
                    <input class="form-control" type="text" v-model="skillIconForm.name" name="name" :placeholder="trans('behaviour.skill_icon_name')">
                    <show-error :form-name="skillIconForm" prop-name="name"></show-error>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <router-link to="/configuration/behaviour/skill/icon" class="btn btn-danger waves-effect waves-light " v-show="id">{{trans('general.cancel')}}</router-link>
            <button v-if="!id" type="button" class="btn btn-danger waves-effect waves-light " @click="$emit('cancel')">{{trans('general.cancel')}}</button>
            <button type="submit" class="btn btn-info waves-effect waves-light">
                <span v-if="id">{{trans('general.update')}}</span>
                <span v-else>{{trans('general.save')}}</span>
            </button>
        </div>
    </form>
</template>


<script>
    export default {
        data() {
            return {
                skillIconForm: new Form({
                    name : '',
                })
            };
        },
        props: ['id'],
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
                this.skillIconForm.post('/api/behaviour/skill-icon')
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
                axios.get('/api/behaviour/skill-icon/'+this.id)
                    .then(response => {
                        this.skillIconForm.name = response.name;
                        loader.hide();
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                        this.$router.push('/configuration/behaviour/skill/icon');
                    });
            },
            update(){
                let loader = this.$loading.show();
                this.skillIconForm.patch('/api/behaviour/skill-icon/'+this.id)
                    .then(response => {
                        toastr.success(response.message);
                        loader.hide();
                        this.$router.push('/configuration/behaviour/skill/icon');
                    })
                    .catch(error => {
                        loader.hide();
                        helper.showErrorMsg(error);
                    });
            }
        }
    }
</script>
