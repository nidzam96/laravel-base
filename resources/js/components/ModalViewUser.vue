<template>

    <form>

        <div class="form-row">

            <div class="form-group col-lg-6">
                <label>Name <span class="text-danger">*</span></label>
                <input 
                    type="text"
                    id="name"
                    name="name"
                    v-model.trim="$v.user.name.$model"
                    class="form-control"
                    v-bind:class="{'is-invalid': $v.user.name.$error, 'is-valid': $v.user.name.$dirty && !$v.user.name.$error}"
                >

                <span class="invalid-feedback" role="alert">
                    <strong v-if="!$v.user.name.required">Name required</strong>
                </span>
            </div>

            <div class="form-group col-lg-6">
                <label>Email <span class="text-danger">*</span></label>
                <input 
                    type="text"
                    id="email"
                    name="email"
                    v-model.trim="$v.user.email.$model" 
                    class="form-control"
                    v-bind:class="{'is-invalid': $v.user.email.$error, 'is-valid': $v.user.email.$dirty && !$v.user.email.$error}"
                    :readonly="action != 'create'"
                >
                <span class="invalid-feedback" role="alert">
                    <strong v-if="!$v.user.email.required">E-mail is required</strong>
                </span>
            </div>

        </div>
        
        <div class="form-row">

            <div v-if="action == 'update'" class="col-12">
                <div class="alert alert-info">
                    Plese leave the <strong>Password</strong> and <strong>Confirm Password</strong> fields blank if you do not want to reset the password
                </div>
            </div>

            <div class="form-group col-lg-6">
                <label>Password <span class="text-danger">*</span></label>
                <input 
                    id="password" 
                    type="password" 
                    class="form-control" 
                    name="password" 
                    v-model.trim="$v.user.password.$model"
                    v-bind:class="{'is-invalid': $v.user.password.$error, 'is-valid': $v.user.password.$dirty && !$v.user.password.$error}"
                >
                <span class="invalid-feedback" role="alert">
                    <strong>
                        <span v-show="!$v.user.password.required">Password is required</span>
                        <span v-show="!$v.user.password.minLength">Password must be atleast 8 characters</span>
                    </strong>
                </span>
            </div>

            <div class="form-group col-lg-6">
                <label>Confirm Password <span class="text-danger">*</span></label>
                <input 
                    id="password-confirm" 
                    type="password" 
                    class="form-control" 
                    name="password_confirmation"
                    v-model.trim="$v.user.repeatPassword.$model"
                    v-bind:class="{'is-invalid': $v.user.repeatPassword.$error, 'is-valid': $v.user.repeatPassword.$dirty && !$v.user.repeatPassword.$error }"
                >
                <span class="invalid-feedback" role="alert">
                    <strong>
                        <span v-show="!$v.user.repeatPassword.sameAs">Password is not match</span>
                    </strong>
                </span>
            </div>

        </div> 

        <div v-if="roles.length > 1" class="form-row mb-3 custom-select2">
            <label for="role">Role <span class="text-danger">*</span></label>
            <select2 
                id="role" 
                name="role" 
                class="form-control" 
                v-model.trim="$v.user.role.$model"
                v-bind:class="{'is-invalid': $v.user.role.$error, 'is-valid': $v.user.role.$dirty && !$v.user.role.$error}"
                :disabled="roles.length == 1"
            >
                <option value="0">Please Select</option>
                <option v-for="role in roles" :value="role.id" :selected="user.role == role.id">{{ role.text }}</option>
            </select2>
            <span class="invalid-feedback" role="alert">
                <strong>
                    Role is required
                </strong>
            </span>
        </div>

        <div v-if="userRole == 'superadmin'" class="form-row mb-3 custom-select2">
            <label for="parent_user">Parent</label>
            <select2 
                id="parent_user" 
                name="parent_user" 
                class="form-control" 
                v-model="user.parent"
                width="100%"
            >
                <option value="0">Please Select</option>
                <option v-for="userOption in userOptions" :value="userOption.id" :selected="user.parent == userOption.id">{{ userOption.text }}</option>
            </select2>
        </div>

    </form>
	    
</template>
<script>
import { required, sameAs, minLength, minValue, between, integer, helpers } from 'vuelidate/lib/validators';

import Swal from 'sweetalert2';

export default {

    props: [
        'action',
        'userData',
        'apiRoles',
        'apiUsers',
        'userRole',
    ],

    validations() {
        let validations = {
            'user': {
                'email'     : {required},
                'name'      : {required},
                'role'      : {required, minValue: minValue(1)}
            }
        }

        if (this.action == 'create') {
            validations['user']['password'] = {required, minLength: minLength(8)};
            validations['user']['repeatPassword'] = {sameAsPassword: sameAs('password')};
        } 
        else {
            if (this.user.password == '') {
                validations['user']['password'] = {};
                validations['user']['repeatPassword'] = {};
            }
            else {
                validations['user']['password'] = {required, minLength: minLength(8)};
                validations['user']['repeatPassword'] = {sameAsPassword: sameAs('password')};
            }
        }

        return validations;
    },

    mounted() {

        //get list of roles
        $.get(this.apiRoles)
        .done(response => {
            this.roles = this.roles.concat(response);
        });

        $.get(this.apiUsers)
        .done(response => {
            this.userOptions = response;
        });

        if (this.action == 'update') {
            this.$v.user.$touch();
            this.$v.user.password.$reset();
            this.$v.user.repeatPassword.$reset();

            // let parent know the status invalid
            this.$emit('watch-user-form', {
                'invalid'   : this.$v.user.$invalid,
                'data'      : {
                    'id'        : this.user.id,
                    'email'     : this.user.email,
                    'name'      : this.user.name,
                    'password'  : this.user.password,
                    'role'      : this.user.role,
                    'parent'    : this.user.parent
                }
            });
        }
        else {
            this.$v.user.$reset();
        }
    },

    data() {
        return {
            'user'  : this.userData,
            roles   : [],
            userOptions: []
        }
    },

    watch: {
        user: {
            handler(val){
                //console.log(this.$v.user.$invalid);
                this.$emit('watch-user-form', {
                    'invalid'   : this.$v.user.$invalid,
                    'data'      : {
                        'id'        : this.user.id,
                        'email'     : this.user.email,
                        'name'      : this.user.name,
                        'password'  : this.user.password,
                        'role'      : this.user.role,
                        'parent'    : this.user.parent
                    }
                });
            },
            deep: true
        }
    },

    methods: {
        // all saving is done outside at the root page
    }
}
</script>